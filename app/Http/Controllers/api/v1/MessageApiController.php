<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Message\MarkAsReadRequest;
use App\Http\Requests\Message\StoreMessageRequest;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class MessageApiController extends Controller
{
    /**
     * Récupérer toutes les conversations de l'utilisateur courant.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $currentUser = Auth::user();

        // Récupérer les IDs des utilisateurs avec qui l'utilisateur courant a échangé des messages
        $sentToIds = Message::where('sender_id', $currentUser->id)
                    ->distinct('receiver_id')
                    ->pluck('receiver_id');

        $receivedFromIds = Message::where('receiver_id', $currentUser->id)
                    ->distinct('sender_id')
                    ->pluck('sender_id');

        // Combiner et obtenir les utilisateurs uniques
        $contactIds = $sentToIds->merge($receivedFromIds)->unique();

        $contacts = User::whereIn('id', $contactIds)->get();

        // Pour chaque contact, récupérer le dernier message
        $conversations = $contacts->map(function ($contact) use ($currentUser) {
            $lastMessage = Message::where(function ($query) use ($currentUser, $contact) {
                $query->where('sender_id', $currentUser->id)
                    ->where('receiver_id', $contact->id);
            })->orWhere(function ($query) use ($currentUser, $contact) {
                $query->where('sender_id', $contact->id)
                    ->where('receiver_id', $currentUser->id);
            })->orderBy('created_at', 'desc')->first();

            return [
                'contact' => $contact,
                'last_message' => $lastMessage,
                'unread_count' => Message::where('sender_id', $contact->id)
                                ->where('receiver_id', $currentUser->id)
                                ->whereNull('read_at')
                                ->count()
            ];
        });

        return response()->json($conversations);
    }

    /**
     * Récupérer tous les messages échangés avec un utilisateur spécifique.
     *
     * @param int $userId L'ID de l'autre utilisateur
     * @return JsonResponse
     */
    public function conversation(int $userId): JsonResponse
    {
        $currentUser = Auth::user();

        // Vérifier si l'autre utilisateur existe
        $otherUser = User::find($userId);
        if (!$otherUser) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        // Récupérer les messages entre les deux utilisateurs (dans les deux sens)
        $messages = Message::where(function ($query) use ($currentUser, $userId) {
            $query->where('sender_id', $currentUser->id)
                ->where('receiver_id', $userId);
        })->orWhere(function ($query) use ($currentUser, $userId) {
            $query->where('sender_id', $userId)
                ->where('receiver_id', $currentUser->id);
        })->orderBy('created_at', 'asc')->get();

        return response()->json($messages);
    }

    /**
     * Envoyer un nouveau message.
     *
     * @param StoreMessageRequest $request
     * @return JsonResponse
     */
    public function store(StoreMessageRequest $request): JsonResponse
    {
        $currentUser = Auth::user();
        $validatedData = $request->validated();

        // Créer le message
        $message = Message::create([
            'sender_id' => $currentUser->id,
            'receiver_id' => $validatedData['receiver_id'],
            'content' => $validatedData['content'],
        ]);

        return response()->json($message, 201);
    }

    /**
     * Marquer comme lus tous les messages d'un expéditeur spécifique.
     *
     * @param MarkAsReadRequest $request
     * @param int $userId L'ID de l'expéditeur des messages
     * @return JsonResponse
     */
    public function markAsRead(MarkAsReadRequest $request, int $userId): JsonResponse
    {
        $currentUser = Auth::user();

        // Vérifier si l'utilisateur existe
        if (!User::find($userId)) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        // Marquer tous les messages non lus de cet expéditeur comme lus
        $updatedCount = Message::where('sender_id', $userId)
            ->where('receiver_id', $currentUser->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'message' => 'Messages marqués comme lus',
            'updated_count' => $updatedCount
        ]);
    }

    /**
     * Supprimer un message spécifique.
     *
     * @param int $id L'ID du message à supprimer
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $currentUser = Auth::user();
        $message = Message::find($id);

        if (!$message) {
            return response()->json(['message' => 'Message non trouvé'], 404);
        }

        // Vérifier que l'utilisateur est bien l'expéditeur ou le destinataire
        if ($message->sender_id !== $currentUser->id && $message->receiver_id !== $currentUser->id) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $message->delete();

        return response()->json(['message' => 'Message supprimé']);
    }

    /**
     * Obtenir le nombre de messages non lus pour l'utilisateur actuel.
     *
     * @return JsonResponse
     */
    public function unreadCount(): JsonResponse
    {
        $currentUser = Auth::user();

        $count = Message::where('receiver_id', $currentUser->id)
                ->whereNull('read_at')
                ->count();

        return response()->json(['unread_count' => $count]);
    }
}

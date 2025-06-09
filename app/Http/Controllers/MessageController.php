<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Récupérer tous les messages d'une conversation entre utilisateurs.
     *
     * @param  int  $userId  L'ID de l'autre utilisateur
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
     * Récupérer toutes les conversations de l'utilisateur.
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
     * Envoyer un nouveau message.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string',
        ]);

        $currentUser = Auth::user();

        // Créer le message
        $message = Message::create([
            'sender_id' => $currentUser->id,
            'receiver_id' => $validatedData['receiver_id'],
            'content' => $validatedData['content'],
        ]);

        return response()->json($message, 201);
    }

    /**
     * Marquer les messages comme lus.
     *
     * @param  int  $userId  L'ID de l'expéditeur des messages
     * @return JsonResponse
     */
    public function markAsRead(int $userId): JsonResponse
    {
        $currentUser = Auth::user();

        // Marquer tous les messages non lus de cet expéditeur comme lus
        Message::where('sender_id', $userId)
            ->where('receiver_id', $currentUser->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['message' => 'Messages marqués comme lus']);
    }

    /**
     * Supprimer un message.
     *
     * @param  int  $id  L'ID du message
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
}

<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Requests\User\UserAuctionFilteredIndexRequest;
use App\Http\Requests\User\UserDestroyRequest;
use App\Http\Requests\User\UserStoreRequest;
use App\Mail\WelcomeMail;
use App\Models\Auction;
use App\Models\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;

class UserApiController extends Controller
{
    // Afficher tous les utilisateurs
    public function index()
    {
        $users = User::paginate(20); // Récupère tous les utilisateurs
        return response()->json($users);
    }

    // Afficher un utilisateur spécifique
    public function show($id)
    {
        $user = User::findOrFail($id); // Trouve l'utilisateur par son ID
        return response()->json($user); // Retourne l'utilisateur en JSON
    }

    public function showSelf()
    {
        $user = User::findOrFail(auth()->id());
        $user->makeVisible('email');
        // Calculer les statistiques
        $stats = [
            'auctions_ongoing' => $user->Auctions()->where('end_at', '>', now())->count(),
            'auctions_ended' => $user->Auctions()->where('end_at', '<', now())->count(),
            'auctions_won' => Auction::whereHas('offers', function($query) use ($user) {
                $query->where('author_id', $user->id)
                    ->whereRaw('offers.id = (SELECT MAX(o.id) FROM offers o WHERE o.auction_id = auctions.id)');
            })->where('end_at', '<', now())->count()
        ];

        // Ajouter les statistiques à la réponse
        $userData = $user->toArray();
        $userData['stats'] = $stats;
        return response()->json($userData); // Retourne l'utilisateur en JSON
    }
    public function getAuctions($id, UserAuctionFilteredIndexRequest $request)
    {
        $filter = $request->input('filter');
        $user = User::findOrFail($id);
        $auctions = $user->Auctions();
        if($filter == "ongoing"){
            $auctions = $auctions->where('end_at', '>', now());
        }elseif($filter == "ended"){
            $auctions = $auctions->where('end_at', '<', now());
        } elseif($filter == "won"){
            $auctions = Auction::whereHas('offers', function($query) use ($user) {
                $query->where('author_id', $user->id)
                    ->whereRaw('offers.id = (SELECT MAX(o.id) FROM offers o WHERE o.auction_id = auctions.id)');
            })->where('end_at', '<', now());
        }
        return response()->json($auctions->paginate(20));
    }

    public function getOffers($id)
    {
        $user = User::findOrFail($id);
        $offers = $user->Offers()->with('author')->paginate(20);
        return response()->json($offers);
    }

    // Formulaire pour créer un utilisateur
    public function create()
    {
        return view('users.create');
    }

    // Sauvegarder un utilisateur
    public function store(UserStoreRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        Mail::mailer('mailtrap')->to($user->email)->send(new WelcomeMail($user->name));

        return response()->json($user);
    }

    public function destroy($id, UserDestroyRequest $request)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'User deleted']);
    }
}

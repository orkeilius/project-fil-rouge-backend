<?php

namespace App\Http\Controllers\api\v1;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class UserApiController extends Controller
{
    // Afficher tous les utilisateurs
    public function index()
    {
        $users = User::all(); // Récupère tous les utilisateurs
        return response()->json($users);
    }

    // Afficher un utilisateur spécifique
    public function show($id)
    {
        $user = User::findOrFail($id); // Trouve l'utilisateur par son ID
        return response()->json($user); // Retourne l'utilisateur en JSON
    }
    public function getAuctions($id)
    {
        $user = User::findOrFail($id);
        $auctions = $user->Auctions()->get();
        return response()->json($auctions);
    }

    public function getOffers($id)
    {
        $user = User::findOrFail($id);
        $offers = $user->Offers()->with('author')->get();
        return response()->json($offers);
    }

    // Formulaire pour créer un utilisateur
    public function create()
    {
        return view('users.create');
    }

    // Sauvegarder un utilisateur
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        return response()->json($user);
    }
}

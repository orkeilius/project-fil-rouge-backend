<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Afficher tous les utilisateurs
    public function index()
    {
        $users = User::all(); // Récupère tous les utilisateurs
        return view('users.index', compact('users')); // Retourne la vue avec les utilisateurs
    }

    // Afficher un utilisateur spécifique
    public function show($id)
    {
        $user = User::findOrFail($id); // Trouve l'utilisateur par son ID
        return view('users.show', compact('user')); // Retourne la vue avec l'utilisateur
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

        return redirect()->route('users.index');
    }
}

<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Requests\User\UserDestroyRequest;
use App\Http\Requests\User\UserStoreRequest;
use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Mailtrap\MailtrapClient;
use Mailtrap\Mime\MailtrapEmail;
use Symfony\Component\Mime\Address;

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
    public function getAuctions($id)
    {
        $user = User::findOrFail($id);
        $auctions = $user->Auctions()->paginate(20);
        return response()->json($auctions);
    }

    public function getOffers($id)
    {
        $user = User::findOrFail($id);
        $offers = $user->Offers()->with('author')->paginate(20);;
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

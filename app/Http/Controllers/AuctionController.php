<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use Illuminate\Http\Request;

class AuctionController extends Controller
{
    // Afficher toutes les ventes
    public function index()
    {
        $auctions = Auction::all();
        return view('auctions.index', compact('auctions'));
    }

    // Afficher une vente spécifique
    public function show($id)
    {
        $auction = Auction::findOrFail($id); 
        return view('auctions.show', compact('auction'));
    }

    // Formulaire pour créer une vente
    public function create()
    {
        return view('auctions.create');
    }

    // Sauvegarder une vente
    public function store(Request $request)
    {
        // Validation des données du formulaire
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255', 
            'starting_price' => 'required|numeric|min:0',
            'end_at' => 'required|date|after:today',
        ]);

        // Création de la vente aux enchères
        Auction::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'starting_price' => $validated['starting_price'],
            'end_at' => $validated['end_at'],
        ]);

        // Rediriger vers la page des ventes aux enchères avec un message de succès
        return redirect()->route('auction.index')->with('success', 'Vente créée avec succès.');
    }
}
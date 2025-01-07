<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use Illuminate\Http\Request;

class AuctionController extends Controller
{
    // Afficher toutes les ventes
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $auctions = Auction::paginate($perPage);
        
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

    public function edit($id)
    {
        // Récupérer l'enchère à partir de son ID
        $auction = Auction::findOrFail($id);

        // Retourner la vue pour éditer l'enchère
        return view('auctions.edit', compact('auction'));
    }

    public function update(Request $request, $id)
    {
        // Validation des données
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'starting_price' => 'required|numeric|min:0',
            'end_at' => 'required|date|after:today',
        ]);
    
        // Récupérer l'enchère et la mettre à jour
        $auction = Auction::findOrFail($id);
        $auction->update($validated);
    
        // Rediriger avec un message de succès
        return redirect()->route('auctions.index')->with('success', 'Vente mise à jour avec succès.');
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
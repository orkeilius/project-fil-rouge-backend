<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Offer\StoreOfferRequest;
use App\Http\Requests\Offer\UpdateOfferRequest;
use App\Models\Offer;
use Illuminate\Support\Facades\Auth;

class OfferApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $offers = Offer::with('author')->paginate(20);
        return response()->json($offers);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $offer = Offer::findOrFail($id);
        $offer->load('author');
        $offer->load('auction');
        return response()->json($offer);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOfferRequest $request)
    {
        $validated = $request->validated();
        $offer = Offer::create([
            'price' => $validated['price'],
            'auction_id' => $validated['auction_id'],
            'author_id' => Auth::id(),
        ]);

        return response()->json($offer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOfferRequest $request, Offer $offer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Offer $offer)
    {
        //
    }
}

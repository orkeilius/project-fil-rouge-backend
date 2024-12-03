<?php

namespace App\Http\Controllers\api\v1;

use App\Models\Auction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuctionApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $auctions = Auction::with('author')->get();
        return response()->json($auctions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $auction = User::findOrFail($id)->with('author')->get();
        return response()->json($auction);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Auction $auction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Auction $auction)
    {
        //
    }
}

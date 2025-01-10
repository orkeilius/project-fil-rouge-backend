<?php

namespace App\Http\Requests\Offer;

use App\Models\Auction;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class StoreOfferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        $user = User::findOrFail(Auth::id());
        $auction =Auction::findOrFail($this->request->get('auction_id'));

        $isAuthor = $auction->author_id == $user->id;
        if($isAuthor){
            return Response::deny('Auction Author cannot make an offer');
        }

        $hasOffer = $auction->offers->where('author_id', $user->id)->count() > 0;
        if($hasOffer){
            return Response::deny('User already made an offer');
        }
        if($auction->highest_offer >= $this->request->get('price')){
            $priceText = json_encode($auction->highest_offer, JSON_PRESERVE_ZERO_FRACTION);
            return Response::deny('Offer price must be higher than current highest offer :' . $priceText);
        }


        if($auction->isEnded()){
            return Response::deny('Auction is ended');
        }


        return Response::allow();

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'price' => ['required', 'numeric'],
            'auction_id' => ['required', 'numeric'],
        ];
    }
}

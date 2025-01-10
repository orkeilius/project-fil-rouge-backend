<?php

namespace App\Http\Requests\Offer;

use App\Models\Auction;
use App\Models\Offer;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateOfferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): Response
    {
        $user = User::findOrFail(Auth::id());
        $offer = Offer::findOrFail($this->route('id'));
        $auction = $offer->auction;
        if(!$user){
            return Response::deny('User not found');
        }
        if($auction->isEnded()){
            return Response::deny('Auction is ended');
        }
        $isAuthor = $user->isOfferAuthor($offer) || $user->isAdmin();
        if(!$isAuthor){
            return Response::deny('User is not the author of the offer');
        }
        return Response::allow("");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "price" => ['required', 'numeric'],
        ];
    }
}

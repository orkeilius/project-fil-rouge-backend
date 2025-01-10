<?php

namespace App\Http\Requests\Auction;

use App\Models\Auction;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AuctionUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = User::findOrFail(Auth::id());
        $auction = Auction::findOrFail($this->route('id'));
        if(!$user){
            return false;
        }
        return $user->isAuctionAuthor($auction) || $user->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'description' => ['string'],
        ];
    }
}

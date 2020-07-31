<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Apartment;
use App\Review;

use Illuminate\Http\Request;

class SendReviewController extends Controller
{
    function sendReview(Request $request, Apartment $apartment)
    {
        $data = $request->validate([
            'body' => 'required|max:3000'
        ]);
        
        $newReview = new Review();
        $newReview->fill($data);
        $newReview->apartment_id = $apartment->id;
        $newReview->user_id = Auth::user()->id;

        $saved = $newReview->save();
        
        if($saved) {
            return back()->with('success-review', 'Grazie per la recensione');
        }
    }
}

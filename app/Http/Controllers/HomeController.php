<?php

namespace App\Http\Controllers;
use App\Apartment;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {
        $res = Apartment::where('is_visible', '<>', 0)->get();
        $now = Carbon::now();
        $sponsoreds = [];

        foreach ($res as $apartment) {
            
            if( $apartment->sponsorships->isNotEmpty() ) {
                $sponsorship = $apartment->sponsorships->last();
                $sponsorshipDate = $sponsorship->pivot->created_at;
                $difference = $now->diffInHours($sponsorshipDate);
                $duration = $sponsorship->duration;

                if($difference < $duration) {
                    $sponsoreds[] = $apartment;
                }
            }
        }

        return view('guests.index', compact('sponsoreds'));
    }
}

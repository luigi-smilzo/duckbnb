<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Apartment;
use App\Service;

class SearchController extends Controller
{
    public function index()
    {
        $services = Service::all();

        $res = Apartment::where('is_visible', '<>', 0)->get();
        $now = Carbon::now();
        $sponsoreds = $this->sponsoredIndex($res, $now);

        $origin = [
            'lat' => '',
            'lng' => '',
        ];

        return view('guests.search', compact('origin', 'services', 'sponsoreds'));
    }

    public function search(Request $request)
    {   
        $services = Service::all();

        $res = Apartment::where('is_visible', '<>', 0)->get();
        $now = Carbon::now();
        $sponsoreds = $this->sponsoredIndex($res, $now);

        $origin = [
            'lat' => $request->lat,
            'lng' => $request->lng
        ];
        
        if (!empty($request)) {
            $ids = $request->id[0];
            $array = explode(',', $ids);

            $apartments = Apartment::whereIn('id', $array)->where('is_visible', '<>', 0)->get();

            return view('guests.search', compact('apartments', 'origin', 'services', 'sponsoreds'));
        } else {
            return view('guests.search', compact('services'));
        }
    }

    private function sponsoredIndex($res, $now)
    {
        $res = Apartment::where('is_visible', '<>', 0)->get();
        $now = Carbon::now();
        $sponsoreds = [];

        foreach ($res as $apartment) {
            
            if(($apartment->sponsorships)->isNotEmpty()) {
                $sponsorship = $apartment->sponsorships->last();
                $sponsorshipDate = $sponsorship->pivot->created_at;
                $difference = $now->diffInHours($sponsorshipDate);
                $duration = $sponsorship->duration;

                if($difference < $duration) {
                    $sponsoreds[] = $apartment;
                }
            }
        }

        return $sponsoreds;
    }
}

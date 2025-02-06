<?php

namespace App\Http\Controllers;
use App\Http\Controllers;
use App\Models\TransportUnit;
use App\Enums\TransportUnitType;
use Illuminate\Http\Request;

class TransportUnitController extends Controller {
    public function index(Request $request) {
        $validated = $request->validate([
            'type' => 'nullable|in:truck,trailer',
            'search' => 'nullable|string|max:255',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);
    
        
        $query = TransportUnit::query();
        if ($request->has('type')) {
            $query->where('type', $validated['type']);
        }
    
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $validated['search'] . '%');
        }

        
        /*
        Code optimization could be done here; one single query 
        to get total trucks and trailers. E.g.
        Can also be cached for better performance,
        by reducing the number of queries.
        */
        $paginator = $query->paginate($validated['per_page'] ?? 5);
        $totalTrucks = TransportUnit::where('type', 'truck')->count();
        $totalTrailers = TransportUnit::where('type', 'trailer')->count();


        /*
        Redundant code
        As its doing in in the return response below.
        */
        $paginator->appends(['meta' => [
            'total_trucks' => $totalTrucks,
            'total_trailers' => $totalTrailers,
        ]]);
    
        return response()->json([
            'data' => $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'total_trucks' => $totalTrucks,
                'total_trailers' => $totalTrailers,
            ],
        ]);
    }

    /* 
    If cache is used, Then this method needs a cache:forget.
    to insure that the data is always up to date.
    edge cases could be other update sources.
    */
    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:truck,trailer',
        ]);
    
        $transportUnit = TransportUnit::create($validated);
    
        return response()->json($transportUnit, 201);
    }
}

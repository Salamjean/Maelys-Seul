<?php

namespace App\Http\Controllers\Home;

use App\Models\Bien;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $query = Bien::where('statut', 'actif');

        // Filtrage... (commun au deux si besoin, mais ici on garde l'index simple ou filtré)
        if ($request->filled('commune')) { $query->where('commune', 'LIKE', '%' . $request->commune . '%'); }
        if ($request->filled('type_bien')) { $query->where('type_bien', $request->type_bien); }
        if ($request->filled('loyer_max')) { $query->where('loyer_mensuel', '<=', $request->loyer_max); }

        $biens = $query->latest()->take(8)->get(); // Limité à 8 sur l'accueil
        $carouselBiens = Bien::where('statut', 'actif')->latest()->take(3)->get();

        return view('home.index', compact('biens', 'carouselBiens'));
    }

    public function allBiens(\Illuminate\Http\Request $request)
    {
        $query = Bien::where('statut', 'actif');

        if ($request->filled('commune')) { $query->where('commune', 'LIKE', '%' . $request->commune . '%'); }
        if ($request->filled('type_bien')) { $query->where('type_bien', $request->type_bien); }
        if ($request->filled('loyer_max')) { $query->where('loyer_mensuel', '<=', $request->loyer_max); }

        $biens = $query->latest()->paginate(12);

        return view('home.all-biens', compact('biens'));
    }

    public function show(Bien $bien)
    {
        return view('home.show', compact('bien'));
    }
}

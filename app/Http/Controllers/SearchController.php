<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Project;
use App\Models\Services;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    function search(Request $request){
        $query = trim($request->q);

        if(!$query || mb_strlen($query) < 2){
            return back();
        }

        $products = Product::where(function($q) use ($query){
            $q->where('title', 'ILIKE', '%'.$query.'%')
              ->orWhere('description', 'ILIKE', '%'.$query.'%');
        })
            ->limit(10)
            ->get()
            ->map(fn($item) => [
                'title' => $item->title,
                'url' => route('products.show', $item),
                'type' => 'Товары',
            ]);

        $services = Services::where(function($q) use ($query){
            $q->where('title', 'ILIKE', '%'.$query.'%')
                ->orWhere('description', 'ILIKE', '%'.$query.'%');
        })
            ->limit(10)
            ->get()
            ->map(fn($item) => [
                'title' => $item->title,
                'url' => route('services.show', $item),
                'type' => 'Услуги',
            ]);

        $projects = Project::where(function($q) use ($query){
            $q->where('title', 'ILIKE', '%'.$query.'%')
                ->orWhere('description', 'ILIKE', '%'.$query.'%');
        })
            ->limit(10)
            ->get()
            ->map(fn($item) => [
                'title' => $item->title,
                'url' => route('projects.show', $item),
                'type' => 'Проекты',
            ]);

        $results = collect()
            ->merge($products)
            ->merge($services)
            ->merge($projects);

        return view('search.index', compact('results', 'query'));
    }
}

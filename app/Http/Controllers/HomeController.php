<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Companies;
use App\Models\Delivery;
use App\Models\EmailType;
use App\Models\Product;
use App\Models\Project;
use App\Models\Slider;

class HomeController extends Controller
{
    public function index()
    {
        $emailTypes = EmailType::query()
            ->orderBy('id')
            ->get(['id', 'type']);

        $newProducts = Product::query()
            ->latest()
//            ->take(8)
            ->get(['id', 'title', 'image', 'short_description', 'slug']);

        $slides = Slider::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get(['title', 'description', 'image']);

        $brands = Brand::query()
            ->take(10)
            ->get(['id', 'name','image']);

        $projects = Project::query()
//            ->take(8)
            ->get(['id','image','title', 'description']);

        $deliveryes = Delivery::query()
            ->take(3)
            ->get(['id','title','icon', 'description']);

        $companies = Companies::query()
            ->get(['name']);

        return view('welcome', compact(
            'emailTypes',
            'newProducts',
            'slides',
            'brands',
            'projects',
            'deliveryes',
            'companies'
        ));
    }
}

<?php

namespace App\Http\Controllers;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    function show()
    {
        $slides = Slider::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get(['title','description','image']);

        return view('welcome', compact('slides'));
    }
}

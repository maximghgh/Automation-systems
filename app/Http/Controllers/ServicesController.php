<?php

namespace App\Http\Controllers;

use App\Models\Services;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    function index()
    {
        $services = Services::query()
            ->select('id', 'title', 'description', 'image')
            ->orderByDesc('id')
            ->paginate(6);

        return view('services', compact('services'));
    }

    function show(Services $service){
        return view('services.show', compact('service'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\Faq;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $artikel_data = Artikel::orderBy('id', 'DESC')->get();
        $faq_data = Faq::orderBy('id', 'ASC')->get();

        return view('home', compact('artikel_data', 'faq_data'));
    }

    public function show($id)
    {
        $artikel = Artikel::findOrFail($id);
        return view('artikel', compact('artikel'));
    }

    public function getArtikelAjax()
    {
        $artikel_data = Artikel::orderBy('id', 'DESC')->get();
        return view('partials.artikel_list', compact('artikel_data'))->render();
    }

    public function contact()
    {
        return view('contact');
    }
}

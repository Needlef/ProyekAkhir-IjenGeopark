<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\Faq;
use App\Models\CustomerStory;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $artikel_data = Artikel::orderBy('id', 'DESC')->get();
        $faq_data = Faq::orderBy('id', 'ASC')->get();
        $stories_data = CustomerStory::orderBy('id', 'DESC')->limit(3)->get();

        return view('home', compact('artikel_data', 'faq_data', 'stories_data'));
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

    public function customerStories()
    {
        $stories = CustomerStory::orderBy('id', 'DESC')->get();
        return view('customer_stories', compact('stories'));
    }

    public function showCustomerStory($id)
    {
        $story = CustomerStory::findOrFail($id);
        return view('customer_story', compact('story'));
    }
}
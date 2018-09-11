<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    //
    public function index() {

        $title = 'Welcome to the App!!';
        return view ('pages.index')->with('title', $title);

    }

    public function about() {

        $title = 'About the App';
        return view ('pages.about')->with('title', $title);
    }

    public function services() {

        $data = array (

            'title' => 'Our Services',
            'message' => 'These are our services',
            'services' => ['Tractors', 'Farming', 'Dirty hands']

        );
        $title = 'These Are Our Services';
        return view ('pages.services')->with($data);
    }


}

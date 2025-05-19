<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function newWorkmenPage(){
        return view('new-workmen');
    }
    public function reportsPage(){
        return view('reports');
    }
    public function dashboard(){
        return view('dashboard');
    }
}

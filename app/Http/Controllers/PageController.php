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
    public function sitePage(){
        return view('locations');
    }
    public function dashboard(){
        return view('dashboard');
    }
}

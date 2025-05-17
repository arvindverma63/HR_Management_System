<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function takeAttendencePage(){
        return view('Attendence');
    }
    public function newWorkmenPage(){
        return view('welcome');
    }
    public function reportsPage(){
        return view('reports');
    }
}

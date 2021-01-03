<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\LogActivity;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
//    public function __construct()
//    {
//        $this->middleware('auth');
//    }
//    public function myTestAddToLog()
//    {
//        LogActivity::addToLog('My Testing Add To Log.');
//    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
}

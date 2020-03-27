<?php
namespace App\Http\Controllers;

use App\Models\Services\Utility\MyLogger2;

class LogController extends Controller
{
    public function debug()
    {
        MyLogger2::debug("Logged");
        return view('index');
    }

    public function info()
    {
        MyLogger2::info("Logged");
        return view('index');
    }
    
    public function warning()
    {
        MyLogger2::warning("Logged");
        return view('index');
    }
    
    public function error()
    {
        MyLogger2::error("Logged");
        return view('index');
    }
}

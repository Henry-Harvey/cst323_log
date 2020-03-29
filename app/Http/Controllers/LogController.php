<?php
namespace App\Http\Controllers;

use App\Models\Services\Utility\MyLogger3;

class LogController extends Controller
{
    public function debug()
    {
        MyLogger3::debug("Logged");
        return view('index');
    }

    public function info()
    {
        MyLogger3::info("Logged");
        return view('index');
    }
    
    public function warning()
    {
        MyLogger3::warning("Logged");
        return view('index');
    }
    
    public function error()
    {
        MyLogger3::error("Logged");
        return view('index');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Logs;

class PageController extends Controller
{

    public function index()
    {
        return view('index');
    }

    public function dashboard()
    {
        return view('dashboard');
    }
}

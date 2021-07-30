<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absen;

class DataAbsenController extends Controller
{
    public function index()
    {
        $data = Absen::with('user')->get();
        // dd($data);
        return view('absen.data-absen-page', \compact('data'));
    }
}

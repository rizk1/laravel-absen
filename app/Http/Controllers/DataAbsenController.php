<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absen;

class DataAbsenController extends Controller
{
    public function index()
    {
        $data = Absen::with('user')->latest()->get();
        // dd($data);
        return view('absen.data-absen-page', \compact('data'));
    }

    public function showMap($id)
    {
        $data = Absen::where('id', $id)->first();
        if ($data) {
            return \response()->json([
                'msg' => 'success',
                'mapData' => '<iframe id="iframe-map" width="600" height="350" frameborder="0" allowfullscreen=""src="https://maps.google.com/maps?q=+-6.3517663+,+106.9799218+&hl=id&z=14&amp;output=embed"></iframe>',
                'alert' => 'Data found',
                'type' => 'success'
            ]);
        }else{
            return \response()->json([
                'msg' => 'failed',
                'alert' => 'Data not found',
                'type' => 'error'
            ]);
        }
    }
}

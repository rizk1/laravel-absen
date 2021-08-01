<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class DetailUserController extends Controller
{
    public function index($id)
    {
        $data = User::where('id', $id)->first();
        if ($data) {
            return view('user.detail-user', \compact('data'));
        }else {
            return \abort(404);
        }
    }
}

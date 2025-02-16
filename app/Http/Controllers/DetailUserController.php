<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DetailUser;
use App\Models\Jabatan;
use App\Models\Shift;
use App\Models\Absen;
use Illuminate\Support\Facades\Auth;
use Storage;

class DetailUserController extends Controller
{
    public function index(Request $request)
    {
        $id = Auth::user()->id;
        $user = User::with('detailUser', 'jabatan', 'shift')->findOrFail($id);
        $jabatan = Jabatan::all();
        $shift = Shift::all();
        $userDetail = DetailUser::where('user_id', $id)->first();
        $absen = Absen::where('user_id', $id)->first();
        $isEditing = $request->isEditing;
        if ($user) {
            return view('user.detail-user', compact('user', 'userDetail', 'jabatan', 'shift', 'isEditing', 'absen'));
        }else {
            return \abort(404);
        }
    }

    public function saveOrUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $DetailUser = DetailUser::where('user_id', $id)->first();

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'jabatan' => 'required',
            'photo' => 'nullable|image|max:2048',
        ]);

        // Update user basic info
        $user->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'jabatan_id' => $validatedData['jabatan'],
            // 'shift_id' => $validatedData['shift']          
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('public/user_photos');
            $photoUrl = Storage::url($path);
            
            if ($DetailUser) {
                $DetailUser->update(['avatar' => $photoUrl]);
            } else {
                DetailUser::create([
                    'user_id' => $user->id,
                    'avatar' => $photoUrl,
                ]);
            }
        }

        return redirect()->back()->with('success', 'User details saved successfully');
    }
}

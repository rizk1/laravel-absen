<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Jabatan;
use App\Models\Shift;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {   
        $jabatan = Jabatan::all();
        $shift = Shift::all();
        if ($request->ajax()) {
            $users = User::with('shift', 'detailUser', 'jabatan');
            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '<a href="javascript:void(0)" data-id="'.$row->id.'" class="edit-user btn btn-success btn-sm">Edit</a> ';
                    $actionBtn .= '<a href="javascript:void(0)" data-id="'.$row->id.'" class="delete-user btn btn-danger btn-sm">Delete</a>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->orderColumn('DT_RowIndex', function ($query, $order) {
                    $query->orderBy('id', $order);
                })
                ->make(true);
        }
        
        return view('user.data-users', compact('jabatan', 'shift'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'jabatan' => 'required',
            'shift' => 'required',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'jabatan_id' => $validated['jabatan'],
            'shift_id' => $validated['shift'],
        ]);

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            UserDetail::create([
                'user_id' => $user->id,
                'avatar' => $avatarPath,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'User created successfully.']);
    }

    public function edit($id)
    {
        $user = User::with('detailUser')->findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:6',
            'jabatan' => 'required|string|max:255',
            'shift' => 'required|string|in:pagi,siang,malam',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->jabatan_id = $validated['jabatan'];
        $user->shift_id = $validated['shift'];
        $user->save();

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            
            $userDetail = UserDetail::updateOrCreate(
                ['user_id' => $user->id],
                ['avatar' => $avatarPath]
            );

            // Delete old avatar if exists
            if ($userDetail->wasRecentlyCreated === false && $userDetail->getOriginal('avatar')) {
                Storage::disk('public')->delete($userDetail->getOriginal('avatar'));
            }
        }

        return response()->json(['success' => true, 'message' => 'User updated successfully.']);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Delete avatar if exists
        if ($user->detailUser && $user->detailUser->avatar) {
            Storage::disk('public')->delete($user->detailUser->avatar);
        }
        
        $user->detailUser()->delete();
        $user->delete();

        return response()->json(['success' => true, 'message' => 'User deleted successfully.']);
    }
}

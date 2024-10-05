<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class JabatanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Jabatan::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '<a href="javascript:void(0)" data-id="'.$row->id.'" class="edit btn btn-success btn-sm edit-jabatan">Edit</a> <a href="javascript:void(0)" data-id="'.$row->id.'" class="delete btn btn-danger btn-sm delete-jabatan">Delete</a>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
        return view('jabatan.jabatan');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jabatan' => 'required|string|max:255|unique:jabatans,jabatan',
        ]);
        $jabatan = Jabatan::create($request->all());

        return response()->json(['success' => 'Jabatan berhasil ditambahkan.']);
    }

    public function edit($id)
    {
        $jabatan = Jabatan::findOrFail($id);
        return response()->json($jabatan);
    }

    public function update(Request $request, $id)
    {   
        $request->validate([
            'jabatan' => 'required|string|max:255|unique:jabatans,jabatan,'.$id,
        ]);

        $jabatan = Jabatan::findOrFail($id);
        $jabatan->update($request->all());

        return response()->json(['success' => 'Jabatan berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $jabatan = Jabatan::findOrFail($id);
        $jabatan->delete();

        return response()->json(['success' => 'Jabatan berhasil dihapus.']);
    }
}

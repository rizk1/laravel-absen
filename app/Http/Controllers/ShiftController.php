<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Models\Jabatan;

class ShiftController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Shift::with('jabatan')->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('jabatan', function($row){
                    return $row->jabatan->jabatan;
                })
                ->addColumn('action', function($row){
                    $actionBtn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm edit-shift">Edit</a> ';
                    $actionBtn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$row->id.'" data-original-title="Delete" class="delete btn btn-danger btn-sm delete-shift">Delete</a>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
        $jabatan = Jabatan::all();
        return view('shift.shift', compact('jabatan'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'shift' => 'required|string|max:255',
            'mulai' => 'required|date_format:H:i',
            'selesai' => 'required|date_format:H:i|after:mulai',
            'jabatan_id' => 'required|exists:jabatans,id',
        ], [
            'shift.required' => 'Nama Shift harus diisi',
            'mulai.required' => 'Waktu Mulai harus diisi',
            'selesai.required' => 'Waktu Selesai harus diisi',
            'selesai.after' => 'Waktu Selesai harus lebih besar dari Waktu Mulai',
            'jabatan_id.required' => 'Jabatan harus dipilih',
            'jabatan_id.exists' => 'Jabatan yang dipilih tidak valid',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $shift = Shift::create($request->all());

        return response()->json(['message' => 'Shift berhasil ditambahkan', 'data' => $shift], 201);
    }

    public function edit($id)
    {
        $shift = Shift::find($id);
        return response()->json($shift);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'shift' => 'required|string|max:255',
            'mulai' => 'required|date_format:H:i',
            'selesai' => 'required|date_format:H:i|after:mulai',
            'jabatan_id' => 'required|exists:jabatans,id',
        ], [
            'shift.required' => 'Nama Shift harus diisi',
            'mulai.required' => 'Waktu Mulai harus diisi',
            'selesai.required' => 'Waktu Selesai harus diisi',
            'selesai.after' => 'Waktu Selesai harus lebih besar dari Waktu Mulai',
            'jabatan_id.required' => 'Jabatan harus dipilih',
            'jabatan_id.exists' => 'Jabatan yang dipilih tidak valid',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $shift = Shift::findOrFail($id);
        $shift->update($request->all());

        return response()->json(['message' => 'Shift berhasil diperbarui', 'data' => $shift]);
    }

    public function destroy($id)
    {
        Shift::find($id)->delete();
        return response()->json(['success'=>'Shift deleted successfully.']);
    }
}

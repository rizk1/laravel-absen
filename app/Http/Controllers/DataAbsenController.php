<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absen;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class DataAbsenController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $isAdmin = auth()->user()->jabatan_id === 1; 

            $data = $isAdmin 
                ? Absen::with('user', 'shift')->orderBy('created_at', 'asc')
                : Absen::with('user', 'shift')->where('user_id', auth()->id())->orderBy('created_at', 'asc');

            if ($isAdmin && $request->has('user_id') && $request->user_id != '' && $request->user_id != 'all') {
                $data->where('user_id', $request->user_id);
            }

            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('status_badge', function($absen) {
                if ($absen->status == 'tepat waktu') {
                    return '<span class="badge badge-success">Tepat Waktu</span>';
                } elseif ($absen->status == 'telat') {
                    return '<span class="badge badge-danger">Terlambat</span>';
                }
                return '';
            })
            ->addColumn('map_button', function($absen) {
                return '<button type="button" class="btn btn-alt-info map-show-data" data-id="'.$absen->id.'">Lihat Detail</button>';
            })
            ->addColumn('action', function($absen) {
                $userButton = '<a href="'.route('detail-user', $absen->user->id).'">
                    <button type="button" class="btn btn-sm btn-primary" data-toggle="tooltip" title="View User">
                        <i class="fa fa-user"></i>
                    </button>
                </a>';
                $deleteButton = '<button type="button" class="btn btn-sm btn-danger delete-absen" data-toggle="tooltip" title="Delete" data-id="'.$absen->id.'">
                    <i class="fa fa-trash"></i>
                </button>';
                return $userButton . ' ' . $deleteButton;
            })
            ->rawColumns(['status_badge', 'action', 'map_button'])
            ->make(true);
        }

        $isAdmin = auth()->user()->jabatan_id === 1;
        $users = $isAdmin ? User::all() : collect([auth()->user()]);
        $data = $isAdmin 
            ? Absen::with('user', 'shift')->latest()->get()
            : Absen::with('user', 'shift')->where('user_id', auth()->id())->latest()->get();
            
        return view('absen.data-absen-page', compact('data', 'users'));
    }

    public function showMap($id)
    {
        $data = Absen::where('id', $id)->first();
        if ($data) {
            return \response()->json([
                'msg' => 'success',
                'mapData' => '<iframe id="iframe-map" width="600" height="350" frameborder="0" allowfullscreen=""src="https://maps.google.com/maps?q=+'.$data->lat.'+,+'.$data->long.'+&hl=id&z=14&amp;output=embed"></iframe>',
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

    public function destroy($id)
    {
        $absen = Absen::find($id);

        if (!$absen) {
            return response()->json([
                'success' => false,
                'message' => 'Data absen tidak ditemukan.'
            ], 404);
        }

        try {
            $absen->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data absen berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data absen.'
            ], 500);
        }
    }

    public function downloadPdf(Request $request)
    {
        $isAdmin = auth()->user()->jabatan_id === 1;
        $query = $isAdmin 
            ? Absen::with('user', 'shift')->orderBy('created_at', 'desc')
            : Absen::with('user', 'shift')->where('user_id', auth()->id())->orderBy('created_at', 'desc');

        $selectedUserId = $request->user_id;
        $selectedUser = null;

        if ($selectedUserId && $selectedUserId != 'all') {
            $query->where('user_id', $selectedUserId);
            $selectedUser = User::find($selectedUserId);
        }

        $data = $query->get();

        $pdf = PDF::loadView('absen.pdf-report', compact('data', 'selectedUser'));
        
        $fileName = 'absen_report_' . Carbon::now()->format('Y-m-d_H-i-s') . '.pdf';
        
        return $pdf->download($fileName);
    }
}

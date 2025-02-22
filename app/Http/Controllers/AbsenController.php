<?php

namespace App\Http\Controllers;

use App\Models\Absen;
use App\Models\User;
use App\Models\Shift;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use DB;

class AbsenController extends Controller
{
    public function index()
    {
        $user = User::with('shift')->findOrFail(Auth::id());
        $shift = Shift::where('jabatan_id', $user->jabatan_id)->get();
        return view('absen.absen-page', compact('user', 'shift'));
    }

    public function absen(Request $request)
    {
        if (!Auth::check()) {
            return $this->jsonResponse('failed', 'Session expired', 'error');
        }

        $shiftId = $request->shift;
        $now = Carbon::now();

        // Check if the user has already clocked in for any shift today
        $activeShift = Absen::where('user_id', Auth::id())
            ->whereDate('tanggal', Carbon::today())
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('absens as b')
                    ->whereColumn('b.user_id', 'absens.user_id')
                    ->whereColumn('b.tanggal', 'absens.tanggal')
                    ->whereColumn('b.shift_id', 'absens.shift_id')
                    ->where('b.type', 'pulang');
            })
            ->first();

        // If the user has clocked in, they can only clock overtime or out for the same shift
        if ($activeShift) {
            if ($activeShift->shift_id != $shiftId) {
                return $this->jsonResponse('failed', 'Anda tidak bisa absen di shift lain.', 'warning');
            }

            if ($request->tipeAbsen == 'lembur' && !$this->hasAbsenToday('lembur', $shiftId)) {
                $message = $this->createAbsen($request, 'lembur', $shiftId);
                return $this->jsonResponse('success', $message, 'success', $message);
            }

            if ($request->tipeAbsen == 'pulang') {
                $absenPulang = Absen::where('user_id', Auth::id())
                    ->where('type', 'pulang')
                    ->whereDate('tanggal', Carbon::today())
                    ->where('shift_id', $shiftId)
                    ->first();

                if ($absenPulang) {
                    return $this->jsonResponse('failed', 'Anda sudah absen pulang untuk shift ini.', 'warning');
                }

                $message = $this->createAbsen($request, 'pulang', $shiftId);
                return $this->jsonResponse('success', $message, 'success', $message);
            }

            return $this->jsonResponse('failed', 'Tipe absen tidak valid atau tidak diizinkan.', 'warning');
        }

        // If no active shift, allow clocking in
        if ($request->tipeAbsen == 'masuk') {
            $shiftPulang = Shift::where('id', $shiftId)->pluck('selesai')->first();
            $time = Carbon::now()->format('H:i');
            if ($time > $shiftPulang) {
                return $this->jsonResponse('failed', 'Waktu absen sudah lewat.', 'warning');
            }
            
            $completedShift = Absen::where('user_id', Auth::id())
                ->whereDate('tanggal', Carbon::today())
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('absens as b')
                        ->whereColumn('b.user_id', 'absens.user_id')
                        ->whereColumn('b.tanggal', 'absens.tanggal')
                        ->whereColumn('b.shift_id', 'absens.shift_id')
                        ->where('b.type', 'masuk')
                        ->where('b.type', 'pulang');
                })
                ->first();

            if (!$completedShift) {
                $message = $this->createAbsen($request, 'masuk', $shiftId);
                return $this->jsonResponse('success', $message, 'success', $message);
            } else {
                return $this->jsonResponse('failed', 'Anda sudah menyelesaikan shift hari ini.', 'warning');
            }
        }

        return $this->jsonResponse('failed', 'Tipe absen tidak valid atau tidak diizinkan.', 'warning');
    }

    private function createAbsen(Request $request, $type, $shiftId)
    {
        $now = Carbon::now();
        $absen = Absen::create([
            'user_id' => Auth::id(),
            'jam_absen' => $now,
            'type' => $type,
            'shift_id' => $shiftId,
            'long' => $request->long,
            'lat' => $request->lat,
            'tanggal' => $now->toDateString()
        ]);

        return "Berhasil absen $type";
    }

    private function hasAbsenToday($type, $shiftId)
    {
        return Absen::where('type', $type)
            ->whereDate('tanggal', Carbon::today())
            ->where('user_id', Auth::id())
            ->when($shiftId, function ($query) use ($shiftId) {
                return $query->where('shift_id', $shiftId);
            })
            ->exists();
    }

    private function jsonResponse($msg, $alert, $type, $text = null)
    {
        return response()->json(compact('msg', 'alert', 'type', 'text'));
    }


}

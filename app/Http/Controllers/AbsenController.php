<?php

namespace App\Http\Controllers;

use App\Models\Absen;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AbsenController extends Controller
{
    public function index()
    {
        $user = User::with('shift')->findOrFail(Auth::id());
        return view('absen.absen-page', compact('user'));
    }

    public function absen(Request $request)
    {
        if (!Auth::check()) {
            return $this->jsonResponse('failed', 'Session expired', 'error');
        }

        $user = User::with('shift')->findOrFail(Auth::id());
        $now = Carbon::now();
        
        $shiftStart = Carbon::parse($user->shift->mulai);
        $shiftEnd = Carbon::parse($user->shift->selesai);

        if ($this->hasAbsenToday('masuk') && $this->hasAbsenToday('pulang')) {
            return $this->jsonResponse('failed', 'Anda Sudah Absen Masuk dan Pulang', 'warning');
        }

        $absenType = $this->determineAbsenType($now, $shiftStart, $shiftEnd);

        if ($absenType === 'masuk' && $this->hasAbsenToday('masuk')) {
            return $this->jsonResponse('failed', 'Anda Sudah Absen Masuk', 'warning');
        }

        if ($absenType === 'pulang' && !$this->hasAbsenToday('masuk')) {
            return $this->jsonResponse('failed', 'Anda belum absen masuk hari ini', 'warning');
        }

        $status = $this->determineAbsenStatus($now, $absenType, $shiftStart, $shiftEnd);
        $message = $this->createAbsen($request, $absenType, $user->shift->id, $status);
        return $this->jsonResponse('success', $message, 'success', $message);
    }

    private function determineAbsenType($now, $shiftStart, $shiftEnd)
    {
        if (!$this->hasAbsenToday('masuk')) {
            return 'masuk';
        } elseif (!$this->hasAbsenToday('pulang')) {
            return 'pulang';
        }
        return null;
    }

    private function determineAbsenStatus($now, $type, $shiftStart, $shiftEnd)
    {
        if ($type === 'masuk') {
            return $now->lte($shiftStart) ? 'tepat waktu' : 'telat';
        } elseif ($type === 'pulang') {
            return $now->gte($shiftEnd) ? 'tepat waktu' : 'pulang awal';
        }
        return 'tidak valid';
    }

    private function createAbsen(Request $request, $type, $shiftId, $status)
    {
        $now = Carbon::now();
        $absen = Absen::create([
            'user_id' => Auth::id(),
            'jam_absen' => $now,
            'type' => $type,
            'status' => $status,
            'shift_id' => $shiftId,
            'long' => $request->long,
            'lat' => $request->lat,
            'tanggal' => $now->toDateString()
        ]);

        $message = "Berhasil absen $type";
        if ($status === 'telat') {
            $message .= ". Anda telat absen!";
        }

        return $message;
    }

    private function hasAbsenToday($type)
    {
        return Absen::where('type', $type)
                    ->whereDate('tanggal', Carbon::today())
                    ->where('user_id', Auth::id())
                    ->exists();
    }

    private function jsonResponse($msg, $alert, $type, $text = null)
    {
        return response()->json(compact('msg', 'alert', 'type', 'text'));
    }
}

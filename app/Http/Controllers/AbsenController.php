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
        $shift = Shift::where(function ($query) use ($user) {
            if ($user->jabatan_id !== 3) {
                $query->whereIn('shift', ['Shift 1', 'Shift 2', 'Shift 3']);
            } else {
                $query->where('shift', 'Non Shift');
            }
        })->get();
        return view('absen.absen-page', compact('user', 'shift'));
    }

    public function absen(Request $request)
    {
        if (!Auth::check()) {
            return $this->jsonResponse('failed', 'Session expired', 'error');
        }

        $shiftId = $request->shift;
        $now = Carbon::now();

        // Ambil shift yang dipilih dari tabel shift
        $shift = Shift::findOrFail($shiftId);

        // Cek apakah sudah ada absen masuk untuk hari ini
        $activeShift = Absen::where('user_id', Auth::id())
            ->where('type', 'masuk')
            ->whereDate('tanggal', Carbon::today())
            ->where('shift_id', $shiftId) // Pastikan shift yang dipilih sesuai
            ->first();

        // Cek apakah shift sebelumnya sudah selesai (absen pulang atau lembur)
        $completedShift = Absen::where('user_id', Auth::id())
            ->whereDate('tanggal', Carbon::today())
            ->whereIn('type', ['pulang', 'lembur'])
            ->first();

        // Jika sudah ada absen masuk untuk shift ini, bisa absen pulang atau lembur
        if ($activeShift) {
            // Cek jika tipe absen adalah pulang dan belum ada absen pulang untuk shift ini
            if ($request->tipeAbsen == 'pulang') {
                $absenPulang = Absen::where('user_id', Auth::id())
                    ->where('type', 'pulang')
                    ->whereDate('tanggal', Carbon::today())
                    ->where('shift_id', $shiftId)
                    ->first();

                // Jika sudah absen pulang, beri pesan bahwa sudah selesai shift ini
                if ($absenPulang) {
                    return $this->jsonResponse('failed', 'Anda sudah absen pulang untuk shift ini.', 'warning');
                }

                // Jika belum ada absen pulang, lakukan absen pulang
                $message = $this->createAbsen($request, 'pulang', $shiftId);
                return $this->jsonResponse('success', $message, 'success', $message);
            }

            // Cek jika tipe absen adalah lembur dan belum ada absen lembur untuk shift ini
            if ($request->tipeAbsen == 'lembur' && !$this->hasAbsenToday('lembur', $shiftId)) {
                $message = $this->createAbsen($request, 'lembur', $shiftId);
                return $this->jsonResponse('success', $message, 'success', $message);
            }

            // Jika tipe absen tidak sesuai
            return $this->jsonResponse('failed', 'Tipe absen tidak valid atau tidak diizinkan.', 'warning');
        }

        // Cek jika shift sebelumnya sudah selesai (absen pulang atau lembur), maka pengguna bisa melakukan absen masuk ke shift baru
        if ($completedShift) {
            // Pastikan shift yang baru belum ada absen masuk
            $absenMasuk = Absen::where('user_id', Auth::id())
                ->where('type', 'masuk')
                ->whereDate('tanggal', Carbon::today())
                ->where('shift_id', $shiftId)
                ->first();

            // Jika belum ada absen masuk untuk shift ini, lanjutkan absen masuk
            if (!$absenMasuk) {
                // Lakukan absen masuk untuk shift ini
                $message = $this->createAbsen($request, 'masuk', $shiftId);
                return $this->jsonResponse('success', $message, 'success', $message);
            } else {
                return $this->jsonResponse('failed', 'Anda sudah absen masuk untuk shift ini.', 'warning');
            }
        }

        // Jika belum ada absen masuk dan shift sebelumnya belum selesai, maka kita bisa absen masuk ke shift pertama
        if (!$completedShift && $request->tipeAbsen == 'masuk') {
            $message = $this->createAbsen($request, 'masuk', $shiftId);
            return $this->jsonResponse('success', $message, 'success', $message);
        }

        // Jika tidak ada kondisi yang cocok, beri pesan gagal
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

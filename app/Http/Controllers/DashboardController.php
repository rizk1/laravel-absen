<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absen;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->input('filter', 'today');
        $user = Auth::user();
        $isAdmin = $user->jabatan_id === 1;

        $query = Absen::query();

        if ($filter === 'today') {
            $query->whereDate('tanggal', Carbon::today());
        }

        if (!$isAdmin) {
            $query->where('user_id', $user->id);
        }

        $absenMasuk = (clone $query)->where('type', 'masuk')->count();
        $absenPulang = (clone $query)->where('type', 'pulang')->count();
        $absenLembur = (clone $query)->where('type', 'lembur')->count();
        $totalAbsen = $query->count();

        $totalUsers = $isAdmin ? User::count() : 1;

        $totalAllDaysQuery = Absen::query();
        if (!$isAdmin) {
            $totalAllDaysQuery->where('user_id', $user->id);
        }

        $totalAllDays = [
            'masuk' => (clone $totalAllDaysQuery)->where('type', 'masuk')->count(),
            'pulang' => (clone $totalAllDaysQuery)->where('type', 'pulang')->count(),
            'lembur' => (clone $totalAllDaysQuery)->where('type', 'lembur')->count(),
            'total' => $totalAllDaysQuery->count(),
        ];

        return view('dashboard.dashboard', compact('absenMasuk', 'absenPulang', 'absenLembur', 'totalAbsen', 'totalUsers', 'totalAllDays', 'filter', 'isAdmin'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Absen;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

class AbsenController extends Controller
{
    public function index()
    {
        return view('absen-page');
    }

    public function absen(Request $request)
    {
        if (!Auth::check()) {
            return \response()->json([
                'msg' => 'session expired',
            ]);
        }
        //absen mulai masuk
        $fromMasuk = date('Y-m-d 06:00:00');
        $toMasuk = date('Y-m-d 09:00:00');
        //

        //absen mulai pulang
        $fromPulang = date('Y-m-d 16:00:00');
        $toPulang = date('Y-m-d 20:00:00');
        //

        $cekMasuk = Absen::where('type', 'masuk')->where('tanggal', date('Y-m-d'))->where('id_user', Auth::user()->id)->first();

        if (date('Y-m-d H:i:s') >= $toMasuk && date('Y-m-d H:i:s') <= $fromPulang) {
            if (!$cekMasuk) {
                $jam = date('Y-m-d H:i:s');
                $absen = Absen::create([
                    'id_user' => Auth::user()->id,
                    'jam_absen' => $jam,
                    'type' => 'masuk',
                    'status' => 'telat',
                    'tanggal' => $jam
                ]);

                return \response()->json([
                    'msg' => 'success',
                    'alert' => 'Anda terlabat absen',
                    'type' => 'warning'
                ]);
            }else {
                return \response()->json([
                    'msg' => 'failed',
                    'alert' => 'Anda Sudah Absen',
                    'type' => 'warning'
                ]);
            }
        }else {
            if (date('Y-m-d H:i:s') >= $fromMasuk && date('Y-m-d H:i:s') <= $toMasuk) {
                if (!$cekMasuk) {
                    $jam = date('Y-m-d H:i:s');
                    $absen = Absen::create([
                        'id_user' => Auth::user()->id,
                        'jam_absen' => $jam,
                        'type' => 'masuk',
                        'status' => 'tepat waktu',
                        'tanggal' => $jam
                    ]);
    
                    return \response()->json([
                        'msg' => 'success',
                        'alert' => 'Berhasil absen masuk',
                        'type' => 'success'
                    ]);
                }else {
                    return \response()->json([
                        'msg' => 'failed',
                        'alert' => 'Sudah absen',
                        'type' => 'warning'
                    ]);
                }
            }
        }

        if (date('Y-m-d H:i:s') >= $fromPulang && date('Y-m-d H:i:s') <= $toPulang) {
            $cekPulang = Absen::where('type', 'pulang')->where('id', Auth::user()->id)->first();
            if (!$cekPulang) {
                $absen = Absen::create([
                    'id_user' => Auth::user()->id,
                    'jam_absen' => date('Y-m-d H:i:s'),
                    'type' => 'pulang',
                    'tanggal' => date('Y-m-d')
                ]);

                return \response()->json([
                    'msg' => 'success',
                    'alert' => 'Berhasil absen pulang'
                ]);
            }else {
                return \response()->json([
                    'msg' => 'failed',
                    'alert' => 'Sudah absen'
                ]);
            }
        }else {
            return \response()->json([
                'msg' => 'failed',
                'alert' => 'Sudah lewat waktu absen'
            ]);
        }
    }
}
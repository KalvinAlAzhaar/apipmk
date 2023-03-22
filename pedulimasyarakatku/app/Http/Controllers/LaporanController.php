<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(){
        $data = DB::table('laporans')
        ->join('penggunas', 'penggunas.id','=','laporans.idPelapor')
        ->select('laporans.*', 'penggunas.pengguna','penggunas.telp')
        ->orderBy('laporans.created_at','DESC')
        ->get();

        return response()->json($data);
    }

    public function buatLaporan(Request $request){
        $this->validate($request, [
            'idkategori'=>'required',
            'idPelapor'=>'required',
            'lokasiKejadian'=>'required',
            'tglKejadian'=>'required',
            'isiLaporan'=>'required'
        ]);

        $gambar = $request->file('gambar')->getClientOriginalName();
        $request->file('gambar')->move('laporan', $gambar);

        $data = [
            'idkategori'=>$request->idkategori,
            'idPelapor'=>$request->idPelapor,
            'lokasiKejadian'=>$request->lokasiKejadian,
            'tglKejadian'=>$request->tglKejadian,
            'isiLaporan'=>$request->isiLaporan,
            'gambar'=>url('laporan/'.$gambar),
        ];

        $run = Laporan::create($data);

        if ($run){
            return response()->json([
                'pesan'=>'Data disimpan',
                'data'=>$data
            ]);
        }

        
    }
}

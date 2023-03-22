<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Admin;
use App\Models\Laporan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreAdminRequest;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Admin::all();

        return response()->json($data);
    }

    // /**
    //  * Show the form for creating a new resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function create(Request $request)
    // {
    //     //validasi
    //     $this->validate($request, [
    //         'admin' => 'required | alpha_dash | max:255',
    //         'namaLengkap' => 'required | max:255',
    //         'email' => 'required | string | email | max:255 | unique:admins',
    //         'password' => 'required | min:8 | regex:/[a-z]/ | regex:/[A-Z]/ | regex:/[0-9]/ | regex:/[@$!%*#?&]/',
    //         'telp' => 'required | numeric'
    //     ]);

    //     //inisiasi data
    //     $data = [
    //         'admin' => $request->input('admin'),
    //         'namaLengkap' => $request->input('namaLengkap'),
    //         'email' => $request->input('email'),
    //         'password' => Hash::make($request->input('password')),
    //         'telp' => $request->input('telp'),

    //     ];

    //     //menjalankan data
    //     $run = Admin::create($data);

    //     //output
    //     if ($run) {
    //         return response()->json([
    //             'pesan' => 'Data berhasil disimoan',
    //             'status' => 200,
    //             'data' => $data
    //         ]);
    //     }
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->validate([
            'admin' => 'required | alpha_dash | max:255',
            'namaLengkap' => 'required | max:255',
            'email' => 'required | string | email | max:255 | unique:admins',
            'password' => 'required | min:8 | regex:/[a-z]/ | regex:/[A-Z]/ | regex:/[0-9]/ | regex:/[@$!%*#?&]/',
            'telp' => 'required | numeric',
        ]);

        //di if karena gambarnya nullable
        $token = Hash::make(Str::random(80));
        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar')->getClientOriginalName();
            $request->file('gambar')->move('upload', $gambar);


            $data = [
                'admin' => $request->input('admin'),
                'namaLengkap' => $request->input('namaLengkap'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'telp' => $request->input('telp'),
                'gambar' => url('upload/' . $gambar),
                'token' => $token
            ];
        } else {
            $data = [
                'admin' => $request->input('admin'),
                'namaLengkap' => $request->input('namaLengkap'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'telp' => $request->input('telp'),
                'token' => $token
            ];
        }

        $run = Admin::create($data);

        if ($run) {
            return response()->json([
                'pesan' => 'Data berhasil disimpan',
                'status' => 200,
                'data' => $data,
            ]);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAdminRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAdminRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        $data = Admin::where('id', $id)->get();

        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function edit(Admin $admin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAdminRequest  $request
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'admin' => 'required | alpha_dash | max:255',
            'namaLengkap' => 'required | max:255',
            'email' => 'required | string | email | max:255 | unique:admins',
            'password' => 'required | min:8 | regex:/[a-z]/ | regex:/[A-Z]/ | regex:/[0-9]/ | regex:/[@$!%*#?&]/',
            'telp' => 'required | numeric',
        ]);

        $data = [
            'admin' => $request->input('admin'),
            'namaLengkap' => $request->input('namaLengkap'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'telp' => $request->input('telp')
        ];

        $run = Admin::where('id', $id)->update($data);

        if ($run) {
            return response()->json([
                'pesan' => 'Data berhasil diperbaharui',
                'status' => 200,
                'data' => $data
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $run = Admin::where('id', $id)->delete();

        if ($run) {
            return response()->json([
                'pesan' => 'Data berhasil dihapus',
                'status' => 200
            ]);
        }
    }


    public function login(Request $request)
    {
        $request->validate([
            'namaLengkap' => 'required | max:255',
            'password' => 'required | min:8 | regex:/[a-z]/ | regex:/[A-Z]/ | regex:/[0-9]/ | regex:/[@$!%*#?&]/'
        ]);

        $namaLengkap = $request->input('namaLengkap');
        $password = $request->input('password');

        $user = Admin::where('namaLengkap', $namaLengkap)->first();

        if (isset($user)) {
            if ($user->status == 1) {
                if (Hash::check($password, $user->password)) {
                    return response()->json([
                        'pesan' => 'Login berhasil',
                        'data' => $user
                    ]);
                } else {
                    return response()->json([
                        'pesan' => 'Password salah',
                        'data' => ''
                    ]);
                }
            } else {
                return response()->json([
                    'pesan' => 'Login tak dapat dilakukan karena akun diblokir',
                    'data' => ''
                ]);
            }
        } else {
            return response()->json([
                'pesan' => 'Akun tidak ditemukan',
                'data' => ''
            ]);
        }
    }


    public function ubahStatusLaporan($idLaporan)
    {
        $data = [
            'status' => 'Selesai',
            'tgl_selesai' => Carbon::now()
        ];

        $run = Laporan::where('id', $idLaporan)->update($data);

        if ($run) {
            return response()->json([
                'pesan' => 'Data diubah',
                'data' => $data
            ]);
        }
    }
}

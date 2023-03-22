<?php

namespace App\Http\Controllers;

use Nexmo\Client;
use App\Models\Pengguna;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Nexmo\Client\Credentials\Basic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StorePenggunaRequest;
use App\Http\Requests\UpdatePenggunaRequest;

class PenggunaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $data = Pengguna::all();

        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'pengguna'=>'required | max:255 | unique:penggunas',
            'email'=>'required | string | email | max:255 | unique:penggunas',
            'password'=>'required | min:8 | regex:/[a-z]/ | regex:/[A-Z]/ | regex:/[0-9]/ | regex:/[@$!%*#?&]/',
            'telp'=>'required | numeric',
        ]);

        //di if karena gambarnya nullable
        $token = Hash::make(Str::random(80));
        if($request->hasFile('gambar')){
            $gambar = $request->file('gambar')->getClientOriginalName();
            $request->file('gambar')->move('upload', $gambar);


            $data = [
                'pengguna'=>$request->input('pengguna'),
                'email'=>$request->input('email'),
                'password'=>Hash::make($request->input('password')),
                'telp'=>$request->input('telp'),
                'gambar'=>url('upload/'.$gambar),
                'token'=>$token
            ];
        } else {
            $data = [
                'pengguna'=>$request->input('pengguna'),
                'email'=>$request->input('email'),
                'password'=>Hash::make($request->input('password')),
                'telp'=>$request->input('telp'),
                'token'=>$token
            ];
        }

        $run = Pengguna::create($data);
        $otp = mt_rand(10000, 999999);

        if($run){
            return response()->json([
                'pesan'=>'Data berhasil disimpan',
                'status'=>200,
                'data'=>$data,
                'otp'=>$otp,
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePenggunaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePenggunaRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pengguna  $pengguna
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Pengguna::where('id',$id)->get();


        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pengguna  $pengguna
     * @return \Illuminate\Http\Response
     */
    public function edit(Pengguna $pengguna)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePenggunaRequest  $request
     * @param  \App\Models\Pengguna  $pengguna
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'pengguna'=>'required | max:255 | unique:penggunas',
            'email'=>'required | string | email | max:255 | unique:penggunas',
            'password'=>'required | min:8 | regex:/[a-z]/ | regex:/[A-Z]/ | regex:/[0-9]/ | regex:/[@$!%*#?&]/',
            'telp'=>'required | numeric',
        ]);

        if($request->hasFile('gambar')){
            $gambar = $request->file('gambar')->getClientOriginalName();
            $request->file('gambar')->move('upload', $gambar);

            $data = [
                'pengguna'=>$request->input('pengguna'),
                'email'=>$request->input('email'),
                'password'=>Hash::make($request->input('password')),
                'telp'=>$request->input('telp'),
                'gambar'=>url('upload/'.$gambar)
            ];

        } else {
            $data = [
                'pengguna'=>$request->input('pengguna'),
                'email'=>$request->input('email'),
                'password'=>Hash::make($request->input('password')),
                'telp'=>$request->input('telp')
            ];
        }

        $run = Pengguna::where('id', $id)->update($data);

        if($run){
            return response()->json([
                'pesan'=>'Data berhasil diperbaharui',
                'status'=>200,
                'data'=>$data
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pengguna  $pengguna
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $run = Pengguna::where('id',$id)->delete();

        if($run){
            return response()->json([
                'pesan'=>'Data berhasil dihapus',
                'status'=>200
            ]);
        }
    }

    //login
    public function login(Request $request){
        $this->validate($request, [
            'email'=>'required | string | email | max:255',
            'password'=>'required | min:8 | regex:/[a-z]/ | regex:/[A-Z]/ | regex:/[0-9]/ | regex:/[@$!%*#?&]/'
        ]);

        $email = $request->input('email');
        $password = $request->input('password');

        //buatmencocokkan data
        $user = Pengguna::where('email', $email)->first();//ambil data pengguna berdasarkan email
        $token = DB::table('penggunas')->select('penggunas.token')
        ->where('email',$email)->first();

        if(isset($user)){
            if($user->status == 1){
                if(Hash::check($password, $user->password)){ //password disandingkan apakah match
                    // sendsms();
                    return response()->json([
                        'pesan'=>'Login Berhasil',
                        'data'=>$user
                    ]);

                } else {
                    return response()->json([
                        'pesan'=>'Password salah',
                        'data'=>''
                    ]);
                }

            } else {
                return response()->json([
                    'pesan'=>'Login tak dapat dilakukan karena akun diblokir',
                    'data'=>''
                ]);
            }
        } else {
            return response()->json([
                'pesan'=>'Email tidak ditemukan',
                'data'=>''
            ]);
        }
    }


    //forget password
    public function forgot(Request $request){
        $this->validate($request, [
            'pengguna'=>'required | max:255',
            'email'=>'required | string | email | max:255',
            'password'=>'required | min:8 | regex:/[a-z]/ | regex:/[A-Z]/ | regex:/[0-9]/ | regex:/[@$!%*#?&]/'
        ]);

        $email = $request->input('email');
        $password = Hash::make($request->input('password'));
        $pengguna = $request->input('pengguna');

        $user = Pengguna::where('email', $email)->where('pengguna', $pengguna);

        $data = [
            'password'=>$password,
        ];

        $run = Pengguna::where('email', $email)->where('pengguna', $pengguna)->update($data);

        if($run){
            return response()->json([
                'pesan'=>'Password telah diubah'
            ]);
        } else {
            return response()->json([
                'pesan'=>'Email atau nama salah!'
            ]);
        }
    }    
}
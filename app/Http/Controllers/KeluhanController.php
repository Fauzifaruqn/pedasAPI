<?php

namespace App\Http\Controllers;
use App\Keluhan;
use Illuminate\Http\Request;

class KeluhanController extends Controller
{
    //

    public function index(){
        $keluhan = Keluhan::all();
        foreach($keluhan as $keluh){
            $keluhan->view_keluhan = [
                'href' => 'api/v1/keluhan' . $keluh->id,
                'method' => 'GET'
            ];
        }

        $response = [
            'msg' => 'List of All Keluhan',
            'keluhan' => $keluhan
        ];

        return response()->json($response,200);

    }

    public function store(Request $request){

        $this->validate($request, [
            'nama_pengadu' => 'required',
            'jenis_keluhan' => 'required',
            'judul_keluhan' => 'required',
            'region' => 'required',
            'deskripsi' => 'required',
            'time' => 'required',
            'status' => 'required',
            'gambar' => 'required',
            'user_id' => 'required'
        ]);

        $nama_pengadu = $request->input('nama_pengadu');
        $jenis_keluhan = $request->input('jenis_keluhan');
        $judul_keluhan = $request->input('judul_keluhan');
        $region = $request->input('region');
        $deskripsi = $request->input('deskripsi');
        $time = $request->input('time');
        $status = $request->input('status');
        $gambar = $request->input('gambar');
        $user_id = $request->input('user_id');

        $keluh = new Keluhan([
            'nama_pengadu' => $nama_pengadu,
            'jenis_keluhan' => $jenis_keluhan,
            'judul_keluhan' => $judul_keluhan,
            'region' => $region,
            'deskripsi' => $deskripsi,
            'time' => $time,
            'status' => $status,
            'gambar' => $gambar,
        ]);
           if($keluh->save()){
               $keluh->users()->attach($user_id);
               $keluh->view_keluhan =
               [
                'href' => 'api/v1/keluhan/1' . $keluh->id,
                'method' => 'GET'
               ];

               $message = [
                    'msg' => 'Keluhan Created',
                    'data' => $keluh,
                ];
                return response()->json($message,200);
           }
             $response = [
                 'msg' => 'Error During Creating'
             ];
             return response()->json($response,404);
    }

    public function show($id)
    {
        $keluh = Keluhan::with('users')->where('id',$id)->firstOrFail();
        $keluh->view_keluhan = [
            'href' => 'api/v1/keluhan',
            'method' => 'GET'
        ];

        $response = [
            'msg' => 'Keluhan Information',
            'keluhan' => $keluh
        ];
        return response()->json($response,200);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nama_pengadu' => 'required',
            'jenis_keluhan' => 'required',
            'judul_keluhan' => 'required',
            'region' => 'required',
            'deskripsi' => 'required',
            'time' => 'required',
            'status' => 'required',
            'gambar' => 'required',
            'user_id' => 'required'
        ]);


        $nama_pengadu = $request->input('nama_pengadu');
        $jenis_keluhan = $request->input('jenis_keluhan');
        $judul_keluhan = $request->input('judul_keluhan');
        $region = $request->input('region');
        $deskripsi = $request->input('deskripsi');
        $time = $request->input('time');
        $status = $request->input('status');
        $gambar = $request->input('gambar');
        $user_id = $request->input('user_id');

        $keluh = Keluhan::with('users')->findOrFail($id);

        // melakukan validasi
        if(!$keluh->users()->where('user_id', $user_id)->first())
        {
            return response()->json(['msg' => 'user not registered for keluhan, update no successfuly'],404);
        };

        $keluh->nama_pengadu = $nama_pengadu;
        $keluh->jenis_keluhan = $jenis_keluhan;
        $keluh->judul_keluhan = $judul_keluhan;
        $keluh->region = $region;
        $keluh->deskripsi = $deskripsi;
        $keluh->time = $time;
        $keluh->status = $status;
        $keluh->gambar = $gambar;

        // ketika error melakukan update
        if(!$keluh->update()){
            return response()->json([
                'msg' => 'Error during Update'
            ],404);
        }

        $keluh->view_keluhan = [
            'href' => 'api/v1/keluhan/' . $keluh->id,
            'method' => 'GET'
        ];

        $response = [
            'msg' => 'keluhan Updated',
            'keluhan' => $keluh
        ];

        return response()->json($response,200);
    }

    public function destroy($id)
    {
        $keluh = Keluhan::findOrFail($id);
        $users = $keluh->users;
        // proses pelepasan data users
        $keluh->users()->detach();


        if(!$keluh->delete()){
            foreach($users as $user){
                $keluh->users()->attach($user);
            }
            return response()->json([
                'msg' => 'Deletion Failed'
            ],404);
        }

        $response = [
            'msg' => 'Keluhan Deleted',
            'create' => [
                    'href' => 'api/v1/keluhan',
                    'method' => 'POST',
                    'params' => 'nama_pengadu, jenis_keluhan, judul_keluhan, region, deskripsi,time,gambar,status'
                ]
            ];

            return response()->json($response, 200);
        // arahkan ke proses create
    }
}

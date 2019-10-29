<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Materi;
class MateriController extends Controller
{
    //

    public function index(){
        $materi = Materi::all();
        foreach($materi as $mtr){
            $materi->view_materi = [
                'href' => 'api/v1/materi' . $mtr->id,
                'method' => 'GET'
            ];
        }

        $response = [
            'msg' => 'List of All Keluhan',
            'keluhan' => $materi
        ];

        return response()->json($response,200);

    }

    public function store(Request $request){

        $this->validate($request, [
            'judul_materi' => 'required',
            'isi_materi' => 'required',
            'nama_penulis' => 'required',
        ]);

        $judul_materi = $request->input('judul_materi');
        $isi_materi = $request->input('isi_materi');
        $nama_penulis = $request->input('nama_penulis');
        $gambar_materi = $request->input('gambar_materi');
        $video_materi = $request->input('video_materi');

        $materi = new Materi([
            'judul_materi' => $judul_materi,
            'isi_materi' => $isi_materi,
            'nama_penulis' => $nama_penulis,
            'gambar_materi' => $gambar_materi,
            'video_materi' => $video_materi,
        ]);
           if($materi->save()){
            //    $keluh->users()->attach($user_id);
               $materi->view_materi =
               [
                'href' => 'api/v1/materi/1' . $materi->id,
                'method' => 'GET'
               ];

               $message = [
                    'msg' => 'Materi Created',
                    'data' => $materi,
                ];
                return response()->json($message,200);
           }
             $response = [
                 'msg' => 'Error During Creating'
             ];
             return response()->json($response,404);
    }
}

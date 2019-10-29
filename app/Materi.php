<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    //

    protected $table = 'materi';
    protected $fillable = ['judul_materi','isi_materi','nama_penulis','gambar_materi','video_materi'];
}

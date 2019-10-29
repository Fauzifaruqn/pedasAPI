<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Keluhan extends Model
{
    //
    protected $table = 'keluhan';
    protected $fillable = ['nama_pengadu','jenis_keluhan','judul_keluhan','region','deskripsi','status','time','gambar'];

    public function users(){
        return $this->belongstoMany(User::class);
    }


}

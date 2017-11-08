<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
    protected $table = 'jurnal';
    protected $primaryKey = 'id';
	protected $fillable=['tanggal', 'keterangan', 'no_bukti', 'jenis', 'periode_id'];
    public $timestamps=false;
	protected $guarded=['id'];
}

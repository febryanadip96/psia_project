<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    protected $table = 'periode';
    protected $primaryKey = 'id';
	protected $fillable=['tgl_awal', 'tgl_akhir'];
    public $timestamps=false;
	protected $guarded=['id'];
}

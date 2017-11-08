<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $table = 'laporan';
    protected $primaryKey = 'id';
	protected $fillable=['nama'];
    public $timestamps=false;
	protected $guarded=['id'];
}

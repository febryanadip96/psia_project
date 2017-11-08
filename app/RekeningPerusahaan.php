<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RekeningPerusahaan extends Model
{
    protected $table = 'rekening_perusahaan';
    protected $primaryKey = 'id';
	protected $fillable=['no_rek', 'bank_id'];
    public $timestamps=false;
	protected $guarded=['id'];
}

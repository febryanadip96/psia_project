<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Akun extends Model
{
    protected $table = 'akun';
    protected $primaryKey = 'nomor';
	protected $fillable=['nama','saldo_normal'];
    public $timestamps=false;
}

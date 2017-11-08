<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotaTerimaJual extends Model
{
    protected $table = 'nota_terima_jual';
    protected $primaryKey = 'nomor';
	protected $fillable=['nomor', 'tanggal', 'nota_jual_nomor' ];
    public $timestamps=false;
}

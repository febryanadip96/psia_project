<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotaTerimaBeli extends Model
{
    protected $table = 'nota_terima_beli';
    protected $primaryKey = 'nomor';
	protected $fillable=['nomor', 'tanggal', 'nota_beli_nomor' ];
    public $timestamps=false;
}

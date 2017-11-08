<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'kode';
	protected $fillable=['nama', 'stok', 'harga_jual', 'harga_beli_rata', 'jenis_id'];
    public $timestamps=false;
}

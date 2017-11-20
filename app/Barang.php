<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    public $incrementing =false;
    protected $keyType = 'string';
    protected $primaryKey = 'kode';
	protected $fillable=['kode', 'nama', 'stok', 'harga_jual', 'harga_beli_rata', 'jenis_id'];
    public $timestamps=false;

    public function jenis()
    {
    	return $this->belongsTo('App\Jenis', 'jenis_id');
    }

    public function notaBeli()
    {
    	return $this->belongsToMany('App\NotaBeli', 'barang_has_nota_beli', 'barang_kode', 'nota_beli_nomor')->withPivot('qty', 'harga', 'subtotal');
    }

    public function notaJual()
    {
    	return $this->belongsToMany('App\NotaJual', 'barang_has_nota_jual', 'barang_kode', 'nota_jual_id')->withPivot('qty', 'harga', 'subtotal');
    }
}

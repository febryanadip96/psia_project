<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'supplier';
    protected $primaryKey = 'id';
	protected $fillable=['nama', 'alamat', 'no_telp'];
    public $timestamps=false;
	protected $guarded=['id'];
}

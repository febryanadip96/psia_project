<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jenis extends Model
{
    protected $table = 'jenis';
    protected $primaryKey = 'id';
	protected $fillable=['nama'];
    public $timestamps=false;
	protected $guarded=['id'];   
}

<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\NotaJual;
use App\NotaPelunasanJual;

class NotaPelunasanJualController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth');
    }

    public function pelunasan($id)
    {
    	$notaJual = NotaJual::find($id);
    	return view('user.penjualan.pelunasan', ['notaJual' => $notaJual]);
    }

    public function simpan(Request $request)
    {
    	$tahun = substr(date("Y",strtotime($request->tanggal)), 2);
        $nomor = $tahun.date("md",strtotime($request->tanggal));
        $jumlah = NotaPelunasanJual::where('nomor','like', 'PJ'.$nomor.'%')->count()+1;
        if($jumlah<10){
            $nomor = $nomor."00".$jumlah;
        }
        else if($jumlah<100)
        {
            $nomor = $nomor."0".$jumlah;
        }
        else
        {
            $nomor = $nomor.$jumlah;
        }
    	$notaPelunasanJual = new NotaPelunasanJual();
    	$notaPelunasanJual->nomor= 'PJ'.$nomor;
    	$notaPelunasanJual->nota_jual_nomor = $request->nomor_nota;
    	$notaPelunasanJual->tanggal = $request->tanggal;
    	$notaPelunasanJual->nominal_seharusnya = $request->nominal_seharusnya;
    	$notaPelunasanJual->diskon_pelunasan = $request->diskon_pelunasan;
    	$notaPelunasanJual->nominal_bayar = $request->nominal_bayar;

    	NotaJual::where('nomor', $request->nomor_nota)->update(['status' => 2]);

    	$notaPelunasanJual->save();

    	return redirect()->action('User\PenjualanController@index');
    }

    public function lihat($id)
    {
    	$notaJual = NotaJual::find($id);
    	return view('user.penjualan.lihatpelunasan', ['notaJual' => $notaJual]);
    }
}

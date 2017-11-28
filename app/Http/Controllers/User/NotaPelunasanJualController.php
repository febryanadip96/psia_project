<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\NotaJual;
use App\NotaPelunasanJual;
use App\Bank;

class NotaPelunasanJualController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth');
    }

    public function pelunasan($id)
    {
    	$notaJual = NotaJual::find($id);
        $banks = Bank::all();
    	return view('user.penjualan.pelunasan', ['notaJual' => $notaJual, 'banks' => $banks]);
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
        $notaPelunasanJual->cara_bayar = $request->cara_bayar;
        $notaPelunasanJual->bank_id = $request->bank_id;
        $notaPelunasanJual->no_rek = $request->no_rek;
        $notaPelunasanJual->pemilik_no_rek = $request->pemilik_no_rek;
        $notaPelunasanJual->no_cek = $request->no_cek;


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

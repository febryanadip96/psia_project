<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\NotaBeli;
use App\NotaPelunasanBeli;
use App\Bank;

class NotaPelunasanBeliController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth');
    }

    public function pelunasan($id)
    {
    	$notaBeli = NotaBeli::find($id);
        $banks = Bank::all();
    	return view('user.pembelian.pelunasan', ['notaBeli' => $notaBeli, 'banks' => $banks]);
    }

    public function simpan(Request $request)
    {
    	$tahun = substr(date("Y",strtotime($request->tanggal)), 2);
        $nomor = $tahun.date("md",strtotime($request->tanggal));
        $jumlah = NotaPelunasanBeli::where('nomor','like', 'PB'.$nomor.'%')->count()+1;
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
    	$notapelunasanbeli = new NotaPelunasanBeli();
    	$notapelunasanbeli->nomor= 'PB'.$nomor;
    	$notapelunasanbeli->nota_beli_nomor = $request->nomor_nota;
    	$notapelunasanbeli->tanggal = $request->tanggal;
    	$notapelunasanbeli->nominal_seharusnya = $request->nominal_seharusnya;
    	$notapelunasanbeli->diskon_pelunasan = $request->diskon_pelunasan;
    	$notapelunasanbeli->nominal_bayar = $request->nominal_bayar;
        $notapelunasanbeli->cara_bayar = $request->cara_bayar;
        $notapelunasanbeli->bank_id = $request->bank_id;
        $notapelunasanbeli->no_rek = $request->no_rek;
        $notapelunasanbeli->pemilik_no_rek = $request->pemilik_no_rek;
        $notapelunasanbeli->no_cek = $request->no_cek;


    	NotaBeli::where('nomor', $request->nomor_nota)->update(['status' => 2]);

    	$notapelunasanbeli->save();

    	return redirect()->action('User\PembelianController@index');
    }

    public function lihat($id)
    {
    	$notaBeli = NotaBeli::find($id);
    	return view('user.pembelian.lihatpelunasan', ['notaBeli' => $notaBeli]);
    }
}

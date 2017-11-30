<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\NotaJual;
use App\NotaPelunasanJual;
use App\Bank;
use App\Jurnal;
use App\Akun;
use App\Periode;

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

        //Jurnal
        $jurnal = new Jurnal();
        $jurnal->tanggal = $notaPelunasanJual->tanggal;
        $jurnal->no_bukti = $notaPelunasanJual->nomor;
        $jurnal->jenis = 1;
        $periodeAktif = Periode::where('tgl_awal', '<=', $notaPelunasanJual->tanggal)->where('tgl_akhir', '>=', $notaPelunasanJual->tanggal)->first();
        $jurnal->periode_id = $periodeAktif->id;
        $keterangan = "Pelunasan Transaksi Penjualan ".$notaPelunasanJual->notaJual->tanggal;
        if($notaPelunasanJual->cara_bayar == 1){
            $keterangan .= " dengan tunai";
        }
        else if($notaPelunasanJual->cara_bayar == 2){
            $keterangan .= " dengan transfer";
        }
        else{
            $keterangan .= " dengan cek";
        }
        $jurnal->keterangan = $keterangan;
        $jurnal->save();

        //akun has jurnal
        $urutan = 1;
        //pemasukan
        if($notaPelunasanJual->cara_bayar == 1){//kas di tangan
            $akun = Akun::find('101');
            $akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => $notaPelunasanJual->nominal_bayar, 'nominal_kredit' => 0]);
        }
        else if($notaPelunasanJual->cara_bayar == 2){//transfer
            if($notaJual->bank_id == 1){//bank baca-baca
                $akun = Akun::find('102');
            }
            else{//bank suka sendiri
                $akun = Akun::find('103');
            }
            $akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => $notaPelunasanJual->nominal_bayar, 'nominal_kredit' => 0]);
        }
        else{//cek
            $akun = Akun::find('105');
            $akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => $notaPelunasanJual->nominal_bayar, 'nominal_kredit' => 0 ]);
        }
        $urutan++;

        //piutang dagang berkurang
        $akun = Akun::find('104');
        $akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => 0, 'nominal_kredit' => $notaPelunasanJual->nominal_seharusnya]);
        $urutan++;

        //diskon penjualan
        if($notaPelunasanJual->nominal_seharusnya!=$notaPelunasanJual->nominal_bayar){
            $akun = Akun::find('402');
            $akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => $notaPelunasanJual->nominal_seharusnya-$notaPelunasanJual->nominal_bayar, 'nominal_kredit' => 0]);
            $urutan++;
        }
        
    	return redirect()->action('User\PenjualanController@index');
    }


    public function lihat($id)
    {
    	$notaJual = NotaJual::find($id);
    	return view('user.penjualan.lihatpelunasan', ['notaJual' => $notaJual]);
    }
}

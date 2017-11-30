<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\NotaBeli;
use App\NotaPelunasanBeli;
use App\Bank;
use App\Jurnal;
use App\Akun;
use App\Periode;
use App\Barang;

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
    	$notaPelunasanBeli = new NotaPelunasanBeli();
    	$notaPelunasanBeli->nomor= 'PB'.$nomor;
    	$notaPelunasanBeli->nota_beli_nomor = $request->nomor_nota;
    	$notaPelunasanBeli->tanggal = $request->tanggal;
    	$notaPelunasanBeli->nominal_seharusnya = $request->nominal_seharusnya;
    	$notaPelunasanBeli->diskon_pelunasan = $request->diskon_pelunasan;
    	$notaPelunasanBeli->nominal_bayar = $request->nominal_bayar;
        $notaPelunasanBeli->cara_bayar = $request->cara_bayar;
        $notaPelunasanBeli->bank_id = $request->bank_id;
        $notaPelunasanBeli->no_rek = $request->no_rek;
        $notaPelunasanBeli->pemilik_no_rek = $request->pemilik_no_rek;
        $notaPelunasanBeli->no_cek = $request->no_cek;

    	NotaBeli::where('nomor', $request->nomor_nota)->update(['status' => 2]);

    	$notaPelunasanBeli->save();

        //jurnal
        $jurnal = new Jurnal();
        $jurnal->tanggal = $notaPelunasanBeli->tanggal;
        $jurnal->no_bukti = $notaPelunasanBeli->nomor;
        $jurnal->jenis = 1;
        $periodeAktif = Periode::where('tgl_awal', '<=', $notaPelunasanBeli->tanggal)->where('tgl_akhir', '>=', $notaPelunasanBeli->tanggal)->first();
        $jurnal->periode_id = $periodeAktif->id;
        $keterangan = "Pelunasan Transaksi Pembelian ".$notaPelunasanBeli->notaBeli->tanggal;
        if($notaPelunasanBeli->cara_bayar == 1){
            $keterangan .= " dengan tunai";
        }
        else if($notaPelunasanBeli->cara_bayar == 2){
            $keterangan .= " dengan transfer";
        }
        else{
            $keterangan .= " dengan cek";
        }
        $jurnal->keterangan = $keterangan;
        $jurnal->save();

        //akun has jurnal
        $urutan = 1;
        //hutang dagang
        $akun = Akun::find('201');
        $akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => $notaPelunasanBeli->nominal_seharusnya, 'nominal_kredit' => 0]);
        $urutan++;

        //pilih akun pembayaran
        if($notaPelunasanBeli->cara_bayar == 1){//tunai
            $akun = Akun::find('101');
            $akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => 0, 'nominal_kredit' => $notaPelunasanBeli->nominal_bayar]);
        }
        else if($notaPelunasanBeli->cara_bayar == 2){//transfer
            if($notaPelunasanBeli->bank_id == 1){//bank baca baca
                $akun = Akun::find('102');
            }
            else{//bank suka sendiri
                $akun = Akun::find('103');
            }
            $akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => 0, 'nominal_kredit' => $notaPelunasanBeli->nominal_bayar]);
        }
        else{//cek
            $akun = Akun::find('203');
            $akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => 0, 'nominal_kredit' => $notaPelunasanBeli->nominal_bayar]);
        }
        $urutan++;

        //update sediaan barang
        if($notaPelunasanBeli->nominal_seharusnya != $notaPelunasanBeli->nominal_bayar){
            $nominal = $notaPelunasanBeli->nominal_seharusnya-$notaPelunasanBeli->nominal_bayar;
            $barangs = $notaPelunasanBeli->notaBeli->barang;
            $jumlah_barang = $barangs->count();
            $nominalPerBarang = $nominal/$jumlah_barang;
            $alat_tulis = false;
            $rumah_tangga = false;
            foreach ($barangs as $key => $barang) {
                //tanyain
                $barang = Barang::where('kode', $barang->kode)->first();
                $total = $barang->stok*$barang->harga_beli_rata+$nominalPerBarang;
                $barang->harga_beli_rata = $total/$barang->stok;
                $barang->save();
                if($barang->jenis_id == 1){
                    $alat_tulis = true;
                }
                else{
                    $rumah_tangga =true;
                }
            }
            if($alat_tulis && $rumah_tangga){
                $akun = Akun::find('106');
                $akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => 0, 'nominal_kredit' => $nominal/2]);
                $urutan++;
                $akun = Akun::find('107');
                $akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => 0, 'nominal_kredit' => $nominal/2]);
            }
            else if($alat_tulis){
                $akun = Akun::find('106');
                $akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => 0, 'nominal_kredit' => $nominal]);
            }
            else{
                $akun = Akun::find('107');
                $akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => 0, 'nominal_kredit' => $nominal]);
            }
        }
        $urutan++;
    	return redirect()->action('User\PembelianController@index');
    }

    public function lihat($id)
    {
    	$notaBeli = NotaBeli::find($id);
    	return view('user.pembelian.lihatpelunasan', ['notaBeli' => $notaBeli]);
    }
}

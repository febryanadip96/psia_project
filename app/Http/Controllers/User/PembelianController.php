<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Barang;
use App\Supplier;
use App\Bank;
use App\NotaBeli;
use App\JasaPengiriman;
use Carbon\Carbon;
use App\Jurnal;
use App\Periode;
use App\Akun;

class PembelianController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

    public function index()
    {
        $notaBelis = NotaBeli::all();
    	return view('user.pembelian.index', ['notaBelis' => $notaBelis]);
    }

    public function create(Request $request)
    {
    	$barangs = Barang::all();
        $suppliers = Supplier::all();
        $banks = Bank::all();
        $jasa_pengirimans = JasaPengiriman::all();
    	return view('user.pembelian.create', ['barangs' => $barangs, 'suppliers' => $suppliers, 'banks' => $banks, 'jasa_pengirimans'=> $jasa_pengirimans]);
    }

    public function store(Request $request)
    {
        $tahun = substr(date("Y",strtotime($request->tanggal)), 2);
        $nomor = $tahun.date("md",strtotime($request->tanggal));
        $jumlah = NotaBeli::where('nomor','like', 'B'.$nomor.'%')->count()+1;
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
        $notaBeli = new NotaBeli();
        $notaBeli->nomor = 'B'.$nomor;
        $notaBeli->tanggal = $request->tanggal;
        $notaBeli->supplier_id = $request->supplier_id;
        $notaBeli->cara_bayar = $request->cara_bayar;
        $notaBeli->grand_total = $request->grand_total;
        $notaBeli->diskon_langsung = $request->diskon_langsung;
        if($request->cara_bayar==1){//tunai
            $notaBeli->status = 2;
        } else if($request->cara_bayar==2){//transfer
            $notaBeli->status = 2;
            $notaBeli->bank_id = $request->bank;
            $notaBeli->no_rek = $request->no_rek;
            $notaBeli->nama_pemilik_rek = $request->atas_nama;
        } else if($request->cara_bayar==3){//kredit
            $notaBeli->status = 1;
            $notaBeli->tgl_jatuh_tempo = $request->tgl_jatuh_tempo;
            $notaBeli->diskon_pelunasan = $request->diskon_pelunasan;
            $notaBeli->tgl_batas_diskon = $request->tgl_batas_diskon;
        } else{//pembelian dengan cek
            $notaBeli->status = 2;
            $notaBeli->no_cek = $request->no_cek;
        }
        //bila pengiriman = 1 tidak ada biaya dan dibayar oleh
        if($request->pengiriman==2){
            $notaBeli->biaya_kirim = $request->biaya_kirim;
            $notaBeli->dibayar_oleh = $request->pengiriman;
            $notaBeli->jasa_pengiriman_id = $request->jasa_pengiriman_id;
        }


        $notaBeli->save();

        $barangs = $request->barang;
        $hargas = $request->harga;
        $qtys = $request->qty;
        $subtotals = $request->subtotal;

        foreach ($barangs as $key => $barang) {
            $notaBeli->barang()->attach($barang, ['qty' => $qtys[$key], 'harga' => $hargas[$key], 'subtotal' => $subtotals[$key]*(100-$notaBeli->diskon_langsung)/100]);
            $barang = Barang::where('kode', $barang)->first();
            $barang->harga_beli_rata = (($barang->harga_beli_rata*$barang->stok)+($subtotals[$key]*(100-$notaBeli->diskon_langsung)/100))/($barang->stok+$qtys[$key]);
            $barang->stok += $qtys[$key];
            $barang->save();
        }


        //jurnal
        $jurnal = new Jurnal();
        $jurnal->tanggal = $notaBeli->tanggal;
        $jurnal->no_bukti = $notaBeli->nomor;
        $jurnal->jenis = 1;
        $keterangan = "";
        if($notaBeli->cara_bayar == 1){//tunai
            $keterangan = "Transaksi Pembelian Tunai";
        }
        else if($notaBeli->cara_bayar == 2){ //transfer
            $keterangan = "Transaksi Pembelian Transfer ke ".$notaBeli->bank->nama;
        }
        else if($notaBeli->cara_bayar == 3){//kredit
            $keterangan = "Transaksi Pembelian Kredit";
        }
        else{ //cek
            $keterangan = "Transaksi Pembelian Cek";
        }

        if($notaBeli->diskon_langsung){
            $keterangan .= " dengan diskon pembayaran";
        }

        if($request->pengiriman == 2){
            if($notaBeli->dibayar_oleh == 1){
                $keterangan .= " - FOB Shipping Point";
            }
            else{
                $keterangan .= " - FOB Destination Point";
            }
        }
        $jurnal->keterangan = $keterangan;

        $periodeAktif = Periode::where('tgl_awal', '<=', $notaBeli->tanggal)->where('tgl_akhir', '>=', $notaBeli->tanggal)->first();
        $jurnal->periode_id = $periodeAktif->id;
        $jurnal->save();

        //akun has jurnal
        $urutan = 1;
        $alat_tulis = 0;
        $rumah_tangga = 0;
        foreach ($barangs as $key => $barang) {
            $barang = Barang::find($barang);
            if($barang->jenis_id == 1){
                $alat_tulis += $subtotals[$key]*(100-$notaBeli->diskon_langsung)/100;
            }
            else{
                $rumah_tangga += $subtotals[$key]*(100-$notaBeli->diskon_langsung)/100;
            }
        }
        if($alat_tulis != 0){
            $akun = Akun::find('106');
            $akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => $alat_tulis, 'nominal_kredit' => 0]);
            $urutan++;
        }
        if($rumah_tangga != 0){
            $akun = Akun::find('107');
            $akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => $rumah_tangga, 'nominal_kredit' => 0]);
            $urutan++;
        }

        if($notaBeli->cara_bayar == 1){//tunai
            $akun = Akun::find('101');
            $akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => 0, 'nominal_kredit' => $notaBeli->grand_total]);
        }
        else if($notaBeli->cara_bayar == 2){//transfer
            if($notaBeli->bank_id == 1){//bank baca-baca
                $akun = Akun::find('102');
            }
            else{//bank suka sendiri
                $akun = Akun::find('103');
            }
            $akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => 0, 'nominal_kredit' => $notaBeli->grand_total]);
        }
        else if($notaBeli->cara_bayar == 3){//kredit
            $akun = Akun::find('201');
            $akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => 0, 'nominal_kredit' => $notaBeli->grand_total]);
        }
        else if($notaBeli->cara_bayar == 4){//cek
            $akun = Akun::find('203');
            $akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => 0, 'nominal_kredit' => $notaBeli->grand_total]);
        }
        $urutan++;

        if($request->dibayar_oleh == 1){
            if($alat_tulis !=0 && $rumah_tangga !=0){
                $akun = Akun::find('106');
                $akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => $notaBeli->biaya_kirim/2, 'nominal_kredit' => 0]);
                $urutan++;
                $akun = Akun::find('107');
                $akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => $notaBeli->biaya_kirim/2, 'nominal_kredit' => 0]);
            }
            else if($alat_tulis != 0){
                $akun = Akun::find('106');
                $akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => $notaBeli->biaya_kirim, 'nominal_kredit' => 0]);
            }
            else{
                $akun = Akun::find('107');
                $akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => $notaBeli->biaya_kirim, 'nominal_kredit' => 0]);
            }
            $urutan++;

            if($notaBeli->cara_bayar == 1){//tunai
                $akun = Akun::find('101');
                $akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => 0, 'nominal_kredit' => $notaBeli->biaya_kirim]);
            }
            else if($notaBeli->cara_bayar == 2){//transfer
                if($notaBeli->bank_id == 1){
                    $akun = Akun::find('102');
                }
                else{
                    $akun = Akun::find('103');
                }
                $akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => 0, 'nominal_kredit' => $notaBeli->biaya_kirim]);
            }
            else if($notaBeli->cara_bayar == 3){//kredit
                $akun = Akun::find('201');
                $akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => 0, 'nominal_kredit' => $notaBeli->biaya_kirim]);
            }
            else{//cek
                $akun = Akun::find('203');
                $akun->jurnal()->attach($jurnal->id, ['urutan' => $urutan, 'nominal_debet' => 0, 'nominal_kredit' => $notaBeli->biaya_kirim]);
            }
        }


        return redirect()->action('User\PembelianController@index');
    }
}


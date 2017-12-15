<?php

namespace App\Helpers;

use DB;

class Data
{
    public static function SaldoAkhir($idPeriode)
    {
        $hasil = DB::table('akun as a')
            ->leftJoin('akun_has_jurnal as ja', 'ja.akun_nomor', '=', 'a.nomor')
            ->join('periode_has_akun as pa', 'pa.akun_nomor', '=', 'a.nomor')
            ->join('periode as p', 'p.id', '=', 'pa.periode_id')
            ->selectRaw('a.nomor as nomor, a.nama as NamaAkun, pa.saldo_awal + ifnull(((sum(ja.nominal_debet) - sum(ja.nominal_kredit)) * a.saldo_normal),0) AS SaldoAkhir',[])
            ->whereRaw('p.id = ?', [$idPeriode])
            ->groupBy('ja.akun_nomor','a.nama','a.nomor','pa.saldo_awal', 'a.saldo_normal')
            ->orderBy('a.nomor');
        return $hasil;
    }

    public static function PerubahanEkuitas($idPeriode)
    {
        $first = DB::table('akun as a')->join('periode_has_akun as p', 'a.nomor', '=', 'p.akun_nomor')->whereRaw('a.nomor = "000" and p.periode_id = ?',[$idPeriode])->selectRaw('a.nomor AS nomor, a.nama AS nama, p.saldo_awal AS SaldoAkhir');
        $hasil = DB::table('akun as a')
            ->leftJoin('akun_has_jurnal as ja', 'ja.akun_nomor', '=', 'a.nomor')
            ->join('periode_has_akun as pa', 'pa.akun_nomor', '=', 'a.nomor')
            ->join('periode as p', 'p.id', '=', 'pa.periode_id')
            ->selectRaw('a.nomor as nomor, a.nama as NamaAkun, pa.saldo_awal + ifnull(((sum(ja.nominal_debet) - sum(ja.nominal_kredit)) * a.saldo_normal),0) AS SaldoAkhir',[])
            ->whereRaw('p.id = ?', [$idPeriode])
            ->groupBy('ja.akun_nomor','a.nama','a.nomor','pa.saldo_awal', 'a.saldo_normal')
            ->orderBy('a.nomor')
            ->join('laporan_has_akun as l', 'a.nomor', '=', 'l.akun_nomor')
            ->whereRaw('l.laporan_id = "PE"',[])
            ->union($first);
        return $hasil;
    }

    public static function Neraca($idPeriode)
    {
        $hasil = DB::table('akun as a')
            ->leftJoin('akun_has_jurnal as ja', 'ja.akun_nomor', '=', 'a.nomor')
            ->join('periode_has_akun as pa', 'pa.akun_nomor', '=', 'a.nomor')
            ->join('periode as p', 'p.id', '=', 'pa.periode_id')
            ->join('laporan_has_akun as l', 'a.nomor', '=', 'l.akun_nomor')
            ->selectRaw('a.nomor as nomor, a.nama as NamaAkun, pa.saldo_awal + ifnull(((sum(ja.nominal_debet) - sum(ja.nominal_kredit)) * a.saldo_normal),0) AS SaldoAkhir',[])
            ->whereRaw('p.id = ? and l.laporan_id = "NR"', [$idPeriode])
            ->groupBy('ja.akun_nomor','a.nama','a.nomor','pa.saldo_awal', 'a.saldo_normal')
            ->orderBy('a.nomor');
        return $hasil;
    }

    public static function LaporanJurnal($idPeriode)
    {
        $hasil = DB::table('jurnal as j')
            ->join('akun_has_jurnal as ja', 'j.id', '=', 'ja.jurnal_id')
            ->join('akun as a', 'ja.akun_nomor', '=', 'a.nomor')
            ->selectRaw('j.tanggal as tanggal, j.keterangan as keterangan, a.nama as NamaAkun, ja.nominal_debet as Debet ,ja.nominal_kredit AS Kredit, j.no_bukti AS NomorBukti',[])
            ->whereRaw('j.periode_id = ?',[$idPeriode])
            ->orderBy('j.id', 'asc')
            ->orderBy('ja.urutan', 'asc');
        return $hasil;
    }

    public static function LabaRugi($idPeriode)
    {
        $hasil = DB::table('akun as a')
            ->leftJoin('akun_has_jurnal as ja', 'ja.akun_nomor', '=', 'a.nomor')
            ->join('periode_has_akun as pa', 'pa.akun_nomor', '=', 'a.nomor')
            ->join('periode as p', 'p.id', '=', 'pa.periode_id')
            ->join('laporan_has_akun as l', 'a.nomor', '=', 'l.akun_nomor')
            ->selectRaw('a.nomor as nomor, a.nama as NamaAkun, pa.saldo_awal + ifnull(((sum(ja.nominal_debet) - sum(ja.nominal_kredit)) * a.saldo_normal),0) AS SaldoAkhir',[])
            ->whereRaw('p.id = ? and l.laporan_id = "LR"', [$idPeriode])
            ->groupBy('ja.akun_nomor','a.nama','a.nomor','pa.saldo_awal', 'a.saldo_normal')
            ->orderBy('a.nomor');
        return $hasil;
    }

    public static function ArusKas($idPeriode)
    {
        $hasil = DB::table('akun as a')
            ->join('akun_has_jurnal as ja', 'ja.akun_nomor', '=', 'a.nomor')
			->join('jurnal as j', 'ja.jurnal_id', '=', 'j.id')
            ->join('periode_has_akun as pa', 'pa.akun_nomor', '=', 'a.nomor')
            ->join('periode as p', 'p.id', '=', 'j.periode_id')
            ->join('laporan_has_akun as l', 'a.nomor', '=', 'l.akun_nomor')
            ->selectRaw('a.nomor as nomor, a.nama as NamaAkun, pa.saldo_awal + ifnull(((sum(ja.nominal_debet) - sum(ja.nominal_kredit)) * a.saldo_normal),0) AS SaldoAkhir',[])
            ->whereRaw('p.id = ? and l.laporan_id = "AK"', [$idPeriode])
            ->groupBy('ja.akun_nomor','a.nama','a.nomor','pa.saldo_awal', 'a.saldo_normal')
            ->orderBy('a.nomor');
        return $hasil;
    }

    public static function BukuBesar($idPeriode)
    {
        $hasil = DB::table('akun_has_jurnal as aj')
            ->join('akun as a', 'aj.akun_nomor', '=', 'a.nomor')
            ->join('periode_has_akun as p', 'p.akun_nomor', '=', 'a.nomor')
            ->join('jurnal as j', 'aj.jurnal_id', '=', 'j.id')
            ->selectRaw('aj.akun_nomor AS akun_nomor, a.nama AS NamaAkun, j.tanggal AS tanggal, j.keterangan AS keterangan, aj.nominal_debet AS nominal_debet, aj.nominal_kredit AS nominal_kredit, j.no_bukti AS no_bukti',[])
			->whereRaw('j.periode_id = ?',[$idPeriode])
            ->orderBy('aj.akun_nomor');
        return $hasil;
    }
}

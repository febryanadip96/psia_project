@extends('layouts.app')

@section('content')
<div class="container">

    <a href="{{url('penjualan/create')}}" class="btn btn-md btn-success pull-right"><span class="glyphicon glyphicon-plus"></span> Tambah</a>
    <h3>Tabel Penjualan</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nomor</th>
                <th>Pelanggan</th>
                <th>Tanggal</th>
                <th>Total Bayar</th>
                <th>Jenis Pembayaran</th>
                <th>Status</th>
                <th>Nota Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            @foreach($notaJuals as $notaJual)
                <tr>
                    <td>{{$notaJual->nomor}}</td>
                    <td>{{$notaJual->pelanggan->nama}}</td>
                    <td>{{Carbon\Carbon::parse($notaJual->tanggal)->formatLocalized('%A, %d %B %Y')}}</td>
                    <td>Rp. {{$notaJual->grand_total}}</td>
                    <td>{{$notaJual->cara_bayar}}</td>
                    <td>{{$notaJual->status}}</td>
                    <td>
                        @if($notaJual->status!='Lunas')
                            <a href="{{url('penjualan/pelunasan/'.$notaJual->nomor)}}"><span class="glyphicon glyphicon-file"></span></a>
                        @endif
                        @if($notaJual->status=='Lunas' && $notaJual->cara_bayar == 'kredit')
                            <a href="{{url('penjualan/pelunasan/'.$notaJual->nomor.'/lihat')}}"><span class="glyphicon glyphicon-eye-open"></span></a>
                        @endif
                        @if($notaJual->status=='Lunas' && $notaJual->cara_bayar == 'tunai')
                            <a href="{{url('penjualan/pelunasan/'.$notaJual->nomor.'/lihat')}}"><span class="glyphicon glyphicon-eye-open"></span></a>
                        @endif
                        @if($notaJual->status=='Lunas' && $notaJual->cara_bayar == 'cek')
                            <a href="{{url('penjualan/pelunasan/'.$notaJual->nomor.'/lihat')}}"><span class="glyphicon glyphicon-eye-open"></span></a>
                        @endif
                        @if($notaJual->status=='Lunas' && $notaJual->cara_bayar == 'transfer')
                            <a href="{{url('penjualan/pelunasan/'.$notaJual->nomor.'/lihat')}}"><span class="glyphicon glyphicon-eye-open"></span></a>
                        @endif
                    </td>
                </tr>
            @endforeach
            
        </tbody>
    </table>
</div>

@endsection

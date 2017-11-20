@extends('layouts.app')

@section('content')
<div class="container">

    <a href="{{url('pembelian/create')}}" class="btn btn-md btn-success pull-right"><span class="glyphicon glyphicon-plus"></span> Tambah</a>
    <h3>Tabel Pembelian</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nomor</th>
                <th>Supplier</th>
                <th>Tanggal</th>
                <th>Total Bayar</th>
                <th>Jenis Pembayaran</th>
                <th>Status</th>
                <th>Nota Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            @foreach($notaBelis as $notaBeli)
                <tr>
                    <td><a>{{$notaBeli->nomor}}</a></td>
                    <td>{{$notaBeli->supplier->nama}}</td>
                    <td>{{Carbon\Carbon::parse($notaBeli->tanggal)->formatLocalized('%A, %d %B %Y')}}</td>
                    <td>Rp. {{$notaBeli->grand_total}}</td>
                    <td>{{$notaBeli->cara_bayar}}</td>
                    <td>{{$notaBeli->status}}</td>
                    <th><span class="glyphicon glyphicon-file"></span></th>
                </tr>
            @endforeach
            
        </tbody>
    </table>
</div>
@endsection
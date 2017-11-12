@extends('layouts.app')

@section('content')
<div class="container">

    <h3>Tabel Penjualan</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tanggal Awal</th>
                <th>Tanggal Akhir</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{$periode->id}}</td>
                <td>{{\Carbon\Carbon::parse($periode->tgl_awal)->formatLocalized('%A, %d %B %Y')}}</td>
                <td>{{\Carbon\Carbon::parse($periode->tgl_akhir)->formatLocalized('%A, %d %B %Y')}}</td>
                <td><a href="{{url('tutup')}}" class="btn btn-danger">Tutup <span class="glyphicon glyphicon-remove"></span></a></td>
            </tr>
        </tbody>
    </table>
</div>

@endsection

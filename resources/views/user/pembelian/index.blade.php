@extends('layouts.app')

@section('content')
<div class="container">
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#pembelian">Pembelian</a></li>
        <li><a data-toggle="tab" href="#terima">Terima</a></li>
        <li><a data-toggle="tab" href="#bayar">Bayar</a></li>
    </ul>

    <div class="tab-content">
        <div id="pembelian" class="tab-pane fade in active">
            <a href="{{url('pembelian/create')}}" class="btn btn-md btn-success pull-right"><span class="glyphicon glyphicon-plus"></span> Tambah</a>
            <h3>Tabel Pembelian</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID Nota</th>
                        <th>Supplier</th>
                        <th>Total Bayar</th>
                        <th>Jenis Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><a>001</a></td>
                        <td>UD 'Suka Sendiri'</td>
                        <td>Rp. 70.000.000</td>
                        <td>Kredit</td>
                    </tr>
                    <tr>
                        <td><a>002</a></td>
                        <td>UD 'Suka Sendiri'</td>
                        <td>Rp. 45.000.000</td>
                        <td>Tunai</td>
                    </tr>
                    <tr>
                        <td><a>003</a></td>
                        <td>UD 'Suka Sendiri'</td>
                        <td>Rp. 15.000.000</td>
                        <td>Transfer</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div id="terima" class="tab-pane fade">
            <h3>Terima</h3>
            <p>Some content in menu 1.</p>
        </div>
        <div id="bayar" class="tab-pane fade">
            <h3>Bayar</h3>
            <p>Some content in menu 2.</p>
        </div>
    </div>
</div>
@endsection

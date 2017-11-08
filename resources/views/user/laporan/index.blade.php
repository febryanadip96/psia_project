@extends('layouts.app')

@section('content')
<div class="container">
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#laporan">Laporan</a></li>
        <li><a data-toggle="tab" href="#terima">Terima</a></li>
        <li><a data-toggle="tab" href="#bayar">Bayar</a></li>
    </ul>

    <div class="tab-content">
        <div id="pembelian" class="tab-pane fade in active">
            <h3>Ini Laporan</h3>
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

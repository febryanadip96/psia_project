@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="text-center">Form Pembelian</h4><br>
    <form class="form-horizontal">
        <div class="form-group">
            <label class="control-label col-sm-2" for="nomor_nota">No. Nota:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="nomor_nota" id="nomor_nota" placeholder="Nomor Nota" required>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="tanggal_nota">Tanggal:</label>
            <div class="col-sm-10"> 
                <input type="date" class="form-control" name="tanggal_nota" id="tanggal_nota" required>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="supplier">Supplier:</label>
            <div class="col-sm-10"> 
                <select class="form-control" id="supplier" name="supplier_id" required>
                    @foreach($suppliers as $supplier)
                        <option value="{{$supplier->id}}">{{$supplier->nama}}</option>
                    @endforeach
                </select><br>
                <p>Alamat:<span id="alamat"></span></p>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="tgl_jatuh_tempo">Tanggal Jatuh Tempo:</label>
            <div class="col-sm-10"> 
                <input type="date" class="form-control" id="tgl_jatuh_tempo" name="tgl_jatuh_tempo" required>
            </div>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="tabel-barang">
                
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="text-center"><a id="tambah" class="btn btn-sm btn-primary" style="vertical-align: middle;"><span class="glyphicon glyphicon-plus"></span></a></td>
                </tr>
                
            </tfoot>
        </table>
        <div class="form-group">
            <label class="control-label col-sm-2" for="grand_total">Grand Total:</label>
            <div class="col-sm-10"> 
                <input id="grand_total" class='form-control' type='number' disabled>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="jenis_pembayaran">Jenis Pembayaran:</label>
            <div class="col-sm-10"> 
                <div class="radio">
                    <label><input type="radio" name="jenis_pembayaran" checked>Tunai</label>
                </div>
                <div class="radio">
                    <label><input type="radio" name="jenis_pembayaran">Kredit</label>
                </div>
                <div class="radio">
                    <label><input type="radio" name="jenis_pembayaran">Transfer</label>
                </div>
                <div class="radio">
                    <label><input type="radio" name="jenis_pembayaran">Cek</label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="bank">Nama Bank:</label>
            <div class="col-sm-10">
                <select class="form-control" id="bank" name="bank">
                    @foreach($banks as $bank)
                        <option value="{{$bank->id}}">{{$bank->nama}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="no_rek">No. Rek:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="no_rek" id="no_rek" placeholder="Nomor Rekening">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="atas_nama">Atas Nama:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="atas_nama" id="atas_nama" placeholder="Atas Nama">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="pengiriman">Status Pengiriman:</label>
            <div class="col-sm-10"> 
                <div class="radio">
                    <label><input type="radio" name="pengiriman" checked>Diambil</label>
                </div>
                <div class="radio">
                    <label><input type="radio" name="pengiriman">Dikirim</label>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary pull-right">Simpan</button>
    </form>
</div><br>

<script>
    $(function(){
        $('#tambah').on('click', function(){
            $('#tabel-barang').append("<tr class='item'><td><select name='barang[]' class='form-control'><option></option>"+
                @foreach($barangs as $barang)
                "<option value='{{$barang->kode}}'>{{$barang->nama}}</option>"+ 
                @endforeach
                "</select><td><input type='number' class='harga form-control' name='harga[]'></td><td><input class='qty form-control' type='number'></td><td><input class='subtotal form-control' type='number' disabled></td><td><a class='btn btn-sm btn-danger hapus' style='vertical-align: middle;'><span class='glyphicon glyphicon-minus'></span></a></td></tr>"
            );
            $('.harga').change(function(){
                var row = $(this).closest('tr');
                var harga = $(this).val();
                var qty = row.find('.qty').val();
                var subtotal = harga*qty;
                row.find('.subtotal').val(subtotal);
            });
            $('.qty').change(function(){
                var row = $(this).closest('tr');
                var qty = $(this).val();
                var harga = row.find('.harga').val();
                var subtotal = harga*qty;
                row.find('.subtotal').val(subtotal);
            });
            $('.hapus').on('click', function(){
                $(this).closest('tr').remove();
                var grand_total = 0;
                $('.item').each(function(){
                    grand_total += parseInt($(this).find('.subtotal').val());
                });
                $('#grand_total').val(grand_total);
            });
            $('.harga, .qty').change(function(){
                //alert('masuk');
                var grand_total = 0;
                $('.item').each(function(){
                    grand_total += parseInt($(this).find('.subtotal').val());
                });
                $('#grand_total').val(grand_total);
            });
        });
    });

</script>
@endsection

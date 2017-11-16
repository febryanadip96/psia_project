@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{url('pembelian')}}" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Kembali</a>
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
                    <td colspan="5" class="text-center"><a id="tambah" class="btn btn-sm btn-primary" style="vertical-align: middle;"><span class="glyphicon glyphicon-plus"></span> Tambah Barang</a></td>
                </tr>
            </tfoot>
        </table>
        <div class="form-group">
            <label class="control-label col-sm-2" for="grand_total">Grand Total:</label>
            <div class="col-sm-10"> 
                <input id="grand_total" class="form-control" name="grand_total" type="number" value="0" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="diskon_langsung">Diskon Langsung:</label>
            <div class="col-sm-10"> 
                <input id="diskon_langsung" class="form-control" name="diskon_langsung"  type="number">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="cara_bayar">Cara Bayar:</label>
            <div class="col-sm-10"> 
                <div class="radio">
                    <label><input type="radio" name="cara_bayar" value="1" id="tunai" checked>Tunai</label>
                </div>
                <div class="radio">
                    <label><input type="radio" name="cara_bayar" value="2" id="kredit">Kredit</label>
                </div>
                <div class="radio">
                    <label><input type="radio" name="cara_bayar" value="3" id="transfer">Transfer</label>
                </div>
                <div class="radio">
                    <label><input type="radio" name="cara_bayar" value="4" id="cek">Cek</label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="bank">Nama Bank:</label>
            <div class="col-sm-10">
                <select class="form-control" id="bank" name="bank" disabled>
                    @foreach($banks as $bank)
                        <option value="{{$bank->id}}">{{$bank->nama}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="no_rek">No. Rekening Tujuan:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="no_rek" id="no_rek" placeholder="Nomor Rekening" disabled>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="atas_nama">Atas Nama:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="atas_nama" id="atas_nama" placeholder="Atas Nama" disabled>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="no_cek">No. Cek:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="no_cek" id="no_cek" placeholder="Nomor Cek" disabled>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="tgl_jatuh_tempo">Tanggal Jatuh Tempo:</label>
            <div class="col-sm-10"> 
                <input type="date" class="form-control" id="tgl_jatuh_tempo" name="tgl_jatuh_tempo" disabled>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="diskon_pelunasan">Diskon Pelunasan:</label>
            <div class="col-sm-10"> 
                <input id="diskon_pelunasan" class="form-control" name="diskon_pelunasan" type="number" disabled>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="tgl_batas_diskon">Tanggal Batas Diskon:</label>
            <div class="col-sm-10"> 
                <input type="date" class="form-control" id="tgl_batas_diskon" name="tgl_batas_diskon" disabled>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2">Status Pengiriman:</label>
            <div class="col-sm-10"> 
                <div class="radio">
                    <label><input type="radio" id="diambil" name="pengiriman" value="1" checked>Diambil</label>
                </div>
                <div class="radio">
                    <label><input type="radio" id="dikirim" name="pengiriman" value="2">Dikirim</label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="biaya_kirim">Biaya Kirim:</label>
            <div class="col-sm-10"> 
                <input id="biaya_kirim" name="biaya_kirim" class="form-control" type="number" disabled>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="dibayar_oleh">Dibayar Oleh:</label>
            <div class="col-sm-10"> 
                <div class="radio">
                    <label><input type="radio" id="dibayar_perusahaan" name="dibayar_oleh" value="1" checked disabled>Perusahaan</label>
                </div>
                <div class="radio">
                    <label><input type="radio" id="dibayar_supplier" name="dibayar_oleh" value="2" disabled>Supplier</label>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-floppy-disk"></span> Simpan</button>
    </form>
</div><br>

<script>
    $(function(){
        $('#tunai').change(function(){
            $('#bank').attr('disabled',true);
            $('#no_rek').attr('disabled',true);
            $('#no_cek').attr('disabled',true);
            $('#atas_nama').attr('disabled',true);
            $('#tgl_jatuh_tempo').attr('disabled', true);
            $('#diskon_pelunasan').attr('disabled', true);
            $('#tgl_batas_diskon').attr('disabled', true);
        });
        $('#kredit').change(function(){
            $('#bank').attr('disabled',true);
            $('#no_rek').attr('disabled',true);
            $('#no_cek').attr('disabled',true);
            $('#atas_nama').attr('disabled',true);
            $('#tgl_jatuh_tempo').attr('disabled', false);
            $('#diskon_pelunasan').attr('disabled', false);
            $('#tgl_batas_diskon').attr('disabled', false);
        });
        $('#transfer').change(function(){
            $('#bank').attr('disabled',false);
            $('#no_rek').attr('disabled',false);
            $('#no_cek').attr('disabled',true);
            $('#atas_nama').attr('disabled',false);
            $('#tgl_jatuh_tempo').attr('disabled', true);
            $('#diskon_pelunasan').attr('disabled', true);
            $('#tgl_batas_diskon').attr('disabled', true);
        });
        $('#cek').change(function(){
            $('#bank').attr('disabled',true);
            $('#no_rek').attr('disabled',true);
            $('#no_cek').attr('disabled',false);
            $('#atas_nama').attr('disabled',true);
            $('#tgl_jatuh_tempo').attr('disabled', true);
            $('#diskon_pelunasan').attr('disabled', true);
            $('#tgl_batas_diskon').attr('disabled', true);
        });
        $('#diambil').change(function(){
            $('#biaya_kirim').attr('disabled', true);
            $('#dibayar_perusahaan').attr('disabled', true);
            $('#dibayar_supplier').attr('disabled', true);
        });
        $('#dikirim').change(function(){
            $('#biaya_kirim').attr('disabled', false);
            $('#dibayar_perusahaan').attr('disabled', false);
            $('#dibayar_supplier').attr('disabled', false);
        });
        $('#tambah').on('click', function(){
            $('#tabel-barang').append("<tr class='item'><td><select name='barang[]' class='form-control'><option></option>"+
                @foreach($barangs as $barang)
                "<option value='{{$barang->kode}}'>{{$barang->nama}}</option>"+ 
                @endforeach
                "</select><td><input type='number' class='harga form-control' name='harga[]'></td><td><input class='qty form-control' type='number'></td><td><input class='subtotal form-control' type='number' readonly></td><td><a class='btn btn-sm btn-danger hapus' style='vertical-align: middle;'><span class='glyphicon glyphicon-minus'></span></a></td></tr>"
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
                hitungGrandTotal();
            });
            $('.harga, .qty').change(function(){
                hitungGrandTotal();
            });
            function hitungGrandTotal(){
                var grand_total = 0;
                $('.item').each(function(){
                    grand_total += Number($(this).find('.subtotal').val());
                });
                grand_total = parseInt(grand_total);
                $('#grand_total').val(grand_total);
            }
        });
    });
    

</script>
@endsection
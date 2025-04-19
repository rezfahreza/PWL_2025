@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <button onclick="modalAction('{{ url('/stok/import') }}')" class="btn btn-info btn-sm mt-1">Import Stok</button>
            <a href="{{ url('/stok/export_excel') }}" class="btn btn-primary btn-sm mt-1"><i class="fa fa-file excel"></i> Export Stok</a>
            <a href="{{ url('/stok/export_pdf') }}" class="btn btn-warning btn-sm mt-1"><i class="fa fa-file pdf"></i> Export Stok</a>
            <button onclick="modalAction('{{ url('/stok/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah Ajax</button>
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
             <div class="alert alert-success">{{ session('success') }}</div>
         @endif
         @if (session('error'))
             <div class="alert alert-danger">{{ session('error') }}</div>
         @endif
         <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="barang_id">Nama Barang</label>
                    <select class="form-control" id="barang_id" name="barang_id">
                        <option value="">- Semua -</option>
                        @foreach($barang as $item)
                            <option value="{{ $item->barang_id }}">{{ $item->barang_nama }}</option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">Filter berdasarkan barang</small>
                </div>
            </div>
        
            <div class="col-md-4">
                <div class="form-group">
                    <label for="supplier_id">Nama Supplier</label>
                    <select class="form-control" id="supplier_id" name="supplier_id">
                        <option value="">- Semua -</option>
                        @foreach($supplier as $item)
                            <option value="{{ $item->supplier_id }}">{{ $item->supplier_nama }}</option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">Filter berdasarkan supplier</small>
                </div>
            </div>
        
            <div class="col-md-4">
                <div class="form-group">
                    <label for="user_id">Username User</label>
                    <select class="form-control" id="user_id" name="user_id">
                        <option value="">- Semua -</option>
                        @foreach($user as $item)
                            <option value="{{ $item->user_id }}">{{ $item->username }}</option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">Filter berdasarkan user</small>
                </div>
            </div>
        </div>                
        <table class="table table-bordered table-striped table-hover table-sm" id="table_stok">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Barang</th>
                    <th>Supplier</th>
                    <th>User</th>
                    <th>Tanggal</th>
                    <th>Jumlah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('css')
@endpush

@push('js')
<script>
    function modalAction(url = '') {
        $('#myModal').load(url, function () {
            $('#myModal').modal('show');
        });
    }

    var dataStok;
    $(document).ready(function() {
        dataStok = $('#table_stok').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                "url": "{{ url('stok/list') }}",
                "type": "POST",
                "data": function (d) {
                    d.barang_id   = $('#barang_id').val();
                    d.supplier_id = $('#supplier_id').val();
                    d.user_id     = $('#user_id').val();
                }
            },
            searchDelay: 1000,
            columns: [
                {
                    data: "DT_RowIndex",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "barang.barang_nama",
                    orderable: false,
                    searchable: true
                },
                {
                    data: "supplier.supplier_nama",
                    orderable: false,
                    searchable: true
                },
                {
                    data: "user.username",
                    orderable: false,
                    searchable: true
                },
                {
                    data: "stok_tanggal",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "stok_jumlah",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "aksi",
                    className: "",
                    orderable: false,
                    searchable: false
                }
            ]
        });

        $('#barang_id, #supplier_id, #user_id').on('change', function() {
            dataStok.ajax.reload();
        });

    });
</script>
@endpush
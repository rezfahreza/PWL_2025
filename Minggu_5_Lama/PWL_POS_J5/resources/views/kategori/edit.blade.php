@extends('layouts.app')

@section('subtitle', 'Edit Kategori')
@section('content_header_title', 'Edit Kategori')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h5>Edit Kategori</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('kategori.update', $kategori->kategori_id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="kode_kategori" class="form-label">Kode Kategori</label>
                    <input type="text" name="kode_kategori" class="form-control" 
                           value="{{ old('kode_kategori', $kategori->kode_kategori) }}" required>
                </div>
                <div class="mb-3">
                    <label for="nama_kategori" class="form-label">Nama Kategori</label>
                    <input type="text" name="nama_kategori" class="form-control" 
                           value="{{ old('nama_kategori', $kategori->nama_kategori) }}" required>
                </div>
                <button type="submit" class="btn btn-success">Update</button>
            </form>
        </div>
    </div>
</div>
@endsection
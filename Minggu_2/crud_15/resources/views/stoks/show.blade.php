<!DOCTYPE html>
<html>
<head>
    <title>Stok List</title>
</head>
<body>
    <h1>Stoks</h1>
    @if(session('success'))<!--Mengecek apakah ada pesan success-->
        <p>{{ session('success') }}</p><!--Menampilkan pesan sukses-->
    @endif<!--menutup blok if-->
    <a href="{{ route('stoks.create') }}">Add Stok</a><!--mengarahkan halaman ke tambah stok-->
    <ul>
        @foreach ($stoks as $stok)
            <li>
                {{ $stok->nama }} -
                <a href="{{ route('stoks.edit', $stok) }}">Edit</a><!-- Link untuk mengedit stok tertentu.-->
                <form action="{{ route('stoks.destroy', $stok) }}" method="POST" style="display:inline;"><!--Membuat form untuk menghapus stok-->
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            </li>
        @endforeach
    </ul>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <title>Stoks List</title>
</head>
<body>
    <h1>Stoks</h1>
    @if (session('success')) <!--Mengecek apakah ada pesan sukses dalam sesi.-->
        <p>{{session('succes')}}</p> <!--Menampilkan pesan sukses-->
    @endif<!--menutup blok if-->
    <a href="{{route('stoks.create')}}">Add Stoks</a> <!--mengarahkan halaman ke tambah stok-->
    <ul>
        @foreach ($stoks as $stok)
            <li>
                {{$stok->nama}} -
                <a href="{{route('stoks.edit', $stok)}}">Edit</a> <!-- Link untuk mengedit stok tertentu.-->
                <form action="{{rote('stoks.destroy', $stok)}}" method="POST" style="display:inline;"></form>
                @csrf
                @method('DELETE')<!--Membuat form untuk menghapus stok-->
                <button type="submit">Delete</button>
            </li>
        @endforeach
    </ul>
</body>
</html>
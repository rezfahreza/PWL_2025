<!DOCTYPE html>
<html>
<head>
    <title>Add Stok</title>
</head>
<body>
    <h1>Add Stoks</h1>
    <form action="{{ route('stoks.store') }}" method="POST">
        @csrf
        <label for="nama">Nama:</label>
        <input type="text" name="nama" required> <!--Input teks wajib diisi untuk Nama-->
        <br>
        <label for="kondisi">Kondisi:</label>
        <textarea name="kondisi" required></textarea><!--Input area teks wajib diisi untuk Kondisi-->
        <br>
        <button type="submit">Add Stok</button>
    </form>
    <a href="{{ route('stoks.index') }}">Back to list</a><!-- Link kembali ke daftar stok-->
</body>
</html>
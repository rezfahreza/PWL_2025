<!DOCTYPE html>
<html>
<head>
    <title>Edit Stok</title>
</head>
<body>
    <h1>Edit Stok</h1>
    <form action="{{ route('stoks.update', $stok) }}" method="POST"><!--Membuat form untuk memperbarui stok dengan metode POST-->
        @csrf
        @method('PUT')<!-- Mengubah metode request menjadi PUT untuk update data-->
        <label for="nama">Nama:</label>
        <input type="text" name="nama" value="{{ $stok->nama }}" required><!--Input teks dengan nilai default dari stok-->
        <br>
        <label for="kondisi">Kondisi:</label>
        <textarea name="kondisi" required>{{ $stok->kondisi }}</textarea><!--Input area teks dengan nilai default dari stok-->
        <br>
        <button type="submit">Update Stok</button>
    </form>
    <a href="{{ route('stoks.index') }}">Back to List</a><!--Link kembali ke daftar stok-->
</body>
</html>
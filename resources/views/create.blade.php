<!DOCTYPE html>
<html>
<head>
    <title>Tambah Barang</title>
</head>
<body>

<h1>Tambah Barang</h1>

<form action="/barang" method="POST">
    @csrf
    <input type="text" name="nama" placeholder="Nama Barang">
    <input type="number" name="jumlah" placeholder="Jumlah">
    <button type="submit">Simpan</button>
</form>

</body>
</html>
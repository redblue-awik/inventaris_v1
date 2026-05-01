<h1>Data Barang</h1>

@foreach ($barangs as $barang)
    <p>{{ $barang->nama }} - {{ $barang->jumlah }}</p>
@endforeach
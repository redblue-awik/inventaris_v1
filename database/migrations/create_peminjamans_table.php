<?php
// database/migrations/xxxx_xx_xx_000002_create_peminjamans_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained()->onDelete('cascade');
            $table->string('peminjam');
            $table->date('tgl_pinjam');
            $table->date('tgl_kembali')->nullable();
            $table->integer('jumlah');
            $table->enum('status', ['Dipinjam', 'Dikembalikan'])->default('Dipinjam');
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists('peminjamans'); }
};
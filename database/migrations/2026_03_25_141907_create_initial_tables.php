    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up() {
            // Tabel User (Modifikasi default Laravel)
            Schema::create('tb_users', function (Blueprint $table) {
                $table->id();
                $table->string('nip_nik')->unique();
                $table->string('name');
                $table->string('jabatan')->nullable();
                $table->string('subbagian')->nullable();
                $table->string('instansi')->nullable();
                $table->string('hp')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
            });

            // Tabel Asset
            Schema::create('tb_assets', function (Blueprint $table) {
                $table->id();
                $table->string('kode_asset')->unique();
                $table->string('nama_asset');
                $table->string('foto')->nullable();
                $table->string('kategori');
                $table->integer('stok_tersedia')->default(0);
                $table->integer('stok_dipinjam')->default(0);
                $table->integer('rusak_ringan')->default(0);
                $table->integer('rusak_berat')->default(0);
                $table->string('lokasi');
                $table->timestamps();
            });

            // Tabel Peminjaman
            Schema::create('tb_peminjaman', function (Blueprint $table) {
                $table->id();
                $table->string('kode_peminjaman')->unique();
                $table->enum('tipe_peminjaman', ['internal', 'eksternal']);
                $table->foreignId('user_id')->constrained('tb_users');
                $table->string('nama_kegiatan');
                $table->date('tgl_pinjam');
                $table->date('tgl_kembali');
                $table->string('nomor_surat')->nullable();
                $table->date('tgl_surat')->nullable();
                $table->string('file_surat')->nullable(); // Path PDF
                $table->string('status'); // Menunggu, Disetujui, Dipinjam, Selesai, Ditolak
                $table->text('catatan_penolakan')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->timestamps();
            });

            // Tabel Detail Peminjaman (Barang apa saja yang dipinjam)
            Schema::create('tb_peminjaman_detail', function (Blueprint $table) {
                $table->id();
                $table->foreignId('peminjaman_id')->constrained('tb_peminjaman')->onDelete('cascade');
                $table->foreignId('asset_id')->constrained('tb_assets');
                $table->integer('qty');
                $table->string('satuan');
                $table->string('kondisi_keluar')->nullable();
                $table->string('kondisi_kembali')->nullable();
                $table->string('foto_keluar')->nullable();
                $table->string('foto_kembali')->nullable();
                $table->timestamps();
            });

            // Tabel Setting
            Schema::create('tb_settings', function (Blueprint $table) {
                $table->id();
                $table->string('nama_instansi');
                $table->text('alamat');
                $table->string('telepon1');
                $table->string('telepon2')->nullable();
                $table->string('email');
                $table->string('logo')->nullable();
                $table->string('nama_kasubbag');
                $table->string('nip_kasubbag');
                $table->string('jabatan_kasubbag');
                $table->timestamps();
            });
        }

        public function down() {
            Schema::dropIfExists('tb_settings');
            Schema::dropIfExists('tb_peminjaman_detail');
            Schema::dropIfExists('tb_peminjaman');
            Schema::dropIfExists('tb_assets');
            Schema::dropIfExists('tb_users');
        }
    };
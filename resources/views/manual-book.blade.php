<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manual Book - AssetHub</title>
    <link rel="icon" type="image/png" href="{{ asset('images/assethub-icon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { 
            --primary: #2563eb; 
            --primary-dark: #1e40af; 
            --slate-800: #1e293b; 
            --slate-700: #334155; 
            --slate-500: #64748b; 
            --slate-400: #94a3b8;
            --slate-100: #f1f5f9;
        }
        
        * { box-sizing: border-box; -webkit-print-color-adjust: exact; }
        
        body { 
            background: #f1f5f9; 
            font-family: 'Inter', sans-serif; 
            margin: 0; 
            padding: 0; 
            scroll-behavior: smooth;
        }

        /* --- ORNAMEN AKSEN HALAMAN --- */
        .page::before {
            content: ""; position: absolute; top: -100px; right: -100px;
            width: 300px; height: 300px; border-radius: 50%;
            background: radial-gradient(circle, rgba(37,99,235,0.06) 0%, rgba(255,255,255,0) 70%);
            z-index: 0;
        }

        .page::after {
            content: ""; position: absolute; bottom: -50px; left: -50px;
            width: 250px; height: 250px; border-radius: 30px;
            background: linear-gradient(135deg, rgba(37,99,235,0.04) 0%, rgba(29,78,216,0.07) 100%);
            transform: rotate(15deg);
            z-index: 0;
        }

        /* --- LAYOUT HALAMAN --- */
        .page {
            background: white;
            position: relative;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-sizing: border-box;
            transition: all 0.3s ease;
            z-index: 1;
        }

        @media screen {
            .page {
                width: 210mm;
                min-height: 297mm;
                margin: 25px auto;
                padding: 15mm 20mm;
                box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
            }
        }

        @media screen and (max-width: 210mm) {
            .page { width: 95%; height: auto; margin: 15px auto; padding: 25px; border-radius: 12px; }
            .cover-logo { width: 80% !important; }
        }

        @page { size: A4; margin: 0; }
        @media print {
            body { background: none; }
            .no-print { display: none !important; }
            .page { 
                margin: 0 !important; width: 210mm !important; height: 297mm !important; 
                padding: 15mm 20mm !important; page-break-after: always; border: none !important;
            }
        }

        /* --- FLOATING BUTTONS (DESKTOP & MOBILE) --- */
        .no-print { 
            position: fixed; 
            z-index: 1000; 
            display: flex; 
            gap: 10px; 
        }

        /* Desktop Style */
        @media screen and (min-width: 1024px) {
            .no-print { top: 25px; right: 30px; }
        }

        /* Mobile Style (Kanan Tengah Bersebelahan) */
        @media screen and (max-width: 1023px) {
            .no-print { 
                top: 5%; right: 15px; 
                transform: translateY(-50%);
                flex-direction: row; /* Bersebelahan horizontal */
                align-items: center;
            }
            .btn span { display: none; } /* Sembunyikan teks di mobile jika terlalu sempit */
        }

        .fab-container { position: relative; }
        .btn { 
            background: var(--primary); color: white; padding: 12px 18px; 
            border-radius: 10px; border: none; cursor: pointer; font-weight: 700; font-size: 13px;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3); display: flex; align-items: center; gap: 8px;
        }
        .btn-pdf { background: var(--slate-800); box-shadow: 0 4px 12px rgba(0,0,0,0.2); }

        .dropdown-menu {
            position: absolute; right: 0; top: 100%; background: white;
            min-width: 220px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            display: none; border: 1px solid var(--slate-100); z-index: 1100;
        }
        .fab-container:hover .dropdown-menu { display: block; }
        .dropdown-menu a {
            display: block; padding: 12px 18px; color: var(--slate-700);
            text-decoration: none; font-size: 13px; font-weight: 500; border-bottom: 1px solid var(--slate-100);
        }
        .dropdown-menu a:hover { background: var(--slate-100); color: var(--primary); }

        /* --- COVER SPECIFIC --- */
        .cover { text-align: center; justify-content: center; align-items: center; }
        .cover::before { background: radial-gradient(circle, rgba(37,99,235,0.12) 0%, rgba(255,255,255,0) 70%); width: 450px; height: 450px; }
        .cover-content { z-index: 10; position: relative; }
        .cover-logo { width: 380px; margin-bottom: 25px; filter: drop-shadow(0 10px 15px rgba(0,0,0,0.05)); }
        .cover-title { font-size: 56px; color: var(--primary); font-weight: 800; letter-spacing: -2px; margin: 0; line-height: 1; }
        .line-accent { width: 60px; height: 5px; background: var(--primary); margin: 30px auto; border-radius: 10px; }
        .instansi-name { font-size: 18px; color: var(--slate-800); font-weight: 700; line-height: 1.6; }

        /* --- CONTENT STYLE --- */
        h1 { color: var(--primary); font-size: 26px; border-bottom: 2px solid var(--slate-100); padding-bottom: 12px; position: relative; z-index: 1; margin-bottom: 0; }
        h2 { color: var(--slate-800); font-size: 18px; margin-top: 25px; display: flex; align-items: center; z-index: 1; position: relative; }
        h2::before { content: ""; width: 5px; height: 20px; background: var(--primary); margin-right: 12px; border-radius: 10px; }
        p, li { color: var(--slate-700); line-height: 1.8; font-size: 14px; position: relative; z-index: 1; }

        .img-box { border: 1px solid var(--slate-100); border-radius: 12px; margin: 20px 0; background: white; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); z-index: 1; position: relative; }
        .img-box img { width: 100%; display: block; }
        .caption { padding: 12px; text-align: center; font-size: 11px; color: var(--slate-500); background: var(--slate-100); font-weight: 600; }
        
        /* --- FOOTER HALAMAN --- */
        .page-footer { 
            margin-top: auto; padding-top: 15px; border-top: 1px solid var(--slate-100);
            font-size: 11px; color: var(--slate-500); display: flex; justify-content: space-between;
            z-index: 1; position: relative; font-weight: 500;
        }
    </style>
</head>
<body>

<div class="no-print">
    <button onclick="window.print()" class="btn btn-pdf"><span>PDF</span> ⬇️</button>
    <div class="fab-container">
        <button class="btn">🚀 <span>Navigasi Menu</span></button>
        <div class="dropdown-menu">
            <a href="javascript:void(0)" onclick="scrollToTop()">🏠 Cover</a>
            <a href="#toc">📋 Daftar Isi</a>
            <hr>
            <a href="#dashboard">📊 1. Dashboard</a>
            <a href="#master-list">📦 2. Master Asset</a>
            <a href="#internal-list">🏢 3. Pinjam Internal</a>
            <a href="#external-list">🌍 4. Pinjam Eksternal</a>
            <a href="#item-history">🕒 5. History Barang</a>
            <a href="#borrower-history">👤 5.1 History Peminjam</a>
        </div>
    </div>
</div>

<div class="page cover" id="cover">
    <div class="cover-content">
        <p style="text-transform: uppercase; letter-spacing: 3px; font-weight: 700; color: var(--slate-400); font-size: 14px; margin-bottom: 10px;">Manual Book</p>
        <img src="{{ asset('images/assethub-horizontal.png') }}" class="cover-logo" alt="Logo AssetHub">
        <div class="line-accent"></div>
        <div class="instansi-name">
            Sekretariat Komisi Pemilihan Umum<br>
            Kabupaten Pasuruan
        </div>
        <p style="margin-top: 50px; font-size: 13px; color: var(--slate-400); font-weight: 600;">Tahun 2026 &bull; Versi 1.0.0</p>
    </div>
</div>

<div class="page" id="toc">
    <h1>Daftar Isi</h1>
    <div style="margin-top: 40px;">
        <style>
            .toc-item { font-size: 14px; display: flex; justify-content: space-between; padding: 15px 0; border-bottom: 1px dashed var(--slate-100); text-decoration: none; color: var(--slate-700); font-weight: 600; }
            .toc-item:hover { color: var(--primary); }
            .toc-sub { padding-left: 25px; font-weight: 400; color: var(--slate-500); font-size: 13px; }
        </style>
        
        <a href="#dashboard" class="toc-item"><span>1. Dashboard</span> <span>Hal. 3</span></a>
        
        <a href="#master-list" class="toc-item"><span>2. Master Asset & Inventaris</span> <span>Hal. 4</span></a>
        
        <a href="#internal-list" class="toc-item"><span>3. Peminjaman Internal</span> <span>Hal. 6</span></a>
        <a href="#internal-form" class="toc-item toc-sub"><span>3.1 Tambah Peminjaman Internal</span> <span>Hal. 7</span></a>
        <a href="#internal-approval" class="toc-item toc-sub"><span>3.2 Persetujuan & Persiapan Barang</span> <span>Hal. 8</span></a>
        <a href="#return-process" class="toc-item toc-sub"><span>3.4 Proses Pengembalian Barang</span> <span>Hal. 10</span></a>
        
        <a href="#external-borrowing" class="toc-item"><span>4. Peminjaman Eksternal</span> <span>Hal. 11</span></a>
        <a href="#external-borrowing" class="toc-item toc-sub"><span>4.1 Tambah Peminjaman Eksternal</span> <span>Hal. 12</span></a>
        <a href="#external-approval" class="toc-item toc-sub"><span>4.2 Persetujuan & Administrasi</span> <span>Hal. 13</span></a>
        <a href="#external-return" class="toc-item toc-sub"><span>4.4 Proses Pengembalian Eksternal</span> <span>Hal. 15</span></a>
        
        <a href="#item-history" class="toc-item"><span>5. History Berdasarkan Barang</span> <span>Hal. 16</span></a>
        
        <a href="#borrower-history" class="toc-item"><span>5.1 History Berdasarkan Peminjam</span> <span>Hal. 17</span></a>
    </div>
    
    <div class="page-footer">
        <span>AssetHub Manual Book</span>
        <span>Hal. 2 - Daftar Isi</span>
    </div>
</div>

<div class="page" id="dashboard">
    <h1>1. Dashboard</h1>
    <div style="margin-top: 10px;">Dashboard merupakan pusat kendali sistem AssetHub yang menyajikan ringkasan data inventaris dan aktivitas peminjaman secara real-time untuk memudahkan pengambilan keputusan bagi operator dan pimpinan.</div>
    
    <div class="img-box">
        <img src="{{ asset('manualbook/dashboard.png') }}" alt="Dashboard">
        <div class="caption">Gambar 1.1: Interface Dashboard Overview</div>
    </div>

    <h2>Komponen Dashboard</h2>
    <style>
        .feature-list { margin-bottom: 25px; }
        .feature-item { margin-bottom: 15px; display: flex; gap: 15px; align-items: flex-start; }
        .feature-icon { background: var(--slate-100); color: var(--primary); padding: 8px; border-radius: 8px; font-weight: bold; min-width: 40px; text-align: center; }
        .feature-text strong { color: var(--slate-800); display: block; margin-bottom: 3px; }
    </style>

    <div class="feature-list">
        <div class="feature-item">
            <div class="feature-icon">01</div>
            <div class="feature-text">
                <strong>Filter Periode Tanggal</strong>
                Fungsi ini memungkinkan pengguna untuk memfilter seluruh data dashboard berdasarkan rentang waktu tertentu (Periode Awal s/d Periode Akhir) untuk analisis data yang lebih spesifik.
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">02</div>
            <div class="feature-text">
                <strong>4 Card Informasi Utama</strong>
                Menampilkan statistik cepat mencakup <em>Total Asset</em>, <em>Total Peminjaman</em>, <em>Sedang Dipinjam</em> (Aktif), dan <em>Selesai</em> untuk memantau sirkulasi aset secara instan.
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">03</div>
            <div class="feature-text">
                <strong>Grafik Frekuensi Peminjaman</strong>
                Visualisasi berupa <em>Bar Chart</em> (Frekuensi Peminjaman per Subbagian / Instansi) dan <em>Pie Chart</em> (Frekuensi Peminjaman Asset) untuk melihat aset mana yang paling sering dipinjam.
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">04</div>
            <div class="feature-text">
                <strong>Tabel Peminjaman Akan Kembali</strong>
                Menampilkan daftar aset yang jatuh tempo pengembaliannya minimal H-1, berfungsi sebagai pengingat (reminder) bagi operator untuk melakukan tindak lanjut.
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">05</div>
            <div class="feature-text">
                <strong>Tabel Persetujuan (Approval)</strong>
                Menyediakan akses cepat bagi peran <em>Kasubbag</em> atau <em>Pimpinan</em> untuk menyetujui atau menolak pengajuan peminjaman yang masuk ke dalam sistem.
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">06</div>
            <div class="feature-text">
                <strong>10 Aktivitas Peminjaman Terakhir</strong>
                Menampilkan log kronologis dari sepuluh transaksi terbaru, memudahkan pelacakan aktivitas terkini oleh siapa, unit apa, dan status transaksinya.
            </div>
        </div>
    </div>

    <div class="page-footer">
        <span>AssetHub Manual Book</span>
        <span>Hal. 3 - Dashboard</span>
    </div>
</div>

<div class="page" id="master-list">
    <h1>2. Master Asset</h1>
    <div style="margin-top: 10px;">Halaman Master Asset berfungsi sebagai basis data utama seluruh aset yang dimiliki oleh KPU Kabupaten Pasuruan. Di sini, operator dapat memantau kondisi stok secara mendalam untuk memastikan kesiapan logistik kantor.</div>
    
    <div class="img-box">
        <img src="{{ asset('manualbook/asset-daftar.png') }}" alt="Daftar Master Asset">
        <div class="caption">Gambar 2.1: Tabel Inventaris Barang dan Manajemen Stok</div>
    </div>

    <h2>Informasi Tabel Asset</h2>
    <div class="feature-list">
        <div class="feature-item">
            <div class="feature-icon">01</div>
            <div class="feature-text">
                <strong>Identitas Asset</strong>
                Menampilkan visual (foto) barang, nama resmi aset, serta kode unik untuk mempermudah identifikasi fisik saat pemeriksaan.
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">02</div>
            <div class="feature-text">
                <strong>Klasifikasi Kategori</strong>
                Pengelompokan aset berdasarkan jenis (misal: Peralatan Kantor, Mesin, Kendaraan) untuk mempermudah pemetaan aset dalam laporan manajerial.
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">03</div>
            <div class="feature-text">
                <strong>Status Kondisi (Tersedia/Rusak)</strong>
                Menampilkan jumlah stok secara spesifik berdasarkan kondisi: <em>Tersedia (Baik)</em>, <em>Rusak Ringan</em>, dan <em>Rusak Berat</em>. Hal ini penting untuk menentukan kelayakan barang saat akan dipinjam.
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">04</div>
            <div class="feature-text">
                <strong>Tombol Edit & Hapus</strong>
                Tombol kontrol untuk memperbarui data jika terjadi perubahan spesifikasi atau menghapus data aset yang sudah tidak digunakan/dihapuskan dari inventaris.
            </div>
        </div>
    </div>

    <div class="page-footer">
        <span>AssetHub Manual Book</span>
        <span>Hal. 4 - Master Asset</span>
    </div>
</div>

<div class="page" id="master-form">
    <h1>2.1 Tambah Asset Baru</h1>
    <div style="margin-top: 10px;">Prosedur penambahan aset dilakukan melalui formulir terstruktur untuk memastikan validitas data yang masuk ke dalam AssetHub.</div>
    
    <div class="img-box" style="max-width: 500px; margin: 20px auto;">
        <img src="{{ asset('manualbook/asset-tambah.png') }}" alt="Form Tambah Asset">
        <div class="caption">Gambar 2.2: Formulir Input Asset Baru</div>
    </div>

    <h2>Komponen Formulir</h2>
    <div class="feature-list">
        <div class="feature-item">
            <div class="feature-icon">01</div>
            <div class="feature-text">
                <strong>Kategori & Kode Aset</strong>
                Pemilihan kategori akan menentukan penomoran kode aset secara otomatis untuk menjaga konsistensi penamaan di database.
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">02</div>
            <div class="feature-text">
                <strong>Pendataan Stok Kondisi</strong>
                User wajib mengisi jumlah unit pada kolom <em>Stok Baik</em>, <em>Rusak Ringan</em>, dan <em>Rusak Berat</em> sebagai data awal aset.
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">03</div>
            <div class="feature-text">
                <strong>Lokasi & Status</strong>
                Menentukan lokasi penyimpanan fisik aset (misal: Gudang A / Ruang Umum) serta status aktif/non-aktif aset dalam sistem.
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">04</div>
            <div class="feature-text">
                <strong>Unggah Foto</strong>
                Fitur unggah foto aset untuk membantu operator mengenali barang secara visual saat proses verifikasi peminjaman.
            </div>
        </div>
    </div>

    <div class="page-footer">
        <span>AssetHub Manual Book</span>
        <span>Hal. 5 - Tambah Asset</span>
    </div>
</div>

<div class="page" id="internal-list">
    <h1>3. Peminjaman Internal</h1>
    <div style="margin-top: 10px;">Menu ini digunakan untuk mengelola dan memantau seluruh permintaan peminjaman aset yang dilakukan oleh staf internal di lingkungan KPU Kabupaten Pasuruan.</div>
    
    <div class="img-box">
        <img src="{{ asset('manualbook/internal-pinjam-daftar.png') }}" alt="Daftar Peminjaman Internal">
        <div class="caption">Gambar 3.1: Tabel Monitoring Peminjaman Staf KPU</div>
    </div>

    <h2>Informasi Tabel Peminjaman Internal</h2>
    <div class="feature-list">
        <div class="feature-item">
            <div class="feature-icon">01</div>
            <div class="feature-text">
                <strong>Peminjam & Kegiatan</strong>
                Menampilkan kode unik transaksi (contoh: 001/INV/III/2026), nama lengkap peminjam, serta tujuan atau nama kegiatan penggunaan aset.
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">02</div>
            <div class="feature-text">
                <strong>Rentang Waktu</strong>
                Informasi jelas mengenai tanggal pengambilan barang (Tgl Pinjam) dan batas waktu pengembalian barang (Tgl Kembali).
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">03</div>
            <div class="feature-text">
                <strong>Daftar Barang & Status</strong>
                Daftar aset yang dipinjam beserta jumlahnya (QTY) yang ditampilkan dalam label biru, serta status terkini transaksi seperti <em>DIPINJAM</em> atau <em>SELESAI</em>.
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">04</div>
            <div class="feature-text">
                <strong>Detail Transaksi</strong>
                Tombol ikon mata untuk melihat rincian lengkap transaksi, termasuk riwayat persetujuan dan kondisi barang saat dikembalikan.
            </div>
        </div>
    </div>

    <div class="page-footer">
        <span>AssetHub Manual Book</span>
        <span>Hal. 6 - Peminjaman Internal</span>
    </div>
</div>

<div class="page" id="internal-form">
    <h1>3.1 Input Peminjaman Baru</h1>
    <div div style="margin-top: 10px;">Proses permohonan peminjaman dilakukan dengan mengisi data identitas dan memilih daftar barang secara sistematis untuk divalidasi oleh sistem.</div>
    
    <div class="img-box">
        <img src="{{ asset('manualbook/internal-pinjam-tambah.png') }}" alt="Form Peminjaman Internal">
        <div class="caption">Gambar 3.2: Interface Formulir Pengajuan Peminjaman</div>
    </div>

    <h2>Langkah Pengisian Form</h2>
    <div class="feature-list">
        <div class="feature-item">
            <div class="feature-icon">01</div>
            <div class="feature-text">
                <strong>Informasi Peminjam</strong>
                Input data diri peminjam yang meliputi Nama, NIP/NIK, asal Subbagian, serta keterangan Nama Kegiatan yang akan dilaksanakan.
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">02</div>
            <div class="feature-text">
                <strong>Penjadwalan</strong>
                Penentuan tanggal mulai pinjam dan rencana tanggal kembali. Sistem akan melakukan pengecekan ketersediaan stok pada rentang tanggal tersebut.
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">03</div>
            <div class="feature-text">
                <strong>Daftar Barang (Basket)</strong>
                Pengguna dapat memilih satu atau lebih aset yang akan dipinjam melalui menu dropdown, menentukan jumlah (QTY), lalu menekan tombol <em>TAMBAH</em>.
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">04</div>
            <div class="feature-text">
                <strong>Finalisasi</strong>
                Setelah semua data benar, tekan tombol <em>SIMPAN PENGAJUAN</em> untuk meneruskan permohonan ke tahap approval selanjutnya.
            </div>
        </div>
    </div>

    <div class="page-footer">
        <span>AssetHub Manual Book</span>
        <span>Hal. 7 - Input Peminjaman</span>
    </div>
</div>

<div class="page" id="internal-approval">
    <h1>3.2 Persetujuan</h1>
    <div div style="margin-top: 10px;">Sistem AssetHub menerapkan alur persetujuan berjenjang untuk memastikan setiap peminjaman aset telah diverifikasi oleh pihak berwenang sesuai dengan hierarki organisasi.</div>
    
    <div class="img-box">
        <img src="{{ asset('manualbook/internal-pinjam-persetujuan.png') }}" alt="Detail Persetujuan">
        <div class="caption">Gambar 3.3: Interface Detail Peminjaman untuk Verifikator</div>
    </div>

    <h2>Mekanisme Approval</h2>
    <div class="feature-list">
        <div class="feature-item">
            <div class="feature-icon">01</div>
            <div class="feature-text">
                <strong>Opsi Penolakan (Tolak)</strong>
                Apabila pengajuan tidak sesuai, verifikator dapat menekan tombol <em>TOLAK</em>. User diwajibkan mengisi alasan penolakan yang akan tersimpan dalam sistem dan mengubah status menjadi <b>Ditolak</b>.
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">02</div>
            <div class="feature-text">
                <strong>Persetujuan Bertahap</strong>
                Penekanan tombol <em>SETUJUI</em> akan meneruskan status secara berjenjang: mulai dari sebelumnya status <b>Menunggu acc Pimpinan</b>, berubah jadi <b>Menunggu acc Kasubbag</b> hingga menjadi <b>Disetujui Kasubbag</b> setelah diverifikasi oleh masing-masing level akses.
            </div>
        </div>
    </div>

    <div class="page-footer">
        <span>AssetHub Manual Book</span>
        <span>Hal. 8 - Persetujuan</span>
    </div>
</div>

<div class="page" id="operator-prep">
    <h1>3.3 Persiapan & Pengambilan Barang</h1>
    <div style="margin-top: 10px;">Setelah pengajuan disetujui secara administratif, tugas beralih kepada Operator untuk menyiapkan fisik barang dan memvalidasi pengambilan aset.</div>
    
    <div class="img-box">
        <img src="{{ asset('manualbook/internal-pinjam-siapdiambil.png') }}" alt="Aksi Operator">
        <div class="caption">Gambar 3.4: Kontrol Status oleh Operator</div>
    </div>

    <h2>Manajemen Status Operator</h2>
    <div class="feature-list">
        <div class="feature-item">
            <div class="feature-icon">01</div>
            <div class="feature-text">
                <strong>Status: Siap Diambil</strong>
                Tombol <em>SIAP DIAMBIL</em> akan muncul saat status pengajuan telah <b>Disetujui Kasubbag</b>. Setelah barang fisik disiapkan, operator menekan tombol ini untuk menginformasikan peminjam bahwa barang tersedia.
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">02</div>
            <div class="feature-text">
                <strong>Status: Dipinjam</strong>
                Saat peminjam datang mengambil aset, operator menekan tombol <em>DIPINJAM</em>. Aksi ini secara resmi mengubah status transaksi menjadi aktif dan mulai menghitung masa pinjam di dalam sistem.
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">03</div>
            <div class="feature-text">
                <strong>Dokumen Scan</strong>
                Operator dapat mengunggah bukti fisik atau dokumen pendukung melalui fitur <em>UNGGAH</em> dokumen scan untuk melengkapi arsip digital transaksi.
            </div>
        </div>
    </div>

    <div class="page-footer">
        <span>AssetHub Manual Book</span>
        <span>Hal. 9 - Persiapan Barang</span>
    </div>
</div>

<div class="page" id="return-process">
    <h1>3.4 Proses Pengembalian Barang</h1>
    <div style="margin-top: 10px;">Tahap akhir dari siklus peminjaman adalah proses pengembalian. Operator bertanggung jawab penuh untuk memvalidasi kondisi fisik aset sebelum status transaksi dinyatakan selesai.</div>
    
    <div class="img-box">
        <img src="{{ asset('manualbook/internal-proses-pengembalian.png') }}" alt="Proses Pengembalian">
        <div class="caption">Gambar 3.5: Interface Manajemen Pengembalian Aset</div>
    </div>

    <h2>Prosedur Validasi Pengembalian</h2>
    <div class="feature-list">
        <div class="feature-item">
            <div class="feature-icon">01</div>
            <div class="feature-text">
                <strong>Input Tanggal Kembali Riil</strong>
                Operator wajib memasukkan tanggal saat barang benar-benar diterima kembali oleh kantor. Tanggal ini digunakan untuk mencatat durasi peminjaman yang sebenarnya di dalam log sistem.
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">02</div>
            <div class="feature-text">
                <strong>Pemeriksaan Kondisi Fisik</strong>
                Operator memeriksa kondisi barang secara detail dan memasukkan jumlah unit pada kolom yang tersedia: <em>Baik</em>, <em>Rusak Ringan</em>, <em>Rusak Berat</em>, atau <em>Hilang</em>. Data ini akan secara otomatis memperbarui saldo stok pada Master Asset.
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">03</div>
            <div class="feature-text">
                <strong>Dokumentasi Visual (Unggah Foto)</strong>
                Tersedia fitur <em>UNGGAH</em> foto kembali untuk mendokumentasikan kondisi terakhir barang saat diterima kembali sebagai bukti pendukung jika terjadi kerusakan pasca-peminjaman.
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">04</div>
            <div class="feature-text">
                <strong>Finalisasi (Simpan Pengembalian)</strong>
                Setelah semua data valid, klik tombol <em>SIMPAN PENGEMBALIAN</em>. Status transaksi akan berubah menjadi <b>Selesai</b> dan siklus peminjaman untuk transaksi tersebut dinyatakan berakhir secara sistem.
            </div>
        </div>
    </div>

    <div class="page-footer">
        <span>AssetHub Manual Book</span>
        <span>Hal. 10 - Pengembalian Barang</span>
    </div>
</div>

<div class="page" id="external-borrowing">
    <h1>4. Peminjaman Eksternal</h1>
    <div style="margin-top: 10px;">
        Halaman ini digunakan untuk melihat dan mengelola daftar peminjaman aset yang dilakukan oleh pihak eksternal atau instansi di luar KPU Kabupaten Pasuruan.
    </div>
    
    <div class="img-box">
        <img src="{{ asset('manualbook/eksternal-pinjam-daftar.png') }}" alt="Daftar Peminjaman Eksternal">
        <div class="caption">Gambar 4.1: Tabel Monitoring Peminjaman Pihak Luar</div>
    </div>

    <h2>Informasi Tabel Peminjaman Eksternal</h2>
    <div class="feature-list">
        <div class="feature-item">
            <div class="feature-icon">01</div>
            <div class="feature-text">
                <strong>Identitas Peminjam & Kegiatan</strong>
                Menampilkan kode transaksi unik, nama instansi atau pihak luar yang meminjam, serta detail kegiatan yang akan dilaksanakan.
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">02</div>
            <div class="feature-text">
                <strong>Administrasi Surat</strong>
                Kolom khusus yang memuat nomor surat resmi dan tanggal surat permohonan yang diajukan oleh pihak eksternal sebagai dasar peminjaman.
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">03</div>
            <div class="feature-text">
                <strong>Jadwal & Barang</strong>
                Informasi mengenai periode pinjam (tanggal mulai s/d tanggal kembali) serta daftar barang beserta jumlah (QTY) yang digunakan.
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">04</div>
            <div class="feature-text">
                <strong>Status & Aksi</strong>
                Menampilkan label status terkini (seperti <em>Menunggu ACC Pimpinan</em>) dan tombol aksi untuk melihat detail rincian atau memproses transaksi tersebut.
            </div>
        </div>
    </div>

    <div class="note-box">
        <strong>Informasi:</strong> Gunakan tombol <strong>+ TAMBAH PEMINJAMAN</strong> di pojok kanan atas untuk meregistrasi permohonan peminjaman baru dari pihak luar.
    </div>

    <div class="page-footer">
        <span>AssetHub Manual Book</span>
        <span>Hal. 11 - Peminjaman Eksternal</span>
    </div>
</div>

<div class="page" id="external-input">
    <h1>4.1 Input Peminjaman Eksternal</h1>
    <div style="margin-top: 10px;">
        Formulir ini digunakan oleh operator untuk mencatat permohonan peminjaman dari pihak luar secara sistematis agar dapat diproses oleh Pimpinan.
    </div>
    
    <div class="img-box">
        <img src="{{ asset('manualbook/eksternal-pinjam-tambah.png') }}" alt="Form Input Eksternal">
        <div class="caption">Gambar 4.2: Interface Input Peminjaman Pihak Luar</div>
    </div>

    <h2>Kelengkapan Data Pengajuan</h2>
    <div class="feature-list">
        <div class="feature-item">
            <div class="feature-icon">01</div>
            <div class="feature-text">
                <strong>Informasi Identitas & Instansi</strong>
                Pengguna wajib mengisi data lengkap peminjam meliputi Nama, NIP/NIK/NIS, nomor telepon aktif, jabatan, serta nama instansi atau organisasi asal.
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">02</div>
            <div class="feature-text">
                <strong>Dokumentasi Surat Pengantar</strong>
                Masukkan nomor surat, tanggal surat, dan wajib melakukan <em>UNGGAH PDF SURAT</em> sebagai bukti legalitas permohonan dari instansi terkait.
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">03</div>
            <div class="feature-text">
                <strong>Detail Kegiatan & Waktu</strong>
                Tuliskan deskripsi kegiatan serta tentukan rentang waktu peminjaman pada bagian <em>WAKTU PEMINJAMAN</em> (Tanggal Pinjam s/d Tanggal Kembali).
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">04</div>
            <div class="feature-text">
                <strong>Daftar Asset</strong>
                Pilih barang dari daftar stok yang tersedia, lalu tekan tombol <em>TAMBAH</em> untuk memasukkannya ke dalam daftar pinjam dan tentukan jumlahnya (QTY).
            </div>
        </div>
    </div>

    <div class="note-box">
        <strong>Penting:</strong> Setelah menekan tombol <strong>SIMPAN PENGAJUAN</strong>, sistem akan menerbitkan status awal yaitu <strong>"Menunggu acc Pimpinan"</strong>.
    </div>

    <div class="page-footer">
        <span>AssetHub Manual Book</span>
        <span>Hal. 12 - Input Eksternal</span>
    </div>
</div>

<div class="page" id="external-approval">
    <h1>4.2 Persetujuan & Administrasi</h1>
    <div style="margin-top: 10px;">
        Halaman ini merupakan pusat kendali administratif bagi Pimpinan, Kasubbag, dan Operator untuk memproses legalitas peminjaman dari pihak luar.
    </div>
    
    <div class="img-box">
        <img src="{{ asset('manualbook/eksternal-pinjam-persetujuan.png') }}" alt="Persetujuan Eksternal">
        <div class="caption">Gambar 4.3: Detail Administrasi dan Kontrol Approval Eksternal</div>
    </div>

    <h2>Fitur Administrasi & Ekspor</h2>
    <div class="feature-list">
        <div class="feature-item">
            <div class="feature-icon">01</div>
            <div class="feature-text">
                <strong>Ekspor Dokumen (PDF & Word)</strong>
                Tersedia tombol untuk mengunduh draf surat atau rincian peminjaman dalam format PDF dan Word guna keperluan arsip fisik atau cetak surat balasan.
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">02</div>
            <div class="feature-text">
                <strong>Koreksi Nomor Surat</strong>
                Operator memiliki akses untuk mengubah atau memperbarui nomor surat jika terdapat penyesuaian penomoran surat masuk pada buku agenda kantor.
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">03</div>
            <div class="feature-text">
                <strong>Verifikasi Surat Pengantar</strong>
                Pimpinan dapat meninjau langsung surat permohonan yang diunggah oleh peminjam melalui tombol <em>Lihat Surat</em> untuk memastikan keaslian pengajuan.
            </div>
        </div>
    </div>

    <h2>Alur Approval Berjenjang</h2>
    <div class="feature-list">
        <div class="feature-item">
            <div class="feature-icon">01</div>
            <div class="feature-text">
                <strong>Persetujuan Pimpinan</strong>
                Pimpinan dapat menyetujui yang akan mengubah status menjadi <b>"Menunggu acc Kasubbag"</b>, atau menolak dengan mengisi alasan penolakan yang wajib dicantumkan.
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">02</div>
            <div class="feature-text">
                <strong>Persetujuan Kasubbag</strong>
                Setelah pimpinan setuju, Kasubbag melakukan verifikasi akhir dengan Setuju atau menolak, persetujuan akan mengubah status menjadi <b>"Disetujui Kasubbag"</b> sebelum barang disiapkan oleh operator.
            </div>
        </div>
    </div>

    <div class="page-footer">
        <span>AssetHub Manual Book</span>
        <span>Hal. 13 - Persetujuan Eksternal</span>
    </div>
</div>

<div class="page" id="external-preparation">
    <h1>4.3 Persiapan & Pengambilan Barang</h1>
    <div style="margin-top: 10px;">
        Tahap ini merupakan proses teknis di mana operator menyiapkan fisik aset dan mendokumentasikan proses serah terima kepada pihak eksternal.
    </div>
    
    <div class="img-box">
        <img src="{{ asset('manualbook/eksternal-pinjam-siapdiambil.png') }}" alt="Persiapan Barang Eksternal">
        <div class="caption">Gambar 4.4: Interface Manajemen Persiapan Barang oleh Operator</div>
    </div>

    <h2>Tanggung Jawab Operator</h2>
    <div class="feature-list">
        <div class="feature-item">
            <div class="feature-icon">01</div>
            <div class="feature-text">
                <strong>Penyesuaian Administrasi</strong>
                Operator masih memiliki akses untuk mengubah nomor surat permohonan pada kolom penyesuaian jika diperlukan sinkronisasi data akhir sebelum barang keluar.
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">02</div>
            <div class="feature-text">
                <strong>Dokumentasi Visual (Foto Keluar)</strong>
                Tersedia fitur <em>LIHAT FOTO</em> atau unggah foto kondisi barang terkini untuk memastikan keadaan aset sebelum diambil oleh pihak peminjam eksternal.
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">03</div>
            <div class="feature-text">
                <strong>Verifikasi Dokumen Scan</strong>
                Operator dapat mengunggah hasil scan surat atau berita acara yang telah ditandatangani oleh pihak-pihak terkait sebagai bukti legalitas serah terima fisik.
            </div>
        </div>
    </div>

    <h2>Perubahan Status Transaksi</h2>
    <div class="feature-list">
        <div class="feature-item">
            <div class="feature-icon">01</div>
            <div class="feature-text">
                <strong>Siap Diambil</strong>
                Setelah status mencapai <b>"Disetujui Kasubbag"</b> dan barang telah disiapkan, operator menekan tombol <em>SIAP DIAMBIL</em> untuk menginformasikan kepada pihak eksternal.
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">02</div>
            <div class="feature-text">
                <strong>Dipinjam</strong>
                Saat pihak eksternal secara fisik mengambil barang, operator menekan tombol <em>DIPINJAM</em> untuk mengaktifkan masa peminjaman secara resmi di dalam sistem.
            </div>
        </div>
    </div>

    <div class="page-footer">
        <span>AssetHub Manual Book</span>
        <span>Hal. 14 - Pengambilan Eksternal</span>
    </div>
</div>

<div class="page" id="external-return">
    <h1>4.4 Proses Pengembalian Eksternal</h1>
    <div style="margin-top: 10px;">
        Proses ini digunakan untuk mencatat pengembalian fisik aset dari pihak eksternal serta melakukan audit kondisi barang secara mendalam.
    </div>
    
    <div class="img-box">
        <img src="{{ asset('manualbook/eksternal-proses-pengembalian.png') }}" alt="Pengembalian Eksternal">
        <div class="caption">Gambar 4.5: Interface Penilaian Kondisi Aset Kembali Eksternal</div>
    </div>

    <h2>Audit Kondisi & Stok</h2>
    <div class="feature-list">
        <div class="feature-item">
            <div class="feature-icon">01</div>
            <div class="feature-text">
                <strong>Tanggal Kembali Riil</strong>
                Operator memasukkan tanggal penerimaan barang secara aktual untuk memvalidasi ketepatan waktu pengembalian sesuai periode yang telah dijanjikan sebelumnya.
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">02</div>
            <div class="feature-text">
                <strong>Klasifikasi Kondisi Aset</strong>
                Petugas harus menentukan jumlah unit berdasarkan kondisi nyata saat diterima: <em>Baik</em>, <em>Rusak Ringan</em>, <em>Rusak Berat</em>, atau <em>Hilang</em>.
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">03</div>
            <div class="feature-text">
                <strong>Dokumentasi Pasca-Pinjam</strong>
                Tersedia fitur <em>UNGGAH</em> foto untuk setiap barang guna mendokumentasikan kondisi fisik terakhir sebagai bukti pendukung jika terdapat penurunan kualitas aset.
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">04</div>
            <div class="feature-text">
                <strong>Penyelesaian Transaksi</strong>
                Setelah data terisi lengkap, tekan tombol <b>SIMPAN PENGEMBALIAN</b>. Sistem akan secara otomatis mengubah status menjadi <b>"Selesai"</b> dan memperbarui saldo stok pada master asset.
            </div>
        </div>
    </div>

    <div class="page-footer">
        <span>AssetHub Manual Book</span>
        <span>Hal. 15 - Pengembalian Eksternal</span>
    </div>
</div>

<div class="page" id="item-history">
    <h1>5. History Peminjaman Berdasarkan Barang</h1>
    <div style="margin-top: 10px;">
        Halaman ini menyediakan rekam jejak penggunaan setiap aset secara mendetail, memudahkan operator untuk melacak siapa saja yang pernah menggunakan barang tertentu.
    </div>
    
    <div class="img-box">
        <img src="{{ asset('manualbook/history-barang.png') }}" alt="Card History Barang">
        <div class="caption">Gambar 5.1: Katalog Aset untuk Pemantauan Riwayat</div>
    </div>

    <h2>Katalog Riwayat Aset</h2>
    <div class="feature-list">
        <div class="feature-item">
            <div class="feature-icon">01</div>
            <div class="feature-text">
                <strong>Visualisasi Card Barang</strong>
                Setiap aset ditampilkan dalam bentuk kartu (card) yang memuat Kode Asset (PKM), Nama Barang, dan Kategori Aset.
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">02</div>
            <div class="feature-text">
                <strong>Akses Detail Log</strong>
                Gunakan ikon <strong>Mata</strong> atau klik area card untuk masuk ke halaman <em>Detail Log</em> yang berisi daftar lengkap peminjam aset tersebut.
            </div>
        </div>
    </div>

    <div class="img-box" style="margin-top: 30px;">
        <img src="{{ asset('manualbook/history-barang-detail.png') }}" alt="Detail Log Peminjaman">
        <div class="caption">Gambar 5.2: Daftar Riwayat Peminjam per Barang</div>
    </div>

    <h2>Informasi Detail Log</h2>
    <div class="feature-list">
        <div class="feature-item">
            <div class="feature-icon">A</div>
            <div class="feature-text">
                <strong>Identitas & Tipe Peminjam</strong>
                Menampilkan nama peminjam, instansi, serta label tipe peminjaman (Internal atau Eksternal).
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">B</div>
            <div class="feature-text">
                <strong>Rekapitulasi Kondisi Kembali</strong>
                Informasi detail mengenai rentang waktu pinjam, tanggal kembali riil, serta jumlah barang yang kembali dalam kondisi Baik, Rusak, atau Hilang.
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">C</div>
            <div class="feature-text">
                <strong>Link ke Transaksi Utama</strong>
                Tersedia tombol aksi (ikon mata) di sisi kanan setiap baris log untuk melihat detail lengkap transaksi peminjaman asal.
            </div>
        </div>
    </div>

    <div class="page-footer">
        <span>AssetHub Manual Book</span>
        <span>Hal. 16 - History Barang</span>
    </div>
</div>

<div class="page" id="borrower-history">
    <h1>5.1 History Peminjaman Berdasarkan Unit/Instansi</h1>
    <div style="margin-top: 10px;">
        Fitur ini mengelompokkan data transaksi berdasarkan unit kerja (Internal) atau organisasi asal (Eksternal) untuk memantau intensitas penggunaan aset oleh kelompok tertentu.
    </div>
    
    <div class="img-box">
        <img src="{{ asset('manualbook/history-peminjam.png') }}" alt="Card History Peminjam">
        <div class="caption">Gambar 5.3: Pengelompokan Berdasarkan Subbagian & Instansi Luar</div>
    </div>

    <h2>Kategori Peminjam</h2>
    <div class="feature-list">
        <div class="feature-item">
            <div class="feature-icon">01</div>
            <div class="feature-text">
                <strong>Label Tipe Peminjam</strong>
                Setiap kartu memiliki label penanda seperti <em>INTERNAL</em> untuk unit kerja di Sekretariat KPU Kabupaten Pasuruan, atau <em>EKSTERNAL</em> untuk instansi luar.
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">02</div>
            <div class="feature-text">
                <strong>Navigasi Riwayat Unit</strong>
                Klik pada area kartu atau ikon mata untuk melihat daftar seluruh transaksi peminjaman yang pernah dilakukan oleh unit atau instansi tersebut.
            </div>
        </div>
    </div>

    <div class="img-box" style="margin-top: 30px;">
        <img src="{{ asset('manualbook/history-peminjam-detail.png') }}" alt="Detail Log Unit">
        <div class="caption">Gambar 5.4: Daftar Riwayat Transaksi per Unit Kerja</div>
    </div>

    <h2>Log Transaksi Unit</h2>
    <div class="feature-list">
        <div class="feature-item">
            <div class="feature-icon">A</div>
            <div class="feature-text">
                <strong>Rekap Barang & QTY</strong>
                Halaman ini merinci barang apa saja yang dipinjam dalam satu nomor surat, jumlahnya (QTY), serta status kondisi saat barang tersebut dikembalikan.
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">B</div>
            <div class="feature-text">
                <strong>Monitoring Pengembalian</strong>
                Menampilkan rentang waktu peminjaman asli dibandingkan dengan tanggal pengembalian riil untuk melihat kedisiplinan pengembalian aset.
            </div>
        </div>

        <div class="feature-item">
            <div class="feature-icon">C</div>
            <div class="feature-text">
                <strong>Detail Transaksi</strong>
                Sama halnya dengan riwayat per barang, tersedia tombol navigasi di sisi kanan untuk melihat rincian formulir peminjaman secara utuh.
            </div>
        </div>
    </div>

    <div class="page-footer">
        <span>AssetHub Manual Book</span>
        <span>Hal. 17 - History Peminjam</span>
    </div>
</div>

<script>
    function scrollToTop() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) target.scrollIntoView({ behavior: 'smooth' });
        });
    });
</script>

</body>
</html>
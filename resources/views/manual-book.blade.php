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
            position: absolute; right: 0; top: 110%; background: white;
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
        <button class="btn">🚀 <span>Navigasi</span></button>
        <div class="dropdown-menu">
            <a href="javascript:void(0)" onclick="scrollToTop()">🏠Cover</a>
            <a href="#toc">📋 Daftar Isi</a>
            <a href="#dashboard">📊 1. Dashboard</a>
            <a href="#master-list">📦 2. Master Asset</a>
            <a href="#internal-list">📦 3. Alur Peminjaman Internal</a>
            <a href="#eksternal-list">📦 4. Alur Peminjaman Eksternal</a>
            <a href="#transaksi">🔄 3. Alur Transaksi</a>
            <a href="#laporan">📑 4. Laporan</a>
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
        </style>
        <a href="#dashboard" class="toc-item"><span>1. Dashboard</span> <span>Hal. 3</span></a>
        <a href="#master" class="toc-item"><span>2. Master Asset & Inventaris</span> <span>Hal. 4</span></a>
        <a href="#transaksi" class="toc-item"><span>3. Alur Transaksi Peminjaman</span> <span>Hal. 5</span></a>
        <a href="#laporan" class="toc-item"><span>4. Laporan & Riwayat History</span> <span>Hal. 6</span></a>
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

    <h2>Informasi Tabel Peminjaman</h2>
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
                Penekanan tombol <em>SETUJUI</em> akan meneruskan status secara berjenjang: dari <b>Menunggu ACC Kasubbag</b> hingga menjadi <b>Disetujui Kasubbag</b> setelah diverifikasi oleh masing-masing level akses.
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
    <div div style="margin-top: 10px;">Setelah pengajuan disetujui secara administratif, tugas beralih kepada Operator untuk menyiapkan fisik barang dan memvalidasi pengambilan aset.</div>
    
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
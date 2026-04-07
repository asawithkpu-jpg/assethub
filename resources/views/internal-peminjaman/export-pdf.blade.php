<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>AssetHub - Export Pdf</title>
    <link rel="icon" type="image/png" href="{{ asset('images/assethub-icon.png') }}">
    <style>
        @page { size: 8.5in 13in; margin: 1cm; }
        body { font-family: 'Helvetica', sans-serif; font-size: 11pt; line-height: 1.2; color: black; }
        
        /* Header & Logo */
        .header { text-align: center; border-bottom: 2px solid black; padding-bottom: 5px; margin-bottom: 10px; position: relative; height: 110px; }
        
        .logo-placeholder { 
            position: absolute; left: 0; top: 0; width: 75px; height: 90px; 
            border: 1px dashed #ccc; display: flex; align-items: center; justify-content: center; 
        }
        .logo-text { font-size: 8pt; color: #666; font-weight: bold; text-align: center; line-height: 1.1; }

        .header-text { margin-left: 85px; padding-top: 10px; }
        .instansi-line-1 { font-size: 16pt; font-weight: bold; margin-bottom: -2px; }
        .instansi-line-2 { font-size: 14pt; font-weight: bold; text-transform: uppercase; margin-bottom: 5px; }
        .alamat-line { font-size: 12pt; }

        /* Isi Surat */
        .title { font-weight: bold; text-align: center; margin-top: 15px; margin-bottom: 15px; font-size: 12pt; }
        .info-table td { vertical-align: top; padding: 2px 0; }
        
        /* Tabel Barang */
        .item-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .item-table th, .item-table td { border: 1px solid black; padding: 5px; text-align: center; font-size: 10pt; }
        .item-table td.nama-barang { text-align: left; }

        /* Tanda Tangan */
        .signature-table { width: 100%; margin-top: 25px; border-collapse: collapse; }

        /* PERBAIKAN: Hilangkan padding bottom khusus untuk baris tanggal agar rapat ke "Petugas Operator" */
        .signature-table tr:first-child td { padding-bottom: 0; }
        .signature-table td { text-align: center; vertical-align: top; padding-bottom: 10px; }

        .nama-ttd { font-weight: bold; text-decoration: underline; }
        
        .uppercase { text-transform: uppercase; }
    </style>
</head>
<body>
    <div class="header">
        @if($logoBase64)
            {{-- Ini muncul di hosting --}}
            <img src="{{ $logoBase64 }}" style="position: absolute; left: 0; top: 0; width: 100px; height: auto;">
        @else
            {{-- Gbr 1: Placeholder di Lokal --}}
            <div class="logo-placeholder">
                <div class="logo-text">LOGO<br>KPU</div>
            </div>
        @endif

        <div class="header-text">
            {{-- Memecah nama_instansi menjadi 2 baris secara dinamis --}}
            @php
                $nama = $setting->nama_instansi ?? '';
                // Mencari kata 'KABUPATEN' atau 'KOTA' sebagai titik potong baris kedua
                $search = ['Kabupaten', 'KABUPATEN', 'Kota', 'KOTA'];
                $displayNama = str_replace($search, '<br>KABUPATEN', strtoupper($nama));
            @endphp

            <div class="instansi-line-1" style="font-size: 14pt; font-weight: bold; line-height: 1.1;">
                {!! $displayNama !!}
            </div>
            
            <div class="alamat-line uppercase" style="margin-top: 5px;">
                {{ $setting->alamat ?? 'JL. SUDARSONO NO. 1 POGAR BANGIL - PASURUAN' }}
            </div>
            <div style="font-size: 11pt;">
                Telp: {{ $setting->telepon1 ?? '' }}{{ $setting->telepon2 ? ', '.$setting->telepon2 : '' }} 
                Email: {{ $setting->email ?? 'kab_pasuruan@kpu.go.id' }}
            </div>
        </div>
    </div>

    <div class="title">PERMOHONAN PINJAM PERALATAN PENDUKUNG KEGIATAN</div>

    <p style="margin-bottom: 5px;">Yang bertandatangan dibawah ini :</p>
    <table class="info-table" style="margin-left: 20px; width: 100%;">
        <tr><td width="120">Nama</td><td>: {{ $peminjaman->user->name }}</td></tr>
        <tr><td>Jabatan</td><td>: {{ $peminjaman->user->jabatan ?? '-' }}</td></tr>
        <tr><td>NIP</td><td>: {{ $peminjaman->user->nip_nik ?? '-' }}</td></tr>
        <tr><td>Nama Kegiatan</td><td>: {{ $peminjaman->nama_kegiatan ?? '-' }}</td></tr>
        {{-- Gbr 2: Format Indonesia --}}
        <tr><td>Tanggal</td><td>: {{ \Carbon\Carbon::parse($peminjaman->tgl_pinjam)->translatedFormat('d F Y') }}</td></tr>
    </table>

    <p style="margin-top: 15px; margin-bottom: 5px;">Dengan ini mengajukan permohonan peralatan pendukung kegiatan yang terdiri :</p>
    <table class="item-table">
        <thead>
            <tr>
                <th width="30">No.</th>
                <th>Nama Barang</th>
                <th width="40">Jml</th>
                <th width="85">Tgl. Pinjam</th>
                <th width="85">Tgl. Kembali</th>
                <th width="45">Paraf</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peminjaman->details as $index => $detail)
            <tr>
                <td>{{ $index + 1 }}.</td>
                <td class="nama-barang">{{ $detail->asset->nama_asset }}</td>
                <td>{{ $detail->qty }}</td>
                {{-- Gbr 2: Format d M Y (Contoh: 22 Mar 2026) --}}
                <td>{{ \Carbon\Carbon::parse($peminjaman->tgl_pinjam)->translatedFormat('d M Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($peminjaman->tgl_kembali)->translatedFormat('d M Y') }}</td>
                <td></td>
                <td></td>
            </tr>
            @endforeach
            
            {{-- Filler 15 baris --}}
            @for($i = 0; $i < $filler; $i++)
            <tr>
                <td>{{ $peminjaman->details->count() + $i + 1 }}.</td>
                <td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td>
            </tr>
            @endfor
        </tbody>
    </table>

    <p style="font-size: 9pt; margin-top: 15px; text-align: justify; line-height: 1.3;">
        Dengan kesanggupan untuk mempertanggung jawabkan, apabila barang yang dipinjam tersebut hilang atau rusak. Demikian untuk menjadikan periksa dan mendapatkan perhatian sepenuhnya.
    </p>

    <table class="signature-table">
        <tr>
            <td width="50%"></td>
            <td>Pasuruan, {{ \Carbon\Carbon::parse($peminjaman->tgl_kembali_real ?? $peminjaman->tgl_pinjam)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td>Peminjam,</td>
            <td>Petugas,</td>
        </tr>
        <tr><td></td><td></td></tr>
        <tr><td></td><td></td></tr>
        <tr><td></td><td></td></tr>
        <tr><td></td><td></td></tr>
        <tr><td></td><td></td></tr>
        <tr>
            <td>
                <span class="nama-ttd">{{ $peminjaman->user->name }}</span><br>
                {{ $peminjaman->user->nip_nik ?? '-' }}
            </td>
            <td>
                <span>____________________</span><br>
                
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;">
                Mengetahui,<br>
                {{ $setting->jabatan_kasubbag ?? 'Kasubbag Keuangan, Umum dan Logistik' }}
                <br><br><br><br><br>
                <span class="nama-ttd">{{ $setting->nama_kasubbag ?? '' }}</span><br>
                {{ $setting->nip_kasubbag ?? '' }}
            </td>
        </tr>
    </table>
</body>
</html>
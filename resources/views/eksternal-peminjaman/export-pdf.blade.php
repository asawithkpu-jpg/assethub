<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>AssetHub - Export Pdf</title>
    <link rel="icon" type="image/png" href="{{ asset('images/assethub-icon.png') }}">
    <style>
        @page { size: 8.5in 13in; margin: 1cm; }
        body { font-family: 'Arial', sans-serif; font-size: 11pt; line-height: 1.2; color: black; }
        
        /* Header KPU */
        .header { text-align: center; border-bottom: 2px solid black; padding-bottom: 5px; margin-bottom: 10px; height: 100px; }
        .header-text { margin-left: 90px; }
        
        /* Judul Dokumen (Sesuai Gambar 1) */
        .doc-title { text-align: center; margin-top: 10px; font-weight: bold; text-decoration: underline; font-size: 12pt; }
        .doc-number { text-align: center; font-size: 11pt; margin-bottom: 15px; }

        /* Label Identitas */
        .info-table td { padding: 1px 0; vertical-align: top; font-size: 10pt; }
        .line-filler { border-bottom: 1px solid black; min-width: 300px; display: inline-block; }

        /* Tabel Barang (Sesuai Gambar 1) */
        .item-table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        .item-table th, .item-table td { border: 1px solid black; padding: 4px; font-size: 9pt; text-align: center; }
        .item-table th { background-color: #f2f2f2; text-transform: uppercase; font-size: 8pt; }

        /* Tanda Tangan */
        .signature-table { width: 100%; margin-top: 30px; border-collapse: collapse; }
        .signature-table td { text-align: center; vertical-align: top; }
        .nama-ttd { font-weight: bold; text-decoration: underline; text-transform: uppercase; }

        .uppercase { text-transform: uppercase; }
    </style>
</head>
<body>
    <div class="header">
        @if($logoBase64)
            <img src="{{ $logoBase64 }}" style="position: absolute; left: 0; top: 0; width: 85px;">
        @endif
        <div class="header-text">
            {{-- Memecah nama_instansi menjadi 2 baris secara dinamis --}}
            @php
                $nama = $setting->nama_instansi ?? '';
                $search = ['Kabupaten', 'KABUPATEN', 'Kota', 'KOTA'];
                $displayNama = str_replace($search, '<br>KABUPATEN', strtoupper($nama));
            @endphp

            <div class="instansi-line-1" style="font-size: 14pt; font-weight: bold; line-height: 1.1;">
                {!! $displayNama !!}
            </div>
            <div style="font-size: 12pt;" class="uppercase">{{ $setting->alamat ?? '' }}</div>
            <div style="font-size: 11pt;">
                Telp: {{ $setting->telepon1 ?? '' }}{{ $setting->telepon2 ? ', '.$setting->telepon2 : '' }} 
                Email: {{ $setting->email ?? 'kab_pasuruan@kpu.go.id' }}
            </div>
        </div>
    </div>

    <div class="doc-title">TANDA TERIMA PINJAM BARANG INVENTARIS</div>
    <div class="doc-number">NOMOR : {{ $peminjaman->kode_peminjaman }}</div>

    <p style="margin-bottom: 8px;">Yang bertandatangan di bawah ini :</p>
    <table class="info-table" style="width: 100%; margin-bottom: 10px;">
        <tr><td width="130" style="font-weight: bold;">Nama</td><td width="10">:</td><td style="border-bottom: 1px solid #ccc;">{{ $peminjaman->user->name }}</td></tr>
        <tr><td style="font-weight: bold;">NIK/NIS</td><td>:</td><td style="border-bottom: 1px solid #ccc;">{{ $peminjaman->user->nip_nik ?? '-' }}</td></tr>
        <tr><td style="font-weight: bold;">Jabatan</td><td>:</td><td style="border-bottom: 1px solid #ccc;">{{ $peminjaman->user->jabatan ?? '-' }}</td></tr>
        <tr><td style="font-weight: bold;">Unit Kerja</td><td>:</td><td style="border-bottom: 1px solid #ccc;">{{ $peminjaman->user->instansi ?? '-' }}</td></tr>
        <tr><td style="font-weight: bold;">No. Telp. / Fax. / HP</td><td>:</td><td style="border-bottom: 1px solid #ccc;">{{ $peminjaman->user->hp ?? '-' }}</td></tr>
    </table>

    <p style="font-size: 10pt;">telah menerima pinjaman <b>Barang Inventaris</b> milik <b>Sekretariat {{ ucwords(strtolower($setting->nama_instansi ?? 'Kabupaten Pasuruan')) }}</b>, berupa :</p>

    <table class="item-table">
        <thead>
            <tr>
                <th width="15">NO</th>
                <th width="150">NAMA BARANG</th>
                <th width="35">MERK / TYPE</th>
                <th width="35">JUMLAH</th>
                <th width="35">SATUAN</th>
                <th width="35">KONDISI</th>
                <th width="200">KETERANGAN</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peminjaman->details as $index => $detail)
            <tr>
                <td>{{ $index + 1 }}.</td>
                <td style="text-align: left;">{{ $detail->asset->nama_asset }}</td>
                <td>{{ $detail->asset->merk ?? '-' }}</td>
                <td>{{ $detail->qty }}</td>
                <td>{{ $detail->satuan ?? 'Buah' }}</td>
                <td>Baik</td>
                
                {{-- Merge kolom keterangan hanya di baris pertama --}}
                @if($index == 0)
                <td rowspan="{{ $peminjaman->details->count() + $filler }}" style="text-align: left; vertical-align: top; font-size: 9pt; padding: 15px 5px 5px 5px;">
                    Dasar Rujukan Surat Kepala {{ $peminjaman->user->instansi ?? '..........' }} <br>
                    <span style="font-weight: bold;">Nomor :</span> {{ $peminjaman->nomor_surat ?? '....................' }} <br>
                    <span style="font-weight: bold;">Tanggal :</span> {{ $peminjaman->tgl_surat ? \Carbon\Carbon::parse($peminjaman->tgl_surat)->translatedFormat('d F Y') : '....................' }}
                </td>
                @endif
            </tr>
            @endforeach
            
            @for($i = 0; $i < $filler; $i++)
            <tr>
                <td>{{ $peminjaman->details->count() + $i + 1 }}.</td>
                <td>&nbsp;</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                {{-- Kolom keterangan tidak perlu di-render di sini karena sudah ter-cover oleh rowspan di atas --}}
                @if($peminjaman->details->count() == 0 && $i == 0)
                <td rowspan="{{ $filler }}" style="text-align: left; vertical-align: top; font-size: 9pt; padding: 15px 5px 5px 5px;">
                    Dasar Rujukan Surat Kepala {{ $peminjaman->user->instansi ?? '..........' }} <br>
                    <span style="font-weight: bold;">Nomor :</span> {{ $peminjaman->nomor_surat ?? '....................' }} <br>
                    <span style="font-weight: bold;">Tanggal :</span> {{ $peminjaman->tgl_surat ? \Carbon\Carbon::parse($peminjaman->tgl_surat)->translatedFormat('d F Y') : '....................' }}
                </td>
                @endif
            </tr>
            @endfor
        </tbody>
    </table>

    <table class="info-table" style="margin-top: 15px;">
        <tr><td width="175" style="font-weight: bold;">Tanggal Pinjam</td><td>: {{ \Carbon\Carbon::parse($peminjaman->tgl_pinjam)->translatedFormat('d F Y') }}</td></tr>
        <tr><td style="font-weight: bold;">Tanggal Kembali (Jatuh Tempo)</td><td>: {{ \Carbon\Carbon::parse($peminjaman->tgl_kembali)->translatedFormat('d F Y') }}</td></tr>
    </table>

    <p style="font-size: 10pt; margin-top: 15px; text-align: justify;">
        Dengan kesanggupan untuk <b>mengganti</b> atau <b>memperbaiki</b>, apabila barang yang dipinjam tersebut <b>hilang</b> atau <b>rusak</b>. Demikian untuk menjadikan periksa dan mendapatkan perhatian sepenuhnya.
    </p>

    <table class="signature-table" style="font-size: 11pt;">
        <tr>
            <td width="50%"></td>
            <td style="padding-bottom: 5px;">Pasuruan, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td style="padding-bottom: 70px; font-weight: bold;">Peminjam,</td>
            <td style="padding-bottom: 70px; font-weight: bold;">Petugas KPU,</td>
        </tr>
        <tr>
            <td><span class="nama-ttd">{{ $peminjaman->user->name }}</span></td>
            <td><span>____________________</span></td>
        </tr>
        <tr>
            <td colspan="2" style="padding-top: 25px;">
                Mengetahui :<br><br>
                
                {!! $displayNama !!}<br>
                <div style="font-weight:bold;">
                {{ strtoupper($setting->jabatan_kasubbag ?? 'KASUBBAG KEUANGAN, UMUM DAN LOGISTIK') }}
                </div>
                <br><br><br><br>
                <span class="nama-ttd">{{ $setting->nama_kasubbag ?? '' }}</span><br>
                NIP. {{ $setting->nip_kasubbag ?? '' }}
            </td>
        </tr>
    </table>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>AssetHub - Export Word</title>
    <style>
        /* Ukuran F4 (8.5 x 13 inci) dengan margin sempit 0.5cm */
        @page {
            size: 8.5in 13in;
            margin: 0cm 0cm;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 10pt;
            /* Memaksa line height serapat mungkin */
            line-height: 1.0; 
            color: black;
            margin: 0;
            padding: 0;
        }

        /* Reset semua margin elemen bawaan Word */
        p, div, td, th {
            margin: 0;
            padding: 0;
            line-height: 1.0;
        }

        /* Header KPU */
        .header-table { 
            width: 100%; 
            border-bottom: 2px solid black; 
            margin-bottom: 5px;
            padding-bottom: 3px;
        }

        .doc-title {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            font-size: 11pt;
            margin-top: 5px;
            text-transform: uppercase;
        }
        
        .doc-number {
            text-align: center;
            font-size: 10pt;
            margin-bottom: 8px;
        }

        /* Tabel Identitas - Row Height Diperkecil */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }
        
        .info-table td {
            padding: 1px 0;
            vertical-align: top;
            font-size: 10pt;
        }

        /* Tabel Barang */
        .item-table {
            width: 100%;
            border-collapse: collapse;
            margin: 5px 0;
        }
        
        .item-table th, .item-table td {
            border: 1px solid black;
            padding: 2px 3px;
            font-size: 9pt;
            text-align: center;
        }
        
        .item-table th {
            font-weight: bold;
            text-transform: uppercase;
            background-color: #f2f2f2;
        }

        /* Area Tanda Tangan Compact */
        .signature-table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
        }
        
        .signature-table td {
            text-align: center;
            vertical-align: top;
            font-size: 10pt;
        }

        .nama-ttd {
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
        }

        /* Mengatur spasi antar baris khusus untuk MS Word */
        .mso-spacing {
            margin: 0pt;
            line-height: 11pt;
        }

        .uppercase {
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    @php
        $nama = $setting->nama_instansi ?? '';
        $search = ['Kabupaten', 'KABUPATEN', 'Kota', 'KOTA'];
        $displayNama = str_replace($search, '<br>KABUPATEN', strtoupper($nama));
    @endphp
    <table class="header-table" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td width="100" style="text-align: center; padding-bottom: 5px;">
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" width="85">
                @endif
            </td>
            <td style="text-align: center;">
                <div style="font-size: 12pt; font-weight: bold;">{!! $displayNama !!}</div>
                <div style="font-size: 11pt;" class="uppercase">{{ $setting->alamat ?? 'JL. SUDARSONO NO. 1 POGAR BANGIL - PASURUAN' }}</div>
                <div style="font-size: 10pt;">
                    Telp: {{ $setting->telepon1 ?? '' }}{{ $setting->telepon2 ? ', '.$setting->telepon2 : '' }} 
                    Email: {{ $setting->email ?? 'kab_pasuruan@kpu.go.id' }}
                </div>
            </td>
        </tr>
    </table>

    <div class="doc-title">TANDA TERIMA PINJAM BARANG INVENTARIS</div>
    <div class="doc-number">NOMOR : {{ $peminjaman->kode_peminjaman }}</div>

    <p class="mso-spacing" style="margin: 5px 0;">Yang bertandatangan di bawah ini :</p>
    <table class="info-table">
        <tr><td width="120" style="font-weight: bold;">Nama</td><td width="15">:</td><td>{{ $peminjaman->user->name }}</td></tr>
        <tr><td style="font-weight: bold;">NIK/NIS</td><td>:</td><td>{{ $peminjaman->user->nip_nik ?? '-' }}</td></tr>
        <tr><td style="font-weight: bold;">Jabatan</td><td>:</td><td>{{ $peminjaman->user->jabatan ?? '-' }}</td></tr>
        <tr><td style="font-weight: bold;">Unit Kerja</td><td>:</td><td>{{ $peminjaman->user->instansi ?? '-' }}</td></tr>
        <tr><td style="font-weight: bold;">No. Telp. / Fax. / HP</td><td>:</td><td>{{ $peminjaman->user->hp ?? '-' }}</td></tr>
    </table>

    <p class="mso-spacing" style="margin: 5px 0;">telah menerima pinjaman <b>Barang Inventaris</b> milik <b>Sekretariat Komisi Pemilihan Umum Kabupaten Pasuruan</b>, berupa :</p>

    <table class="item-table">
        <thead>
            <tr>
                <th width="25">NO</th>
                <th width="145">NAMA BARANG</th>
                <th width="45">MERK / TYPE</th>
                <th width="35">JML</th>
                <th width="35">SATUAN</th>
                <th width="55">KONDISI</th>
                <th width="180">KETERANGAN</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peminjaman->details as $index => $detail)
            <tr>
                <td>{{ $index + 1 }}.</td>
                <td style="text-align: left;">{{ $detail->asset->nama_asset }}</td>
                <td>{{ $detail->asset->merk ?? '-' }}</td>
                <td>{{ $detail->qty }}</td>
                <td>{{ $detail->satuan ?? 'unit' }}</td>
                <td>Baik</td>
                @if($index == 0)
                <td rowspan="{{ $peminjaman->details->count() + $filler }}" style="text-align: left; vertical-align: top; font-size: 9pt;">
                    Dasar Rujukan Surat Kepala {{ $peminjaman->user->instansi ?? '..........' }}<br>
                    <b>Nomor :</b> {{ $peminjaman->nomor_surat ?? '..........' }}<br>
                    <b>Tanggal :</b> {{ $peminjaman->tgl_surat ? \Carbon\Carbon::parse($peminjaman->tgl_surat)->translatedFormat('d F Y') : '..........' }}
                </td>
                @endif
            </tr>
            @endforeach
            
            @for($i = 0; $i < $filler; $i++)
            <tr style="height: 12pt;">
                <td>{{ $peminjaman->details->count() + $i + 1 }}.</td>
                <td>&nbsp;</td><td></td><td></td><td></td><td></td>
            </tr>
            @endfor
        </tbody>
    </table>

    <table class="info-table">
        <tr><td width="225" style="font-weight: bold; font-size: 10pt;">Tanggal Pinjam</td><td>: {{ \Carbon\Carbon::parse($peminjaman->tgl_pinjam)->translatedFormat('d F Y') }}</td></tr>
        <tr><td width="225" style="font-weight: bold; font-size: 10pt;">Tanggal Kembali (Jatuh Tempo)</td><td>: {{ \Carbon\Carbon::parse($peminjaman->tgl_kembali)->translatedFormat('d F Y') }}</td></tr>
    </table>

    <p style="text-align: justify; font-size: 10pt; margin-top: 5px;">
        Dengan kesanggupan untuk <b>mengganti</b> atau <b>memperbaiki</b>, apabila barang yang dipinjam tersebut <b>hilang</b> atau <b>rusak</b>. Demikian untuk menjadikan periksa dan mendapatkan perhatian sepenuhnya.
    </p>

    <table class="signature-table">
        <tr>
            <td width="50%"></td>
            <td>Pasuruan, {{ \Carbon\Carbon::parse($peminjaman->tgl_kembali_real ?? $peminjaman->tgl_pinjam)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Peminjam,</td>
            <td style="font-weight: bold;">Petugas KPU,</td>
        </tr>
        <tr>
            <td style="padding-top: 45px;"><span class="nama-ttd">{{ $peminjaman->user->name }}</span></td>
            <td style="padding-top: 45px;"><span>____________________</span></td>
        </tr>
        <tr style="padding-top: 10px;">
            <td colspan="2">
                <div>Mengetahui :</div>
                <div style="margin-top: 2px;">
                    {!! $displayNama !!}
                </div>
                <div style="font-weight: bold; margin-top: 2px;">
                    {{ strtoupper($setting->jabatan_kasubbag ?? 'KASUBBAG KEUANGAN, UMUM DAN LOGISTIK') }}
                </div>
                <div style="margin-top: 45px;">
                    <span class="nama-ttd">{{ $setting->nama_kasubbag ?? 'BARDA SURAIDAH, S.E., M.A.' }}</span><br>
                    NIP. {{ $setting->nip_kasubbag ?? '19850924 201013 2 002' }}
                </div>
            </td>
        </tr>
    </table>

</body>
</html>
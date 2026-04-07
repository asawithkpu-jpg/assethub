<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'>
<head>
    <meta charset="utf-8">
    <style>
        /* Pengaturan Kertas F4 */
        @page Section1 {
            size: 8.5in 13in;
            margin: 1cm 1cm 1cm 1cm;
        }
        div.Section1 { page: Section1; }

        body { font-family: 'Arial', sans-serif; font-size: 11pt; color: black; }
        
        /* Layout Tables */
        table { width: 100%; border-collapse: collapse; }
        .header-table td { vertical-align: middle; }
        
        /* Typography */
        .instansi-1 { font-size: 16pt; font-weight: bold; }
        .instansi-2 { font-size: 14pt; font-weight: bold; }
        .alamat-line { font-size: 11pt; }
        .title { font-weight: bold; text-align: center; font-size: 12pt; margin: 15px 0; }
        
        /* Item Table */
        .item-table th, .item-table td { border: 1px solid black; padding: 4px; font-size: 10pt; }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        
        /* Signature */
        .nama-ttd { font-weight: bold; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="Section1">
        <table class="header-table" style="border-bottom: 2.5pt solid black; margin-bottom: 10px;">
            <tr>
                <td width="80" align="left">
                    @if($logoBase64)
                        <img src="{{ $logoBase64 }}" width="90" style="display:block;">
                    @endif
                </td>
                <td align="center" style="padding-right: 80px;">
                    @php
                        $nama = strtoupper($setting->nama_instansi ?? '');
                        $displayNama = str_replace(['KABUPATEN', 'KOTA'], '<br/>KABUPATEN', $nama);
                    @endphp
                    <div class="instansi-2">{!! $displayNama !!}</div>
                    <div class="alamat-line">{{ strtoupper($setting->alamat ?? '') }}</div>
                    <div style="font-size: 11pt;">
                        Telp: {{ $setting->telepon1 ?? '' }} | Email: {{ $setting->email ?? 'kab_pasuruan@kpu.go.id' }}
                    </div>
                </td>
            </tr>
        </table>

        <div class="title">PERMOHONAN PINJAM PERALATAN PENDUKUNG KEGIATAN</div>

        <p style="margin-bottom: 5px;">Yang bertandatangan dibawah ini :</p>
        <table style="margin-left: 15px;">
            <tr><td width="130">Nama</td><td>: {{ $peminjaman->user->name }}</td></tr>
            <tr><td>Jabatan</td><td>: {{ $peminjaman->user->jabatan ?? '-' }}</td></tr>
            <tr><td>NIP</td><td>: {{ $peminjaman->user->nip_nik ?? '-' }}</td></tr>
            <tr><td>Nama Kegiatan</td><td>: {{ $peminjaman->nama_kegiatan ?? '-' }}</td></tr>
            <tr><td>Tanggal</td><td>: {{ \Carbon\Carbon::parse($peminjaman->tgl_pinjam)->translatedFormat('d F Y') }}</td></tr>
        </table>

        <p style="margin-top: 15px; margin-bottom: 5px;">Dengan ini mengajukan permohonan peralatan pendukung kegiatan yang terdiri :</p>
        
        <table class="item-table">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th width="30">No.</th>
                    <th>Nama Barang</th>
                    <th width="40">Jml</th>
                    <th width="90">Tgl. Pinjam</th>
                    <th width="90">Tgl. Kembali</th>
                    <th width="50">Paraf</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($peminjaman->details as $index => $detail)
                <tr>
                    <td class="text-center">{{ $index + 1 }}.</td>
                    <td class="text-left">{{ $detail->asset->nama_asset }}</td>
                    <td class="text-center">{{ $detail->qty }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($peminjaman->tgl_pinjam)->translatedFormat('d M Y') }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($peminjaman->tgl_kembali)->translatedFormat('d M Y') }}</td>
                    <td></td>
                    <td></td>
                </tr>
                @endforeach
                
                @for($i = 0; $i < $filler; $i++)
                <tr>
                    <td class="text-center">{{ $peminjaman->details->count() + $i + 1 }}.</td>
                    <td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td>
                </tr>
                @endfor
            </tbody>
        </table>

        <p style="font-size: 9pt; margin-top: 10px; text-align: justify;">
            Dengan kesanggupan untuk mempertanggung jawabkan, apabila barang yang dipinjam tersebut hilang atau rusak. Demikian untuk menjadikan periksa dan mendapatkan perhatian sepenuhnya.
        </p>

        <table>
            <tr>
                <td width="50%"></td>
                <td align="center">Pasuruan, {{ \Carbon\Carbon::parse($peminjaman->tgl_kembali_real ?? $peminjaman->tgl_pinjam)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td align="center">Peminjam,</td>
                <td align="center">Petugas,</td>
            </tr>
            <tr><td height="60" colspan="2"></td></tr>
            <tr style="margin-top: 20px;">
                <td align="center">
                    <span class="nama-ttd">{{ $peminjaman->user->name }}</span><br/>
                    {{ $peminjaman->user->nip_nik ?? '-' }}
                </td>
                <td align="center">
                    <span>____________________</span><br/>
                    &nbsp;
                </td>
            </tr>
            <tr style="margin-top: 15px;"><td height="20" colspan="2"></td></tr>
            <tr style="margin-top: 15px;">
                <td colspan="2" align="center">
                    Mengetahui,<br/>
                    {{ $setting->jabatan_kasubbag ?? 'Kasubbag Keuangan, Umum dan Logistik' }}
                    <br/><br/><br/><br/><br/>
                    <span class="nama-ttd">{{ $setting->nama_kasubbag ?? '' }}</span><br/>
                    {{ $setting->nip_kasubbag ?? '' }}
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
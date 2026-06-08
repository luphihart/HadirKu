<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Rekap Presensi Siswa</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        /* Kop Surat (School Letterhead) */
        .kop-surat {
            border-bottom: 3px double #000;
            padding-bottom: 8px;
            margin-bottom: 20px;
            width: 100%;
        }
        .kop-logo {
            float: left;
            width: 70px;
            height: 70px;
        }
        .kop-logo img {
            width: 70px;
            height: 70px;
            object-fit: contain;
        }
        .kop-text {
            text-align: center;
            margin-left: 80px;
            margin-right: 20px;
        }
        .kop-text h2 {
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
            font-weight: bold;
        }
        .kop-text h3 {
            margin: 3px 0 0 0;
            font-size: 13px;
            font-weight: bold;
        }
        .kop-text p {
            margin: 5px 0 0 0;
            font-size: 9px;
            color: #555;
        }

        .clear {
            clear: both;
        }

        /* Document Header */
        .doc-title {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 15px;
            color: #1a1a1a;
        }

        /* Metadata info */
        .meta-table {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }
        .meta-table td {
            padding: 3px 0;
            font-size: 10px;
        }
        .meta-label {
            width: 100px;
            font-weight: bold;
            color: #555;
        }
        .meta-value {
            color: #222;
        }

        /* Stats cards block */
        .stats-block {
            margin-bottom: 20px;
            width: 100%;
        }
        .stats-card {
            display: inline-block;
            width: 18%;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 8px;
            text-align: center;
            margin-right: 1.5%;
            background-color: #f8fafc;
        }
        .stats-card-last {
            margin-right: 0;
        }
        .stats-title {
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 4px;
        }
        .stats-count {
            font-size: 14px;
            font-weight: bold;
            color: #1e293b;
        }

        /* Attendance Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .data-table th {
            background-color: #4f46e5;
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
            padding: 8px 6px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .data-table th.center, .data-table td.center {
            text-align: center;
        }
        .data-table td {
            padding: 7px 6px;
            border: 1px solid #ddd;
            font-size: 9.5px;
        }
        .data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }

        /* Status colors */
        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 8px;
            font-weight: bold;
            border-radius: 4px;
            text-transform: uppercase;
        }
        .badge-hadir { background-color: #d1fae5; color: #065f46; }
        .badge-terlambat { background-color: #fef3c7; color: #92400e; }
        .badge-sakit { background-color: #dbeafe; color: #1e40af; }
        .badge-izin { background-color: #f3e8ff; color: #5b21b6; }
        .badge-alpa { background-color: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>

    <!-- KOP SURAT SEKOLAH -->
    <div class="kop-surat">
        @if($school->logo)
            <div class="kop-logo">
                <img src="{{ public_path('storage/' . $school->logo) }}" alt="Logo">
            </div>
        @endif
        <div class="kop-text">
            <h2>{{ $school->nama_sekolah }}</h2>
            @if($school->npsn)
                <h3>NPSN: {{ $school->npsn }}</h3>
            @endif
            <p>
                Alamat: {{ $school->alamat_sekolah }}<br>
                @if($school->telepon) Telp: {{ $school->telepon }} @endif
                @if($school->email) | Email: {{ $school->email }} @endif
            </p>
        </div>
        <div class="clear"></div>
    </div>

    <!-- DOCUMENT TITLE -->
    <div class="doc-title">Laporan Rekapitulasi Presensi Murid</div>

    <!-- METADATA FILTER INFO -->
    <table class="meta-table">
        <tr>
            <td class="meta-label">Periode</td>
            <td class="meta-value">: {{ $tanggalMulai }} s.d {{ $tanggalSelesai }}</td>
            <td class="meta-label">Kelas</td>
            <td class="meta-value">: {{ $kelas }}</td>
        </tr>
        @if($murid)
            <tr>
                <td class="meta-label">Nama Murid</td>
                <td class="meta-value">: {{ $murid }}</td>
                <td class="meta-label">Unduh Pada</td>
                <td class="meta-value">: {{ now()->translatedFormat('d F Y H:i') }} WIB</td>
            </tr>
        @else
            <tr>
                <td class="meta-label">Unduh Pada</td>
                <td class="meta-value" colspan="3">: {{ now()->translatedFormat('d F Y H:i') }} WIB</td>
            </tr>
        @endif
    </table>

    <!-- SUMMARY SECTION -->
    <div class="stats-block">
        <div class="stats-card">
            <div class="stats-title" style="color: #059669;">Hadir</div>
            <div class="stats-count">{{ $summary['hadir'] }}</div>
        </div>
        <div class="stats-card">
            <div class="stats-title" style="color: #d97706;">Terlambat</div>
            <div class="stats-count">{{ $summary['terlambat'] }}</div>
        </div>
        <div class="stats-card">
            <div class="stats-title" style="color: #2563eb;">Sakit</div>
            <div class="stats-count">{{ $summary['sakit'] }}</div>
        </div>
        <div class="stats-card">
            <div class="stats-title" style="color: #7c3aed;">Izin</div>
            <div class="stats-count">{{ $summary['izin'] }}</div>
        </div>
        <div class="stats-card stats-card-last">
            <div class="stats-title" style="color: #dc2626;">Alpa</div>
            <div class="stats-count">{{ $summary['tidak_presensi'] }}</div>
        </div>
    </div>

    <!-- TABLE OF RECORDS -->
    <table class="data-table">
        <thead>
            <tr>
                <th class="center" style="width: 30px;">No</th>
                <th style="width: 70px;">NIS</th>
                <th>Nama Murid</th>
                <th style="width: 70px;">Kelas</th>
                <th style="width: 80px;">Tanggal</th>
                <th class="center" style="width: 50px;">Jam Masuk</th>
                <th class="center" style="width: 50px;">Jam Pulang</th>
                <th class="center" style="width: 70px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($presensi as $index => $row)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td>{{ $row->user->nis ?? '-' }}</td>
                    <td><strong>{{ $row->user->name }}</strong></td>
                    <td>{{ $row->user->kelas->nama_kelas ?? '-' }}</td>
                    <td>{{ $row->tanggal->translatedFormat('d-m-Y') }}</td>
                    <td class="center">{{ $row->jam_masuk ? substr($row->jam_masuk, 0, 5) : '-' }}</td>
                    <td class="center">{{ $row->jam_pulang ? substr($row->jam_pulang, 0, 5) : '-' }}</td>
                    <td class="center">
                        @php 
                            $class = match($row->status) {
                                'hadir' => 'badge-hadir',
                                'terlambat' => 'badge-terlambat',
                                'sakit' => 'badge-sakit',
                                'izin' => 'badge-izin',
                                'tidak_presensi' => 'badge-alpa',
                                default => ''
                            };
                        @endphp
                        <span class="badge {{ $class }}">{{ $row->status_label }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="center" style="padding: 20px; color: #888;">
                        Tidak ada riwayat presensi tercatat untuk kriteria ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>

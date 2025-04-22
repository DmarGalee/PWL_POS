<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Data Penjualan</title>
    <style>
        /* Gaya CSS Polinema */
        body {
            font-family: "Times New Roman", Times, serif;
            margin: 6px 20px 5px 20px;
            line-height: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td, th {
            padding: 4px 3px;
        }
        th {
            text-align: left;
        }
        .d-block {
            display: block;
        }
        img.image {
            width: auto;
            height: 80px;
            max-width: 150px;
            max-height: 150px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .p-1 {
            padding: 5px 1px 5px 1px;
        }
        .font-10 {
            font-size: 10pt;
        }
        .font-11 {
            font-size: 11pt;
        }
        .font-12 {
            font-size: 12pt;
        }
        .font-13 {
            font-size: 13pt;
        }
        .border-bottom-header {
            border-bottom: 1px solid;
        }
        .border-all, .border-all th, .border-all td {
            border: 1px solid;
        }
        .badge {
            padding: 3px 6px;
            border-radius: 3px;
            font-weight: bold;
        }
        .badge-success {
            background-color: #28a745;
            color: white;
        }
        .badge-danger {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body>
    <table class="border-bottom-header">
        <tr>
            <td width="15%" class="text-center">
                <img src="{{ asset('polinema-bw.png') }}">
            </td>
            <td width="85%">
                <span class="text-center d-block font-11 font-bold mb-1">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</span>
                <span class="text-center d-block font-13 font-bold mb-1">POLITEKNIK NEGERI MALANG</span>
                <span class="text-center d-block font-10">Jl. Soekarno-Hatta No. 9 Malang 65141</span>
                <span class="text-center d-block font-10">Telepon (0341) 404424 Pes. 101105, 0341-404420, Fax. (0341) 404420</span>
                <span class="text-center d-block font-10">Laman: www.polinema.ac.id</span>
            </td>
        </tr>
    </table>

    <h3 class="text-center">LAPORAN DATA PENJUALAN BARANG</h3>

    <table class="border-all">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>No Penjualan</th>
                <th>Tanggal Penjualan</th>
                <th>Nama Kasir</th>
                <th>Nama Barang</th>
                <th class="text-center">Jumlah Penjualan</th>
                <th class="text-center">Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($penjualan as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->no_penjualan }}</td>
                <td>{{ $item->tanggal_penjualan }}</td>
                <td>{{ $item->user->nama_lengkap ?? '-' }}</td>
                <td>
                    @if ($item->PenjualanDetail)
                        @foreach ($item->PenjualanDetail as $detail)
                            {{ $detail->barang->nama_barang ?? '-' }} <br>
                        @endforeach
                    @else
                        -
                    @endif
                </td>
                <td class="text-center">
                    @if ($item->PenjualanDetail)
                        @foreach ($item->PenjualanDetail as $detail)
                            {{ $detail->jumlah_barang }} <br>
                        @endforeach
                    @else
                        -
                    @endif
                </td>
                <td class="text-center">
                    @if ($item->PenjualanDetail)
                        {{ $item->PenjualanDetail->sum('harga_satuan') }}
                    @else
                        0
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

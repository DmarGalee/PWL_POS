<!DOCTYPE html>
<html>
<head>
    <title>Data Kategori</title>
</head>
<body>
    <h1>Data Kategori</h1>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Kategori</th>
                <th>Deskripsi Kategori</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $kategori)
            <tr>
                <td>{{ $kategori->id }}</td>
                <td>{{ $kategori->nama_kategori }}</td>
                <td>{{ $kategori->deskripsi_kategori }}</td>
                <td>{{ $kategori->created_at }}</td>
                <td>{{ $kategori->updated_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
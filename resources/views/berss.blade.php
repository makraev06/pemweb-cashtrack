<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Members | SIMAKAS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

    <div class="sidebar">
        <div>
            <h2>SIMAKAS</h2>
            <ul>
                <li><a href="/dashboard" class="menu-link"><span>Dashboard</span></a></li>
                <li><a href="/members" class="menu-link active"><span>Members</span></a></li>
                <li><a href="/kas" class="menu-link"><span>Kas</span></a></li>
                <li><a href="/transactions" class="menu-link"><span>Transactions</span></a></li>
            </ul>
        </div>
    </div>

    <div class="main">
        <div class="topbar">
            <h1>Members</h1>
            <button class="btn btn-primary" onclick="openModal()">+ Tambah Member</button>
        </div>

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Divisi</th>
                        <th>Angkatan</th>
                        <th>HP</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach($members as $row)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $row->name }}</td>
                            <td>{{ $row->division }}</td>
                            <td>{{ $row->angkatan }}</td>
                            <td>{{ $row->phone }}</td>
                            <td>
                                @if($row->status == 'aktif')
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <a href="/members/hapus/{{ $row->id }}" class="btn btn-danger"
                                    onclick="return confirm('Yakin hapus?')">Hapus</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal" id="modal">
        <div class="modal-content">
            <h3>Tambah Member</h3>
            <form method="POST" action="/members/tambah">
                @csrf <input type="text" name="name" placeholder="Nama" required>
                <input type="text" name="division" placeholder="Divisi" required>
                <input type="text" name="angkatan" placeholder="Angkatan" required>
                <input type="text" name="phone" placeholder="No HP" required>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <button type="button" onclick="closeModal()" class="btn btn-danger">Batal</button>
            </form>
        </div>
    </div>

    <script>
        function openModal() { document.getElementById("modal").style.display = "flex" }
        function closeModal() { document.getElementById("modal").style.display = "none" }
    </script>
</body>

</html>
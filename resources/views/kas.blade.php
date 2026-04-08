<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Kas | SIMAKAS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

    <div class="sidebar">
        <div>
            <h2>SIMAKAS</h2>
            <ul>
                <li>
                    <a href="/dashboard" class="menu-link {{ request()->is('dashboard') ? 'active' : '' }}">
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="/members" class="menu-link {{ request()->is('members') ? 'active' : '' }}">
                        <span>Members</span>
                    </a>
                </li>
                <li>
                    <a href="/kas" class="menu-link {{ request()->is('kas') ? 'active' : '' }}">
                        <span>Kas</span>
                    </a>
                </li>
                <li>
                    <a href="/transactions" class="menu-link {{ request()->is('transactions') ? 'active' : '' }}">
                        <span>Transactions</span>
                    </a>
                </li>
            </ul>
        </div>
        <div>
            <ul>
                <li><a href="#" class="btn btn-danger text-center">Logout</a></li>
            </ul>
        </div>
    </div>

    <div class="main">

        <div class="topbar">
            <h1>Kas Bulanan</h1>
        </div>

        <div class="filter-box">
            <form method="GET" action="/kas">
                <select name="month">
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                            {{ date("F", mktime(0, 0, 0, $m, 1)) }}
                        </option>
                    @endfor
                </select>

                <input type="number" name="year" value="{{ $selectedYear }}" style="width:100px">
                <button class="btn btn-primary">Filter</button>
            </form>
        </div>

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Divisi</th>
                        <th>Status</th>
                        <th>Nominal</th>
                    </tr>
                </thead>
                <tbody>

                    @php $no = 1; @endphp
                    @foreach($members as $row)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $row->name }}</td>
                            <td>{{ $row->division }}</td>

                            <td>
                                @if($row->kas_status == 'lunas')
                                    <span class="badge badge-success">Lunas</span>
                                @else
                                    <span class="badge badge-danger">Belum</span>
                                @endif
                            </td>

                            <td>
                                <form method="POST" action="{{ $row->kas_status == 'lunas' ? '/kas/batal' : '/kas/bayar' }}"
                                    style="display:flex;align-items:center;gap:12px;width:100%;">
                                    @csrf
                                    <input type="hidden" name="member_id" value="{{ $row->id }}">
                                    <input type="hidden" name="month" value="{{ $selectedMonth }}">
                                    <input type="hidden" name="year" value="{{ $selectedYear }}">

                                    <span style="flex:1;">
                                        Rp {{ number_format($row->amount ?? 10000, 0, ',', '.') }}
                                    </span>

                                    @if($row->kas_status == 'lunas')
                                        <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('Yakin ingin membatalkan pembayaran ini?')">Batal</button>
                                    @else
                                        <button type="submit" class="btn btn-success"
                                            onclick="return confirm('Yakin ingin menandai pembayaran Rp 10.000 sebagai LUNAS?')">Bayar</button>
                                    @endif

                                </form>
                            </td>

                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

    </div>

</body>

</html>

<form method="GET" action="/kas">
    <select name="month">
        @for ($m = 1; $m <= 12; $m++)
            <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                {{ date("F", mktime(0, 0, 0, $m, 1)) }}
            </option>
        @endfor
    </select>
    <input type="number" name="year" value="{{ $selectedYear }}">
    <button class="btn btn-primary">Filter</button>
</form>

@foreach($members as $row)
    <tr>
        <td>{{ $row->name }}</td>
        <td>
            @if($row->kas_status == 'lunas')
                <span class="badge badge-success">Lunas</span>
            @else
                <span class="badge badge-danger">Belum</span>
            @endif
        </td>
        <td>
            <form method="POST" action="{{ $row->kas_status == 'lunas' ? '/kas/batal' : '/kas/bayar' }}">
                @csrf
                <input type="hidden" name="member_id" value="{{ $row->id }}">
                <input type="hidden" name="month" value="{{ $selectedMonth }}">
                <input type="hidden" name="year" value="{{ $selectedYear }}">

                <span>Rp {{ number_format($row->amount ?? 10000, 0, ',', '.') }}</span>

                @if($row->kas_status == 'lunas')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Batal?')">Batal</button>
                @else
                    <button type="submit" class="btn btn-success" onclick="return confirm('Bayar?')">Bayar</button>
                @endif
            </form>
        </td>
    </tr>
@endforeach
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMAKAS Dashboard</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    <div class="sidebar">
        <div>
            <h2>SIMAKAS</h2>
            <ul>
                <li>
                    <a href="/dashboard" class="menu-link active">
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="/members" class="menu-link">
                        <span>Members</span>
                    </a>
                </li>
                <li>
                    <a href="/kas" class="menu-link">
                        <span>Kas</span>
                    </a>
                </li>
                <li>
                    <a href="/transactions" class="menu-link">
                        <span>Transactions</span>
                    </a>
                </li>
            </ul>
        </div>
        <div>
            <ul>
                <li>
                    <a href="#" class="btn btn-danger text-center">Logout</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="main">
        <div class="topbar">
            <h1>Dashboard</h1>
        </div>

        <div class="cards">
            <div class="card">
                <h3>Saldo</h3>
                <h2 style="color:#007bff">Rp {{ number_format($saldo, 0, ',', '.') }}</h2>
            </div>

            <div class="card">
                <h3>Total Income</h3>
                <h2 style="color:#28a745">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h2>
            </div>

            <div class="card">
                <h3>Total Expense</h3>
                <h2 style="color:#dc3545">Rp {{ number_format($totalExpense, 0, ',', '.') }}</h2>
            </div>

            <div class="card">
                <h3>Total Members</h3>
                <h2>{{ $totalMembers }}</h2>
            </div>
        </div>

        <div class="table-container">
            <h3>Laporan Transaksi Terbaru</h3>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentTransactions as $row)
                        <tr>
                            <td>{{ date('d-m-Y', strtotime($row->date)) }}</td>
                            <td>{{ $row->description }}</td>
                            <td style="color:{{ $row->type == 'Income' ? '#28a745' : '#dc3545' }}; font-weight:600">
                                {{ $row->type }}
                            </td>
                            <td>Rp {{ number_format($row->amount, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card">
            <h3>Financial Overview ({{ date('Y') }})</h3>
            <div class="chart-container">
                <canvas id="myChart"></canvas>
            </div>
        </div>

        <script>
            const ctx = document.getElementById('myChart');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Income',
                        data: @json($incomeData),
                        backgroundColor: '#28a745'
                    }, {
                        label: 'Expense',
                        data: @json($expenseData),
                        backgroundColor: '#dc3545'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        </script>

    </div>
</body>

</html>
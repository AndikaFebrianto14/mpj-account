<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Profit & Loss Statement</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #212529;
            margin: 0;
            padding: 20px;
        }

        h2,
        h4 {
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            color: #0d6efd;
        }

        .period {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th,
        td {
            padding: 6px 10px;
            border: 1px solid #dee2e6;
        }

        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        tfoot th {
            background-color: #e9ecef;
        }

        .text-end {
            text-align: right;
        }

        .revenue {
            color: #198754;
        }

        .expense {
            color: #dc3545;
        }

        .profit {
            color: #0d6efd;
            font-weight: bold;
        }

        .loss {
            color: #dc3545;
            font-weight: bold;
        }

        .summary {
            margin-top: 30px;
            padding: 10px;
            border: 1px solid #dee2e6;
            background-color: #f8f9fa;
        }

        .summary div {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Profit & Loss Statement</h2>
        <div class="period">
            Period: {{ $startDate ?? 'All' }} to {{ $endDate ?? 'All' }}
        </div>
    </div>

    <!-- Revenues Table -->
    <h4>Revenues</h4>
    <table>
        <thead>
            <tr>
                <th>Account</th>
                <th class="text-end">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($revenues as $r)
            <tr>
                <td>{{ $r['acc']->account_number }} - {{ $r['acc']->account_name }}</td>
                <td class="text-end revenue">{{ number_format($r['balance'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>Total Revenue</th>
                <th class="text-end revenue">{{ number_format($totalRevenue, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    <!-- Expenses Table -->
    <h4>Expenses</h4>
    <table>
        <thead>
            <tr>
                <th>Account</th>
                <th class="text-end">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($expenses as $e)
            <tr>
                <td>{{ $e['acc']->account_number }} - {{ $e['acc']->account_name }}</td>
                <td class="text-end expense">{{ number_format($e['balance'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>Total Expenses</th>
                <th class="text-end expense">{{ number_format($totalExpense, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    <!-- Net Profit/Loss Summary -->
    <div class="summary">
        <div>
            <span>Total Revenue:</span>
            <span class="revenue">{{ number_format($totalRevenue, 0, ',', '.') }}</span>
        </div>
        <div>
            <span>Total Expenses:</span>
            <span class="expense">{{ number_format($totalExpense, 0, ',', '.') }}</span>
        </div>
        <div>
            <span>Net {{ $netProfit >= 0 ? 'Profit' : 'Loss' }}:</span>
            <span class="{{ $netProfit >= 0 ? 'profit' : 'loss' }}">{{ number_format($netProfit, 0, ',', '.') }}</span>
        </div>
    </div>
</body>

</html>
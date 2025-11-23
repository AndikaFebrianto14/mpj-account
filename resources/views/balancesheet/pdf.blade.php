<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Balance Sheet</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 0;
        }

        header {
            text-align: center;
            margin-bottom: 20px;
        }

        header h1 {
            color: #0d6efd;
            font-size: 20px;
            margin: 0;
        }

        header p {
            font-size: 12px;
            margin: 2px 0;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th,
        td {
            padding: 6px 8px;
            border: 1px solid #ccc;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tfoot tr {
            font-weight: bold;
            background-color: #f9f9f9;
        }

        .section-title {
            background-color: #0d6efd;
            color: #fff;
            font-weight: bold;
            padding: 5px;
            margin-top: 10px;
        }

        .text-right {
            text-align: right;
        }

        .balance-status {
            font-weight: bold;
            font-size: 13px;
            margin-top: 15px;
            padding: 10px;
            border-radius: 5px;
        }

        .balanced {
            color: #155724;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
        }

        .not-balanced {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>

<body>

    <header>
        <h1>Balance Sheet</h1>
        <p>Period: {{ $startDate ?? 'All' }} to {{ $endDate ?? 'All' }}</p>
    </header>

    {{-- Assets Section --}}
    <div class="section-title">Assets</div>
    <table>
        <thead>
            <tr>
                <th>Account Number</th>
                <th>Account Name</th>
                <th class="text-right">Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($report['assets'] as $a)
            <tr>
                <td>{{ $a['acc']->account_number }}</td>
                <td>{{ $a['acc']->account_name }}</td>
                <td class="text-right">{{ number_format($a['balance'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">Total Assets</td>
                <td class="text-right">{{ number_format($totalAssets, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- Liabilities Section --}}
    <div class="section-title">Liabilities</div>
    <table>
        <thead>
            <tr>
                <th>Account Number</th>
                <th>Account Name</th>
                <th class="text-right">Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($report['liabilities'] as $l)
            <tr>
                <td>{{ $l['acc']->account_number }}</td>
                <td>{{ $l['acc']->account_name }}</td>
                <td class="text-right">{{ number_format($l['balance'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">Total Liabilities</td>
                <td class="text-right">{{ number_format($totalLiabilities, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- Equity Section --}}
    <div class="section-title">Equity</div>
    <table>
        <thead>
            <tr>
                <th>Account Number</th>
                <th>Account Name</th>
                <th class="text-right">Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($report['equity'] as $e)
            <tr>
                <td>{{ $e['acc']->account_number }}</td>
                <td>{{ $e['acc']->account_name }}</td>
                <td class="text-right">{{ number_format($e['balance'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">Total Equity</td>
                <td class="text-right">{{ number_format($totalEquity, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- Balance Check --}}
    <div class="balance-status {{ $totalAssets == $totalLiabilities + $totalEquity ? 'balanced' : 'not-balanced' }}">
        Status: {{ $totalAssets == $totalLiabilities + $totalEquity ? 'Balanced ✅' : 'Not Balanced ❌' }}
    </div>

</body>

</html>
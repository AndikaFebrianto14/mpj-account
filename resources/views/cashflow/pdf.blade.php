<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Cash Flow Statement</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            color: #212529;
        }

        .container {
            padding: 25px;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #0d6efd;
            padding-bottom: 12px;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 26px;
            margin-bottom: 5px;
            color: #0d6efd;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header .date-range {
            font-size: 0.95rem;
            color: #495057;
        }

        .summary {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
        }

        .summary-box {
            flex: 1;
            padding: 15px;
            border-radius: 8px;
            margin-right: 10px;
            text-align: center;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            color: #fff;
            font-weight: bold;
            font-size: 14px;
        }

        .summary-box:last-child {
            margin-right: 0;
        }

        .cash-in {
            background-color: #198754;
            margin-bottom: 10px;
        }

        .cash-out {
            background-color: #dc3545;
            margin-bottom: 10px;
        }

        .net-positive {
            background-color: #0d6efd;
            margin-bottom: 10px;
        }

        .net-negative {
            background-color: #6c757d;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            font-size: 12px;
        }

        th,
        td {
            padding: 8px 10px;
            border: 1px solid #dee2e6;
        }

        th {
            background-color: #f1f3f5;
            font-weight: bold;
        }

        tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        td.text-right {
            text-align: right;
            font-family: monospace;
        }

        tfoot td {
            font-weight: 700;
            background-color: #e9ecef;
        }

        .positive {
            color: #198754;
            font-weight: bold;
        }

        .negative {
            color: #dc3545;
            font-weight: bold;
        }

        .net-cash {
            font-size: 16px;
            font-weight: bold;
            padding: 12px;
            text-align: center;
            border-radius: 6px;
            color: #fff;
            margin-bottom: 30px;
        }

        .net-positive-box {
            background: linear-gradient(90deg, #0d6efd, #0b5ed7);
        }

        .net-negative-box {
            background: linear-gradient(90deg, #dc3545, #b02a37);
        }

        .footer {
            text-align: center;
            font-size: 10px;
            color: #6c757d;
            margin-top: 30px;
            border-top: 1px solid #dee2e6;
            padding-top: 5px;
        }
    </style>
</head>

<body>
    <div class="container">

        <div class="header">
            <h1>Cash Flow Statement</h1>
            <div class="date-range">
                Period: {{ $startDate ?? '-' }} to {{ $endDate ?? '-' }}
            </div>
        </div>

        <div class="summary">
            <div class="summary-box cash-in">
                Total Cash In<br>{{ number_format($totalIn, 0, ',', '.') }}
            </div>
            <div class="summary-box cash-out">
                Total Cash Out<br>{{ number_format($totalOut, 0, ',', '.') }}
            </div>
            <div class="summary-box {{ $netCash >= 0 ? 'net-positive' : 'net-negative' }}">
                Net Cash Flow<br>{{ number_format($netCash, 0, ',', '.') }}
            </div>
        </div>

        <h3>Cash Inflows</h3>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Description</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cashIn as $c)
                <tr>
                    <td>{{ $c['date'] }}</td>
                    <td>{{ $c['desc'] }}</td>
                    <td class="text-right positive">{{ number_format($c['amount'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2">Total Cash In</td>
                    <td class="text-right positive">{{ number_format($totalIn, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <h3>Cash Outflows</h3>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Description</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cashOut as $c)
                <tr>
                    <td>{{ $c['date'] }}</td>
                    <td>{{ $c['desc'] }}</td>
                    <td class="text-right negative">{{ number_format($c['amount'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2">Total Cash Out</td>
                    <td class="text-right negative">{{ number_format($totalOut, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="net-cash {{ $netCash >=0 ? 'net-positive-box' : 'net-negative-box' }}">
            Net Cash Flow: {{ number_format($netCash, 0, ',', '.') }}
        </div>

        <div class="footer">
            Generated by Your Accounting System
        </div>

    </div>
</body>

</html>
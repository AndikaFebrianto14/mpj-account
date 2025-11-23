<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Ledger Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        h2,
        h3 {
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
        }

        .info-box {
            border: 1px solid #000;
            padding: 10px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table th {
            background: #f0f0f0;
            font-weight: bold;
        }

        table th,
        table td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .fw-bold {
            font-weight: bold;
        }

        .totals-row {
            background: #e8e8e8;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>GENERAL LEDGER REPORT</h2>
        <h3>{{ $account->account_number }} - {{ $account->account_name }}</h3>
    </div>

    <div class="info-box">
        <p><strong>Period:</strong> {{ $start }} to {{ $end }}</p>
        <p><strong>Generated at:</strong> {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Entry No</th>
                <th>Description</th>
                <th class="text-right">Debit</th>
                <th class="text-right">Credit</th>
                <th class="text-right">Balance</th>
            </tr>
        </thead>
        <tbody>
            @php $balance = 0; @endphp

            @foreach ($entries as $e)
            @php
            $debit = $e->debit_amount ?? 0;
            $credit = $e->credit_amount ?? 0;
            $balance += $debit - $credit;
            @endphp
            <tr>
                <td>{{ $e->journal->entry_date }}</td>
                <td>{{ $e->journal->entry_number }}</td>
                <td>{{ $e->description }}</td>
                <td class="text-right">{{ $debit > 0 ? number_format($debit, 0, ',', '.') : '-' }}</td>
                <td class="text-right">{{ $credit > 0 ? number_format($credit, 0, ',', '.') : '-' }}</td>
                <td class="text-right fw-bold">{{ number_format($balance, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr class="totals-row">
                <td colspan="3" class="text-right">Total:</td>
                <td class="text-right">{{ number_format($entries->sum('debit_amount'), 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($entries->sum('credit_amount'), 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($balance, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

</body>

</html>
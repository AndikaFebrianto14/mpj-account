<!DOCTYPE html>
<html>

<head>
    <title>General Ledger - Print</title>
    <meta charset="utf-8">

    <style>
        body {
            font-family: "Calibri", "Segoe UI", sans-serif;
            font-size: 13px;
            color: #222;
            margin: 20px 40px;
        }

        /* Header */
        .report-title {
            text-align: center;
            margin-bottom: 5px;
        }

        .report-title h1 {
            font-size: 26px;
            letter-spacing: 1px;
            margin: 0;
            font-weight: 600;
        }

        .divider {
            height: 3px;
            background: #000;
            margin: 10px 0 25px 0;
        }

        /* Account Info Box */
        .info-box {
            border: 1px solid #444;
            padding: 12px 15px;
            margin-bottom: 25px;
            background: #fafafa;
        }

        .info-row {
            margin-bottom: 4px;
        }

        .info-label {
            width: 140px;
            display: inline-block;
            font-weight: bold;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead tr {
            background: #e8e8e8;
        }

        th {
            padding: 8px;
            font-weight: 600;
            border-bottom: 1px solid #555;
            border-top: 1px solid #555;
        }

        td {
            padding: 7px 6px;
            border-bottom: 1px solid #ddd;
            vertical-align: top;
        }

        .text-end {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* Footer */
        .footer {
            margin-top: 40px;
            font-size: 12px;
            text-align: right;
            color: #666;
        }

        /* Page Setup */
        @media print {
            @page {
                size: A4 portrait;
                margin: 12mm 15mm;
            }
        }
    </style>

</head>

<body onload="window.print()">

    <!-- Header -->
    <div class="report-title">
        <h1>GENERAL LEDGER</h1>
    </div>
    <div class="divider"></div>

    <!-- ACCOUNT INFORMATION BOX -->
    <div class="info-box">
        <div class="info-row"><span class="info-label">Account Number</span>: {{ $account->account_number }}</div>
        <div class="info-row"><span class="info-label">Account Name</span>: {{ $account->account_name }}</div>
        <div class="info-row"><span class="info-label">Normal Balance</span>: {{ $account->normal_balance }}</div>
        <div class="info-row"><span class="info-label">Period</span>: {{ $start }} to {{ $end }}</div>
        <div class="info-row"><span class="info-label">Total Records</span>: {{ count($entries) }}</div>
    </div>

    <!-- TABLE -->
    <table>
        <thead>
            <tr>
                <th width="11%">Date</th>
                <th width="16%">Entry Number</th>
                <th>Description</th>
                <th width="12%" class="text-end">Debit</th>
                <th width="12%" class="text-end">Credit</th>
                <th width="14%" class="text-end">Balance</th>
            </tr>
        </thead>

        <tbody>
            @php $balance = 0; @endphp

            @foreach ($entries as $e)
            @php
            $debit = $e->debit_amount ?? 0;
            $credit = $e->credit_amount ?? 0;
            $balance += ($debit - $credit);
            @endphp

            <tr>
                <td>{{ $e->journal->entry_date }}</td>
                <td>{{ $e->journal->entry_number }}</td>
                <td>{{ $e->description }}</td>
                <td class="text-end">
                    {{ $debit > 0 ? number_format($debit, 0, ',', '.') : '-' }}
                </td>
                <td class="text-end">
                    {{ $credit > 0 ? number_format($credit, 0, ',', '.') : '-' }}
                </td>
                <td class="text-end">
                    {{ number_format($balance, 0, ',', '.') }}
                </td>
            </tr>
            @endforeach

            @if(count($entries) === 0)
            <tr>
                <td colspan="6" class="text-center" style="padding: 20px; color: #888;">
                    No transactions found for this account and period.
                </td>
            </tr>
            @endif
        </tbody>

        @if(count($entries) > 0)
        <tfoot>
            <tr>
                <th colspan="3" class="text-end" style="border-top: 2px solid #000;">TOTAL</th>
                <th class="text-end" style="border-top: 2px solid #000;">
                    {{ number_format($entries->sum('debit_amount'), 0, ',', '.') }}
                </th>
                <th class="text-end" style="border-top: 2px solid #000;">
                    {{ number_format($entries->sum('credit_amount'), 0, ',', '.') }}
                </th>
                <th class="text-end" style="border-top: 2px solid #000;">
                    {{ number_format($balance, 0, ',', '.') }}
                </th>
            </tr>
        </tfoot>
        @endif
    </table>

    <div class="footer">
        Printed on: {{ now()->format('Y-m-d H:i:s') }}
    </div>

</body>

</html>
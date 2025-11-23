<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Trial Balance Report</title>

    <style>
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0 25px;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
        }

        .header .title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .header .subtitle {
            font-size: 13px;
            margin-top: 0;
        }

        .line {
            border-bottom: 2px solid #222;
            margin: 10px 0 20px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        table thead th {
            background: #f2f2f2;
            padding: 8px;
            font-weight: bold;
            border-bottom: 1px solid #bbb;
            border-top: 1px solid #bbb;
        }

        table tbody td {
            padding: 8px;
            border-bottom: 1px solid #e6e6e6;
        }

        table tfoot th {
            padding: 8px;
            border-top: 2px solid #444;
            background: #fafafa;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .total-row {
            background: #f7f7f7;
            font-weight: bold;
        }

        .footer {
            text-align: right;
            margin-top: 30px;
            font-size: 11px;
            color: #777;
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="title">TRIAL BALANCE REPORT</div>
        <div class="subtitle">Period:
            <strong>{{ $startDate }}</strong> to
            <strong>{{ $endDate }}</strong>
        </div>
    </div>

    <div class="line"></div>

    <table>
        <thead>
            <tr>
                <th class="text-left">Account No</th>
                <th class="text-left">Account Name</th>
                <th class="text-right">Debit</th>
                <th class="text-right">Credit</th>
                <th class="text-right">Ending Balance</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($report as $r)
            <tr>
                <td>{{ $r['account_number'] }}</td>
                <td>{{ $r['account_name'] }}</td>
                <td class="text-right">{{ number_format($r['debit'], 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($r['credit'], 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($r['ending'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr class="total-row">
                <th colspan="2" class="text-left">TOTAL</th>
                <th class="text-right">{{ number_format($totalDebit, 0, ',', '.') }}</th>
                <th class="text-right">{{ number_format($totalCredit, 0, ',', '.') }}</th>
                <th></th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Generated on: {{ date('Y-m-d H:i') }}
    </div>

</body>

</html>
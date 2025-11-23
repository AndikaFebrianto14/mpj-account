<x-app-layout>
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            background: #f5f7fb;
        }

        .stat-card {
            border-radius: 14px;
            padding: 20px;
            background: linear-gradient(180deg, #fff, #fbfdff);
            box-shadow: 0 8px 20px rgba(27, 39, 66, 0.04);
        }

        .stat-title {
            font-size: 0.9rem;
            color: #6b7280;
        }

        .stat-value {
            font-size: 1.4rem;
            font-weight: 700;
            margin-top: 6px;
        }

        .chart-card {
            border-radius: 12px;
            padding: 18px;
            background: #fff;
            box-shadow: 0 10px 30px rgba(27, 39, 66, 0.04);
        }

        .section-title {
            font-weight: 700;
            font-size: 1.05rem;
            color: #0f172a;
        }

        .quick-btn {
            border-radius: 10px;
            padding: 10px;
            font-weight: 600;
        }

        .table-hover tbody tr:hover {
            background: rgba(15, 23, 42, 0.03);
        }

        .small-muted {
            color: #6b7280;
            font-size: 0.9rem;
        }

        .card-flex {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .icon-circle {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #eef2ff, #eefcf6);
        }
    </style>

    <div class="container py-4">

        <!-- Header -->
        <div class="d-flex justify-content-between mb-3">
            <div>
                <h3 class="mb-0">Dashboard</h3>
                <div class="small-muted">Financial Summary & Recent Activity</div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="small-muted">Hi, {{ auth()->user()->name }}</div>
            </div>
        </div>

        <!-- Stat cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="card-flex">
                        <div class="icon-circle"><i class="bi bi-bank text-primary"></i></div>
                        <div>
                            <div class="stat-title">Assets</div>
                            <div class="stat-value text-primary">Rp {{ number_format($totalAssets,0,',','.') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card">
                    <div class="card-flex">
                        <div class="icon-circle"><i class="bi bi-wallet2 text-danger"></i></div>
                        <div>
                            <div class="stat-title">Liabilities</div>
                            <div class="stat-value text-danger">Rp {{ number_format($totalLiabilities,0,',','.') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card">
                    <div class="card-flex">
                        <div class="icon-circle"><i class="bi bi-people-fill text-success"></i></div>
                        <div>
                            <div class="stat-title">Equity</div>
                            <div class="stat-value text-success">Rp {{ number_format($totalEquity,0,',','.') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card">
                    <div class="card-flex">
                        <div class="icon-circle"><i class="bi bi-graph-up text-warning"></i></div>
                        <div>
                            <div class="stat-title">Profit / Loss</div>
                            <div class="stat-value text-warning">Rp {{ number_format($profitLoss,0,',','.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main charts -->
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="chart-card mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="section-title">Cash Flow (Last 12 Months)</div>
                        <div class="small-muted">Realtime from journals</div>
                    </div>
                    <div style="height:320px;">
                        <canvas id="cashFlowChart"></canvas>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="chart-card">
                            <div class="section-title mb-2">Income vs Expense</div>
                            <div style="height:220px;"><canvas id="incomeExpenseChart"></canvas></div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="chart-card">
                            <div class="section-title mb-2">Financial Composition</div>
                            <div style="height:220px;"><canvas id="balanceChart"></canvas></div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-lg-4">
                <div class="chart-card mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="section-title">Quick Menu</div>
                        <div class="small-muted">Actions</div>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('journals.create') }}" class="btn btn-primary quick-btn">
                            <i class="bi bi-plus-lg me-2"></i> Input New Journal
                        </a>
                        <a href="{{ route('ledger.index') }}" class="btn btn-outline-secondary quick-btn">General Ledger</a>
                        <a href="{{ route('trialbalance.index') }}" class="btn btn-outline-secondary quick-btn">Trial Balance</a>
                        <a href="{{ route('balancesheet.index') }}" class="btn btn-outline-secondary quick-btn">Balance Sheet</a>
                        <a href="{{ route('profitloss.index') }}" class="btn btn-outline-secondary quick-btn">Profit & Loss Statement</a>
                        <a href="{{ route('cashflow.index') }}" class="btn btn-outline-secondary quick-btn">Cash Flow Statement</a>
                    </div>
                </div>


            </div>
            <div class="col-lg-12">
                <div class="chart-card">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="section-title">Latest Transactions</div>
                        <div class="small-muted">Last 10</div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th class="text-end">Debit</th>
                                    <th class="text-end">Credit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($latestTransactions as $trx)
                                <tr>
                                    <td>{{ $trx->entry_date}}</td>
                                    <td>{{ $trx->description }}</td>
                                    <td class="text-end">Rp {{ number_format($trx->details->sum('debit_amount'),0,',','.') }}</td>
                                    <td class="text-end">Rp {{ number_format($trx->details->sum('credit_amount'),0,',','.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center small-muted py-4">No transactions yet</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js script (premium options) -->
    <script>
        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        font: {
                            size: 12
                        },
                        padding: 16
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(20,20,20,0.92)',
                    titleFont: {
                        size: 13
                    },
                    bodyFont: {
                        size: 13
                    },
                    padding: 10,
                    borderRadius: 8
                }
            },
            scales: {
                y: {
                    grid: {
                        color: "rgba(0,0,0,0.04)"
                    },
                    ticks: {
                        font: {
                            size: 12
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 12
                        }
                    }
                }
            }
        };

        // helper: fetch JSON + build chart safely
        async function fetchJson(url) {
            const res = await fetch(url);
            if (!res.ok) throw new Error('Network response was not ok');
            return res.json();
        }

        // CashFlow Line
        (async () => {
            try {
                const data = await fetchJson('/chart/cashflow');
                const ctx = document.getElementById('cashFlowChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: "Arus Kas",
                            data: data.data,
                            borderColor: "#4e73df",
                            backgroundColor: "rgba(78,115,223,0.12)",
                            borderWidth: 3,
                            pointRadius: 4,
                            pointBackgroundColor: "#4e73df",
                            tension: 0.35
                        }]
                    },
                    options: chartOptions
                });
            } catch (e) {
                console.error(e);
            }
        })();

        // Income vs Expense Bar
        (async () => {
            try {
                const data = await fetchJson('/chart/income-expense');
                const ctx = document.getElementById('incomeExpenseChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [{
                                label: "Pendapatan",
                                data: data.income,
                                backgroundColor: "#1cc88a",
                                borderRadius: 8
                            },
                            {
                                label: "Beban",
                                data: data.expense,
                                backgroundColor: "#e74a3b",
                                borderRadius: 8
                            }
                        ]
                    },
                    options: {
                        ...chartOptions,
                        scales: {
                            ...chartOptions.scales,
                            x: {
                                grid: {
                                    display: false
                                },
                                stacked: false
                            }
                        }
                    }
                });
            } catch (e) {
                console.error(e);
            }
        })();

        // Composition Doughnut
        (async () => {
            try {
                const data = await fetchJson('/chart/composition');
                const ctx = document.getElementById('balanceChart').getContext('2d');
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            data: data.data,
                            backgroundColor: ["#4e73df", "#e74a3b", "#1cc88a"],
                            hoverOffset: 8
                        }]
                    },
                    options: {
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            } catch (e) {
                console.error(e);
            }
        })();
    </script>

</x-app-layout>
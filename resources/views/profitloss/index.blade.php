    <x-app-layout>
        <div class="container py-4">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-0 text-primary"><i class="bi bi-graph-up me-2"></i>Profit & Loss Statement</h2>
                    <p class="text-muted">Financial performance for the selected period</p>
                </div>
                <div class="d-flex">
                    <button class="btn btn-outline-secondary me-2">
                        <i class="bi bi-download me-1"></i> Export
                    </button>
                    <button class="btn btn-primary">
                        <i class="bi bi-printer me-1"></i> Print
                    </button>
                </div>
            </div>

            <!-- Filter Card -->
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-light py-3">
                    <h5 class="card-title mb-0"><i class="bi bi-calendar-range me-2"></i>Select Date Range</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('profitloss.index') }}">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label class="form-label fw-semibold">Start Date</label>
                                <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fw-semibold">End Date</label>
                                <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button class="btn btn-primary w-100">
                                    <i class="bi bi-filter me-1"></i> Apply
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Financial Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card financial-card revenue-card border-0 shadow-sm bg-success bg-opacity-10">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-subtitle mb-1 text-muted">Total Revenue</h6>
                                    <h4 class="card-title fw-bold text-success">{{ number_format($totalRevenue, 0, ',', '.') }}</h4>
                                </div>
                                <div class="bg-success rounded-circle p-3">
                                    <i class="bi bi-currency-dollar text-white fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card financial-card expense-card border-0 shadow-sm bg-danger bg-opacity-10">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-subtitle mb-1 text-muted">Total Expenses</h6>
                                    <h4 class="card-title fw-bold text-danger">{{ number_format($totalExpense, 0, ',', '.') }}</h4>
                                </div>
                                <div class="bg-danger rounded-circle p-3">
                                    <i class="bi bi-cash-coin text-white fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card financial-card profit-card border-0 shadow-sm {{ $netProfit >= 0 ? 'bg-success bg-opacity-10' : 'bg-danger bg-opacity-10' }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-subtitle mb-1 text-muted">Net {{ $netProfit >= 0 ? 'Profit' : 'Loss' }}</h6>
                                    <h4 class="card-title fw-bold {{ $netProfit >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($netProfit, 0, ',', '.') }}
                                    </h4>
                                </div>
                                <div class="rounded-circle p-3 {{ $netProfit >= 0 ? 'bg-success' : 'bg-danger' }}">
                                    <i class="bi {{ $netProfit >= 0 ? 'bi-graph-up-arrow' : 'bi-graph-down-arrow' }} text-white fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial Statements -->
            <div class="row">
                <!-- Revenue Section -->
                <div class="col-md-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-header bg-success text-white py-3">
                            <h5 class="card-title mb-0"><i class="bi bi-currency-dollar me-2"></i>Revenues</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4">Account</th>
                                            <th class="text-end pe-4">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($revenues as $r)
                                        <tr>
                                            <td class="ps-4">{{ $r['acc']->account_number }} - {{ $r['acc']->account_name }}</td>
                                            <td class="text-end pe-4">{{ number_format($r['balance'], 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-group-divider">
                                        <tr class="table-success">
                                            <th class="ps-4">Total Revenue</th>
                                            <th class="text-end pe-4">{{ number_format($totalRevenue, 0, ',', '.') }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Expenses Section -->
                <div class="col-md-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-header bg-danger text-white py-3">
                            <h5 class="card-title mb-0"><i class="bi bi-cash-coin me-2"></i>Expenses</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4">Account</th>
                                            <th class="text-end pe-4">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($expenses as $e)
                                        <tr>
                                            <td class="ps-4">{{ $e['acc']->account_number }} - {{ $e['acc']->account_name }}</td>
                                            <td class="text-end pe-4">{{ number_format($e['balance'], 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-group-divider">
                                        <tr class="table-danger">
                                            <th class="ps-4">Total Expenses</th>
                                            <th class="text-end pe-4">{{ number_format($totalExpense, 0, ',', '.') }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Net Profit/Loss Section -->
            <div class="card border-0 shadow-sm mt-2">
                <div class="card-body">
                    <h5 class="card-title mb-3"><i class="bi bi-calculator me-2"></i>Net Profit/Loss Calculation</h5>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                                <span class="fw-semibold">Total Revenue:</span>
                                <span class="fw-bold positive-value">{{ number_format($totalRevenue, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                                <span class="fw-semibold">Total Expenses:</span>
                                <span class="fw-bold negative-value">{{ number_format($totalExpense, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between border-bottom pb-3 mb-2 pt-2">
                                <span class="fw-bold fs-5">Net {{ $netProfit >= 0 ? 'Profit' : 'Loss' }}:</span>
                                <span class="fw-bold fs-5 {{ $netProfit >= 0 ? 'positive-value' : 'negative-value' }}">
                                    {{ number_format($netProfit, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                            @if ($netProfit >= 0)
                            <div class="alert alert-success w-100 d-flex align-items-center mb-0" role="alert">
                                <i class="bi bi-check-circle-fill me-2 fs-4"></i>
                                <div>
                                    <h5 class="alert-heading mb-1">Profitable Period!</h5>
                                    <p class="mb-0">Revenue exceeds expenses by {{ number_format($netProfit, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            @else
                            <div class="alert alert-danger w-100 d-flex align-items-center mb-0" role="alert">
                                <i class="bi bi-exclamation-circle-fill me-2 fs-4"></i>
                                <div>
                                    <h5 class="alert-heading mb-1">Loss Making Period</h5>
                                    <p class="mb-0">Expenses exceed revenue by {{ number_format(abs($netProfit), 0, ',', '.') }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Metrics (Optional) -->
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <h6 class="text-muted">Profit Margin</h6>
                            <h3 class="{{ $totalRevenue > 0 ? ($netProfit/$totalRevenue >= 0 ? 'text-success' : 'text-danger') : 'text-secondary' }}">
                                {{ $totalRevenue > 0 ? number_format(($netProfit/$totalRevenue)*100, 2) : 0 }}%
                            </h3>
                            <div class="progress mt-2" style="height: 8px;">
                                <div class="progress-bar {{ $totalRevenue > 0 ? ($netProfit/$totalRevenue >= 0 ? 'bg-success' : 'bg-danger') : 'bg-secondary' }}"
                                    role="progressbar"
                                    style="width: {{ $totalRevenue > 0 ? abs(($netProfit/$totalRevenue)*100) : 0 }}%;"
                                    aria-valuenow="{{ $totalRevenue > 0 ? abs(($netProfit/$totalRevenue)*100) : 0 }}"
                                    aria-valuemin="0"
                                    aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <h6 class="text-muted">Expense to Revenue Ratio</h6>
                            <h3 class="{{ $totalRevenue > 0 ? ($totalExpense/$totalRevenue <= 1 ? 'text-success' : 'text-danger') : 'text-secondary' }}">
                                {{ $totalRevenue > 0 ? number_format(($totalExpense/$totalRevenue)*100, 2) : 0 }}%
                            </h3>
                            <div class="progress mt-2" style="height: 8px;">
                                <div class="progress-bar {{ $totalRevenue > 0 ? ($totalExpense/$totalRevenue <= 1 ? 'bg-info' : 'bg-danger') : 'bg-secondary' }}"
                                    role="progressbar"
                                    style="width: {{ $totalRevenue > 0 ? ($totalExpense/$totalRevenue)*100 : 0 }}%;"
                                    aria-valuenow="{{ $totalRevenue > 0 ? ($totalExpense/$totalRevenue)*100 : 0 }}"
                                    aria-valuemin="0"
                                    aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <h6 class="text-muted">Period Performance</h6>
                            <h3 class="{{ $netProfit >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ $netProfit >= 0 ? 'Positive' : 'Negative' }}
                            </h3>
                            <div class="mt-2">
                                <i class="bi {{ $netProfit >= 0 ? 'bi-emoji-smile-fill text-success' : 'bi-emoji-frown-fill text-danger' }} fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
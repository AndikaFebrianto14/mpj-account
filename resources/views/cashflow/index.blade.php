    <x-app-layout>
        <style>
            .financial-card {
                transition: transform 0.2s;
                border-left: 4px solid transparent;
            }

            .financial-card:hover {
                transform: translateY(-5px);
            }

            .cashin-card {
                border-left-color: #198754 !important;
            }

            .cashout-card {
                border-left-color: #dc3545 !important;
            }

            .cashflow-card {
                border-left-color: #0d6efd !important;
            }

            .table-hover tbody tr:hover {
                background-color: rgba(13, 110, 253, 0.05);
            }

            .positive-value {
                color: #198754;
                font-weight: 600;
            }

            .negative-value {
                color: #dc3545;
                font-weight: 600;
            }

            .cashflow-table th {
                background-color: #f8f9fa;
            }

            .cashflow-graph {
                height: 40px;
                border-radius: 4px;
                overflow: hidden;
            }
        </style>
        <div class="container py-4">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-0 text-primary"><i class="bi bi-cash-coin me-2"></i>Cash Flow Statement</h2>
                    <p class="text-muted">Track cash inflows and outflows for the selected period</p>
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
                    <form method="GET" action="{{ route('cashflow.index') }}">
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
                    <div class="card financial-card cashin-card border-0 shadow-sm bg-success bg-opacity-10">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-subtitle mb-1 text-muted">Total Cash In</h6>
                                    <h4 class="card-title fw-bold text-success">{{ number_format($totalIn, 0, ',', '.') }}</h4>
                                </div>
                                <div class="bg-success rounded-circle p-3">
                                    <i class="bi bi-arrow-down-left text-white fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card financial-card cashout-card border-0 shadow-sm bg-danger bg-opacity-10">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-subtitle mb-1 text-muted">Total Cash Out</h6>
                                    <h4 class="card-title fw-bold text-danger">{{ number_format($totalOut, 0, ',', '.') }}</h4>
                                </div>
                                <div class="bg-danger rounded-circle p-3">
                                    <i class="bi bi-arrow-up-right text-white fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card financial-card cashflow-card border-0 shadow-sm {{ $netCash >= 0 ? 'bg-success bg-opacity-10' : 'bg-danger bg-opacity-10' }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-subtitle mb-1 text-muted">Net Cash Flow</h6>
                                    <h4 class="card-title fw-bold {{ $netCash >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($netCash, 0, ',', '.') }}
                                    </h4>
                                </div>
                                <div class="rounded-circle p-3 {{ $netCash >= 0 ? 'bg-success' : 'bg-danger' }}">
                                    <i class="bi {{ $netCash >= 0 ? 'bi-graph-up-arrow' : 'bi-graph-down-arrow' }} text-white fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cash Flow Visualization -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light py-3">
                    <h5 class="card-title mb-0"><i class="bi bi-bar-chart me-2"></i>Cash Flow Overview</h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-success fw-semibold">Cash In: {{ number_format($totalIn, 0, ',', '.') }}</span>
                                <span class="text-danger fw-semibold">Cash Out: {{ number_format($totalOut, 0, ',', '.') }}</span>
                            </div>
                            <div class="progress cashflow-graph" style="height: 30px;">
                                <div class="progress-bar bg-success" role="progressbar"
                                    style="width: {{ $totalIn > 0 ? ($totalIn/($totalIn+$totalOut))*100 : 0 }}%"
                                    aria-valuenow="{{ $totalIn > 0 ? ($totalIn/($totalIn+$totalOut))*100 : 0 }}"
                                    aria-valuemin="0"
                                    aria-valuemax="100">
                                    {{ $totalIn > 0 ? number_format(($totalIn/($totalIn+$totalOut))*100, 1) : 0 }}%
                                </div>
                                <div class="progress-bar bg-danger" role="progressbar"
                                    style="width: {{ $totalOut > 0 ? ($totalOut/($totalIn+$totalOut))*100 : 0 }}%"
                                    aria-valuenow="{{ $totalOut > 0 ? ($totalOut/($totalIn+$totalOut))*100 : 0 }}"
                                    aria-valuemin="0"
                                    aria-valuemax="100">
                                    {{ $totalOut > 0 ? number_format(($totalOut/($totalIn+$totalOut))*100, 1) : 0 }}%
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 text-center mt-3 mt-md-0">
                            <div class="d-inline-block px-4 py-2 rounded {{ $netCash >= 0 ? 'bg-success' : 'bg-danger' }} text-white">
                                <h5 class="mb-0">{{ $netCash >= 0 ? 'Surplus' : 'Deficit' }}</h5>
                                <h3 class="mb-0">{{ number_format($netCash, 0, ',', '.') }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial Statements -->
            <div class="row">
                <!-- Cash In Section -->
                <div class="col-md-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-header bg-success text-white py-3">
                            <h5 class="card-title mb-0"><i class="bi bi-arrow-down-left me-2"></i>Cash Inflows</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 cashflow-table">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4">Date</th>
                                            <th>Description</th>
                                            <th class="text-end pe-4">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cashIn as $c)
                                        <tr>
                                            <td class="ps-4">{{ $c['date'] }}</td>
                                            <td>{{ $c['desc'] }}</td>
                                            <td class="text-end pe-4 positive-value">{{ number_format($c['amount'], 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-group-divider">
                                        <tr class="table-success">
                                            <th colspan="2" class="ps-4">Total Cash In</th>
                                            <th class="text-end pe-4">{{ number_format($totalIn, 0, ',', '.') }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cash Out Section -->
                <div class="col-md-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-header bg-danger text-white py-3">
                            <h5 class="card-title mb-0"><i class="bi bi-arrow-up-right me-2"></i>Cash Outflows</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 cashflow-table">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4">Date</th>
                                            <th>Description</th>
                                            <th class="text-end pe-4">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cashOut as $c)
                                        <tr>
                                            <td class="ps-4">{{ $c['date'] }}</td>
                                            <td>{{ $c['desc'] }}</td>
                                            <td class="text-end pe-4 negative-value">{{ number_format($c['amount'], 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-group-divider">
                                        <tr class="table-danger">
                                            <th colspan="2" class="ps-4">Total Cash Out</th>
                                            <th class="text-end pe-4">{{ number_format($totalOut, 0, ',', '.') }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Net Cash Flow Section -->
            <div class="card border-0 shadow-sm mt-2">
                <div class="card-body">
                    <h5 class="card-title mb-3"><i class="bi bi-calculator me-2"></i>Net Cash Flow Calculation</h5>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                                <span class="fw-semibold">Total Cash In:</span>
                                <span class="fw-bold positive-value">{{ number_format($totalIn, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                                <span class="fw-semibold">Total Cash Out:</span>
                                <span class="fw-bold negative-value">{{ number_format($totalOut, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between border-bottom pb-3 mb-2 pt-2">
                                <span class="fw-bold fs-5">Net Cash Flow:</span>
                                <span class="fw-bold fs-5 {{ $netCash >= 0 ? 'positive-value' : 'negative-value' }}">
                                    {{ number_format($netCash, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                            @if ($netCash >= 0)
                            <div class="alert alert-success w-100 d-flex align-items-center mb-0" role="alert">
                                <i class="bi bi-check-circle-fill me-2 fs-4"></i>
                                <div>
                                    <h5 class="alert-heading mb-1">Positive Cash Flow!</h5>
                                    <p class="mb-0">Cash inflows exceed outflows by {{ number_format($netCash, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            @else
                            <div class="alert alert-danger w-100 d-flex align-items-center mb-0" role="alert">
                                <i class="bi bi-exclamation-circle-fill me-2 fs-4"></i>
                                <div>
                                    <h5 class="alert-heading mb-1">Negative Cash Flow</h5>
                                    <p class="mb-0">Cash outflows exceed inflows by {{ number_format(abs($netCash), 0, ',', '.') }}</p>
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
                            <h6 class="text-muted">Cash In/Out Ratio</h6>
                            <h3 class="{{ $totalOut > 0 ? ($totalIn/$totalOut >= 1 ? 'text-success' : 'text-danger') : 'text-secondary' }}">
                                {{ $totalOut > 0 ? number_format(($totalIn/$totalOut), 2) : 'N/A' }}:1
                            </h3>
                            <div class="progress mt-2" style="height: 8px;">
                                <div class="progress-bar {{ $totalOut > 0 ? ($totalIn/$totalOut >= 1 ? 'bg-success' : 'bg-danger') : 'bg-secondary' }}"
                                    role="progressbar"
                                    style="width: {{ $totalOut > 0 ? min(($totalIn/$totalOut)*50, 100) : 0 }}%;"
                                    aria-valuenow="{{ $totalOut > 0 ? min(($totalIn/$totalOut)*50, 100) : 0 }}"
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
                            <h6 class="text-muted">Average Cash In</h6>
                            <h3 class="text-success">
                                {{ number_format($totalIn/max(count($cashIn), 1), 0, ',', '.') }}
                            </h3>
                            <p class="text-muted small mb-0">Per transaction</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <h6 class="text-muted">Average Cash Out</h6>
                            <h3 class="text-danger">
                                {{ number_format($totalOut/max(count($cashOut), 1), 0, ',', '.') }}
                            </h3>
                            <p class="text-muted small mb-0">Per transaction</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
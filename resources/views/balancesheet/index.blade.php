    <x-app-layout>
        <div class="container py-4">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-0 text-primary"><i class="bi bi-wallet2 me-2"></i>Balance Sheet</h2>
                    <p class="text-muted">Financial position as of selected period</p>
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
                    <form method="GET" action="{{ route('balancesheet.index') }}">
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
                    <div class="card financial-card border-0 shadow-sm bg-success bg-opacity-10">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-subtitle mb-1 text-muted">Total Assets</h6>
                                    <h4 class="card-title fw-bold text-success">{{ number_format($totalAssets, 0, ',', '.') }}</h4>
                                </div>
                                <div class="bg-success rounded-circle p-3">
                                    <i class="bi bi-wallet2 text-white fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card financial-card border-0 shadow-sm bg-info bg-opacity-10">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-subtitle mb-1 text-muted">Total Liabilities</h6>
                                    <h4 class="card-title fw-bold text-info">{{ number_format($totalLiabilities, 0, ',', '.') }}</h4>
                                </div>
                                <div class="bg-info rounded-circle p-3">
                                    <i class="bi bi-arrow-down-left text-white fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card financial-card border-0 shadow-sm bg-primary bg-opacity-10">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-subtitle mb-1 text-muted">Total Equity</h6>
                                    <h4 class="card-title fw-bold text-primary">{{ number_format($totalEquity, 0, ',', '.') }}</h4>
                                </div>
                                <div class="bg-primary rounded-circle p-3">
                                    <i class="bi bi-graph-up text-white fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial Statements -->
            <div class="row">
                <!-- Assets Section -->
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-header bg-success text-white py-3">
                            <h5 class="card-title mb-0"><i class="bi bi-wallet2 me-2"></i>Assets</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <tbody>
                                        @foreach ($report['assets'] as $a)
                                        <tr>
                                            <td class="ps-4">{{ $a['acc']->account_number }} - {{ $a['acc']->account_name }}</td>
                                            <td class="text-end pe-4">{{ number_format($a['balance'], 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-group-divider">
                                        <tr class="table-success">
                                            <th class="ps-4">Total Assets</th>
                                            <th class="text-end pe-4">{{ number_format($totalAssets, 0, ',', '.') }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Liabilities Section -->
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-header bg-info text-white py-3">
                            <h5 class="card-title mb-0"><i class="bi bi-arrow-down-left me-2"></i>Liabilities</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <tbody>
                                        @foreach ($report['liabilities'] as $l)
                                        <tr>
                                            <td class="ps-4">{{ $l['acc']->account_number }} - {{ $l['acc']->account_name }}</td>
                                            <td class="text-end pe-4">{{ number_format($l['balance'], 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-group-divider">
                                        <tr class="table-info">
                                            <th class="ps-4">Total Liabilities</th>
                                            <th class="text-end pe-4">{{ number_format($totalLiabilities, 0, ',', '.') }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Equity Section -->
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-header bg-primary text-white py-3">
                            <h5 class="card-title mb-0"><i class="bi bi-graph-up me-2"></i>Equity</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <tbody>
                                        @foreach ($report['equity'] as $e)
                                        <tr>
                                            <td class="ps-4">{{ $e['acc']->account_number }} - {{ $e['acc']->account_name }}</td>
                                            <td class="text-end pe-4">{{ number_format($e['balance'], 0, ',', '.') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-group-divider">
                                        <tr class="table-primary">
                                            <th class="ps-4">Total Equity</th>
                                            <th class="text-end pe-4">{{ number_format($totalEquity, 0, ',', '.') }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Balance Check Section -->
            <div class="card border-0 shadow-sm mt-2">
                <div class="card-body">
                    <h5 class="card-title mb-3"><i class="bi bi-check2-circle me-2"></i>Balance Check</h5>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                                <span class="fw-semibold">Total Assets:</span>
                                <span class="fw-bold">{{ number_format($totalAssets, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                                <span class="fw-semibold">Liabilities + Equity:</span>
                                <span class="fw-bold">{{ number_format($totalLiabilities + $totalEquity, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                            @if ($totalAssets == $totalLiabilities + $totalEquity)
                            <div class="alert alert-success w-100 d-flex align-items-center mb-0" role="alert">
                                <i class="bi bi-check-circle-fill me-2 fs-4"></i>
                                <div>
                                    <h5 class="alert-heading mb-1">Perfect Balance!</h5>
                                    <p class="mb-0">Assets = Liabilities + Equity</p>
                                </div>
                            </div>
                            @else
                            <div class="alert alert-danger w-100 d-flex align-items-center mb-0" role="alert">
                                <i class="bi bi-exclamation-circle-fill me-2 fs-4"></i>
                                <div>
                                    <h5 class="alert-heading mb-1">Imbalance Detected!</h5>
                                    <p class="mb-0">Assets ≠ Liabilities + Equity</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
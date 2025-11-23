    <x-app-layout>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>

        <div class="container py-4">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-0"><i class="bi bi-bar-chart-line me-2"></i>Trial Balance</h2>
                    <p class="text-muted">Financial summary between selected dates</p>
                </div>
                <div class="d-flex">
                    <div class="dropdown me-2">
                        <button class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bi bi-download me-1"></i> Export
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item"
                                    href="{{ route('trialbalance.export.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}">
                                    <i class="bi bi-file-earmark-pdf me-1 text-danger"></i> Export PDF
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item"
                                    href="{{ route('trialbalance.export.excel', ['start_date' => $startDate, 'end_date' => $endDate]) }}">
                                    <i class="bi bi-file-earmark-excel me-1 text-success"></i> Export Excel
                                </a>
                            </li>
                        </ul>
                    </div>

                    <a class="btn btn-primary"
                        href="{{ route('trialbalance.print', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
                        target="_blank">
                        <i class="bi bi-printer me-1"></i> Print
                    </a>
                </div>

            </div>

            <!-- Filter Card -->
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-light py-3">
                    <h5 class="card-title mb-0"><i class="bi bi-funnel me-2"></i>Filter Report</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('trialbalance.index') }}">
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
                                    <i class="bi bi-filter-circle me-1"></i> Apply
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm bg-success bg-opacity-10">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-subtitle mb-1 text-muted">Total Debit</h6>
                                    <h4 class="card-title fw-bold text-success">{{ number_format($totalDebit, 0, ',', '.') }}</h4>
                                </div>
                                <div class="bg-success rounded-circle p-3">
                                    <i class="bi bi-arrow-down-left text-white fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm bg-info bg-opacity-10">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-subtitle mb-1 text-muted">Total Credit</h6>
                                    <h4 class="card-title fw-bold text-info">{{ number_format($totalCredit, 0, ',', '.') }}</h4>
                                </div>
                                <div class="bg-info rounded-circle p-3">
                                    <i class="bi bi-arrow-up-right text-white fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm {{ $totalDebit == $totalCredit ? 'bg-success bg-opacity-10' : 'bg-danger bg-opacity-10' }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-subtitle mb-1 text-muted">Balance Status</h6>
                                    <h4 class="card-title fw-bold {{ $totalDebit == $totalCredit ? 'text-success' : 'text-danger' }}">
                                        {{ $totalDebit == $totalCredit ? 'Balanced' : 'Not Balanced' }}
                                    </h4>
                                </div>
                                <div class="rounded-circle p-3 {{ $totalDebit == $totalCredit ? 'bg-success' : 'bg-danger' }}">
                                    <i class="bi {{ $totalDebit == $totalCredit ? 'bi-check-lg' : 'bi-exclamation-lg' }} text-white fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Report Table -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light py-3">
                    <h5 class="card-title mb-0"><i class="bi bi-table me-2"></i>Account Details</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Account No</th>
                                    <th>Account Name</th>
                                    <th class="text-end">Debit</th>
                                    <th class="text-end">Credit</th>
                                    <th class="text-end pe-4">Ending Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($report as $r)
                                <tr>
                                    <td class="ps-4 fw-semibold">{{ $r['account_number'] }}</td>
                                    <td>{{ $r['account_name'] }}</td>
                                    <td class="text-end">{{ number_format($r['debit'], 0, ',', '.') }}</td>
                                    <td class="text-end">{{ number_format($r['credit'], 0, ',', '.') }}</td>
                                    <td class="text-end fw-semibold pe-4">{{ number_format($r['ending'], 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-group-divider">
                                <tr class="table-active">
                                    <th colspan="2" class="ps-4">Total</th>
                                    <th class="text-end">{{ number_format($totalDebit, 0, ',', '.') }}</th>
                                    <th class="text-end">{{ number_format($totalCredit, 0, ',', '.') }}</th>
                                    <th class="pe-4"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Balance Alert -->
            <div class="mt-4">
                @if ($totalDebit == $totalCredit)
                <div class="alert alert-success d-flex align-items-center" role="alert">
                    <i class="bi bi-check-circle-fill me-2 fs-4"></i>
                    <div>
                        <h5 class="alert-heading mb-1">Perfect Balance!</h5>
                        <p class="mb-0">Total Debit ({{ number_format($totalDebit, 0, ',', '.') }}) equals Total Credit ({{ number_format($totalCredit, 0, ',', '.') }})</p>
                    </div>
                </div>
                @else
                <div class="alert alert-danger d-flex align-items-center" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2 fs-4"></i>
                    <div>
                        <h5 class="alert-heading mb-1">Imbalance Detected!</h5>
                        <p class="mb-0">Total Debit ({{ number_format($totalDebit, 0, ',', '.') }}) does not equal Total Credit ({{ number_format($totalCredit, 0, ',', '.') }})</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </x-app-layout>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
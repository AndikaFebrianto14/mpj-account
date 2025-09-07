    <x-app-layout>
        <div class="container py-4">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col">
                    <div class="p-4 bg-primary text-white rounded-3 shadow-sm">
                        <h2 class="mb-1"><i class="bi bi-journal-bookmark me-2"></i>General Ledger</h2>
                        <p class="mb-0">View and manage your financial transactions</p>
                    </div>
                </div>
            </div>

            <!-- Filter Form -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-funnel me-2"></i>Filter Options</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('ledger.index') }}" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold"><i class="bi bi-wallet2 me-1"></i>Account</label>
                            <select name="account_id" class="form-select" required>
                                <option value="">-- Select Account --</option>
                                @foreach ($accounts as $acc)
                                <option value="{{ $acc->id }}" @if ($selectedAccount && $selectedAccount->id == $acc->id) selected @endif>
                                    {{ $acc->account_number }} - {{ $acc->account_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold"><i class="bi bi-calendar-event me-1"></i>Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold"><i class="bi bi-calendar-check me-1"></i>End Date</label>
                            <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button class="btn btn-primary w-100"><i class="bi bi-filter-circle me-1"></i> Apply Filter</button>
                        </div>
                    </form>
                </div>
            </div>

            @if ($selectedAccount)
            <!-- Account Info -->
            <div class="alert alert-primary">
                <h5 class="mb-1"><i class="bi bi-wallet2 me-2"></i>Ledger for {{ $selectedAccount->account_number }} - {{ $selectedAccount->account_name }}</h5>
                <p class="mb-0"><i class="bi bi-calendar-range me-1"></i>Period: {{ $startDate }} to {{ $endDate }}</p>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Total Debit</h6>
                                    <h3 class="mb-0 mt-2">{{ number_format($entries->sum('debit_amount'), 0, ',', '.') }}</h3>
                                </div>
                                <i class="bi bi-arrow-down-circle fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Total Credit</h6>
                                    <h3 class="mb-0 mt-2">{{ number_format($entries->sum('credit_amount'), 0, ',', '.') }}</h3>
                                </div>
                                <i class="bi bi-arrow-up-circle fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Ending Balance</h6>
                                    @php
                                    $balance = 0;
                                    foreach ($entries as $e) {
                                    $debit = $e->debit_amount ?? 0;
                                    $credit = $e->credit_amount ?? 0;
                                    $balance += $debit - $credit;
                                    }
                                    @endphp
                                    <h3 class="mb-0 mt-2">{{ number_format($balance, 0, ',', '.') }}</h3>
                                </div>
                                <i class="bi bi-graph-up fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transactions Table -->
            <div class="card shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-table me-2"></i>Transaction Details</h5>
                    <!-- <button class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-download me-1"></i> Export
                    </button> -->
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-primary">
                                <tr>
                                    <th><i class="bi bi-calendar me-1"></i>Date</th>
                                    <th><i class="bi bi-hash me-1"></i>Entry No</th>
                                    <th><i class="bi bi-text-paragraph me-1"></i>Description</th>
                                    <th class="text-end"><i class="bi bi-arrow-down-circle me-1"></i>Debit</th>
                                    <th class="text-end"><i class="bi bi-arrow-up-circle me-1"></i>Credit</th>
                                    <th class="text-end"><i class="bi bi-graph-up me-1"></i>Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $balance = 0; @endphp
                                @forelse ($entries as $e)
                                @php
                                $debit = $e->debit_amount ?? 0;
                                $credit = $e->credit_amount ?? 0;
                                $balance += $debit - $credit;
                                @endphp
                                <tr>
                                    <td>{{ $e->journal->entry_date }}</td>
                                    <td>{{ $e->journal->entry_number }}</td>
                                    <td>{{ $e->description }}</td>
                                    <td class="text-end text-success fw-semibold">{{ $debit > 0 ? number_format($debit, 0, ',', '.') : '-' }}</td>
                                    <td class="text-end text-danger fw-semibold">{{ $credit > 0 ? number_format($credit, 0, ',', '.') : '-' }}</td>
                                    <td class="text-end fw-bold">{{ number_format($balance, 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                                        <p class="text-muted">No transactions found for the selected period.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                            @if(count($entries) > 0)
                            <tfoot class="table-light">
                                <tr class="fw-bold">
                                    <td colspan="3" class="text-end">Total:</td>
                                    <td class="text-end">{{ number_format($entries->sum('debit_amount'), 0, ',', '.') }}</td>
                                    <td class="text-end">{{ number_format($entries->sum('credit_amount'), 0, ',', '.') }}</td>
                                    <td class="text-end">{{ number_format($balance, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
            @else
            <!-- Empty State -->
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-journal-text fs-1 text-muted d-block mb-3"></i>
                    <h5 class="text-muted">Select an account to view ledger</h5>
                    <p class="text-muted">Choose an account and date range to see transaction details</p>
                </div>
            </div>
            @endif
        </div>
    </x-app-layout>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
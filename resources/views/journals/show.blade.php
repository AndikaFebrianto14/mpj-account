<x-app-layout>
    <div class="container py-5">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">Journal Entry #{{ $journal->entry_number }}</h2>
                <small class="text-muted">Detail transactions jurnal</small>
            </div>

            <a href="{{ route('journals.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                <div class="row gy-3">
                    <div class="col-md-4">
                        <div class="text-muted">Date</div>
                        <div class="fw-semibold">{{ $journal->entry_date }}</div>
                    </div>

                    <div class="col-md-4">
                        <div class="text-muted">Total Amount</div>
                        <div class="fw-semibold text-success">
                            Rp {{ number_format($journal->total_amount, 0, ',', '.') }}
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="text-muted">Description</div>
                        <div class="fw-semibold">{{ $journal->description }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- DETAILS -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold">Journal Details</h5>
            </div>

            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 35%">Account</th>
                            <th style="width: 20%" class="text-end">Debit</th>
                            <th style="width: 20%" class="text-end">Credit</th>
                            <th style="width: 25%">Description</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($journal->details as $d)

                        @php
                        $isDebit = $d->debit_amount > 0;
                        $isCredit = $d->credit_amount > 0;

                        // Warna highlight
                        $rowClass = $isDebit ? 'bg-success-subtle' : ($isCredit ? 'bg-danger-subtle' : '');
                        @endphp

                        <tr class="{{ $rowClass }}">
                            <td>
                                <strong>{{ $d->account->account_number }}</strong>
                                — {{ $d->account->account_name }}
                            </td>

                            <td class="text-end fw-semibold text-primary">
                                {{ number_format($d->debit_amount, 0, ',', '.') }}
                            </td>

                            <td class="text-end fw-semibold text-danger">
                                {{ number_format($d->credit_amount, 0, ',', '.') }}
                            </td>

                            <td>{{ $d->description }}</td>
                        </tr>

                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-white">
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('journals.edit', $journal->id) }}" class="btn btn-warning">
                        <i class="bi bi-pencil-square"></i> Edit
                    </a>

                    <form action="{{ route('journals.destroy', $journal->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
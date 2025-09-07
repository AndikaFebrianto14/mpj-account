    <x-app-layout>
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="h4 mb-0"><i class="bi bi-journal-plus me-2"></i>Add Journal Entry</h2>
                            <p class="mb-0 opacity-75">Create a new journal entry</p>
                        </div>
                        <div class="card-body p-4">
                            {{-- Pesan error balance --}}
                            @if($errors->has('balance'))
                            <div class="alert alert-danger mb-4 d-flex align-items-center">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <div>{{ $errors->first('balance') }}</div>
                            </div>
                            @endif

                            <form method="POST" action="{{ route('journals.store') }}">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label for="entry_date" class="form-label">Date</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                            <input type="date" id="entry_date" name="entry_date" class="form-control"
                                                value="{{ old('entry_date') }}" required>
                                        </div>
                                        @error('entry_date')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <label for="reference_number" class="form-label">Reference Number</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-hash"></i></span>
                                            <input type="text" id="reference_number" name="reference_number" class="form-control"
                                                value="{{ old('reference_number') }}" placeholder="Optional reference number">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="description" class="form-label">Description</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-text-paragraph"></i></span>
                                        <textarea id="description" name="description" class="form-control" required
                                            placeholder="Enter journal description" rows="3">{{ old('description') }}</textarea>
                                    </div>
                                    @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <h5 class="section-title mt-5">
                                    <i class="bi bi-list-check me-2"></i>Journal Details
                                </h5>

                                <div class="table-responsive">
                                    <table class="table" id="detailsTable">
                                        <thead>
                                            <tr>
                                                <th>Account</th>
                                                <th>Debit</th>
                                                <th>Credit</th>
                                                <th>Description</th>
                                                <th width="80px"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                            $oldAccounts = old('account_id', [null]); // minimal 1 row
                                            @endphp

                                            @foreach($oldAccounts as $i => $accId)
                                            <tr>
                                                <td>
                                                    <select name="account_id[]" class="form-select" required>
                                                        <option value="">-- Select Account --</option>
                                                        @foreach ($accounts as $acc)
                                                        <option value="{{ $acc->id }}" {{ $accId == $acc->id ? 'selected' : '' }}>
                                                            {{ $acc->account_number }} - {{ $acc->account_name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                    @error("account_id.$i")
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" step="0.01" name="debit_amount[]"
                                                            class="form-control debit-amount" placeholder="0.00"
                                                            value="{{ old('debit_amount.'.$i) }}">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" step="0.01" name="credit_amount[]"
                                                            class="form-control credit-amount" placeholder="0.00"
                                                            value="{{ old('credit_amount.'.$i) }}">
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="text" name="line_description[]" class="form-control"
                                                        value="{{ old('line_description.'.$i) }}" placeholder="Line description">
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-outline-danger btn-sm removeRow"
                                                        title="Remove line" {{ count($oldAccounts) == 1 ? 'disabled' : '' }}>
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <button type="button" id="addRow" class="btn btn-outline-primary mb-4">
                                    <i class="bi bi-plus-circle me-1"></i> Add Line
                                </button>

                                <div class="d-flex justify-content-between align-items-center mt-4">
                                    <a href="{{ route('journals.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left me-1"></i> Back
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-check-lg me-1"></i> Save Entry
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.getElementById('addRow').addEventListener('click', function() {
                let table = document.querySelector('#detailsTable tbody');
                let newRow = table.rows[0].cloneNode(true);

                // Clear all input values
                newRow.querySelectorAll('input').forEach(input => input.value = '');
                newRow.querySelector('select').selectedIndex = 0;

                // Enable remove button for new rows
                newRow.querySelector('.removeRow').disabled = false;

                // Clear error messages
                newRow.querySelectorAll('.text-danger').forEach(el => el.remove());

                table.appendChild(newRow);

                // Enable remove buttons if there's more than one row
                if (document.querySelectorAll('#detailsTable tbody tr').length > 1) {
                    document.querySelectorAll('.removeRow').forEach(btn => {
                        btn.disabled = false;
                    });
                }
            });

            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('removeRow') || e.target.closest('.removeRow')) {
                    const removeBtn = e.target.classList.contains('removeRow') ? e.target : e.target.closest('.removeRow');
                    if (document.querySelectorAll('#detailsTable tbody tr').length > 1) {
                        removeBtn.closest('tr').remove();
                    }

                    // Disable remove button if only one row remains
                    if (document.querySelectorAll('#detailsTable tbody tr').length === 1) {
                        document.querySelector('.removeRow').disabled = true;
                    }
                }
            });

            // Set today's date as default if not already set
            document.addEventListener('DOMContentLoaded', function() {
                const dateInput = document.getElementById('entry_date');
                if (!dateInput.value) {
                    const today = new Date().toISOString().split('T')[0];
                    dateInput.value = today;
                }
            });

            // helper: debit/credit toggle disable
            document.addEventListener('input', function(e) {
                if (e.target.name === 'debit_amount[]') {
                    let row = e.target.closest('tr');
                    let debit = parseFloat(e.target.value) || 0;
                    let creditInput = row.querySelector('input[name="credit_amount[]"]');

                    if (debit > 0) {
                        creditInput.value = '';
                        creditInput.disabled = true;
                    } else {
                        creditInput.disabled = false;
                    }
                }

                if (e.target.name === 'credit_amount[]') {
                    let row = e.target.closest('tr');
                    let credit = parseFloat(e.target.value) || 0;
                    let debitInput = row.querySelector('input[name="debit_amount[]"]');

                    if (credit > 0) {
                        debitInput.value = '';
                        debitInput.disabled = true;
                    } else {
                        debitInput.disabled = false;
                    }
                }
            });

            // Initialize debit/credit disable state on page load
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('input[name="debit_amount[]"]').forEach(input => {
                    let debit = parseFloat(input.value) || 0;
                    let row = input.closest('tr');
                    let creditInput = row.querySelector('input[name="credit_amount[]"]');

                    if (debit > 0) {
                        creditInput.disabled = true;
                    }
                });

                document.querySelectorAll('input[name="credit_amount[]"]').forEach(input => {
                    let credit = parseFloat(input.value) || 0;
                    let row = input.closest('tr');
                    let debitInput = row.querySelector('input[name="debit_amount[]"]');

                    if (credit > 0) {
                        debitInput.disabled = true;
                    }
                });
            });
        </script>
    </x-app-layout>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
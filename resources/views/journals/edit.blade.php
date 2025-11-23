<x-app-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-header">
                        <h2 class="h4 mb-0">
                            <i class="bi bi-pencil-square me-2"></i>Edit Journal Entry #{{ $journal->entry_number }}
                        </h2>
                        <p class="mb-0 opacity-75">Modify existing journal entry</p>
                    </div>

                    <div class="card-body p-4">

                        {{-- Pesan error balance --}}
                        @if($errors->has('balance'))
                        <div class="alert alert-danger mb-4 d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <div>{{ $errors->first('balance') }}</div>
                        </div>
                        @endif

                        <form method="POST" action="{{ route('journals.update', $journal->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Date</label>
                                    <div class="input-group locked-box">
                                        <span class="input-group-text bg-secondary text-white">
                                            <i class="bi bi-calendar"></i>
                                        </span>
                                        <input type="text"
                                            class="form-control bg-light"
                                            value="{{ \Carbon\Carbon::parse($journal->entry_date)->format('d M Y') }}"
                                            readonly>
                                    </div>
                                    <small class="text-muted"><i class="bi bi-lock-fill me-1"></i>Field is not editable</small>

                                    {{-- Hidden supaya tetap terkirim ke controller --}}
                                    <input type="hidden" name="entry_date" value="{{ $journal->entry_date }}">
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Journal Number</label>
                                    <div class="input-group locked-box">
                                        <span class="input-group-text bg-secondary text-white">
                                            <i class="bi bi-hash"></i>
                                        </span>
                                        <input type="text"
                                            class="form-control bg-light"
                                            value="{{ $journal->entry_number }}"
                                            readonly>
                                    </div>
                                    <small class="text-muted"><i class="bi bi-lock-fill me-1"></i>Journal number is not editable</small>
                                </div>
                            </div>


                            <div class="mb-4">
                                <label for="description" class="form-label">Description</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-text-paragraph"></i></span>
                                    <textarea id="description" name="description" class="form-control" required
                                        placeholder="Enter journal description" rows="3">{{ old('description', $journal->description) }}</textarea>
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
                                        @foreach ($journal->details as $i => $detail)
                                        <tr>
                                            <td>
                                                <select name="account_id[]" class="form-select" required>
                                                    <option value="">-- Select Account --</option>
                                                    @foreach ($accounts as $acc)
                                                    <option value="{{ $acc->id }}"
                                                        {{ old("account_id.$i", $detail->account_id) == $acc->id ? 'selected' : '' }}>
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
                                                        class="form-control debit-amount"
                                                        value="{{ old("debit_amount.$i", $detail->debit_amount) }}"
                                                        placeholder="0.00">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="number" step="0.01" name="credit_amount[]"
                                                        class="form-control credit-amount"
                                                        value="{{ old("credit_amount.$i", $detail->credit_amount) }}"
                                                        placeholder="0.00">
                                                </div>
                                            </td>

                                            <td>
                                                <input type="text" name="line_description[]"
                                                    class="form-control"
                                                    value="{{ old("line_description.$i", $detail->description) }}"
                                                    placeholder="Line description">
                                            </td>

                                            <td>
                                                <button type="button" class="btn btn-outline-danger btn-sm removeRow"
                                                    title="Remove line"
                                                    {{ count($journal->details) == 1 ? 'disabled' : '' }}>
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
                                <a href="{{ route('journals.show', $journal->id) }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-1"></i> Back
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-lg me-1"></i> Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT SAMA DENGAN CREATE UNTUK KONSISTENSI --}}
    <script>
        document.getElementById('addRow').addEventListener('click', function() {
            let table = document.querySelector('#detailsTable tbody');
            let newRow = table.rows[0].cloneNode(true);

            newRow.querySelectorAll('input').forEach(input => input.value = '');
            newRow.querySelector('select').selectedIndex = 0;

            newRow.querySelector('.removeRow').disabled = false;

            newRow.querySelectorAll('.text-danger').forEach(el => el.remove());

            table.appendChild(newRow);

            if (document.querySelectorAll('#detailsTable tbody tr').length > 1) {
                document.querySelectorAll('.removeRow').forEach(btn => btn.disabled = false);
            }
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('removeRow') || e.target.closest('.removeRow')) {
                const removeBtn = e.target.closest('.removeRow');
                if (document.querySelectorAll('#detailsTable tbody tr').length > 1) {
                    removeBtn.closest('tr').remove();
                }
                if (document.querySelectorAll('#detailsTable tbody tr').length === 1) {
                    document.querySelector('.removeRow').disabled = true;
                }
            }
        });

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
    </script>

</x-app-layout>
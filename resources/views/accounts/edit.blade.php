    <x-app-layout>
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="h4 mb-0"><i class="bi bi-pencil-square me-2"></i>Edit Account</h2>
                            <p class="mb-0 opacity-75">Update account details</p>
                        </div>
                        <div class="card-body p-4">
                            <form method="POST" action="{{ route('accounts.update', $account->id) }}">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label for="account_number" class="form-label">Account Number</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-hash"></i></span>
                                            <input type="text" id="account_number" name="account_number"
                                                value="{{ $account->account_number }}" class="form-control" required
                                                placeholder="Enter account number">
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <label for="account_name" class="form-label">Account Name</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-card-heading"></i></span>
                                            <input type="text" id="account_name" name="account_name"
                                                value="{{ $account->account_name }}" class="form-control" required
                                                placeholder="Enter account name">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label for="account_type" class="form-label">Account Type</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-diagram-3"></i></span>
                                            <select id="account_type" name="account_type" class="form-select" required>
                                                <option value="Debit" @if ($account->account_type == 'Debit') selected @endif>Debit</option>
                                                <option value="Riil" @if ($account->account_type == 'Riil') selected @endif>Riil</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <label for="normal_balance" class="form-label">Normal Balance</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-arrow-left-right"></i></span>
                                            <select id="normal_balance" name="normal_balance" class="form-select" required>
                                                <option value="Debit" @if ($account->normal_balance == 'Debit') selected @endif>Debit</option>
                                                <option value="Credit" @if ($account->normal_balance == 'Credit') selected @endif>Credit</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label for="kelkode_id" class="form-label">Category</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-layers"></i></span>
                                            <select id="kelkode_id" name="kelkode_id" class="form-select" required>
                                                @foreach ($categories as $cat)
                                                <option value="{{ $cat->id }}" @if ($cat->id == $account->kelkode_id) selected @endif>
                                                    {{ $cat->category_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <label for="code_id" class="form-label">Code</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-code-slash"></i></span>
                                            <select id="code_id" name="code_id" class="form-select" required>
                                                @foreach ($codes as $c)
                                                <option value="{{ $c->id }}" @if ($c->id == $account->code_id) selected @endif>
                                                    {{ $c->code_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label for="parent_account_id" class="form-label">Parent Account</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-diagram-2"></i></span>
                                            <select id="parent_account_id" name="parent_account_id" class="form-select">
                                                <option value="">-- None --</option>
                                                @foreach ($parents as $p)
                                                <option value="{{ $p->id }}" @if ($p->id == $account->parent_account_id) selected @endif>
                                                    {{ $p->account_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <label for="is_active" class="form-label">Status</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-circle-fill"></i></span>
                                            <select id="is_active" name="is_active" class="form-select">
                                                <option value="1" {{ $account->is_active ? 'selected' : '' }}>Active</option>
                                                <option value="0" {{ !$account->is_active ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <a href="{{ route('accounts.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left me-1"></i> Back
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-check-lg me-1"></i> Update Account
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
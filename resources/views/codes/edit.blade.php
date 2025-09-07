    <x-app-layout>
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="h4 mb-0"><i class="bi bi-pencil-square me-2"></i>Edit Account Code</h2>
                            <p class="mb-0 opacity-75">Update account code details</p>
                        </div>
                        <div class="card-body p-4">
                            <form method="POST" action="{{ route('codes.update', $code->id) }}">
                                @csrf
                                @method('PUT')

                                <div class="mb-4">
                                    <label for="code" class="form-label">Code</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-hash"></i></span>
                                        <input type="text" id="code" name="code" class="form-control" required
                                            value="{{ $code->code }}" placeholder="Enter account code">
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="code_name" class="form-label">Code Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                        <input type="text" id="code_name" name="code_name" class="form-control" required
                                            value="{{ $code->code_name }}" placeholder="Enter account code name">
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="kelkode_id" class="form-label">Category</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-layers"></i></span>
                                        <select id="kelkode_id" name="kelkode_id" class="form-select" required>
                                            @foreach ($categories as $cat)
                                            <option value="{{ $cat->id }}" @if ($cat->id == $code->kelkode_id) selected @endif>
                                                {{ $cat->category_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="is_active" class="form-label">Status</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-circle-fill"></i></span>
                                        <select id="is_active" name="is_active" class="form-select">
                                            <option value="1" {{ $code->is_active ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ !$code->is_active ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('codes.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left me-1"></i> Back
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-check-lg me-1"></i> Update Code
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
    <x-app-layout>
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="h4 mb-0"><i class="bi bi-plus-circle me-2"></i>Add Category</h2>
                            <p class="mb-0 opacity-75">Create a new account category</p>
                        </div>
                        <div class="card-body p-4">
                            <form method="POST" action="{{ route('categories.store') }}">
                                @csrf
                                <div class="mb-4">
                                    <label for="category_code" class="form-label">Code</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-code-slash"></i></span>
                                        <input type="text" id="category_code" name="category_code" class="form-control" required placeholder="Enter category code">
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="category_name" class="form-label">Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                        <input type="text" id="category_name" name="category_name" class="form-control" required placeholder="Enter category name">
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="category_type" class="form-label">Type</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-list-ul"></i></span>
                                        <select id="category_type" name="category_type" class="form-select" required>
                                            <option value="" disabled selected>Select category type</option>
                                            <option>Asset</option>
                                            <option>Liability</option>
                                            <option>Equity</option>
                                            <option>Revenue</option>
                                            <option>Expense</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left me-1"></i> Back
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-check-lg me-1"></i> Save Category
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
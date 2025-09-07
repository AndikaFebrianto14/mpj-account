    <x-app-layout>
        <div class="container py-5">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="h4 mb-0"><i class="bi bi-list-check me-2"></i>Account Codes</h2>
                        <p class="mb-0 opacity-75">Manage your account codes</p>
                    </div>
                    <a href="{{ route('codes.create') }}" class="btn btn-light">
                        <i class="bi bi-plus-circle me-1"></i> Add Code
                    </a>
                </div>
                <div class="card-body">
                    @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                        <i class="bi bi-check-circle me-2"></i>
                        <div>{{ session('success') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col" class="ps-4">Code</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($codes as $code)
                                <tr>
                                    <td class="ps-4 fw-medium">{{ $code->code }}</td>
                                    <td>{{ $code->code_name }}</td>
                                    <td>
                                        @if ($code->category)
                                        <span class="category-badge">{{ $code->category->category_name }}</span>
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $code->is_active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $code->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="action-buttons text-center">
                                        <a href="{{ route('codes.edit', $code->id) }}" class="btn btn-sm btn-outline-warning">
                                            <i class="bi bi-pencil me-1"></i> Edit
                                        </a>
                                        <form action="{{ route('codes.destroy', $code->id) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this code?')">
                                                <i class="bi bi-trash me-1"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Showing {{ $codes->firstItem() }} to {{ $codes->lastItem() }} of {{ $codes->total() }} codes
                        </div>
                        <div>
                            {{ $codes->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <x-app-layout>
        <div class="container py-5">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="h4 mb-0"><i class="bi bi-journal-text me-2"></i>Journal Entries</h2>
                        <p class="mb-0 opacity-75">Manage your journal entries</p>
                    </div>
                    <a href="{{ route('journals.create') }}" class="btn btn-light">
                        <i class="bi bi-plus-circle me-1"></i> Add Entry
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
                                    <th scope="col" class="ps-4">No</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Total Amount</th>
                                    <th scope="col" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($entries as $e)
                                <tr>
                                    <td class="ps-4 fw-medium">{{ $e->entry_number }}</td>
                                    <td>{{ \Carbon\Carbon::parse($e->entry_date)->format('d M Y') }}</td>
                                    <td class="description-cell" title="{{ $e->description }}">
                                        {{ $e->description }}
                                    </td>
                                    <td class="amount-positive">Rp {{ number_format($e->total_amount, 0, ',', '.') }}</td>
                                    <td class="action-buttons text-center">
                                        <a href="{{ route('journals.show', $e->id) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye me-1"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Showing {{ $entries->firstItem() }} to {{ $entries->lastItem() }} of {{ $entries->total() }} entries
                        </div>
                        <div>
                            {{ $entries->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
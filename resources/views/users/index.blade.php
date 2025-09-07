    <x-app-layout>
        <div class="container py-4">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between py-3">
                    <h3 class="card-title m-0"><i class="fas fa-users me-2"></i>User Management</h3>
                    <a href="{{ route('users.create') }}" class="btn btn-light btn-sm fw-bold">
                        <i class="fas fa-user-plus me-1"></i> Add New User
                    </a>
                </div>
                <div class="card-body">
                    @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center"
                        role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <div>{{ session('success') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                    @endif

                    <div class="table-responsive rounded">
                        <table class="table table-hover table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" class="ps-4">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                <tr>
                                    <td class="ps-4 fw-medium">{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @foreach ($user->roles->pluck('name') as $role)
                                        <span class="role-badge">{{ $role }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <span
                                            class="badge {{ $user->is_active ? 'text-bg-success' : 'text-bg-danger' }}">
                                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="user-actions text-center">
                                        <a href="{{ route('users.edit', $user->id) }}"
                                            class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                onclick="return confirm('Are you sure you want to delete this user?')"
                                                class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash me-1"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of
                            {{ $users->total() }} users
                        </div>
                        <div>
                            {{ $users->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
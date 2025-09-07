    <x-app-layout>
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="h4 mb-0"><i class="bi bi-person-plus me-2"></i>Edit User</h2>
                            <p class="mb-0 opacity-75">Isi form berikut untuk mengedit informasi user</p>
                        </div>
                        <div class="card-body p-4">
                            <form method="POST" action="{{ route('users.update', $user->id) }}">
                                @csrf @method('PUT')
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Lengkap</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                        <input type="text" id="name" name="name" value="{{ $user->name }}" class="form-control" required placeholder="Masukkan nama lengkap">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                        <input type="email" id="email" name="email" value="{{ $user->email }}" class="form-control" required placeholder="Masukkan alamat email">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">New Password (optional)</label>
                                    <div class="input-group password-container">
                                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                        <input type="password" id="password" name="password" class="form-control" placeholder="Buat password yang kuat">
                                        <span class="password-toggle mx-2 py-2" onclick="togglePassword('password')">
                                            <i class="bi bi-eye"></i>
                                        </span>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                    <div class="input-group password-container">
                                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Ulangi password yang sama">
                                        <span class="password-toggle mx-2 py-2" onclick="togglePassword('password_confirmation')">
                                            <i class="bi bi-eye"></i>
                                        </span>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="role" class="form-label">Role</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                                        <select name="role" class="form-control" required>
                                            @foreach ($roles as $role)
                                            <option value="{{ $role->name }}" @if ($user->roles->pluck('name')->contains($role->name)) selected @endif>
                                                {{ ucfirst($role->name) }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left me-1"></i> Kembali
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-lg me-1"></i> Update User
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function togglePassword(inputId) {
                const passwordInput = document.getElementById(inputId);
                const toggleIcon = passwordInput.parentNode.querySelector('.password-toggle i');

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    toggleIcon.classList.remove('bi-eye');
                    toggleIcon.classList.add('bi-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    toggleIcon.classList.remove('bi-eye-slash');
                    toggleIcon.classList.add('bi-eye');
                }
            }
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </x-app-layout>
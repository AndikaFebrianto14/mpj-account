<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Akuntansi - PT. Mitra Pilar Jaya</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .hero {
            background: linear-gradient(135deg, #0252d8, #3b82f6);
            padding: 110px 0;
            color: white;
        }

        .feature-box {
            transition: 0.3s;
        }

        .feature-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        }

        .nav-link:hover {
            opacity: .8;
        }

        .btn-main {
            background-color: #fff;
            color: #0252d8;
            font-weight: 600;
            padding: 12px 28px;
            border-radius: 30px;
        }

        .btn-main:hover {
            background-color: #e9e9e9;
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="#">
                MPJ Accounting
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">

                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary ms-lg-2 px-3" href="{{ route('login') }}">Login</a>
                    </li>
                </ul>

            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="hero text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-4">
                Modern Accounting System PT. Mitra Pilar Jaya
            </h1>
            <p class="lead mb-5 opacity-75">
                Manage transactions, ledgers, balance sheets, and financial reports with a modern Laravel-based system.
            </p>

            <a href="{{ route('login') }}" class="btn-main shadow">
                Login to the System
            </a>
        </div>
    </section>

    <!-- FEATURES SECTION -->
    <section class="container py-5" id="features">
        <h2 class="text-center fw-bold mb-5">Main Features</h2>

        <div class="row g-4">

            <div class="col-md-4">
                <div class="p-4 bg-white rounded-4 shadow-sm feature-box text-center h-100">
                    <img src="https://cdn-icons-png.flaticon.com/512/1995/1995470.png"
                        width="70" class="mb-3">
                    <h5 class="fw-bold">Journal Entry</h5>
                    <p class="text-muted">
                        Input debit/credit transactions, auto-numbering, and professional accounting system validation.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="p-4 bg-white rounded-4 shadow-sm feature-box text-center h-100">
                    <img src="https://cdn-icons-png.flaticon.com/512/3502/3502458.png"
                        width="70" class="mb-3">
                    <h5 class="fw-bold">Financial Reports</h5>
                    <p class="text-muted">
                        Balance Sheets, Profit & Loss, Cash Flow, General Ledger, and Trial Balance complete & clear.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="p-4 bg-white rounded-4 shadow-sm feature-box text-center h-100">
                    <img src="https://cdn-icons-png.flaticon.com/512/2920/2920244.png"
                        width="70" class="mb-3">
                    <h5 class="fw-bold">Fixed Assets</h5>
                    <p class="text-muted">
                        Asset management & automatic depreciation according to accounting standards.
                    </p>
                </div>
            </div>

        </div>
    </section>

    <!-- FOOTER -->
    <footer class="text-center py-4 bg-light mt-5">
        <p class="text-muted mb-0">
            © {{ date('Y') }} PT. Mitra Pilar Jaya – Andika Febrianto
        </p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
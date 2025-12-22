<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Pencatatan Keuangan')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .auth-page {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #ffffff 0%, #888888 100%);
        }
        .auth-card {
            border-radius: 1rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        .auth-card .card-body {
            padding: 2rem;
        }
        .auth-header {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            gap: 0.75rem;
        }
        .auth-header {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            margin-bottom: 0.6rem;
            gap: 0.35rem;
        }
        .auth-header img {
            max-height: 120px;
            width: auto;
            display: block;
            margin: 0 auto;
        }
        .auth-logo-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #333;
            line-height: 1;
            margin-top: 0.25rem;
            margin-bottom: 0.35rem;
            letter-spacing: 0.3px;
        }
        .auth-title {
            font-size: 1.1rem;
            font-weight: 500;
            letter-spacing: 0.5px;
            color: #555;
            margin-bottom: 1.5rem;
        }
        .form-control, .btn {
            border-radius: 0.5rem;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-primary {
            background-color: #667eea;
            border-color: #667eea;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
        }
        .btn-primary:hover {
            background-color: #5568d3;
            border-color: #5568d3;
        }
        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.95rem;
        }
        .auth-footer a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }
        .auth-footer a:hover {
            text-decoration: underline;
        }
        .alert {
            border-radius: 0.5rem;
        }
        .form-label {
            color: #333;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
    </style>
    
    @yield('css')
</head>
<body>
    <div class="auth-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="card auth-card">
                        <div class="card-body">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('js')
</body>
</html>

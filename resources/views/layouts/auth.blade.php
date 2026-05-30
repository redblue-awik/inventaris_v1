<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="My-Inventory.png" type="image/png">
    <title>@yield('title', 'Autentikasi') - My-Inventory</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">

    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center mb-6">
        <div
            class="w-16 h-16 bg-indigo-600 rounded-2xl flex items-center justify-center mx-auto shadow-lg shadow-indigo-600/30 mb-4">
            <i class="fas fa-cube text-3xl text-white"></i>
        </div>
        <h2 class="text-3xl font-bold text-slate-900">My-Inventory</h2>
        <p class="text-slate-500 mt-2">@yield('subtitle', 'Sistem Manajemen Inventaris')</p>
    </div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow-xl shadow-slate-200/50 sm:rounded-2xl sm:px-10 border border-slate-100">
            @yield('content')
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const pw = document.getElementById('password');
            const toggle = document.getElementById('togglePassword');
            if (pw && toggle) {
                toggle.addEventListener('click', function() {
                    const isPwd = pw.getAttribute('type') === 'password';
                    pw.setAttribute('type', isPwd ? 'text' : 'password');
                    const icon = toggle.querySelector('i');
                    if (icon) {
                        icon.classList.toggle('fa-lock');
                        icon.classList.toggle('fa-lock-open');
                    }
                });
            }

            const pwConf = document.getElementById('password_confirmation');
            const toggleConfBtn = document.getElementById('togglePasswordConf');
            if (pwConf && toggleConfBtn) {
                toggleConfBtn.addEventListener('click', function() {
                    const isPwd = pwConf.getAttribute('type') === 'password';
                    pwConf.setAttribute('type', isPwd ? 'text' : 'password');
                    const icon = toggleConfBtn.querySelector('i');
                    if (icon) {
                        icon.classList.toggle('fa-lock');
                        icon.classList.toggle('fa-lock-open');
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>

</html>

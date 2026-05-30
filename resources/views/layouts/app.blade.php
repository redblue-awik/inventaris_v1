<!DOCTYPE html>
<html lang="id">
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="My-Inventory.png" type="image/png">
    <title>@yield('title', 'Sistem Inventaris') - My-Inventory</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.tailwindcss.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.2/css/responsive.tailwindcss.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Custom styling minor DataTables */
        table.dataTable.dtr-inline.collapsed>tbody>tr>td.dtr-control:before,
        table.dataTable.dtr-inline.collapsed>tbody>tr>th.dtr-control:before {
            background-color: #4f46e5;
        }
        .dt-empty { text-align: center; padding: 2rem; color: #6b7280; }
        .dtr-details { padding: 1rem !important; font-weight: 400; display: flex; flex-direction: column; gap: 0.5rem; }
        .dtr-details .dtr-title { padding-right: 1rem; font-weight: 500; }
        .dtr-control { cursor: pointer; }
    </style>

    @stack('styles')
</head>

<body class="bg-slate-50 text-slate-800">

    <div class="flex h-screen overflow-hidden relative">

        <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/50 z-40 hidden md:hidden transition-opacity cursor-pointer"></div>

        @include('layouts.sidebar')

        <div class="flex-1 flex flex-col overflow-y-auto min-w-0">
            @include('layouts.navbar')

            <main class="p-4 md:p-8">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.tailwindcss.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.tailwindcss.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');

            // Fungsi untuk membuka/menutup sidebar
            function toggleSidebar() {
                sidebar.classList.toggle('-translate-x-full');
                sidebarOverlay.classList.toggle('hidden');
            }

            // Event Listeners
            if(mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', toggleSidebar);
            }
            if(sidebarOverlay) {
                sidebarOverlay.addEventListener('click', toggleSidebar); // Tutup jika klik area gelap
            }
            
            const ToastS = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1300,
                timerProgressBar: true
            });

            const ToastE = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });

            @if (session('success'))
                ToastS.fire({
                    icon: 'success',
                    title: "{{ session('success') }}"
                });
            @endif

            @if (session('error'))
                ToastE.fire({
                    icon: 'error',
                    title: "{{ session('error') }}"
                });
            @endif

            @if ($errors->any())
                let msgs = "";
                @foreach ($errors->all() as $err)
                    msgs += "{{ $err }} \n";
                @endforeach

                ToastE.fire({
                    icon: 'error',
                    title: msgs
                });
            @endif

            @if (session('logout_success'))
                const data = @json(session('logout_success'));
                ToastS.fire({
                    icon: data.icon || 'success',
                    title: data.message || 'Logout berhasil'
                });

                setTimeout(() => {
                    window.location.href = data.redirect || '/login';
                }, data.delay || 1300);
            @endif

        });
    </script>
    @stack('scripts')
</body>

</html>

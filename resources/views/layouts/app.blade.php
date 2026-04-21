<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Gunawan's Laundry - Sistem Manajemen Laundry Professional">
    <title>@yield('title', 'Dashboard') - Gunawan's Laundry</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #3730a3;
            --primary-light: #818cf8;
            --secondary: #06b6d4;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
            --dark: #f8fafc;
            --dark2: #ffffff;
            --dark3: #f1f5f9;
            --text: #111827;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --card: #ffffff;
            --sidebar-w: 260px;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--dark);
            color: var(--text);
            display: flex;
            min-height: 100vh;
        }
        /* ---- SIDEBAR ---- */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--dark2);
            position: fixed;
            top: 0; left: 0; bottom: 0;
            overflow-y: auto;
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            z-index: 100;
            transition: transform .3s;
        }
        .sidebar-logo {
            padding: 24px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid var(--border);
        }
        .sidebar-logo .logo-icon {
            width: 42px; height: 42px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
        }
        .sidebar-logo .logo-text { font-size: 18px; font-weight: 700; }
        .sidebar-logo .logo-sub  { font-size: 11px; color: var(--text-muted); }
        .sidebar-nav { padding: 16px 12px; flex: 1; }
        .nav-section { margin-bottom: 8px; }
        .nav-label {
            font-size: 10px; font-weight: 600; color: var(--text-muted);
            text-transform: uppercase; letter-spacing: 1px;
            padding: 8px 10px 4px;
        }
        .nav-item {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 12px; border-radius: 10px;
            color: var(--text-muted); text-decoration: none;
            font-size: 14px; font-weight: 500;
            transition: all .2s;
            margin-bottom: 2px;
        }
        .nav-item:hover {
            background: #f1f5f9;
            color: var(--primary);
        }
        .nav-item.active {
            background: #eff6ff;
            color: var(--primary);
            font-weight: 600;
        }
        .nav-item i { width: 20px; text-align: center; font-size: 15px; }
        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid var(--border);
        }
        .user-info {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: 10px;
            background: var(--dark3);
            margin-bottom: 8px;
        }
        .user-avatar {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 14px;
        }
        .user-name  { font-size: 13px; font-weight: 600; }
        .user-email { font-size: 11px; color: var(--text-muted); }
        .btn-logout {
            width: 100%;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            padding: 9px; border-radius: 10px;
            background: rgba(239,68,68,.1); color: #ef4444;
            border: 1px solid rgba(239,68,68,.2);
            cursor: pointer; font-size: 13px; font-weight: 500;
            transition: all .2s; text-decoration: none;
        }
        .btn-logout:hover { background: rgba(239,68,68,.2); }
        /* ---- MAIN ---- */
        .main-wrap {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .topbar {
            height: 64px;
            background: rgba(255,255,255,.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 24px;
            position: sticky; top: 0; z-index: 50;
        }
        .topbar-title { font-size: 18px; font-weight: 700; }
        .topbar-breadcrumb { font-size: 12px; color: var(--text-muted); margin-top: 2px; }
        .page-content { padding: 24px; flex: 1; }
        /* ---- CARDS ---- */
        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
        }
        .card-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
        .card-title { font-size: 16px; font-weight: 700; }
        /* ---- ALERTS ---- */
        .alert {
            padding: 12px 16px; border-radius: 10px;
            margin-bottom: 16px; font-size: 14px;
            display: flex; align-items: center; gap: 10px;
        }
        .alert-success { background: #ecfdf5; border: 1px solid #10b98133; color: #065f46; }
        .alert-danger  { background: #fef2f2; border: 1px solid #ef444433; color: #991b1b; }
        .alert-warning { background: #fffbeb; border: 1px solid #f59e0b33; color: #92400e; }
        /* ---- TABLE ---- */
        .table-wrap { overflow-x: auto; border-radius: 12px; }
        table { width: 100%; border-collapse: collapse; }
        thead th {
            padding: 12px 16px; text-align: left;
            font-size: 11px; font-weight: 600;
            text-transform: uppercase; letter-spacing: .8px;
            color: var(--text-muted);
            background: rgba(255,255,255,.03);
            border-bottom: 1px solid var(--border);
        }
        tbody td {
            padding: 13px 16px; font-size: 14px;
            border-bottom: 1px solid rgba(255,255,255,.04);
            vertical-align: middle;
        }
        tbody tr:hover { background: rgba(255,255,255,.025); }
        tbody tr:last-child td { border-bottom: none; }
        /* ---- BADGES ---- */
        .badge {
            display: inline-flex; align-items: center;
            padding: 4px 10px; border-radius: 20px;
            font-size: 11px; font-weight: 600;
        }
        .badge-success  { background: #dcfce7; color: #166534; }
        .badge-warning  { background: #fef3c7; color: #92400e; }
        .badge-info     { background: #dbeafe; color: #1e40af; }
        .badge-primary  { background: #e0e7ff; color: #3730a3; }
        .badge-danger   { background: #fee2e2; color: #991b1b; }
        .badge-secondary{ background: #f1f5f9; color: #475569; }
        /* ---- BUTTONS ---- */
        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 16px; border-radius: 8px;
            font-size: 13px; font-weight: 600;
            text-decoration: none; border: none; cursor: pointer;
            transition: all .2s;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), #6366f1);
            color: #fff;
            box-shadow: 0 4px 12px rgba(79,70,229,.3);
        }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(79,70,229,.4); }
        .btn-secondary { background: #ffffff; color: var(--text-muted); border: 1px solid var(--border); }
        .btn-secondary:hover { background: #f8fafc; color: var(--text); }
        .btn-success { background: #dcfce7; color: #166534; border: 1px solid #10b981; }
        .btn-danger  { background: #fee2e2; color: #991b1b; border: 1px solid #ef4444; }
        .btn-warning { background: #fef3c7; color: #92400e; border: 1px solid #f59e0b; }
        .btn-info    { background: #dbeafe; color: #1e40af; border: 1px solid #3b82f6; }
        .btn-sm { padding: 5px 10px; font-size: 12px; border-radius: 6px; }
        /* ---- FORMS ---- */
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: var(--text); }
        .form-control {
            width: 100%; padding: 10px 14px;
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 10px; color: var(--text);
            font-size: 14px; font-family: inherit;
            transition: border-color .2s, box-shadow .2s;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79,70,229,.2);
            background: rgba(255,255,255,.07);
        }
        .form-control::placeholder { color: var(--text-muted); }
        select.form-control option { background: var(--dark2); }
        textarea.form-control { resize: vertical; min-height: 80px; }
        .invalid-feedback { color: #f87171; font-size: 12px; margin-top: 5px; }
        .is-invalid { border-color: var(--danger) !important; }
        /* ---- GRID ---- */
        .grid { display: grid; gap: 20px; }
        .grid-2 { grid-template-columns: repeat(2, 1fr); }
        .grid-3 { grid-template-columns: repeat(3, 1fr); }
        .grid-4 { grid-template-columns: repeat(4, 1fr); }
        /* ---- PAGINATION ---- */
        .pagination { display: flex; gap: 6px; justify-content: center; margin-top: 20px; flex-wrap: wrap; }
        .pagination a, .pagination span {
            padding: 7px 12px; border-radius: 8px; font-size: 13px;
            text-decoration: none; border: 1px solid var(--border);
            background: var(--card); color: var(--text-muted);
            transition: all .2s;
        }
        .pagination a:hover { background: rgba(79,70,229,.15); color: var(--primary-light); border-color: var(--primary); }
        .pagination .active span { background: var(--primary); color: #fff; border-color: var(--primary); }
        /* ---- SEARCH BAR ---- */
        .search-wrap { display: flex; gap: 10px; align-items: center; }
        .search-input-wrap { position: relative; }
        .search-input-wrap i {
            position: absolute; left: 12px; top: 50%;
            transform: translateY(-50%); color: var(--text-muted); font-size: 14px;
        }
        .search-input-wrap .form-control { padding-left: 38px; width: 260px; }
        /* ---- DETAIL ---- */
        .detail-row { display: flex; align-items: flex-start; gap: 16px; margin-bottom: 12px; font-size: 14px; }
        .detail-label { color: var(--text-muted); min-width: 160px; font-weight: 500; }
        .detail-value { color: var(--text); flex: 1; }
        hr.divider { border: none; border-top: 1px solid var(--border); margin: 20px 0; }
        /* ---- MOBILE ---- */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main-wrap { margin-left: 0; }
            .grid-2, .grid-3, .grid-4 { grid-template-columns: 1fr; }
        }
    </style>
    @stack('styles')
</head>
<body>
    {{-- SIDEBAR --}}
    <aside class="sidebar">
        <div class="sidebar-logo">
            <div>
                <div class="logo-text">Gunawan's Laundry</div>
                <div class="logo-sub">Laundry Management</div>
            </div>
        </div>
        <nav class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-label">Menu Utama</div>
                <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>
            </div>

            @if(in_array(auth()->user()->id_level, [1])) {{-- Admin --}}
            <div class="nav-section">
                <div class="nav-label">Master Data</div>
                <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    Manajemen User
                </a>
                <a href="{{ route('services.index') }}" class="nav-item {{ request()->routeIs('services.*') ? 'active' : '' }}">
                    Jenis Layanan
                </a>
            </div>
            @endif

            @if(in_array(auth()->user()->id_level, [1, 2])) {{-- Admin & Operator --}}
            <div class="nav-section">
                <div class="nav-label">Kemitraan</div>
                <a href="{{ route('customers.index') }}" class="nav-item {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                    Data Pelanggan
                </a>
            </div>
            @endif
            
            @if(in_array(auth()->user()->id_level, [2])) {{-- Operator --}}
            <div class="nav-section">
                <div class="nav-label">Transaksi</div>
                <a href="{{ route('orders.index') }}" class="nav-item {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                    Order Laundry
                </a>
            </div>
            @endif

            @if(in_array(auth()->user()->id_level, [3])) {{-- Pimpinan --}}
            <div class="nav-section">
                <div class="nav-label">Laporan</div>
                <a href="{{ route('report.index') }}" class="nav-item {{ request()->routeIs('report.*') ? 'active' : '' }}">
                    Laporan Penjualan
                </a>
            </div>
            @endif
        </nav>
        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div>
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-email">{{ auth()->user()->email }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <div class="main-wrap">
        <header class="topbar">
            <div>
                <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
                <div class="topbar-breadcrumb">Gunawan's Laundry &rsaquo; @yield('page-title', 'Dashboard')</div>
            </div>
            <div style="font-size: 13px; color: var(--text-muted);">
                {{ now()->format('d M Y') }}
            </div>
        </header>

        <main class="page-content">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
    @stack('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Admin Dashboard') - AdivoQ Admin</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>⚡</text></svg>">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Admin CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    
    @stack('styles')
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="admin-sidebar-header">
                <a href="{{ route('admin.dashboard') }}" class="admin-sidebar-logo">
                    <div class="logo-icon">
                        <i data-lucide="zap"></i>
                    </div>
                    <span>CreatorPay</span>
                </a>
            </div>
            
            <nav class="admin-sidebar-menu">
                <a href="{{ route('admin.dashboard') }}" class="admin-menu-item {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
                    <i data-lucide="layout-dashboard"></i>
                    <span>Dashboard</span>
                </a>
                
                <a href="{{ route('admin.tenants.index') }}" class="admin-menu-item {{ request()->routeIs('admin.tenants*') ? 'active' : '' }}">
                    <i data-lucide="users"></i>
                    <span>Tenants</span>
                </a>
                
                <a href="{{ route('admin.waitlist.index') }}" class="admin-menu-item {{ request()->routeIs('admin.waitlist*') ? 'active' : '' }}">
                    <i data-lucide="clock"></i>
                    <span>Waitlist</span>
                </a>
                
                <a href="{{ route('admin.contacts.index') }}" class="admin-menu-item {{ request()->routeIs('admin.contacts*') ? 'active' : '' }}">
                    <i data-lucide="mail"></i>
                    <span>Contact Enquiries</span>
                </a>
                
                <a href="{{ route('admin.plans.index') }}" class="admin-menu-item {{ request()->routeIs('admin.plans*') ? 'active' : '' }}">
                    <i data-lucide="package"></i>
                    <span>Plans</span>
                </a>
                
                @if(auth('admin')->user()->isSuperAdmin())
                <a href="{{ route('admin.admins.index') }}" class="admin-menu-item {{ request()->routeIs('admin.admins*') ? 'active' : '' }}">
                    <i data-lucide="shield"></i>
                    <span>Admin Users</span>
                </a>
                @endif
                
                <a href="{{ route('admin.activity-logs.index') }}" class="admin-menu-item {{ request()->routeIs('admin.activity-logs*') ? 'active' : '' }}">
                    <i data-lucide="activity"></i>
                    <span>Activity Logs</span>
                </a>
                
                <a href="{{ route('admin.settings.index') }}" class="admin-menu-item {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                    <i data-lucide="settings"></i>
                    <span>Settings</span>
                </a>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="admin-main">
            <!-- Header -->
            <header class="admin-header">
                <div>
                    <h1 class="text-2xl font-bold">@yield('page-title', 'Dashboard')</h1>
                </div>
                
                <div class="flex items-center gap-4">
                    <!-- Admin Profile Dropdown -->
                    <div class="dropdown">
                        <button class="dropdown-toggle flex items-center gap-2">
                            <img src="{{ auth('admin')->user()->avatar_url }}" alt="Avatar" class="w-10 h-10 rounded-full">
                            <span>{{ auth('admin')->user()->name }}</span>
                            <i data-lucide="chevron-down"></i>
                        </button>
                        
                        <div class="dropdown-menu">
                            <a href="{{ route('admin.profile') }}" class="dropdown-item">
                                <i data-lucide="user"></i> Profile
                            </a>
                            <form action="{{ route('admin.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i data-lucide="log-out"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Content -->
            <div class="admin-content">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-error">
                        {{ session('error') }}
                    </div>
                @endif
                
                @yield('content')
            </div>
        </main>
    </div>
    
    <script>
        // Initialize Lucide icons
        lucide.createIcons();
    </script>
    
    @stack('scripts')
</body>
</html>
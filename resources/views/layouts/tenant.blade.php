<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Dashboard') - {{ tenant('company_name') ?? tenant('name') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>⚡</text></svg>">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    
    @stack('styles')
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="sidebar">
            <div class="admin-sidebar-header">
                <a href="{{ route('dashboard') }}" class="admin-sidebar-logo">
                    @if(tenant('logo'))
                        <img src="{{ asset('storage/' . tenant('logo')) }}" alt="Logo" class="w-9 h-9 rounded-lg">
                    @else
                        <div class="logo-icon">
                            <i data-lucide="zap"></i>
                        </div>
                    @endif
                    <span>{{ tenant('company_name') ?? tenant('name') }}</span>
                </a>
            </div>
            
            <nav class="admin-sidebar-menu">
                <a href="{{ route('dashboard') }}" class="admin-menu-item {{ request()->routeIs('dashboard*') ? 'active' : '' }}">
                    <i data-lucide="layout-dashboard"></i>
                    <span>Dashboard</span>
                </a>
                
                <div class="admin-menu-section">Manage</div>
                
                <a href="{{ route('brands.index') }}" class="admin-menu-item {{ request()->routeIs('brands*') ? 'active' : '' }}">
                    <i data-lucide="building-2"></i>
                    <span>Brands</span>
                </a>
                
                <a href="{{ route('campaigns.index') }}" class="admin-menu-item {{ request()->routeIs('campaigns*') ? 'active' : '' }}">
                    <i data-lucide="briefcase"></i>
                    <span>Campaigns</span>
                </a>
                
                <div class="admin-menu-section">Finance</div>
                
                <a href="{{ route('invoices.index') }}" class="admin-menu-item {{ request()->routeIs('invoices*') ? 'active' : '' }}">
                    <i data-lucide="file-text"></i>
                    <span>Invoices</span>
                </a>
                
                <a href="{{ route('payments.index') }}" class="admin-menu-item {{ request()->routeIs('payments*') ? 'active' : '' }}">
                    <i data-lucide="wallet"></i>
                    <span>Payments</span>
                </a>
                
                <a href="{{ route('reports.index') }}" class="admin-menu-item {{ request()->routeIs('reports*') ? 'active' : '' }}">
                    <i data-lucide="bar-chart-3"></i>
                    <span>Reports</span>
                </a>
                
                @if(auth()->user()->isAdmin())
                <div class="admin-menu-section">Admin</div>
                
                <a href="{{ route('team.index') }}" class="admin-menu-item {{ request()->routeIs('team*') ? 'active' : '' }}">
                    <i data-lucide="users"></i>
                    <span>Team</span>
                </a>
                
                <a href="{{ route('settings.index') }}" class="admin-menu-item {{ request()->routeIs('settings*') ? 'active' : '' }}">
                    <i data-lucide="settings"></i>
                    <span>Settings</span>
                </a>
                @endif
            </nav>
            
            <!-- Plan Info -->
            <div class="p-4 mt-auto border-t border-white/10">
                <div class="bg-white/5 rounded-xl p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium">{{ ucfirst(tenant('plan')) }} Plan</span>
                        @if(tenant()->onTrial())
                            <span class="badge badge-warning text-xs">Trial</span>
                        @endif
                    </div>
                    @if(tenant()->onTrial())
                        <p class="text-xs text-gray-400">
                            Trial ends {{ tenant('trial_ends_at')->diffForHumans() }}
                        </p>
                    @endif
                    <a href="{{ route('settings.billing') }}" class="btn btn-outline btn-sm btn-block mt-3">
                        Upgrade Plan
                    </a>
                </div>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="admin-main">
            <!-- Header -->
            <header class="admin-header">
                <div class="flex items-center gap-4">
                    <button class="lg:hidden p-2" onclick="toggleSidebar()">
                        <i data-lucide="menu"></i>
                    </button>
                    <h1 class="text-xl font-bold">@yield('page-title', 'Dashboard')</h1>
                </div>
                
                <div class="flex items-center gap-4">
                    <!-- Quick Actions -->
                    <a href="{{ route('invoices.create') }}" class="btn btn-primary btn-sm hidden md:flex">
                        <i data-lucide="plus"></i> New Invoice
                    </a>
                    
                    <!-- User Dropdown -->
                    <div class="dropdown">
                        <button class="dropdown-toggle flex items-center gap-2">
                            <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="w-9 h-9 rounded-lg">
                            <span class="hidden md:block">{{ auth()->user()->name }}</span>
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </button>
                        
                        <div class="dropdown-menu">
                            <a href="{{ route('profile.index') }}" class="dropdown-item">
                                <i data-lucide="user"></i> Profile
                            </a>
                            <a href="{{ route('settings.index') }}" class="dropdown-item">
                                <i data-lucide="settings"></i> Settings
                            </a>
                            <div class="dropdown-divider"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-red-400">
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
                    <div class="alert alert-success mb-6">
                        <i data-lucide="check-circle"></i>
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-error mb-6">
                        <i data-lucide="alert-circle"></i>
                        {{ session('error') }}
                    </div>
                @endif
                
                @yield('content')
            </div>
        </main>
    </div>
    
    <script>
        lucide.createIcons();
        
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
        }
    </script>
    
    @stack('scripts')
</body>
</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - @yield('title')</title>
    <link href="{{  asset('css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        :root {
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 70px;
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #3498db;
            --hover-color: #3a5169;
            --active-color: #2980b9;
        }

        .sidebar {
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            width: var(--sidebar-width);
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: 3px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .main-content {
            margin-left: var(--sidebar-width);
            transition: all 0.3s ease;
        }

        .main-content.expanded {
            margin-left: var(--sidebar-collapsed-width);
        }

        .sidebar-header {
            padding: 1.5rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 1rem;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: white;
        }

        .sidebar-brand-icon {
            font-size: 1.75rem;
            color: var(--accent-color);
        }

        .sidebar-brand-text {
            font-size: 1.25rem;
            font-weight: 600;
            transition: opacity 0.3s ease;
        }

        .sidebar.collapsed .sidebar-brand-text {
            opacity: 0;
            width: 0;
        }

        .nav-item {
            margin-bottom: 0.25rem;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            margin: 0.125rem 0.5rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            position: relative;
        }

        .nav-link:hover {
            color: white !important;
            background-color: var(--hover-color);
            transform: translateX(5px);
        }

        .nav-link.active {
            color: white !important;
            background-color: var(--active-color);
            box-shadow: 0 4px 8px rgba(52, 152, 219, 0.3);
        }

        .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        .nav-text {
            transition: opacity 0.3s ease;
        }

        .sidebar.collapsed .nav-text {
            opacity: 0;
            width: 0;
        }

        .nav-badge {
            margin-left: auto;
            background: #e74c3c;
            color: white;
            border-radius: 10px;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            min-width: 20px;
            text-align: center;
        }

        .sidebar.collapsed .nav-badge {
            position: absolute;
            top: 5px;
            right: 5px;
            font-size: 0.6rem;
            padding: 0.2rem 0.4rem;
        }

        .sidebar-divider {
            border-color: rgba(255, 255, 255, 0.1);
            margin: 1rem 0.5rem;
        }

        .sidebar-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .toggle-btn {
            background: none;
            border: none;
            color: white;
            padding: 0.5rem;
            border-radius: 0.25rem;
            transition: all 0.3s ease;
        }

        .toggle-btn:hover {
            background-color: var(--hover-color);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .user-info:hover {
            background-color: var(--hover-color);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--accent-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .user-details {
            flex: 1;
            min-width: 0;
        }

        .user-name {
            font-weight: 600;
            margin-bottom: 0.125rem;
        }

        .user-role {
            font-size: 0.8rem;
            opacity: 0.8;
        }

        .sidebar.collapsed .user-details {
            opacity: 0;
            width: 0;
        }

        /* Mobile responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-toggle {
                display: block !important;
            }
        }

        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.25rem;
            color: var(--primary-color);
        }

        .topbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 999;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #e74c3c;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>

<body>
    <!-- Mobile Toggle Button -->
    <button class="mobile-toggle position-fixed top-3 start-3 z-1000" id="mobileToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
                <i class="fas fa-bus sidebar-brand-icon"></i>
                <span class="sidebar-brand-text">OBRS Admin</span>
            </a>
            <button class="toggle-btn position-absolute top-3 end-3" id="sidebarToggle">
                <i class="fas fa-chevron-left"></i>
            </button>
        </div>

        <div class="sidebar-content">
            <!-- Main Navigation -->
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('admin.dashboard')) active @endif"
                        href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('admin.users.*')) active @endif"
                        href="{{ route('admin.users.index') }}">
                        <i class="fas fa-users"></i>
                        <span class="nav-text">User Management</span>
                        <!--<span class="nav-badge">@yield('active-users', 'N/A')</span>-->
                        <span class="nav-badge">{{$totalUsers}}</span>

                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('admin.buses.*')) active @endif"
                        href="{{ route('admin.buses.index') }}">
                        <i class="fas fa-bus"></i>
                        <span class="nav-text">Bus Management</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('admin.routes.*')) active @endif"
                        href="{{ route('admin.routes.index') }}">
                        <i class="fas fa-route"></i>
                        <span class="nav-text">Routes & Schedules</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('admin.bookings.*')) active @endif"
                        href="{{ route('admin.bookings.index') }}">
                        <i class="fas fa-ticket-alt"></i>
                        <span class="nav-text">Bookings</span>
                        <!--<span class="nav-badge">@yield('todays-booking', 'N/A')</span> -->
                        <span class="nav-badge">{{ $totalBookings }}</span>
                    </a>
                </li>
            </ul>

            <hr class="sidebar-divider">

            <!-- Analytics Section -->
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('admin.reports.*')) active @endif"
                        href="{{ route('admin.reports.index') }}">
                        <i class="fas fa-chart-bar"></i>
                        <span class="nav-text">Reports & Analytics</span>
                    </a>
                </li>

                <!--
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-chart-line"></i>
                        <span class="nav-text">Revenue Analytics</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-map-marker-alt"></i>
                        <span class="nav-text">Route Analytics</span>
                    </a>
                </li>
                -->
            </ul>

            <hr class="sidebar-divider">

            <!-- System Section -->

            <!--
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-cog"></i>
                        <span class="nav-text">System Settings</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-bell"></i>
                        <span class="nav-text">Notifications</span>
                        <span class="nav-badge">5</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-question-circle"></i>
                        <span class="nav-text">Help & Support</span>
                    </a>
                </li>
            </ul>
            -->
        </div>

        <!-- Sidebar Footer -->
        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="user-details">
                    <div class="user-name">{{ Auth::user()->name }}</div>
                    <div class="user-role">Administrator</div>
                </div>
                <div class="dropdown">
                    <a class="text-white" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-ellipsis-v"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('admin.profile.index') }}"><i
                                    class="fas fa-user me-2"></i>Profile</a></li>
                        {{-- <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li> --}}
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Topbar -->
        <nav class="navbar navbar-expand-lg topbar px-4 py-3">
            <div class="d-flex align-items-center w-100">
                <div class="flex-grow-1">
                    <h4 class="mb-0 text-dark">@yield('page-title', 'Dashboard')</h4>
                    <small class="text-muted">@yield('page-subtitle', 'Welcome to Admin Panel')</small>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <!-- Notifications -->
                    {{-- <div class="dropdown position-relative">
                        <a class="nav-link text-dark position-relative" href="#" role="button"
                            data-bs-toggle="dropdown">
                            <i class="fas fa-bell fa-lg"></i>
                            <span class="notification-badge">3</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" style="width: 300px;">
                            <li class="dropdown-header">Notifications</li>
                            <li><a class="dropdown-item" href="#">New booking received</a></li>
                            <li><a class="dropdown-item" href="#">System update available</a></li>
                            <li><a class="dropdown-item" href="#">New user registered</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-center" href="#">View All</a></li>
                        </ul>
                    </div> --}}

                    <!-- Quick Stats -->
                    {{-- <div class="dropdown">
                        <a class="nav-link text-dark" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-chart-pie fa-lg"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end p-3" style="width: 300px;">
                            <li class="dropdown-header">Quick Stats</li>
                            <li class="d-flex justify-content-between py-1">
                                <span>Today's Bookings:</span>
                                <strong>@yield('todays-booking', 'N/A')</strong>
                            </li>
                            <li class="d-flex justify-content-between py-1">
                                <span>Revenue:</span>
                                <strong class="text-success">@yield('total-revenue', 'N/A')</strong>
                            </li>
                            <li class="d-flex justify-content-between py-1">
                                <span>Active Users:</span>
                                <strong>@yield('active-users', 'N/A')</strong>
                            </li>
                        </ul>
                    </div> --}}

                    <!-- User Menu -->
                    <div class="dropdown">
                        <a class="nav-link text-dark d-flex align-items-center" href="#" role="button"
                            data-bs-toggle="dropdown">
                            <div class="user-avatar me-2" style="width: 35px; height: 35px; font-size: 0.9rem;">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down ms-2"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('admin.profile.index') }}"><i
                                        class="fas fa-user me-2"></i>My Profile</a></li>
                            {{-- <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                            --}}
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="p-4">
            @yield('content')
        </main>
    </div>

    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const mobileToggle = document.getElementById('mobileToggle');

            // Toggle sidebar
            sidebarToggle.addEventListener('click', function () {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');

                // Rotate chevron icon
                const icon = this.querySelector('i');
                icon.classList.toggle('fa-chevron-left');
                icon.classList.toggle('fa-chevron-right');
            });

            // Mobile toggle
            mobileToggle.addEventListener('click', function () {
                sidebar.classList.toggle('mobile-open');
            });

            // Close sidebar on mobile when clicking outside
            document.addEventListener('click', function (event) {
                if (window.innerWidth <= 768) {
                    if (!sidebar.contains(event.target) && !mobileToggle.contains(event.target)) {
                        sidebar.classList.remove('mobile-open');
                    }
                }
            });

            // Update active nav item
            function updateActiveNav() {
                document.querySelectorAll('.nav-link').forEach(link => {
                    link.classList.remove('active');
                });

                const currentPath = window.location.pathname;
                document.querySelectorAll('.nav-link').forEach(link => {
                    if (link.href && link.href.includes(currentPath)) {
                        link.classList.add('active');
                    }
                });
            }

            updateActiveNav();
        });
    </script>
    @stack('scripts')
</body>

</html>
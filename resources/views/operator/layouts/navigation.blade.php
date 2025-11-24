<nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm">
    <div class="container-fluid">
        <!-- Brand -->
        <a class="navbar-brand fw-bold" href="{{ route('operator.dashboard') }}">
            <i class="fas fa-bus me-2"></i>OBRS Operator
        </a>
        
        <!-- Mobile Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#operatorNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Navigation Links -->
        <div class="collapse navbar-collapse" id="operatorNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('operator.dashboard')) active @endif" 
                       href="{{ route('operator.dashboard') }}">
                        <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle @if(request()->routeIs('operator.buses.*')) active @endif" 
                       href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-bus me-1"></i>Fleet Management
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item @if(request()->routeIs('operator.buses.index')) active @endif" 
                               href="{{ route('operator.buses.index') }}">
                                <i class="fas fa-list me-2"></i>My Buses
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item @if(request()->routeIs('operator.buses.create')) active @endif" 
                               href="{{ route('operator.buses.create') }}">
                                <i class="fas fa-plus me-2"></i>Add New Bus
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('operator.routes.*')) active @endif" 
                       href="{{ route('operator.routes.index') }}">
                        <i class="fas fa-route me-1"></i>My Routes
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle @if(request()->routeIs('operator.schedules.*')) active @endif" 
                       href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-calendar me-1"></i>Schedules
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item @if(request()->routeIs('operator.schedules.index')) active @endif" 
                               href="{{ route('operator.schedules.index') }}">
                                <i class="fas fa-list me-2"></i>All Schedules
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item @if(request()->routeIs('operator.schedules.create')) active @endif" 
                               href="{{ route('operator.schedules.create') }}">
                                <i class="fas fa-plus me-2"></i>Create Schedule
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('operator.bookings.*')) active @endif" 
                       href="{{ route('operator.bookings.index') }}">
                        <i class="fas fa-ticket-alt me-1"></i>Bookings
                    </a>
                </li>
            </ul>
            
            <!-- User Menu -->
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('operator.profile.index') }}">
                                <i class="fas fa-building me-2"></i>Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('operator.reports.index') }}">
                                <i class="fas fa-chart-bar me-2"></i>Reports
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
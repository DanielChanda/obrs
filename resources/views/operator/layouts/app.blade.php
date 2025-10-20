<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OBRS Operator - @yield('title', 'Dashboard')</title>
    <link href="{{ asset('css/all.min.css') }}"  rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" 
      integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" 
      crossorigin="anonymous" 
      referrerpolicy="no-referrer" />
    @stack('styles')
</head>
<body class="bg-light">
    <!-- Operator Navigation -->
    @include('operator.layouts.navigation')
    
    <!-- Main Content -->
    <main class="container-fluid py-4">
        <div class="row">
            <!-- Sidebar -->
            @hasSection('sidebar')
                <aside class="col-md-3">
                    @yield('sidebar')
                </aside>
            @endif
            
            <!-- Main Content Area -->
            <div class="@hasSection('sidebar') col-md-9 @else col-12 @endif">
                <!-- Page Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="h3 mb-0">@yield('page-title')</h2>
                        @hasSection('page-subtitle')
                            <p class="text-muted mb-0">@yield('page-subtitle')</p>
                        @endif
                    </div>
                    @yield('header-actions')
                </div>
                
                <!-- Alerts -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                <!-- Page Content -->
                @yield('content')
            </div>
        </div>
    </main>
    
    <!-- Footer -->
    <footer class="bg-dark text-light py-3 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; {{ date('Y') }} OBRS Operator Portal. All rights reserved.</p>
        </div>
    </footer>
    
    <script src="{{ asset('js/bootstrap.bundle.min.js') }} }}"></script>
    <script src="js/chart.min.js"></script>

    @stack('scripts')
</body>
</html>
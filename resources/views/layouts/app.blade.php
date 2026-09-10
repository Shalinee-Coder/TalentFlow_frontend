<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'TalentFlow – Recruitment & Resume Management System')</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/talentflow-theme.css">
  <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>✨</text></svg>">
  @stack('styles')
</head>
<body>

  <!-- Floating Main Window Container -->
  <div class="tf-window">
    
    <!-- Left Navigation Sidebar -->
    @include('partials.sidebar')

    <!-- Main Content Container -->
    <main class="tf-main-container">
      <!-- Top Header -->
      @include('partials.header')

      <!-- Scrollable Viewport -->
      <section class="tf-viewport">
        @yield('content')
      </section>
    </main>
  </div>

  <!-- Toast Notification Container -->
  <div id="tfToastContainer" class="tf-toast-container"></div>

  <!-- Modals -->
  @include('modals.post-job')
  @include('modals.apply-job')
  @include('modals.transition')
  @include('modals.schedule-interview')
  @include('modals.auth')

  <!-- Core JavaScript Application -->
  <script src="/js/talentflow-app.js?v={{ filemtime(public_path('js/talentflow-app.js')) }}"></script>
  @stack('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Presensi App')</title>

    <!-- Favicon & Mobile Touch Icons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <meta name="theme-color" content="#2563eb">

    <!-- DNS-prefetch & Preconnect for Fast Assets -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="dns-prefetch" href="//cdn.jsdelivr.net">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net">

    <!-- Web Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- SweetAlert2 CDN (Guaranteed instant load & universal availability) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Critical CSS to eliminate Cumulative Layout Shift (CLS) -->
    <style>
        :root {
            --app-bg: #f8fafc;
            --app-surface: #ffffff;
            --app-text: #1e293b;
            --app-text-dim: #64748b;
            --app-accent: #2563eb;
            --app-border: #e2e8f0;
            --app-font-sans: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --app-font-display: 'Outfit', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        html, body {
            margin: 0;
            padding: 0;
            background-color: #f8fafc;
            color: #1e293b;
            font-family: var(--app-font-sans);
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: antialiased;
        }
        /* Lock mobile header height to prevent CLS */
        .mobile-topbar-header {
            min-height: 56px;
            height: 56px;
        }
        /* Pre-stabilize responsive offcanvas sidebar to prevent 0.59+ CLS on body */
        @media (max-width: 991.98px) {
            .sidebar.offcanvas-lg {
                position: fixed !important;
                top: 0;
                bottom: 0;
                left: 0;
                z-index: 1045;
                width: 85%;
                max-width: 285px;
                visibility: hidden;
                transform: translateX(-100%);
            }
            .sidebar.offcanvas-lg.show {
                visibility: visible;
                transform: none;
            }
        }
        @media (min-width: 992px) {
            .sidebar {
                width: 280px !important;
                min-width: 280px !important;
                min-height: 100vh;
            }
        }
        /* Mobile bottom nav height reservation */
        .mobile-bottom-nav {
            min-height: 62px;
            height: 62px;
        }
        /* Avatar & icon aspect ratio reservations */
        .avatar-img-sm {
            width: 34px;
            height: 34px;
            min-width: 34px;
            aspect-ratio: 1 / 1;
            object-fit: cover;
            border-radius: 50%;
        }
        .avatar-img-md {
            width: 40px;
            height: 40px;
            min-width: 40px;
            aspect-ratio: 1 / 1;
            object-fit: cover;
            border-radius: 50%;
        }
        .tabular-nums {
            font-variant-numeric: tabular-nums;
        }
    </style>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    @yield('content')
    @stack('scripts')

    <!-- Native Speculation Rules API for Instant Page Loading (Chrome/Edge/Modern Browsers) -->
    <script type="speculationrules">
    {
      "prefetch": [
        {
          "where": {
            "and": [
              { "href_matches": "/*" },
              { "not": { "href_matches": "/logout" } },
              { "not": { "href_matches": "/*download*" } },
              { "not": { "href_matches": "/*export*" } }
            ]
          },
          "eagerness": "moderate"
        }
      ]
    }
    </script>

    <!-- Lightweight Universal Instant Prefetch Fallback (Desktop hover & Mobile touchstart) -->
    <script>
    (function() {
        if (!('fetch' in window)) return;
        const prefetched = new Set();
        function prefetch(url) {
            if (!url || prefetched.has(url)) return;
            prefetched.add(url);
            const link = document.createElement('link');
            link.rel = 'prefetch';
            link.href = url;
            link.as = 'document';
            document.head.appendChild(link);
        }
        function handleInteraction(e) {
            const a = e.target.closest('a');
            if (!a || !a.href || a.origin !== location.origin) return;
            const path = a.pathname;
            if (path.includes('logout') || path.includes('download') || path.includes('export') || a.hasAttribute('download')) return;
            prefetch(a.href);
        }
        document.addEventListener('mouseover', handleInteraction, { passive: true });
        document.addEventListener('touchstart', handleInteraction, { passive: true });
    })();
    </script>

    <!-- SweetAlert2 Global Confirmation Helpers -->
    <script>
    (function() {
        window.confirmDelete = function(event, target, name = '') {
            if (event) event.preventDefault();
            const form = target && target.tagName === 'FORM' ? target : (target ? target.closest('form') : null);
            if (!form) return false;

            const text = name 
                ? 'Data "' + name + '" yang dihapus tidak dapat dikembalikan!'
                : 'Data yang dihapus tidak dapat dikembalikan!';

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            } else {
                if (confirm(text)) {
                    form.submit();
                }
            }
            return false;
        };

        window.confirmAction = function(event, target, options = {}) {
            if (event) event.preventDefault();
            const form = target && target.tagName === 'FORM' ? target : (target ? target.closest('form') : null);
            if (!form) return false;

            const title = options.title || 'Apakah Anda yakin?';
            const text = options.text || 'Lanjutkan tindakan ini?';
            const icon = options.icon || 'question';
            const confirmColor = options.confirmColor || '#3085d6';
            const cancelColor = options.cancelColor || '#aaa';
            const confirmText = options.confirmText || 'Ya, lanjutkan!';
            const cancelText = options.cancelText || 'Batal';

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: title,
                    text: text,
                    icon: icon,
                    showCancelButton: true,
                    confirmButtonColor: confirmColor,
                    cancelButtonColor: cancelColor,
                    confirmButtonText: confirmText,
                    cancelButtonText: cancelText
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            } else {
                if (confirm(text)) {
                    form.submit();
                }
            }
            return false;
        };

        // Delegated click handler for elements with data-confirm
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('[data-confirm]');
            if (!btn) return;
            const form = btn.closest('form');
            if (!form) return;
            e.preventDefault();

            const text = btn.getAttribute('data-confirm');
            const title = btn.getAttribute('data-confirm-title') || 'Apakah Anda yakin?';
            const icon = btn.getAttribute('data-confirm-icon') || 'warning';
            const confirmText = btn.getAttribute('data-confirm-text') || 'Ya, lanjutkan!';
            const confirmColor = btn.classList.contains('btn-danger') ? '#d33' : '#3085d6';

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: title,
                    text: text,
                    icon: icon,
                    showCancelButton: true,
                    confirmButtonColor: confirmColor,
                    cancelButtonColor: '#aaa',
                    confirmButtonText: confirmText,
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            } else {
                if (confirm(text)) {
                    form.submit();
                }
            }
        });
    })();
    </script>
</body>
</html>

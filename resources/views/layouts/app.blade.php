<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Sistem Peminjaman Asset')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <header>
        <div class="brand">
            <div class="brand-mark">A</div>
            <div>
                <h1>Asset<span>Flow</span></h1>
                <p>Kelola asset dan peminjaman dengan mudah</p>
            </div>
        </div>
        <nav>
            <a class="{{ request()->routeIs('assets.index') ? 'active' : '' }}" href="{{ route('assets.index') }}"><span>▦</span> Asset</a>
            <a class="{{ request()->routeIs('loans.index') ? 'active' : '' }}" href="{{ route('loans.index') }}"><span>↔</span> Peminjaman</a>
        </nav>
    </header>
    <main>
        @yield('content')
    </main>
    <div id="toast" role="status"></div>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>

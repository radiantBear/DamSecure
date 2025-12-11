<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <base href="{{ url('/') . '/' }}">

        <title>{{ $title ? $title . ' | ' : '' }}DamSecure IoT Portal</title>

        <!-- Icons -->
        <link href="https://use.fontawesome.com/releases/v7.1.0/css/all.css" rel="stylesheet" integrity="da9215c2c33304afdc5f491dd34cb7b75eba2f932c3f19c5b872cf7242fd5e24a684d201c1eb0428f88c2cf0a310b8eb" crossorigin="anonymous">

        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
        <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

        {{ $includes ?? '' }}
    </head>
    <body>
        <nav class="navbar navbar-expand-sm bg-body-tertiary">
            <div class="container-xxl">
                <a class="navbar-brand fw-lighter" href="">DamSecure IoT Portal</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a href="{{ url('/docs/api') }}" class="nav-link">API Schema</a></li>
                        @auth
                        <li class="nav-item"><a href="{{ url('/projects') }}" class="nav-link">Projects</a></li>
                        <li class="nav-item"><a href="{{ route('logout') }}" class="nav-link">Log out</a></li>
                        @else
                        <li class="nav-item"><a href="{{ route('login') }}" class="nav-link">Log in</a></li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>

        <main class="container-xxl pt-4">
            {{ $slot }}
        </main>
    </body>
</html>

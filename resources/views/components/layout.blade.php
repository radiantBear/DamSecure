<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <base href="{{ url('/') . '/' }}">

        <title>{{ $title ?? 'Honors Thesis' }}</title>

        <!-- Icons -->
        <link href="https://use.fontawesome.com/releases/v7.1.0/css/all.css" rel="stylesheet" integrity="da9215c2c33304afdc5f491dd34cb7b75eba2f932c3f19c5b872cf7242fd5e24a684d201c1eb0428f88c2cf0a310b8eb" crossorigin="anonymous">

    </head>
    <body>
        <div>
            @if (Route::has('login'))
                <div>
                    @auth
                        <a href="{{ url('/projects') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div>
                {{ $slot }}
            </div>
        </div>
    </body>
</html>

<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
</head>
<body>

<header>
    <p>Q-CHECK</p>
    <form method="POST" action="/logout">
        @csrf
        <button type="submit">Logout</button>
    </form>
</header>

<hr>

<main>
    @yield('content')
</main>

</body>
</html>

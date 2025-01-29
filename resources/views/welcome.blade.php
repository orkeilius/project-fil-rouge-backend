
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>accueil</title>
</head>
<body>
    <h1>Panel admin</h1>
    <a href="{{ route('users.index') }}">
        <button type="button">liste des utilisateurs</button>
    </a>
    <a href="{{ route('auctions.index') }}">
        <button type="button">liste des encheres</button>
    </a>
</body>
</html>

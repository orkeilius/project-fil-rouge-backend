<!-- resources/views/users/index.blade.php -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Liste des utilisateurs</title>
</head>
<body>
    <h1>📋 Liste des utilisateurs 📋</h1>
    <a href="{{ route('users.create') }}">
        <button type="button">➕ Ajouter un nouvel utilisateur</button>
    </a>

    <br><br>

    <ul>
        @foreach ($users as $user)
            <li>
                {{ $user->name }} - {{ $user->email }} 
                <a href="{{ route('users.show', $user->id) }}">Voir</a>
            </li>
        @endforeach
    </ul>
</body>
</html>

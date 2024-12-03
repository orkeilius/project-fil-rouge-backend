<!-- resources/views/users/index.blade.php -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des utilisateurs</title>
</head>
<body>
    <h1>Liste des utilisateurs</h1>
    <a href="{{ route('users.create') }}">Ajouter un nouvel utilisateur</a>
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

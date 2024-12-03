<!-- resources/views/users/show.blade.php -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des utilisateurs</title>
</head>
<body>
    <h1>{{ $user->name }}</h1>
    <p>Email : {{ $user->email }}</p>
    <a href="{{ route('users.index') }}">Retour à la liste</a>
</body>
</html>
<!-- resources/views/users/create.blade.php -->

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
    <h1>Créer un nouvel utilisateur</h1>
    <form action="{{ route('users.store') }}" method="POST">
    @csrf
    <label for="name">Nom</label>
    <input type="text" name="name" id="name" required>
    <label for="email">Email</label>
    <input type="email" name="email" id="email" required>
    <label for="password">Mot de passe</label>
    <input type="password" name="password" id="password" required>
    <label for="password_confirmation">Confirmer le mot de passe</label>
    <input type="password" name="password_confirmation" id="password_confirmation" required>
    <button type="submit">Créer l'utilisateur</button>
    </form>
</body>
</html>

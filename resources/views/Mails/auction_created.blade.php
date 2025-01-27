<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Nouvelle enchère créée</title>
</head>
<body>
    <h1>🎉 Une nouvelle enchère a été créé ! 🎉</h1>
    <p>Nom : {{$auction->name}}</p>
</body>
</html>
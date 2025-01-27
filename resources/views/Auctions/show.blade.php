<!-- resources/views/auctions/show.blade.php -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Détails de la Vente aux Enchères</title>
</head>
<body>
    <h1>Détails de la vente : {{ $auction->name }}</h1>

    <p><strong>Description :</strong> {{ $auction->description }}</p>
    <p><strong>Prix de départ :</strong> {{ $auction->starting_price }} €</p>
    <p><strong>Date de fin :</strong> {{ $auction->end_at }}</p>

    <a href="{{ route('auctions.index') }}">Retour à la liste des ventes</a>
</body>
</html>

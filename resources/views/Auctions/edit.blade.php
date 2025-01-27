<!-- resources/views/auctions/edit.blade.php -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Modifier une vente</title>
</head>
<body>
    <h1>✏️ Modifier la vente : {{ $auction->name }} ✏️</h1>

    <!-- Afficher les erreurs de validation -->
    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('auctions.update', $auction->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label for="name">Nom</label>
        <input type="text" name="name" id="name" value="{{ old('name', $auction->name) }}" required>

        <label for="description">Description</label>
        <textarea name="description" id="description" required>{{ old('description', $auction->description) }}</textarea>

        <label for="starting_price">Prix de départ</label>
        <input type="number" name="starting_price" id="starting_price" value="{{ old('starting_price', $auction->starting_price) }}" required>

        <label for="end_at">Date de fin</label>
        <input type="datetime-local" name="end_at" id="end_at" value="{{ old('end_at', $auction->end_at->format('Y-m-d\TH:i')) }}" required>

        <button type="submit">Mettre à jour</button>
    </form>
    <a href="{{ url('/auctions') }}">
        <button type="button">Retour</button>
    </a>
</body>
</html>

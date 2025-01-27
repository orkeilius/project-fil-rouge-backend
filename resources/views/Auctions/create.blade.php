<!-- resources/views/auctions/create.blade.php -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Créer une Vente aux Enchères</title>
</head>
<body>
    <h1>📦 Créer une nouvelle vente aux enchères 📦</h1>
    <form action="{{ route('auctions.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Nom de la vente</label>
            <input type="text" name="name" id="name" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" required></textarea>
        </div>
        <div class="form-group">
            <label for="starting_price">Prix de départ</label>
            <input type="number" name="starting_price" id="starting_price" required min="0"> €
        </div>
        <div class="form-group">
            <label for="end_at">Date de fin</label>
            <input type="datetime-local" name="end_at" id="end_at" required>
        </div>
        <button type="submit">Créer la vente</button>
    </form>
</body>
</html>

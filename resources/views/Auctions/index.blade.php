<!-- resources/views/auctions/index.blade.php -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Liste des Ventes aux Enchères</title>
</head>
<body>
    <h1>📋 Liste des Ventes aux Enchères 📋</h1>
    
    <a href="{{ route('auctions.create') }}">
        <button type="button">➕ Créer une nouvelle vente</button>
    </a>

    <br><br>
    
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Description</th>
                <th>Prix de départ</th>
                <th>Date de fin</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($auctions as $auction)
                <tr>
                    <td>{{ $auction->name }}</td>
                    <td>{{ $auction->description }}</td>
                    <td>{{ $auction->starting_price }} €</td>
                    <td>{{ $auction->end_at }}</td>
                    <td>
                        <a href="{{ route('auctions.show', $auction->id) }}">Voir</a>
                        <a href="{{ route('auctions.edit', $auction->id) }}">Modifier</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $auctions->appends(['per_page' => request('per_page')])->links() }}

    <!-- Menu déroulant pour le nombre de lignes par page -->
    <form method="GET" action="{{ route('auctions.index') }}">
        <label for="per_page">Afficher par page :</label>
        <select name="per_page" id="per_page" onchange="this.form.submit()">
            <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
        </select>
    </form>
</body>
</html>

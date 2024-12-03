<!-- resources/views/auctions/index.blade.php -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Ventes aux Enchères</title>
</head>
<body>
    <h1>Liste des Ventes aux Enchères</h1>
    
    <a href="{{ route('auctions.create') }}">Créer une nouvelle vente</a>
    
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
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

<!-- resources/views/prices/index.blade.php -->
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comparador de Preus de Proteïna</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6">Comparador de Preus de Proteïna</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($prices as $price)
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-semibold">{{ $price->store }}</h2>
                    <p class="text-gray-700 mt-2">Preu: {{ $price->price }}€</p>
                    <p class="text-gray-700">Descompte: {{ $price->discount }}%</p>
                    <p class="text-sm text-gray-500 mt-4">Actualitzat: {{ $price->created_at->diffForHumans() }}</p>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>
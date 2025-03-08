<?php
// Habilitar CORS para que el frontend pueda comunicarse con el backend
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

// Si es una solicitud OPTIONS (preflight), terminar aquí
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/services/PokemonService.php';

// Analizar la URL solicitada
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

// Comprobar si es la ruta raíz (cuando $uri[1] está vacío)
if (empty($uri[1]) || $uri[1] === '') {
    // Mostrar información de la API en la ruta raíz
    echo json_encode([
        'status' => 'online',
        'message' => 'Pokémon API está funcionando correctamente',
        'endpoints' => [
            '/pokemon/{id}' => 'Obtener Pokémon por ID numérico',
            '/pokemon/{name}' => 'Obtener Pokémon por nombre'
        ]
    ]);
    exit;
}
// Endpoint: /pokemon/{id}
else if ($uri[1] === 'pokemon') {
    $pokemonService = new PokemonService();

    // Si existe un segundo parámetro (ID o nombre)
    if (isset($uri[2])) {
        try {
            $param = $uri[2];
            
            // Determinar si es un ID (número) o un nombre
            if (is_numeric($param)) {
                $pokemon = $pokemonService->getPokemonById($param);
            } else {
                $pokemon = $pokemonService->getPokemonByName($param);
            }
            
            echo json_encode($pokemon);
            
        } catch (Exception $e) {
            // Manejar errores
            http_response_code($e->getCode() ?: 500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        // Si no hay ID o nombre, devolver error 400
        http_response_code(400);
        echo json_encode(['error' => 'Please provide a Pokémon ID or name']);
    }
} else {
    // Ruta no reconocida
    http_response_code(404);
    echo json_encode(['error' => 'Endpoint not found']);
}
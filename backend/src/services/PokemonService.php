<?php
require_once __DIR__ . '/../../vendor/autoload.php';

class PokemonService {
    private $client;
    private $baseUrl = 'https://pokeapi.co/api/v2/';
    private $cache = [];
    private $cacheFile = __DIR__ . '/../cache/pokemon_cache.json';

    public function __construct() {
        $this->client = new GuzzleHttp\Client();
        $this->loadCache();
    }

    private function loadCache() {
        if (file_exists($this->cacheFile)) {
            $this->cache = json_decode(file_get_contents($this->cacheFile), true);
        } else {
            // Asegúrate de que el directorio cache exista
            if (!is_dir(dirname($this->cacheFile))) {
                mkdir(dirname($this->cacheFile), 0755, true);
            }
            $this->cache = [];
            $this->saveCache();
        }
    }

    private function saveCache() {
        file_put_contents($this->cacheFile, json_encode($this->cache));
    }

    public function getPokemonById($id) {
        // Verificar si está en caché
        $cacheKey = "pokemon_$id";
        if (isset($this->cache[$cacheKey])) {
            return $this->cache[$cacheKey];
        }

        try {
            // Si no está en caché, hacer la solicitud a la API
            $response = $this->client->get("{$this->baseUrl}pokemon/$id");
            $data = json_decode($response->getBody(), true);

            // Formatear la respuesta según el formato requerido
            $formattedData = [
                'id' => $data['id'],
                'name' => $data['name'],
                'types' => array_map(function($type) {
                    return $type['type']['name'];
                }, $data['types']),
                'abilities' => array_map(function($ability) {
                    return $ability['ability']['name'];
                }, $data['abilities']),
                'sprite_url' => $data['sprites']['front_default']
            ];

            // Guardar en caché
            $this->cache[$cacheKey] = $formattedData;
            $this->saveCache();

            return $formattedData;
        } catch (GuzzleHttp\Exception\ClientException $e) {
            if ($e->getResponse()->getStatusCode() === 404) {
                throw new Exception("ERROR 404 Pokémon not found", 404);
            }
            throw $e;
        }
    }

    public function getPokemonByName($name) {
        // Convertir nombre a minúsculas para la API
        $name = strtolower($name);
        
        // Verificar si está en caché
        $cacheKey = "pokemon_name_$name";
        if (isset($this->cache[$cacheKey])) {
            return $this->cache[$cacheKey];
        }

        try {
            // Si no está en caché, hacer la solicitud a la API
            $response = $this->client->get("{$this->baseUrl}pokemon/$name");
            $data = json_decode($response->getBody(), true);

            // Formatear la respuesta según el formato requerido
            $formattedData = [
                'id' => $data['id'],
                'name' => $data['name'],
                'types' => array_map(function($type) {
                    return $type['type']['name'];
                }, $data['types']),
                'abilities' => array_map(function($ability) {
                    return $ability['ability']['name'];
                }, $data['abilities']),
                'sprite_url' => $data['sprites']['front_default']
            ];

            // Guardar en caché
            $this->cache[$cacheKey] = $formattedData;
            $this->cache["pokemon_{$data['id']}"] = $formattedData; // También guardamos por ID
            $this->saveCache();

            return $formattedData;
        } catch (GuzzleHttp\Exception\ClientException $e) {
            if ($e->getResponse()->getStatusCode() === 404) {
                throw new Exception("Error 404 Pokémon not found", 404);
            }
            throw $e;
        }
    }
}
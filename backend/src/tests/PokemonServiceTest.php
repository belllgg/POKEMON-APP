<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../src/services/PokemonService.php';

class PokemonServiceTest extends TestCase {
    private $pokemonService;

    protected function setUp(): void {
        $this->pokemonService = new PokemonService();
    }

    public function testGetPokemonById() {
        // Probar con un Pokémon conocido (Pikachu - ID 25)
        $pokemon = $this->pokemonService->getPokemonById(25);
        
        // Verificar estructura y datos básicos
        $this->assertIsArray($pokemon);
        $this->assertEquals(25, $pokemon['id']);
        $this->assertEquals('pikachu', $pokemon['name']);
        $this->assertIsArray($pokemon['types']);
        $this->assertIsArray($pokemon['abilities']);
        $this->assertNotEmpty($pokemon['sprite_url']);
    }

    public function testGetPokemonByIdNotFound() {
        // Probar con un ID que no debería existir (muy alto)
        $this->expectException(Exception::class);
        $this->expectExceptionCode(404);
        $this->pokemonService->getPokemonById(100000);
    }

    public function testGetPokemonByName() {
        // Probar búsqueda por nombre
        $pokemon = $this->pokemonService->getPokemonByName('pikachu');
        
        $this->assertIsArray($pokemon);
        $this->assertEquals(25, $pokemon['id']);
        $this->assertEquals('pikachu', $pokemon['name']);
    }
}
<?php
include_once "jsonstorage.php";

class Pokemon
{
    public $_id = null;
    public $name;
    public $type;
    public $hp;
    public $attack;
    public $defense;
    public $price;
    public $description;
    public $image;

    public function __construct($name = null, $type= null, $hp = null, $attack = null, $defense = null, $price = null, $description = null, $image = null)
    {
        $this->name = $name;
        $this->type = $type;
        $this->hp = $hp;
        $this->attack = $attack;
        $this->defense = $defense;
        $this->price = $price;
        $this->description = $description;
        $this->image = $image;
    }

    public static function from_array(array $arr): Pokemon
    {
        $instance = new Pokemon();
        $instance->_id = $arr['_id'] ?? null;
        $instance->name = $arr['name'] ?? null;
        $instance->type = $arr['type'] ?? null;
        $instance->hp = $arr['hp'] ?? null;
        $instance->attack = $arr['attack'] ?? null;
        $instance->defense = $arr['defense'] ?? null;
        $instance->price = $arr['price'] ?? null;
        $instance->description = $arr['description'] ?? null;
        $instance->image = $arr['image'] ?? null;
        return $instance;
    }

    public static function from_object(object $obj): Pokemon
    {
        return self::from_array((array) $obj);
    }
}

class PokemonRepository
{
    private $storage;
    public function __construct()
    {
        $this->storage = new JsonStorage('data/pokemon.json');
    }
    private function convert(array $arr): array
    {
        return array_map([Pokemon::class, 'from_object'], $arr);
    }
    public function all()
    {
        return $this->convert($this->storage->all());
    }
    public function add(Pokemon $pokemon,string $newid): string
    {
        return $this->storage->insert($pokemon,$newid);
    }
    public function getPokemonsByType(string $type = null): array
    {
        return $this->convert($this->storage->filter(function ($pokemon) use ($type) {
            return $pokemon->type === $type;
        }));
    }
    public function updateName(Pokemon $pokemon, string $newName)
    {
        $this->storage->update(
            function ($existingPokemon) use ($pokemon) {
                return $existingPokemon->_id === $pokemon->_id;
            },
            function (&$existingPokemon) use ($newName) {
                $existingPokemon->name = $newName;
            }
        );
    }
    public function updateType(Pokemon $pokemon, string $newType)
    {
        $this->storage->update(
            function ($existingPokemon) use ($pokemon) {
                return $existingPokemon->_id === $pokemon->_id;
            },
            function (&$existingPokemon) use ($newType) {
                $existingPokemon->type = $newType;
            }
        );
    }
    public function updateHP(Pokemon $pokemon, int $newHP)
    {
        $this->storage->update(
            function ($existingPokemon) use ($pokemon) {
                return $existingPokemon->_id === $pokemon->_id;
            },
            function (&$existingPokemon) use ($newHP) {
                $existingPokemon->hp = $newHP;
            }
        );
    }
    public function updateAttack(Pokemon $pokemon, int $newAttack)
    {
        $this->storage->update(
            function ($existingPokemon) use ($pokemon) {
                return $existingPokemon->_id === $pokemon->_id;
            },
            function (&$existingPokemon) use ($newAttack) {
                $existingPokemon->attack = $newAttack;
            }
        );
    }
    public function updateDefense(Pokemon $pokemon, int $newDefense)
    {
        $this->storage->update(
            function ($existingPokemon) use ($pokemon) {
                return $existingPokemon->_id === $pokemon->_id;
            },
            function (&$existingPokemon) use ($newDefense) {
                $existingPokemon->defense = $newDefense;
            }
        );
    }
    public function updatePrice(Pokemon $pokemon, int $newPrice)
    {
        $this->storage->update(
            function ($existingPokemon) use ($pokemon) {
                return $existingPokemon->_id === $pokemon->_id;
            },
            function (&$existingPokemon) use ($newPrice) {
                $existingPokemon->price = $newPrice;
            }
        );
    }
    public function updateImage(Pokemon $pokemon, string $newImage)
    {
        $this->storage->update(
            function ($existingPokemon) use ($pokemon) {
                return $existingPokemon->_id === $pokemon->_id;
            },
            function (&$existingPokemon) use ($newImage) {
                $existingPokemon->image = $newImage;
            }
        );
    }
    public function updateDesc(Pokemon $pokemon, string $newDesc)
    {
        $this->storage->update(
            function ($existingPokemon) use ($pokemon) {
                return $existingPokemon->_id === $pokemon->_id;
            },
            function (&$existingPokemon) use ($newDesc) {
                $existingPokemon->description = $newDesc;
            }
        );
    }
}

?>
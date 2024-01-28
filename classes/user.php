<?php 
include_once "jsonstorage.php";

class User{
    public $_id = null;
    public $email;
    public $password;
    public $money;
    public $cards;
    public function __construct($email = null,$password=null,$money=null,$cards=null){
        $this->email = $email;
        $this->password = $password;
        $this->money = $money;
        $this->cards = $cards;
    }
    public static function from_array(array $arr): User
    {
        $instance = new User();
        $instance->_id = $arr['_id'] ?? null;
        $instance->email = $arr['email'] ?? null;
        $instance->password = $arr['password'] ?? null;
        $instance->money = $arr['money'] ?? null;
        $instance->cards = $arr['cards'] ?? null;
        return $instance;
    }
    public static function from_object(object $obj): User
    {
        return self::from_array((array) $obj);
    }
}
class UserRepository
{
    private $storage;
    public function __construct()
    {
        $this->storage = new JsonStorage('data/users.json');
    }
    private function convert(array $arr): array
    {
        return array_map([User::class, 'from_object'], $arr);
    }
    public function all()
    {
        return $this->convert($this->storage->all());
    }
    public function add(User $user, string $id): string
    {
        return $this->storage->insert($user,$id);
    }
    public function getUserByEmail(string $email = null): array
    {
        return $this->convert($this->storage->filter(function ($user) use ($email) {
            return $user->email === $email;
        }));
    }
    public function getUserByID(string $_id = null): array
    {
        return $this->convert($this->storage->filter(function ($user) use ($_id) {
            return $user->_id === $_id;
        }));
    }
    public function checkEmail(User $user){
        return filter_var($user->email,FILTER_VALIDATE_EMAIL);
    }
    public function updateCards(User $user, array $newCards)
    {
        $this->storage->update(
            function ($existingUser) use ($user) {
                return $existingUser->_id === $user->_id;
            },
            function (&$existingUser) use ($newCards) {
                $existingUser->cards = $newCards;
            }
        );
    }
    public function updateMoney(User $user, int $income)
    {
        $this->storage->update(
            function ($existingUser) use ($user) {
                return $existingUser->_id === $user->_id;
            },
            function (&$existingUser) use ($income) {
                $existingUser->money = $income;
            }
        );
    }
}
?>
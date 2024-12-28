<?php
namespace App\Models;

use System\Model;

class Character extends Model
{
    public function __construct(
        public int|null $id,        // ID в системе
        public string $name,        // Имя
        public string $description, // Описание (анкета)
        public User $owner,         // Человек, который может писать от лица этого персонажа
        public array $decoration    // Оформление

    ) {
    }
    public function save()
    {
        if ($this->id === null) {
            $query = self::dbQuery("INSERT INTO characters VALUES(NULL, ?, ?, ?, ?) RETURNING id", [
                $this->name,
                $this->owner->username,
                $this->description,
                json_encode($this->decoration, JSON_UNESCAPED_UNICODE)
            ]);
            $this->id = $query->fetch(\PDO::FETCH_NUM)[0];
        } else {
            self::dbQuery("UPDATE characters SET name=?, owner=?, description=?, decoration=?", [
                $this->name,
                $this->owner->username,
                $this->description,
                json_encode($this->decoration, JSON_UNESCAPED_UNICODE)
            ]);
        }
    }
    protected static function convertIntoInstances(\PDOStatement $st)
    {
        $r = [];
        while ($ch = $st->fetch(\PDO::FETCH_ASSOC)) {
            $r[] = new self($ch["id"], $ch["name"], $ch["description"], User::getByUsername($ch["owner"]), json_decode($ch["decoration"], true));
        }
        return $r;
    }
    public static function getByUser(User $user)
    {
        $query = self::dbQuery("SELECT * FROM characters WHERE owner=?", [$user->username]);
        return self::convertIntoInstances($query);
    }
    public static function getAll()
    {
        $query = self::dbQuery("SELECT * FROM characters ORDER BY name");
        return self::convertIntoInstances($query);
    }
    public static function getById(int $id)
    {
        $query = self::dbQuery("SELECT * FROM characters WHERE id=?", [$id]);
        $dt = $query->fetch(\PDO::FETCH_ASSOC);
        if ($dt) {
            return new self($dt["id"],
             $dt["name"],
             $dt["description"], 
             User::getByUsername($dt["owner"]), 
             json_decode($dt["decoration"], true));
        } else return false;
    }
}
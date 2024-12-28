<?php
namespace App\Models;

use System\Model;

class User extends Model {
    public function __construct(
        public string $username,
        public string $password,
        public string $name,
        public int $birthday,
        public array $roles = ["user"],
        public int|null $registered_at = null,
        protected bool $inDb=false
    ) {
        if(!$inDb) $this->password = password_hash($password, PASSWORD_DEFAULT);
        if(!$registered_at) $this->registered_at = time();
    }
    public function save(): bool {
        if(!$this->inDb) {
            try {
                self::dbQuery("INSERT INTO users VALUES(?, ?, ?, ?, ?, ?)",[
                    $this->username,
                    $this->password,
                    $this->name,
                    $this->birthday,
                    join(",", $this->roles),
                    $this->registered_at,
                ]);
            } catch(\PDOException $e) {
                return false;
            }
        } else {
            self::dbQuery("UPDATE users SET password=?, name=?, birthday=?, roles=? WHERE username=?",[
                $this->password,
                $this->name,
                $this->birthday,
                join(",", $this->roles),
                $this->username
            ]);
        }
        return true;
    }
    public function isAdmin() {
        return in_array("admin", $this->roles);
    }
    public static function getByUsername(string $username): bool|User {
        $s = self::dbQuery("SELECT * FROM users WHERE username=?", [$username])->fetch();
        if($s) {
            return new self(
                username: $s["username"],
                password: $s["password"],
                name: $s["name"],
                birthday: $s["birthday"],
                roles: explode(",", $s["roles"]),
                registered_at: $s["registered_at"],
                inDb: true
            );
        } else return false;
    }
}
<?php
namespace App\Models;
use System\Model;

class Session extends Model {
    public function __construct(
        public string|null $token,
        public User $user,
        public int|null $expires=null,
        protected bool $inDb=false
    ) {
        if(!$token) $this->token = bin2hex(random_bytes(16));
        if(!$expires) $this->expires = strtotime("+1 week");
    }
    public function save() {
        if(!$this->inDb) {
            self::dbQuery("INSERT INTO sessions VALUES(?, ?, ?)",[$this->token, $this->user->username, $this->expires]);
            $this->inDb = true;
        } else {
            self::dbQuery("UPDATE sessions SET expires=? WHERE token=?",[$this->expires, $this->token]);
        }
    }
    public static function deleteByToken($token) {
        self::dbQuery("DELETE FROM sessions WHERE token=?",[$token]);
    }
    public function delete() {
        self::deleteByToken($this->token);
        $this->inDb = false;
    }
    public static function getByToken(string $token): bool|Session {
        $s = self::dbQuery("SELECT * FROM sessions WHERE token=?", [$token])->fetch();
        if($s) {
            $user = User::getByUsername($s["user"]);
            if(!$user) {
                self::deleteByToken($token);
                return false;
            }
            return new self(
                token: $token,
                user: $user,
                expires: $s["expires"],
                inDb: true
            );
        } else return false;
    }
}
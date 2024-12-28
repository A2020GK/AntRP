<?php
namespace App\Models;

use System\Model;

class Message extends Model
{
    public function __construct(
        public int|null $id,                      // ID в системе
        public User $sender,                      // Отправитель
        public string $text,                      // Неиллюзорно: текст (удивительно)
        public bool $edited=false,                // Редактировали ли сообщения (yes/no)
        public int|null $sent_at = null,          // Время отправки сообщения
        public Message|null $replyTo=null,        // Сообщение, на которое данное, возможно, является оветом
        public Character|null $fromCharacter=null // Персонаж, от лица которого написанно сообщение
    ){
        if(!$sent_at) $this->sent_at = time();
    }
    public function save() {
        if($this->id===null) {
            $stmt = self::dbQuery("INSERT INTO messages VALUES(NULL, ?, ?, ?, ?, ?, ?) RETURNING id",[
                $this->sender->username,
                $this->text,
                $this->edited ? "yes":"no",
                $this->sent_at,
                $this->replyTo->id ?? null,
                $this->fromCharacter->id ?? null
            ]);
            $this->id=$stmt->fetch(\PDO::FETCH_NUM)[0];
        } else {
            self::dbQuery("UPDATE messages SET text=?, edited=? WHERE id=?",[$this->text,
            $this->edited?"yes":"no",
            $this->id]);
        }
    }
}
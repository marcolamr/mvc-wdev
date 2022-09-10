<?php

namespace App\Model\Entity;

use PDOStatement;
use WilliamCosta\DatabaseManager\Database;

class User
{
    /** @var int $id */
    public $id;

    /** @var string */
    public $nome;

    /** @var string */
    public $email;

    /** @var string */
    public $senha;

    public function cadastrar(): bool
    {
        $this->id = (new Database("usuarios"))->insert([
            "nome" => $this->nome,
            "email" => $this->email,
            "senha" => $this->senha
        ]);

        return true;
    }

    public function atualizar(): bool
    {
        return (new Database("usuarios"))->update("id = {$this->id}", [
            "nome" => $this->nome,
            "email" => $this->email,
            "senha" => $this->senha
        ]);
    }

    public function excluir(): bool
    {
        return (new Database("usuarios"))->delete("id = {$this->id}");
    }

    /**
     * Retorna um usuário com base em seu e-mail
     *
     * @param string $email
     * @return 
     */
    public static function getUserByEmail(string $email)
    {
        return (new Database("usuarios"))->select("email = '" . $email . "'")->fetchObject(self::class);
    }

    public static function getUserById(int $id)
    {
        return self::getUsers("id = {$id}")->fetchObject(self::class);
    }

    /**
     * Retorna Usuários
     *
     * @param string|null $where
     * @param string|null $order
     * @param string|null $limit
     * @param string $fields
     * @return PDOStatement
     */
    public static function getUsers(?string $where = null, ?string $order = null, ?string $limit = null, string $fields = "*")
    {
        return (new Database("usuarios"))->select($where, $order, $limit, $fields);
    }
}

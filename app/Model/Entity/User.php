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
}

<?php

namespace App\Model\Entity;

use PDOStatement;
use WilliamCosta\DatabaseManager\Database;

class Testimony
{
    /** @var int */
    public $id;

    /** @var string */
    public $nome;

    /** @var string */
    public $mensagem;

    /** @var string */
    public $data;

    /**
     * Cadastra a instância atual no banco de dados
     *
     * @return boolean
     */
    public function cadastrar(): bool
    {
        $this->data = date("Y-m-d H:i:s");
        $this->id = (new Database("depoimentos"))->insert([
            "nome" => $this->nome,
            "mensagem" => $this->mensagem,
            "data" => $this->data
        ]);

        return true;
    }

    /**
     * Retorna Depoimentos
     *
     * @param string|null $where
     * @param string|null $order
     * @param string|null $limit
     * @param string $fields
     * @return PDOStatement
     */
    public static function getTestimonies(?string $where = null, ?string $order = null, ?string $limit = null, string $fields = "*")
    {
        return (new Database("depoimentos"))->select($where, $order, $limit, $fields);
    }
}

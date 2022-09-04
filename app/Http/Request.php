<?php

namespace App\Http;

class Request
{
    /** @var string */
    private $httpMethod;

    /** @var string */
    private $uri;

    /** @var array */
    private $queryParams = [];

    /** @var array */
    private $postVars = [];

    /** @var array */
    private $headers = [];

    /**
     * Construtor da classe
     */
    public function __construct()
    {
        $this->queryParams = $_GET ?? [];
        $this->postVars = $_POST ?? [];
        $this->headers = getallheaders();
        $this->httpMethod = $_SERVER["REQUEST_METHOD"] ?? "";
        $this->uri = $_SERVER["REQUEST_URI"] ?? "";
    }

    /**
     * Retorna o método HTTP da requisição
     *
     * @return string
     */
    public function getHttpMethod(): string
    {
        return $this->httpMethod;
    }

    /**
     * Retorna a URI da requisição
     *
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * Retorna os headers da requisição
     *
     * @return string
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Retorna os parâmetros da requisição
     *
     * @return string
     */
    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    /**
     * Retorna as variáveis POST da requisição
     *
     * @return string
     */
    public function getPostVars(): array
    {
        return $this->postVars;
    }
}

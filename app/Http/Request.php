<?php

namespace App\Http;

class Request
{
    /** @var Router */
    private $router;

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
    public function __construct(Router $router)
    {
        $this->router = $router;
        $this->queryParams = $_GET ?? [];
        $this->headers = getallheaders();
        $this->httpMethod = $_SERVER["REQUEST_METHOD"] ?? "";
        $this->setUri();
        $this->setPostVars();
    }

    private function setPostVars()
    {
        if ($this->httpMethod == "GET") return false;

        $this->postVars = $_POST ?? [];

        $inputRaw = file_get_contents("php://input");
        $this->postVars = (strlen($inputRaw) && empty($_POST)) ? json_decode($inputRaw, true) : $this->postVars;
    }

    /**
     * Retorna instância de Router
     *
     * @return Router
     */
    public function getRouter(): Router
    {
        return $this->router;
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

    private function setUri()
    {
        $this->uri = $_SERVER["REQUEST_URI"] ?? "";

        $xUri = explode("?", $this->uri);
        $this->uri = $xUri[0];
    }
}

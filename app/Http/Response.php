<?php

namespace App\Http;

class Response
{
    /** @var int */
    private $httpCode = 200;

    /** @var array */
    private $headers = [];

    /** @var string */
    private $contentType = "text/html";

    /** @var mixed */
    private $content;

    /**
     * Construtor da classe
     *
     * @param integer $httpCode
     * @param mixed $content
     * @param string $contentType
     */
    public function __construct(int $httpCode, mixed $content, string $contentType = "text/html")
    {
        $this->httpCode = $httpCode;
        $this->content = $content;
        $this->setContentType($contentType);
    }

    /**
     * Altera o content type do response
     *
     * @param string $contentType
     * @return void
     */
    public function setContentType(string $contentType): void
    {
        $this->contentType = $contentType;
        $this->addHeader("Content-Type", $contentType);
    }

    /**
     * Adiciona um registro no header do response
     *
     * @param string $key
     * @param string $value
     * @return void
     */
    public function addHeader(string $key, string $value): void
    {
        $this->headers[$key] = $value;
    }

    /**
     * Envia a resposta para o cliente
     *
     * @return void
     */
    public function sendResponse(): void
    {
        $this->sendHeaders();

        switch ($this->contentType) {
            case "text/html":
                echo $this->content;
                exit;
        }
    }

    /**
     * Envia os headers para o cliente
     *
     * @return void
     */
    private function sendHeaders(): void
    {
        http_response_code($this->httpCode);

        foreach ($this->headers as $key => $value) {
            header($key . ": " . $value);
        }
    }
}

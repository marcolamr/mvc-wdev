<?php

namespace App\Http\Middleware;

use App\Http\Request;
use App\Http\Response;
use Closure;
use Exception;

class Queue
{
    /** @var array */
    private static $map = [];

    /** @var array */
    private static $default = [];

    /** @var array */
    private $middlewares = [];

    /** @var Closure */
    private $controller;

    /** @var array */
    private $controllerArgs = [];

    /**
     * Construtor da classe
     *
     * @param array $middlewares
     * @param Closure $controller
     * @param array $controllerArgs
     */
    public function __construct(array $middlewares, Closure $controller, array $controllerArgs)
    {
        $this->middlewares = array_merge(self::$default, $middlewares);
        $this->controller = $controller;
        $this->controllerArgs = $controllerArgs;
    }

    /**
     * Executa o próximo nível da fila de middlewares
     *
     * @param Request $request
     * @return Response
     */
    public function next(Request $request): Response
    {
        if (empty($this->middlewares)) return call_user_func_array($this->controller, $this->controllerArgs);

        $middleware = array_shift($this->middlewares);

        if (!isset(self::$map[$middleware])) {
            throw new Exception("Problemas ao processar o middleware da requisição", 500);
        }

        $queue = $this;
        $next = function (Request $request) use ($queue) {
            return $queue->next($request);
        };

        return (new self::$map[$middleware])->handle($request, $next);
    }

    /**
     * Define o mapeamento de middlewares
     * 
     * @param array $map
     * @return void
     */
    public static function setMap(array $map): void
    {
        self::$map = $map;
    }

    /**
     * Define o mapeamento de middlewares padrões
     * 
     * @param array $default
     * @return void
     */
    public static function setDefault(array $default): void
    {
        self::$default = $default;
    }
}

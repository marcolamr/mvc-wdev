<?php

namespace App\Utils;

class View
{
    /** @var array */
    private static $vars = [];

    /**
     * Define os dados iniciais da classe
     *
     * @param array $vars
     * @return void
     */
    public static function init(array $vars = []): void
    {
        self::$vars = $vars;
    }

    /**
     * Retorna o conteúdo renderizado de uma view
     *
     * @param string $view
     * @param array $vars
     * @return string
     */
    public static function render(string $view, array $vars = []): string
    {
        $contentView = self::getContentView($view);

        $vars = array_merge(self::$vars, $vars);

        $keys = array_keys($vars);
        $keys = array_map(function ($item) {
            return "{{" . $item . "}}";
        }, $keys);

        return str_replace($keys, array_values($vars), $contentView);
    }

    /**
     * Retorna o conteúdo de uma view
     *
     * @param string $view
     * @return string
     */
    private static function getContentView(string $view): string
    {
        $file = __DIR__ . "/../../resources/view/" . $view . ".html";
        return file_exists($file) ? file_get_contents($file) : "";
    }
}

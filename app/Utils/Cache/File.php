<?php

namespace App\Utils\Cache;

use Closure;

class File
{
    public static function getCache(string $hash, int $expiration, Closure $function)
    {
        if ($content = self::getContentCache($hash, $expiration)) {
            return $content;
        }

        $content = $function();

        self::storageCache($hash, $content);

        return $content;
    }

    private static function getContentCache(string $hash, $expiration)
    {
        $cacheFile = self::getFilePath($hash);

        if (!file_exists($cacheFile)) {
            return false;
        }

        $createTime = filemtime($cacheFile);
        $diffTime = time() - $createTime;

        if ($diffTime > $expiration) {
            unlink($cacheFile);
            return false;
        }

        $serialized = file_get_contents($cacheFile);

        return unserialize($serialized);
    }

    private static function storageCache(string $hash, $content): bool
    {
        $serialized = serialize($content);

        $cacheFile = self::getFilePath($hash);

        return file_put_contents($cacheFile, $serialized);
    }

    private static function getFilePath(string $hash): string
    {
        $dir = getenv("CACHE_DIR");

        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        return "{$dir}/{$hash}";
    }
}

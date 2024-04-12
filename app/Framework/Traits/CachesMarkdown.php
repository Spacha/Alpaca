<?php

namespace App\Framework\Traits;

use App\Framework\Libs\Markdown;

/**
 * Gives a model an ability to store and retrieve markdown content
 * that is compiled to HTML after each update.
 */
trait CachesMarkdown
{
    protected function updateMarkdownCache(int $id, string $markdown = '') : bool
    {
        return self::writeCache( $this->cachePath($id), Markdown::toHtml($markdown) );
    }

    protected function getMarkdownCache(int $id) : string
    {
        $cached = self::readCache( $this->cachePath($id) );

        // cached version found, return it
        if ($cached)
            return $cached;

        // no cached version found, create one on the fly?
        return "<i>Failed retrieving a cache. Sorry :(</i>";
    }

    /**
     * Delete specified cache.
     */
    protected function deleteMarkdownCache(int $id) : bool
    {
        $cachePath = $this->cachePath($id);

        // check if the cache subfolder exists - if not, everything's fine
        if (!file_exists(dirname($cachePath)))
            return true;

        return unlink($cachePath);
    }

    /**
     * Write a cache to the file system.
     *
     * @param string $cachePath Cache file name relative to cache folder.
     * @param string $content   Content to be cached.
     */
    private static function writeCache(string $cachePath, string $content) : bool
    {
        // check if the cache subfolder exists - if not, create it
        if (!file_exists(dirname($cachePath))) {
            mkdir(dirname($cachePath), 0777, true);
        }

        $file = @fopen($cachePath, 'w');

        // failed to open/create file, notify
        if (!$file) {
            echo "<b>Warning:</b> Cache file '{$cachePath}' is unwritable, check permissions of the cache folder.";

            return false;
        } else {
            fwrite($file, $content);
            fclose($file);

            return true;
        }
    }

    /**
     * Write a new line to specified log.
     *
     * @param string $cachePath Cache file name relative to cache folder.
     */
    private static function readCache(string $cachePath) : string
    {
        $content = @file_get_contents($cachePath);

        if (!$content) {
            // todo: update cache and return it
            return "";
        }

        return $content;
    }

    private function cachePath(int $id) : string
    {
        return app_path('cache', "$this->cacheName/{$id}.html");
    }
}

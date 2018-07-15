<?php

namespace BBCMS\Models;

class Theme
{
    /**
     * Creates a tree-structured array of directories and files from a given root folder.
     *
     * Cleaned from: http://stackoverflow.com/questions/952263/deep-recursive-array-of-directory-structure-in-php
     *
     * @param string $path
     * @param string $regex
     * @param boolean $ignoreEmpty Do not add empty directories to the tree
     * @return array
     */
    public static function getTemplates($path = null, $regex = null)
    {
        $dirs = [];
        $files = [];
        $path = $path ?? theme_path('views');
        $regex = $regex ?? '/\.(tpl|ini|css|js|blade\.php)/';

        if (! $path instanceof \DirectoryIterator) {
            $path = new \DirectoryIterator((string) $path);
        }

        foreach ($path as $node) {
            if ($node->isDir() and ! $node->isDot()) {
                if (count($tree = self::getTemplates($node->getPathname(), $regex))) {
                    $dirs[$node->getFilename()] = $tree;
                }
            } elseif ($node->isFile()) {
                if (is_null($regex) or preg_match($regex, $name = $node->getFilename())) {
                    $data_path = str_replace(theme_path('views'), '', $node->getPathname());
                    $files[$data_path] = $name;
                }
            }
        }
        // asort($dirs); sort($files);

        return array_merge($dirs, $files);
    }
}

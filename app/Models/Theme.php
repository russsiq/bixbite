<?php

namespace BBCMS\Models;

use BBCMS\Models\BaseModel;
use BBCMS\Models\Setting;

class Theme // extends BaseModel
{
    /**
     * Creates a tree-structured array of directories and files from a given root folder.
     *
     * Gleaned from: http://stackoverflow.com/questions/952263/deep-recursive-array-of-directory-structure-in-php
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

// $path = theme_path('views');
// $iterator = new \RecursiveIteratorIterator(
//     new \RecursiveDirectoryIterator($path,
//         \RecursiveDirectoryIterator::SKIP_DOTS),
//     \RecursiveIteratorIterator::SELF_FIRST);
//
// // $file_tree = [];
// // foreach($iterator as $object) {
// //     if ($object->isDir()) {
// //         // array_set($file_tree[],
// //         //     str_replace([$path, DS], ['', '.'], $object->getPathname()),
// //         //     select_file($object->getPathname(), '*.blade.php')
// //         // );
// //         // $file_tree[] = [
// //         //     str_replace([$path, DS], ['', '.'], $object->getPathname())
// //         //     =>
// //         //     select_file($object->getPathname(), '*.blade.php')
// //         // ];
// //
// //         // $files = [];
// //         // $dir = new \DirectoryIterator($object->getPathname());
// //         // foreach ($dir as $file) {
// //         //     if ($file->isFile()) {
// //         //         $files[$file->getFilename()] = $file->getFilename();
// //         //     }
// //         // }
// //         // $file_tree[] = [$object->getPathname() => $files];
// //
// //         $file_tree[] = [$object->getPathname() => \File::allFiles($object->getPathname())];
// //     }
// // }
// // $file_tree[] = [$path => \File::allFiles($path)]; //  select_file($path, '*.blade.php')
// dd(collect  ($file_tree)->collapse());
// dd(array_collapse ($file_tree));

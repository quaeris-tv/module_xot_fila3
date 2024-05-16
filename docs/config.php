<?php

declare(strict_types=1);

use Illuminate\Support\Str;

$moduleName = 'Xot';

return [
    'baseUrl' => '',
    'production' => false,
    'siteName' => 'Modulo '.$moduleName,
    'siteDescription' => 'Modulo '.$moduleName,
    'lang' => 'it',

    'collections' => [
        'posts' => [
            'path' => static function ($page) {
                // return $page->lang.'/posts/'.Str::slug($page->getFilename());
                // return 'posts/' . ($page->featured ? 'featured/' : '') . Str::slug($page->getFilename());

                return 'posts/'.Str::slug($page->getFilename());
            },
        ],
        'docs' => [
            'path' => static function ($page) {
                // return $page->lang.'/docs/'.Str::slug($page->getFilename());
                return 'docs/'.Str::slug($page->getFilename());
            },
        ],
    ],

    // Algolia DocSearch credentials
    'docsearchApiKey' => env('DOCSEARCH_KEY'),
    'docsearchIndexName' => env('DOCSEARCH_INDEX'),

    // navigation menu
    'navigation' => include_once 'navigation.php',

    // helpers
    'isActive' => static function ($page, $path) {
        return Str::endsWith(trimPath($page->getPath()), trimPath($path));
    },
    'isItemActive' => static function ($page, $item) {
        return Str::endsWith(trimPath($page->getPath()), trimPath($item->getPath()));
    },
    'isActiveParent' => static function ($page, $menuItem) {
        if (is_object($menuItem) && $menuItem->children) {
            return $menuItem->children->contains(
                static function ($child) use ($page) {
                    return trimPath($page->getPath()) === trimPath($child);
                }
            );
        }
    }, /*
    'url' => function ($page, $path) {
        return Str::startsWith($path, 'http') ? $path : '/' . trimPath($path);
    },
    */
    'url' => static function ($page, $path) {
        if (Str::startsWith($path, 'http')) {
            return $path;
        }

        // return url('/'.$page->lang.'/'.trimPath($path));
        return url('/'.trimPath($path));
    },

    'children' => static function ($page, $docs) {
        return $docs->where('parent_id', $page->id);
    },
];

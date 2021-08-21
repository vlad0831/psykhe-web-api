<?php

namespace App\Services\Psykhe;

use App\Services\Psykhe\Models\Catalog\Brand;
use App\Services\Psykhe\Models\Catalog\Category;
use App\Services\Psykhe\Models\Catalog\Menu;

class PsykheCatalogService implements Contracts\PsykheCatalogService
{
    protected static string $prefix = '/api/v3/catalog/';

    protected Contracts\PsykheService $psykhe;

    public function __construct(Contracts\PsykheService $psykhe)
    {
        $this->psykhe = $psykhe;
    }

    public function Brands($brand_identifier = null): iterable
    {
        if ($brand_identifier == null) {
            $next = static::$prefix.'brands?limit=2000';
        } else {
            $next = static::$prefix.'brands/'.$brand_identifier;
        }

        while (true) {
            $response = $this->psykhe->Request('GET', $next);
            $results  = [];

            if ($brand_identifier == null) {
                $results = $response->results;
            } else {
                $results = [$response];
            }
            foreach ($results as $brand) {
                $o             = new Brand();
                $o->identifier = $brand->slug;
                $o->name       = $brand->name;

                yield $o;
            }

            if (isset($response->next)) {
                if (strpos($response->next, '://') !== false) {
                    $next = $response->next;
                } else {
                    $next = static::$prefix.'brands?'.$response->next;
                }

                continue;
            }
            break;
        }
    }

    public function Categories(): iterable
    {
        $next = static::$prefix.'categories?limit=2000';

        while (true) {
            $response = $this->psykhe->Request('GET', $next);

            foreach ($response->results as $category) {
                if (! $category->slug) {
                    continue;
                }

                $o                         = new Category();
                $o->path                   = explode('/', $category->slug);
                $o->in_parent              = (bool) $category->in_parent;
                $o->description_no_login   = (string) $category->description_no_login;
                $o->description_with_login = (string) $category->description_with_login;
                $o->heading_no_login       = (string) $category->heading_no_login;
                $o->heading_with_login     = (string) $category->heading_with_login;

                yield $o;
            }

            if (isset($response->next)) {
                if (strpos($response->next, '://') !== false) {
                    $next = $response->next;
                } else {
                    $next = static::$prefix.'categories?'.$response->next;
                }

                continue;
            }
            break;
        }
    }

    protected function descendMenu($submenus, $parents = null): iterable
    {
        foreach ($submenus as $menu) {
            $path = array_merge($parents ? $parents : [], [$menu->name]);

            $o             = new Menu();
            $o->name       = $menu->name;
            $o->path       = $path;
            $o->level      = $menu->level;
            $o->sort_order = $menu->sort_order;
            $o->category   = $menu->category ?? null;

            yield $o;

            if (isset($menu->submenus)) {
                yield from $this->descendMenu($menu->submenus, $path);
            }
        }
    }

    public function Menu(): iterable
    {
        $next = static::$prefix.'menus';

        while (true) {
            $response = $this->psykhe->Request('GET', $next);

            foreach ($response->results as $menu) {
                yield from $this->descendMenu($menu->items);
            }

            if (isset($response->next)) {
                if (strpos($response->next, '://') !== false) {
                    $next = $response->next;
                } else {
                    $next = static::$prefix.'menus?'.$response->next;
                }

                continue;
            }
            break;
        }
    }
}

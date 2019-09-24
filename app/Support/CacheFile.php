<?php

namespace BBCMS\Support;

use Carbon\Carbon;
use Illuminate\Cache\FileStore;

class CacheFile extends FileStore
{
    protected $cacheMap = [
        // getCachedCategories()
        // Not simple: orderByRaw('ISNULL(`position`), `position` ASC')
        'categories' => \BBCMS\Models\XField::class,

        // roles(), not simple
        'roles' => \BBCMS\Models\Privilege::class,
        // getPrivileges(), not simple
        'privileges' => \BBCMS\Models\User::class,

        // fields(), simple ->get()
        'x_fields' => \BBCMS\Models\XField::class,
    ];

    /**
     * Get datetime of created cache file by key.
     * @param  string $key
     * @return Carbon|null
     */
    public function created(string $key)
    {
        $path = $this->path($key);

        return $this->files->exists($path)
            ? Carbon::createFromTimestamp(
                $this->files->lastModified($path)
            ) : null;
    }

    /**
     * Get the expiration time from cache file by key.
     * @param  string $key
     * @return Carbon|null
     */
    public function expired(string $key)
    {
        $path = $this->path($key);

        return $this->files->exists($path)
            ? Carbon::createFromTimestamp(
                substr($this->files->get($path), 0, 10)
            ) : null;
    }
}

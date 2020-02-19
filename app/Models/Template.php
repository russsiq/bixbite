<?php

namespace BBCMS\Models;

// Исключения.
use Exception;

// Базовые расширения PHP.
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

// Зарегистрированные фасады приложения.
use File;

// Сторонние зависимости.
use Illuminate\Support\Str;

class Template
{
    const ALLOWED_EXTENSIONS = '/\.(tpl|ini|css|js|blade\.php)$/';

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        //
    ];

    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }

    public function fill($attributes)
    {
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }

        return $this;
    }

    public function processSelect()
    {
        // Если возникнет необходимость самостоятельно
        // генерировать идентификатор для маршрутизоторов.
        // В данной версии генерируется во `vuex-orm`.
        // $id = md5($this->filename);

        // Полный путь с корневого каталога сайта.
        $path = $this->path();

        // Определяем, что запрашиваемый файл существует.
        $exists = File::isFile($path) and File::exists($path);

        // Заполняем нулевую информацию.
        $this->attributes = [
            'filename' => $this->filename,
            'path' => $path,
            'exists' => $exists,
            'content' => null,
            'modified' => null,
            'size' => 0,
        ];

        // Если файл существует, то добавляем всю необходимую информацию.
        if ($exists) {
            $this->attributes['content'] = File::get($path);
            $this->attributes['modified'] = strftime('%Y-%m-%d %H:%M', File::lastModified($path));
            $this->attributes['size'] = formatBytes(File::size($path));
        }
    }

    public function path()
    {
        return $this->filename ? $this->themePath().$this->filename : null;
    }

    public function themePath()
    {
        return theme_path('views');
    }

    public function get()
    {
        $this->processSelect();

        return $this;
    }

    /**
     * Write the contents of a file.
     *
     * @param  string  $path
     * @param  string  $contents
     * @return $this
     */
    public function save(array $attributes = [])
    {
        $this->fill($attributes);

        if (! File::isDirectory($dirname = dirname($this->path()))) {
            File::makeDirectory($dirname, 0777, true);
        }

        File::put($this->path(), $this->content, true);

        $this->processSelect();

        return $this;
    }

    /**
     * Delete the file at a given path.
     *
     * @param  string  $path
     * @return bool
     */
    public function delete()
    {
        if (File::delete($this->path())) {
            $this->exists = false;

            return true;
        }

        return false;
    }

    /**
     * Creates a flaten-structured array of files from a current theme root folder.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function all()
    {
        $files = collect([]);

        $dirIterator = new RecursiveDirectoryIterator(theme_path('views'), RecursiveDirectoryIterator::SKIP_DOTS);
        $iterator = new RecursiveIteratorIterator($dirIterator, RecursiveIteratorIterator::LEAVES_ONLY); // RecursiveIteratorIterator::SELF_FIRST

        foreach ($iterator as $node) {
            if ($node->isFile() and preg_match(static::ALLOWED_EXTENSIONS, $node->getFilename())) {

                $template = new Template([
                    'filename' => str_replace(theme_path('views'), '', $node->getPathname()),
                ]);

                $files = $files->push($template->get());
            }
        }

        return $files;
    }

    public function toArray()
    {
        return $this->attributes;
    }

    public function toJson($options = 0)
    {
        $json = json_encode($this->jsonSerialize(), $options);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new Exception('Error encoding model [Template] to JSON: '.json_last_error_msg());
        }

        return $json;
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function getAttribute($key)
    {
        if (! $key) {
            return;
        }

        return $this->attributes[$key] ?? null;
    }

    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;

        return $this;
    }

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    /**
     * Dynamically set attributes on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }

    /**
     * Determine if the given attribute exists.
     *
     * @param  mixed  $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return ! is_null($this->getAttribute($offset));
    }

    /**
     * Get the value for a given offset.
     *
     * @param  mixed  $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->getAttribute($offset);
    }

    /**
     * Set the value for a given offset.
     *
     * @param  mixed  $offset
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->setAttribute($offset, $value);
    }

    /**
     * Unset the value for a given offset.
     *
     * @param  mixed  $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->attributes[$offset]);
    }

    /**
     * Determine if an attribute or relation exists on the model.
     *
     * @param  string  $key
     * @return bool
     */
    public function __isset($key)
    {
        return $this->offsetExists($key);
    }

    /**
     * Unset an attribute on the model.
     *
     * @param  string  $key
     * @return void
     */
    public function __unset($key)
    {
        $this->offsetUnset($key);
    }

    /**
     * Convert the model to its string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }
}

<?php

// To do: class FileUploader extend BBCMS\Models\File\BaseFile

namespace BBCMS\Models;

use BBCMS\Models\User;
use BBCMS\Models\BaseModel;
use BBCMS\Models\Mutators\FileMutators;

use Storage;
use Illuminate\Http\UploadedFile;
use League\Flysystem\Adapter\Local as LocalAdapter;

use \RuntimeException as FileException;

class File extends BaseModel
{
    use FileMutators;

    protected $table = 'files';
    protected $primaryKey = 'id';
    protected $casts = [
        'properties' => 'object',
    ];
    protected $appends = [
        'url', 'path',
    ];
    protected $fillable = [
        'user_id', 'attachment_id', 'attachment_type',
        'disk', 'category', 'type', 'name', 'extension', 'mime_type', 'filesize', 'checksum',
        'title', 'description', 'properties', 'downloads',
    ];
    protected $hidden = [
        'checksum',
    ];
    // Размеры изображений по возрастанию.
    protected $thumbSizes = [
        'thumb' => 240, 'small' => 576, 'medium' => 992
    ];

    protected static function boot()
    {
        parent::boot();

        // Always delete file from storage
        static::deleting(function ($file) {
            $disk = $file->storageDisk($file->disk);
            $disk->delete($file->path);

            if ('image' == $file->type) {
                foreach ($file->thumbSizes as $size => $value) {
                    $disk->delete($file->getPathAttribute($size));
                }
            }
        });

        static::updating(function ($file) {
            if ($file->getOriginalPath() != $file->path) {
                $disk = $file->storageDisk($file->disk);
                $disk->rename($file->getOriginalPath(), $file->path);

                if ('image' == $file->type) {
                    foreach ($file->thumbSizes as $size => $value) {
                        if ($disk->exists($old_path = $file->getOriginalPath($size))) {
                            $disk->rename($old_path,
                                $file->type.DS.$file->category.DS.$size.DS.$file->name.'.'.$file->extension
                            );
                        }
                    }
                }
            }
        });
    }

    public function attachment()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Filter
    public function scopeFilter($query, $filters)
    {
        $query->when($filters['filetype'] ?? false, function($query) use ($filters) {
            $query->where('type', $filters['filetype']);
        });
    }

    public function storageDisk(string $disk = null)
    {
        return Storage::disk($disk ?? $this->disk);
    }

    public function getOriginalPath($thumbSize = null)
    {
        return $this->getOriginal('type').DS.$this->getOriginal('category')
            .($thumbSize ? DS.$thumbSize : '')
            .DS.$this->getOriginal('name').'.'.$this->getOriginal('extension');
    }

    public function manageUpload(UploadedFile $file, array $data)
    {
        if ('gz' == $data['extension']) {
            $data['extension'] = 'tar.gz';
        }

        return $this->fill($data)->uploadFile($file)->save() ? $this->toJson() : false;
    }

    /**
     * Physically file uploading (move from temp folder).
     *
     *  1. This action `only after File::fill($data)`.
     *  2. Get $data from $this filled attributes.
     *  3. a) Pack to zip all `not allowed file types`.
     *  3. b) Change attributes to zipped files.
     *  4. If it is `image` type - then create 3 files with different sizes.
     *
     * @param  Illuminate\Http\UploadedFile $file
     * @return this
     */
    public function uploadFile(UploadedFile $file)
    {
        $data = $this->attributes;
        $disk = $this->storageDisk($data['disk']);
        $isLocalDisk = $disk->getDriver()->getAdapter() instanceof LocalAdapter;

        @$disk->makeDirectory($data['type'].DS.$data['category']);

        // $name = $data['name'];
        // dump($name[0]);

        // Manipulate whith image file. Block mass uploading images.
        if ($isLocalDisk and 'image' == $data['type'] and ! request('mass_uploading')) {
            $this->storeAsImage($file, $data);
        }
        // Manipulate whith archive file.
        elseif ($isLocalDisk and in_array($data['type'], ['forbidden', 'other'])) {
            $this->storeAsZip($file, $data);
        }
        // Default store.
        else {
            $this->storeAsFile($file, $data);
        }

        return $this;
    }

    public function storeAsFile(UploadedFile $file, array $data)
    {
        $path = $data['type'].DS.$data['category'];
        $name = $data['name'].'.'.$data['extension'];
        $options = [
            'disk' => $data['disk'],
        ];

        return $file->storeAs($path, $name, $options);
    }

    // Manipulate whith archive file.
    protected function storeAsZip(UploadedFile $file, array $data)
    {
        $disk = $this->storageDisk($data['disk']);

        // Make zip archive name with full path.
        $zipname = $disk->getDriver()->getAdapter()->getPathPrefix()
                  .$data['type'].DS.$data['category'].DS
                  .$data['name'].'.'.$data['extension'] . '.zip';

        // Check if new file exists
        if ($disk->exists($zipname)) {
            throw new FileException(sprintf(
                'File with name `%s` already exists!', $zipname
            ));
        }

        // Storing zip.
        $zip = new \ZipArchive;
        $zip->open($zipname, \ZipArchive::CREATE);
        $zip->addFile($file->getRealPath(), $data['name'].'.'.$data['extension']);
        $zip->close();

        $this->attributes['name'] = $data['name'].'.'.$data['extension'];
        $this->attributes['extension'] = 'zip';
        $this->attributes['filesize'] = filesize($zipname);
    }

    // Manipulate whith image file.
    protected function storeAsImage(UploadedFile $file, array $data)
    {
        // Store uploaded image, to work it.
        $this->storeAsFile($file, $data);

        // Filesystem.
        $disk = $this->storageDisk($data['disk']);

        // Prepare local variables.
        $properties = [];
        $path_prefix = $data['type'].DS.$data['category'].DS;
        $path_suffix = DS.$data['name'].'.'.$data['extension'];
        $quality = setting('files.images.quality', 100);
        $is_convert = setting('files.images.is_convert', true);

        if (100 > $quality or $is_convert) {
            // Resave original file.
            $original = $this->imageResave(
                $this->getAbsolutePathAttribute(),
                $disk->path($path_prefix.trim($path_suffix, DS)),
                null, null,
                $quality, $is_convert
            );

            // If extension has changed, then delete original file.
            if ($data['extension'] != $original['extension']) {
                $disk->delete($this->path);
            }

            // Revision attributes.
            $this->attributes['mime_type'] = $original['mime_type'];
            $this->attributes['extension'] = $original['extension'];
            $this->attributes['filesize'] = $original['filesize'];
            $properties += [
                'width' => $original['width'],
                'height' => $original['height'],
            ];

            $path_suffix = DS.$data['name'].'.'.$original['extension'];
        }

        // Cutting images.
        foreach ($this->thumbSizes as $key => $value) {
            @$disk->makeDirectory($path_prefix.$key);
            $size = $this->imageResave(
                $this->getAbsolutePathAttribute(),
                $disk->path($path_prefix.$key.$path_suffix),
                setting("files.images.$key.width", $value),
                setting("files.images.$key.height", null),
                $quality, $is_convert
            );

            if (! $size) break;

            // Add to options.
            $properties += [
                $key => [
                    'width' => $size['width'],
                    'height' => $size['height'],
                ]
            ];
        }

        return $this->setAttribute('properties', $properties);
    }

    /**
     * Resize, compress and converting image.
     *
     * @param  string $outfile
     * @param  integer|null $width
     * @param  integer|null $height
     * @param  integer $quality Quality of created image.
     * @param  boolean $is_convert Convert image to `jpeg`.
     *
     * @return array|false Sizes of new created image.
     */
    public function imageResave(string $infile, string $outfile, $width = null, $height = null, int $quality = 75, bool $is_convert = true)
    {
        [$w, $h, $imagetype] = getimagesize($infile = $infile ?? $this->getAbsolutePathAttribute());

		switch ($imagetype) {
			case 1: $source = imagecreatefromgif($infile); break;
			case 2: $source = imagecreatefromjpeg($infile);break;
			case 3: $source = imagecreatefrompng($infile); break;
			case 6: $source = imagecreatefrombmp($infile); break;
			default:
                $instring = file_get_contents($infile);
                $source = imagecreatefromstring($instring);break;
		}

        // $width = min(setting('files.images.max_width', 1920), $width);
        // $height = min(setting('files.images.max_height', 1080), $height);

        // Check image size to resize.
        if ($width > $w or $height > $h) {
            return false;
        }

        // Prepare width and height to new image.
        if (! intval($width) and ! intval($height)) {
            $width = $w; $height = $h;
        } elseif (intval($width)) {
            $percent = $width / ($w / 100); $height = ($h / 100) * $percent;
        } elseif (intval($height)) {
            $percent = $height / ($h / 100); $width = ($w / 100) * $percent;
        }

        $output = imagecreatetruecolor($width, $height);

        // Manipulate with `png` format.
        if (3 == $imagetype and ! $is_convert) {
            imagealphablending($output, false);imagesavealpha($output, true);
        } elseif (3 == $imagetype) {
            imagefill($output, 0, 0, 16777215); // 0xFFFFFF
        }

        imagecopyresampled($output, $source, 0, 0, 0, 0, $width, $height, $w, $h);

        // if save extension.
        if (3 == $imagetype and ! $is_convert) {
            imagepng($output, $outfile, round(intval($quality) / 100)); $extension = 'png';
        } elseif (6 == $imagetype and ! $is_convert) {
            imagebmp($output, $outfile, (int) round(intval($quality) / 100)); $extension = 'bmp';
        } else {
            imagejpeg($output, $outfile, intval($quality)); $extension = 'jpeg';
        }

        // Clear images.
        imagedestroy($output); imagedestroy($source);

        // Get info from new file.
        $props = getimagesize($outfile);
        $width = $props[0]; $height = $props[1]; $mime_type = array_pop($props);
        $filesize = filesize($outfile);

        // Rename $outfile.
        rename($outfile, preg_replace('/\.[^.]+$/', '.', $outfile) . $extension);

        return compact('width', 'height', 'extension', 'mime_type', 'filesize');
    }
}

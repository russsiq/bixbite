<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Adapter\Local as LocalAdapter;
use RuntimeException as FileException;

/**
 * Attachment model.
 *
 * @property-read int $id
 * @property-read int $attachable_id
 * @property-read string $attachable_type
 * @property-read ?int $user_id
 * @property-read ?string $title
 * @property-read ?string $description
 * @property-read string $disk
 * @property-read string $folder
 * @property-read string $type
 * @property-read string $name
 * @property-read string $extension
 * @property-read string $mime_type
 * @property-read int $filesize
 * @property-read array $properties
 * @property-read int $downloads
 * @property-read \Illuminate\Support\Carbon $created_at
 * @property-read \Illuminate\Support\Carbon $updated_at
 *
 * @property-read string $url
 * @property-read string $path
 * @property-read string $absolute_path
 * @property-read int $filesize
 */
class Attachment extends Model
{
    use Mutators\AtachmentMutators;
    use Traits\Dataviewer;
    use HasFactory;

    const TABLE = 'attachments';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = self::TABLE;

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'user_id' => null,
        'title' => null,
        'description' => null,
        'disk' => 'public',
        'folder' => 'uploads',
        'type' => 'other',
        'properties' => '{}',
        'downloads' => 0,
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'url',
        'path',
        'absolute_path',
        'filesize',
        // 'picture_box',
        // 'media_player',
        // 'download_button',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'type' => 'string',
        'filesize' => 'integer',
        'properties' => 'array',
        'downloads' => 'integer',
        'url' => 'string',
        'path' => 'string',
        'absolute_path' => 'string',
        'filesize' => 'integer',
        // 'picture_box' => 'string',
        // 'media_player' => 'string',
        // 'download_button' => 'string',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'attachable_id',
        'attachable_type',
        'title',
        'description',
        'disk',
        'folder',
        'type',
        'name',
        'extension',
        'mime_type',
        'filesize',
        'properties',
    ];

    /**
     * Attributes by which filtering is allowed.
     *
     * @var array
     */
    protected $allowedFilters = [
        'id',
        'title',
        'disk',
        'folder',
        'type',
        'name',
        'extension',
    ];

    /**
     * The attributes by which sorting is allowed.
     *
     * @var array
     */
    protected $orderableColumns = [
        'id',
        'title',
        'name',
        'downloads',
    ];

    /**
     * Размеры миниатюр изображения в порядке возрастания.
     *
     * @var array
     */
    protected $thumbSizes = [
        'thumb' => 240,
        'small' => 576,
        'medium' => 992,
    ];

    public function attachable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id', 'user');
    }

    public function thumbSizes()
    {
        return $this->thumbSizes;
    }

    public function storageDisk(string $disk = null)
    {
        return Storage::disk($disk ?? $this->disk);
    }

    /**
     * Get path to file.
     * Used in `App\Models\Mutators\FileMutators` and `App\Models\Observers\FileObserver`.
     *
     * @param  string|null $thumbSize Thumbnail size for the image file.
     * @return string
     */
    public function path(string $thumbSize = null)
    {
        return $this->attributes['type']
            .DS.$this->attributes['folder']
            .($thumbSize ? DS.$thumbSize : '')
            .DS.$this->attributes['name'].'.'.$this->attributes['extension'];
    }

    public function manageUpload(UploadedFile $file, array $data)
    {
        $this->fill($data)
            ->uploadFile($file)
            ->save();

        return  $this->fresh();
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

        $disk->makeDirectory($data['type'].DS.$data['folder']);

        // $name = $data['name'];
        // dump($name[0]);

        // Manipulate whith image file. Block mass uploading images.
        if ($isLocalDisk and 'image' == $data['type'] and ! (bool) request('mass_uploading')) {
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
        $path = $data['type'].DS.$data['folder'];
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
                  .$data['type'].DS.$data['folder'].DS
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

    /**
     * Manipulate whith image file.
     *
     * @param  Illuminate\Http\UploadedFile $file
     * @param  array $data
     * @return mixed
     */
    protected function storeAsImage(UploadedFile $file, array $data)
    {
        // Store uploaded image, to work it.
        $this->storeAsFile($file, $data);

        // Prepare local variables.
        $properties = [];
        $disk = $this->storageDisk($data['disk']);
        $path_prefix = $data['type'].DS.$data['folder'].DS;
        $path_suffix = DS.$data['name'].'.'.$data['extension'];
        $quality = setting('files.images_quality', 75);
        $is_convert = setting('files.images_is_convert', true);

        // Resave original file.
        $image = $this->imageResave(
            $this->getAbsolutePathAttribute(),
            $disk->path($path_prefix.trim($path_suffix, DS)),
            setting('files.images_max_width', 3840),
            setting('files.images_max_height', 2160),
            $quality, $is_convert
        );

        if ($image) {
            // If extension has changed, then delete original file.
            if ($data['extension'] != $image['extension']) {
                $disk->delete($this->path);
            }

            // Revision attributes.
            $this->attributes['mime_type'] = $image['mime_type'];
            $this->attributes['extension'] = $image['extension'];
            $this->attributes['filesize'] = $image['filesize'];
            $properties = [
                'width' => $image['width'],
                'height' => $image['height'],
            ];

            $path_suffix = DS.$data['name'].'.'.$image['extension'];
        }

        // Cutting images.
        foreach ($this->thumbSizes() as $key => $value) {
            $disk->makeDirectory($path_prefix.$key);
            $image = $this->imageResave(
                $this->getAbsolutePathAttribute(),
                $disk->path($path_prefix.$key.$path_suffix),
                setting('files.images_'.$key.'_width', $value),
                setting('files.images_'.$key.'_height', $value),
                $quality, $is_convert
            );

            if (! $image) break;

            // Add to options.
            $properties += [
                $key => [
                    'width' => $image['width'],
                    'height' => $image['height'],
                    'filesize' => $image['filesize'],
                ]
            ];
        }

        return $this->setAttribute('properties', $properties);
    }

    /**
     * [imageResave description]
     * @param  string        $infile
     * @param  string        $outfile
     * @param  integer|null  $width
     * @param  integer|null  $height
     * @param  integer       $quality       Quality of created image.
     * @param  boolean       $is_convert    Convert image to `jpeg`.
     * @return false|array                  Sizes of new created image.
     */
    public function imageResave(string $infile, string $outfile, int $width = null, int $height = null, int $quality = 75, bool $is_convert = true)
    {
        [$w, $h, $imagetype] = getimagesize($infile);

		switch ($imagetype) {
			case 1: $source = imagecreatefromgif($infile); break;
			case 2: $source = imagecreatefromjpeg($infile);break;
			case 3: $source = imagecreatefrompng($infile); break;
			case 6: $source = imagecreatefrombmp($infile); break;
			case 18: $source = imagecreatefromwebp($infile); break;
			default:
                $instring = file_get_contents($infile);
                $source = imagecreatefromstring($instring);break;
		}

        // $width = min(setting('files.images_max_width', 3840), $width);
        // $height = min(setting('files.images_max_height', 2160), $height);

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
        if (3 == $imagetype and false === $is_convert) {
            imagealphablending($output, false);imagesavealpha($output, true);
        } elseif (3 == $imagetype) {
            imagefill($output, 0, 0, 16777215); // 0xFFFFFF
        }

        imagecopyresampled($output, $source, 0, 0, 0, 0, $width, $height, $w, $h);

        // if save extension.
        if (3 == $imagetype and false === $is_convert) {
            imagepng($output, $outfile, round($quality / 100)); $extension = 'png';
        } elseif (6 == $imagetype and false === $is_convert) {
            imagebmp($output, $outfile, (int) round($quality / 100)); $extension = 'bmp';
        } elseif (18 == $imagetype and false === $is_convert) {
            imagewebp($output, $outfile, $quality); $extension = 'webp';
        } else {
            imagejpeg($output, $outfile, $quality); $extension = 'jpeg';
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

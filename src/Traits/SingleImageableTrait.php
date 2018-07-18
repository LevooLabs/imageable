<?php

namespace LevooLabs\Imageable\Traits;

use Image;
use Storage;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use LevooLabs\Imageable\Enums\ImageType;
use LevooLabs\Imageable\Models\Image as ImageModel;

trait SingleImageableTrait {

    public function getTemplateBaseName() {
        return $this->template_base_name ?: "default";
    }

    public function getContentFolder() {
        return $this->content_folder ?: "uploads";
    }

    public function getImageType() {
        return $this->image_type ?: ImageType::DEFAULT_MAIN;
    }

    public function getDefaultImageName() {
        return $this->default_image_name ?: "default.jpg";
    }

    public function getImageExtension() {
        return $this->image_extension ?: null;
    }

    public function images() {
        return $this->morphOne(\LevooLabs\Imageable\Models\Image::class, 'imageable');
    }

    public function store_image($file, $image_type = null)
    {
        $sep = DIRECTORY_SEPARATOR;
        $filter_class = config('imagecache.templates')[$this->getTemplateBaseName()];
        $image_type = $image_type ?: $this->getImageType();
        $extension = $this->getImageExtension() ?: $file->extension();
        do {
            // try to create a file path until finding a unique one
            $file_name      = self::generateRandomFileName();
            $folders        = substr($file_name, 0, 2).$sep.substr($file_name, 2, 1).$sep;
            $dest_folder    = "{$this->getContentFolder()}{$sep}{$folders}";
            $relative_path  = "{$folders}{$file_name}.{$extension}";
            $full_path      = storage_path("app{$sep}{$this->getContentFolder()}{$sep}{$relative_path}");
        } while (Storage::exists("{$this->getContentFolder()}/{$relative_path}"));
        
        // Creating folders recursively
        Storage::makeDirectory("{$dest_folder}", 0755, true, true);
        // Save image to server
        Image::make($file)->filter(new $filter_class)->save($full_path);
        // Save the uploaded file path to the database and connect it to the imageable object
        return $this->saveImageEntity($relative_path, $image_type);
    }
    
    public function delete_image()
    {
        $this->image->delete();
    }

    public function getHasImageAttribute() {
        return $this->images != null;
    }

    public function getImageAttribute() {
        return $this->images ?: new ImageModel([
            'image_path' => $this->getDefaultImageName(),
            'image_type' => $this->getImageType(),
            'imageable' => $this,
        ]);
    }

    protected function saveImageEntity($relative_path, $image_type) {
        if ($this->image != null) {
            #NOTE Single imageable deletes the previous primary image
            $this->image->delete();
        }
        return $this->images()->save(new ImageModel([
            'image_path'    => $relative_path,
            'image_type'    => $image_type,
        ]));
    }

    protected static function generateRandomFileName()
    {
        $n = 13;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $file_name = '';
        for ($i = 0; $i < $n; $i++) {
            $file_name .= $characters[rand(0, $charactersLength - 1)];
        }
        return $file_name;
    }
}
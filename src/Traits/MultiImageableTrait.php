<?php

namespace LevooLabs\Imageable\Traits;

use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use LevooLabs\Imageable\Constants\ImageType;
use LevooLabs\Imageable\Models\Image as ImageModel;

trait MultiImageableTrait {

    use SingleImageableTrait;

    public function getSecondaryImageType() {
        return $this->secondary_image_type ?: ImageType::DEFAULT_MAIN;
    }

    protected function saveImageEntity($relative_path, $image_type) {
        if ($this->getImageType() == $image_type && $this->image != null) {
            #NOTE multi imageable doesn't delete primary images just demotes them to secondary
            $this->image->update(['image_type' => $this->getSecondaryImageType()]);
        }
        return $this->images()->save(new ImageModel([
            'image_path'    => $relative_path,
            'image_type'    => $image_type
        ]));
    }
    
    public function store_images($files, $image_type = null) {
        $images = [];
        foreach ($files as $file) {
            $images[] = $this->store_image($file, $image_type);
        }
        return $images;
    }

    public function getImageAttribute() {
        $image = $this->images()->where('image_type', $this->getImageType())->first();
        return $image ?: new ImageModel([
            'image_path' => $this->getDefaultImageName(),
            'image_type' => $this->getImageType(),
            'imageable' => $this,
        ]);
    }

    public function getSecondaryImagesAttribute() {
        return $this->images()->where('image_type', $this->getSecondaryImageType())->get();
    }

	public function images() {
        return $this->morphMany(\LevooLabs\Imageable\Models\Image::class, 'imageable');
	}
}
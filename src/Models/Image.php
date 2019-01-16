<?php

namespace LevooLabs\Imageable\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'images';
    
    protected $fillable = [
        'image_path', 'image_type', 'imageable'
    ];

    protected $hidden = [
        'image_path', 'image_type', 'imageable',
    ];

    protected $appends = ['s', 'm', 'l', 'o', ];

    public function imageable()
    {
        return $this->morphTo();
    }

    public function getSAttribute() {
        return url(config('imagecache.route')."/{$this->imageable->getTemplateBaseName()}-s/".str_replace('\\', '/', $this->image_path));
    }

    public function getMAttribute() {
        return url(config('imagecache.route')."/{$this->imageable->getTemplateBaseName()}-m/".str_replace('\\', '/', $this->image_path));
    }

    public function getLAttribute() {
        return url(config('imagecache.route')."/{$this->imageable->getTemplateBaseName()}-l/".str_replace('\\', '/', $this->image_path));
    }
    
    public function getOAttribute() {
        return url(config('imagecache.route')."/original/".str_replace('\\', '/', $this->image_path));
    }
}

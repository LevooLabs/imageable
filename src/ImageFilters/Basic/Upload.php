<?php

namespace LevooLabs\Imageable\ImageFilters\Basic;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class Upload implements FilterInterface 
{
    public function applyFilter(Image $image)
    {
        return $image;
    }
}


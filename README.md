# LevooLabs Imageable

LevooLabs Imageable is an easy to use Eloquent Image model for uploading and displaying images with [intervention/imagecache](http://image.intervention.io/). The package includes Traits to add simple connection between the Image model and any other Eloquent model.

## Demo

### Get the models image in multiple size

```php
$topic = Topic::where('name', 'Awsome topic')->first();
echo $topic->image->s; // Small image url
echo $topic->image->m; // Medium image url
echo $topic->image->l; // Large image url
echo $topic->image->o; // Original image url
```

### Get secondary images
```php
    foreach($image in $product->secondary_images) {
        echo $product->s;
    }
```


### Check whenever a model has an uploaded image
```php
if ($topic->has_image) {
    //
}
```

### Upload image to the server

```php
public function uploadImageAjax(Request $request, Topic $topic)
{
    if ($request->ajax()) {
        $image = $topic->store_image($request->file('file'));
        return response()->json(['ok' => $image->id], 200);
    }
    abort(404);
}
```

Or use `store_images($files, $image_type = null)` for  multiple images.

### Delete image from the server

```php
    $topic->delete_image();
```
Or use `delete_images()` to delete all the images connected to the model.


## Installation

### Step 1: Install package

Install the package through [Composer](http://getcomposer.org/). 

Run the Composer require command from the Terminal:

    composer require levoolabs/imageable
    
### Step 2: Migrations

Run migrations with artisan command

    php aritsan migrate

### Step 3: Publish assets

Publish intervention config files and the Imageable default image with:

    php artisan vendor:publish

### Step 4.1: Traits

For the simplest use just include `SingleImageableTrait` or `MultiImageableTrait` into your Eloquent model and you are all set.

```php
class Topic extends Model
{
    use \LevooLabs\Imageable\Traits\SingleImageableTrait;
    
    protected $table = 'topics';
    
    protected $fillable = [
        'title'
    ];
    
}
```

Or you can set

```php
class Product extends Model
{
    use \LevooLabs\Imageable\Traits\MultiImageableTrait;

    public $template_base_name = "product";
    
    protected $image_type = MyEnums\ImageType::PRODUCT_MAIN;
    protected $secondary_image_type = MyEnums\ImageType::PRODUCT;

    protected $default_image_name = "product.jpg";
    
    protected $extension = "jpg";

    /* ... */
}
```

- The `$template_base_name` contains the base name for the filters defined in the imagecache config file.
- The `$image_type` and `$secondary_image_type` properties holds the value for image_type column in the images table. The `$secondary_image_type` will only be used in `MultiImageableTrait.
- The `$default_image_name` is the name of the placeholder image file located in public/images/defaults folder for models without uploaded images.

### Step 4.2: Custom filters (optional)

If you set the `$template_base_name` value in your model you have to define the filters for that template in the config/imagecache.php file.

```php
        'product'   => \App\ImageFilters\Product\Upload::class,
        'product-s' => \App\ImageFilters\Product\Small::class,
        'product-m' => \App\ImageFilters\Product\Medium::class,
        'product-l' => \App\ImageFilters\Product\Large::class,
```

You can read more about Intervention Image Filters [here](http://image.intervention.io/use/filters).

## License

LevooLabs Imageable is licensed under the [MIT License](http://opensource.org/licenses/MIT).

Copyright 2018 [LevooLabs](http://levoolabs.com/)

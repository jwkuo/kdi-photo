<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Aws\S3\S3Client;

class Project extends Model
{
    use HasFactory;

    /**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['lookup_id', 'directory', 'name', 'sheet_options', 'sheet_prices', 'sheet_promo'];

    protected $appends = ['featured_image'];

    function packages(){
		return $this->hasMany('App\Models\Package');
	}
	
	function items(){
		return $this->hasMany('App\Models\Item');
	}

    function getFeaturedImageAttribute()
    {   
        $client = app()->make(S3Client::class);
        $objects = $client->listObjectsV2([
            'Bucket' => 'kdi-photo',
            'Prefix' => $this->directory . '/',
            'MaxKeys' => 2
        ]);
        if(isset($objects['Contents'][1])) {
            return 'https://s3.amazonaws.com/kdi-photo/' . $objects['Contents'][1]['Key'];
        }
        
        return '/kdi-logo.jpg';
    }
}

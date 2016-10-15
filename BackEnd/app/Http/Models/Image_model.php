<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Image_model extends Model
{
    /**
     * @var string
     */
    protected $table = "image";

    /**
     * @var string
     */
    protected $primaryKey = "image_id";
}

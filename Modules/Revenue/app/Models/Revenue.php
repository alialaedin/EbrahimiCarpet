<?php

namespace Modules\Revenue\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Revenue\Database\Factories\RevenueFactory;

class Revenue extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    protected static function newFactory(): RevenueFactory
    {
        //return RevenueFactory::new();
    }
}

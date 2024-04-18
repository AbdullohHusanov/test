<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'brand_id',
        'district_id'
    ];

    public function images()
    {
        return $this->hasMany(BranchImage::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}

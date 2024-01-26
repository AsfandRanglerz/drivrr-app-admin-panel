<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntertainerFeatureAdsPackage extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function entertainerDetail()
    {
        return $this->hasMany('App\Models\EntertainerDetail','entertainer_feature_ads_packages_id');
    }
}

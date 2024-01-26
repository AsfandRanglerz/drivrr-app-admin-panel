<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenueFeatureAdsPackage extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function venue()
    {
        return $this->hasMany('App\Models\Venue','venue_feature_ads_packages_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventFeatureAdsPackage extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function event()
    {
        return $this->hasMany('App\Models\Event','event_feature_ads_packages_id');
    }
}

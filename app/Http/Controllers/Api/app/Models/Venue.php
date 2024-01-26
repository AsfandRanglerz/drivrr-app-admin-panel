<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function User()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
    public function venueFeatureAdsPackage()
    {
        return $this->belongsTo('App\Models\VenueFeatureAdsPackage', 'venue_feature_ads_packages_id', 'id');
    }
    public function events()
    {
        return $this->belongsToMany('App\Models\Event', 'event_venues', 'venues_id', 'event_id')->withPivot('status');
    }
    public function  venueCategory()
    {
        return $this->belongsTo('App\Models\VenueCategory','category_id','id');
    }
    public function  venuePhoto()
    {
        return $this->hasMany('App\Models\VenuesPhoto');
    }
    public function  venuePricing()
    {
        return $this->hasMany('App\Models\VenuePricing','venues_id','id');
    }
    public function reviews()
    {
        return $this->hasMany('App\Models\Review', 'venue_id', 'id');
    }
    public function getImageAttribute($path)
    {
        if ($path){
            return asset($path);
        }else{
            return null;
        }
    }
}

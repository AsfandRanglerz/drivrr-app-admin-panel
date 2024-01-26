<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function entertainerDetails(){
        return $this->belongsToMany('App\Models\EntertainerDetail','event_entertainers','event_id','entertainer_details_id')->withPivot('status');
     }
     public function eventVenues(){
        return $this->belongsToMany('App\Models\Venue','event_venues','event_id','venues_id')->withPivot('status');
     }
     public function User()
     {
         return $this->belongsTo('App\Models\User','user_id','id');
     }
     public function eventFeatureAdsPackage  ()
     {
         return $this->belongsTo('App\Models\EventFeatureAdsPackage','event_feature_ads_packages_id','id');
     }
     public function reviews()
     {
         return $this->hasMany('App\Models\Review','event_id','id');
     }
    public function getCoverImageAttribute($path)
    {
        if ($path){
            return asset($path);
        }else{
            return null;
        }
    }
}

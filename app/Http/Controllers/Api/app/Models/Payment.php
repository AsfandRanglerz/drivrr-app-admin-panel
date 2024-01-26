<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'status',

    ];
    public function user()
    {
        return $this->belongsTo(User::class,'sender_id','id');
    }
    public function entertainer()
    {
        return $this->belongsTo(User::class,'entertainer_id','id');
    }
    public function talent()
    {
        return $this->belongsTo(EntertainerDetail::class,'entertainer_details_id','id');
    }
    public function venue()
    {
        return $this->belongsTo(User::class,'venue_id','id');
    }
    public function event()
    {
        return $this->belongsTo(Event::class,'event_id','id');
    }
    public function entertainerPackage()
    {
        return $this->belongsTo(EntertainerPricePackage::class,'entertainer_details_id','id');
    }
    public function venuePackage()
    {
        return $this->belongsTo(VenueFeatureAdsPackage::class,'venue_feature_ads_packages_id','id');
    }
    public function eventPackage()
    {
        return $this->belongsTo(EventFeatureAdsPackage::class,'event_feature_ads_packages_id','id');
    }
    public function entertainerFeaturePackage()
    {
        return $this->belongsTo(EntertainerFeatureAdsPackage::class,'entertainer_feature_ads_packages_id','id');
    }
    public function ticket()
    {
        return $this->belongsTo(EventTicket::class,'ticket_id','id');
    }
}

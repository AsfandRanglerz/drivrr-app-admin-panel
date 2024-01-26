<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;



use Illuminate\Foundation\Auth\User as Authenticatable;



class Admin extends Authenticatable

{

    use HasFactory;

    protected $guarded=[];
    public function chatfavourites()
    {
        return $this->hasMany('App\Models\ChatFavourite','admin_id');
    }

}


<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;  // <-- Add this line

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'shop_id',
    ];

    protected $hidden = ['password', 'remember_token'];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}

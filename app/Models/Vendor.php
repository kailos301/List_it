<?php

namespace App\Models;

use App\Models\Instrument\Equipment;
use App\Models\Instrument\EquipmentReview;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class Vendor extends Model implements AuthenticatableContract
{
    use HasFactory, Authenticatable;

    protected $fillable = [
        'photo',
        'email',
        'phone',
        'phone_verified',
        'username',
        'password',
        'trader',
        'status',
        'amount',
        'profile_completed',
        'facebook',
        'twitter',
        'linkedin',
        'avg_rating',
        'email_verified_at',
        'is_dealer',
        'show_email_addresss',
        'show_phone_number',
        'show_contact_form',
    ];

    public function vendor_infos()
    {
        return $this->hasMany(VendorInfo::class);
    }
    public function vendor_info()
    {
        return $this->hasOne(VendorInfo::class);
    }

    //support ticket
    public function support_ticket()
    {
        return $this->hasMany(SupportTicket::class, 'vendor_id', 'id');
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }
    public function cars()
    {
        return $this->hasMany(Car::class, 'vendor_id', 'id');
    }
}

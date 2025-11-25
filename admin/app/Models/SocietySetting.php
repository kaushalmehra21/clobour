<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocietySetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'colony_id',
        'society_name',
        'address',
        'city',
        'state',
        'pincode',
        'phone',
        'email',
        'website',
        'registration_number',
        'gst_number',
        'pan_number',
        'bank_name',
        'bank_account_number',
        'bank_ifsc',
        'bank_branch',
        'logo',
        'payment_gateway_config',
        'sms_config',
        'email_config',
        'notification_settings',
    ];

    protected $casts = [
        'payment_gateway_config' => 'array',
        'sms_config' => 'array',
        'email_config' => 'array',
        'notification_settings' => 'array',
    ];

    public function colony()
    {
        return $this->belongsTo(Colony::class);
    }

    // Get settings for current colony
    public static function getSettings($colonyId = null)
    {
        $colonyId = $colonyId ?? auth()->user()?->current_colony_id;
        
        if (!$colonyId) {
            return null;
        }
        
        return static::firstOrCreate(
            ['colony_id' => $colonyId],
            [
                'society_name' => 'Society Name',
                'address' => '',
            ]
        );
    }
}

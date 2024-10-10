<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegisterDevice extends Model
{
    use HasFactory;

    protected $table = 'register_device';

    protected $fillable = [
        'EquipmentNumber',
        'Brand',
        'Model',
        'DeviceID', // Make sure to add this
        'OtherFeatures',
        'location',
        
    ];
}

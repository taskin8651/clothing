<?php

namespace App\Models;

use DateTimeInterface;
use Hash;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class DeliveryBoy extends Authenticatable implements HasMedia
{
    use SoftDeletes, HasFactory, Notifiable, InteractsWithMedia;

    public $table = 'delivery_boys';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'profile_image',
        'id_proof_image',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'mobile',
        'password',
        'address',
        'city',
        'area',
        'pincode',
        'vehicle_type',
        'vehicle_number',
        'id_proof_type',
        'status',
        'remember_token',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('profile_image')->singleFile();
        $this->addMediaCollection('id_proof_image')->singleFile();
    }

    public function getProfileImageAttribute()
    {
        $file = $this->getFirstMedia('profile_image');

        if ($file) {
            return [
                'id' => $file->id,
                'url' => $file->getUrl(),
                'name' => $file->file_name,
            ];
        }

        return null;
    }

    public function getIdProofImageAttribute()
    {
        $file = $this->getFirstMedia('id_proof_image');

        if ($file) {
            return [
                'id' => $file->id,
                'url' => $file->getUrl(),
                'name' => $file->file_name,
            ];
        }

        return null;
    }

    public function setPasswordAttribute($input)
    {
        if ($input) {
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
        }
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}

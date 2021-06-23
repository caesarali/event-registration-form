<?php

namespace App\Models;

use App\Traits\HasPrice;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasPrice;

    protected $fillable = ['name', 'description', 'additional_information', 'category', 'time'];

    public function levels() {
        return $this->belongsToMany(Level::class, 'event_level', 'event_id', 'level_id');
    }

    public function registrations() {
        return $this->belongsToMany(Registration::class, 'registration_event', 'event_id', 'registration_id');
    }
}

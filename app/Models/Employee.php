<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'location_id',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class);
    }
}

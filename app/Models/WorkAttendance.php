<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkAttendance extends Model
{
    use HasFactory;

    protected $table = 'work_attendances';
  
    protected $fillable = [
        'employee_name',
        'work_date',
        'check_in',
        'check_out',
        'status',
        'note',
    ];
}

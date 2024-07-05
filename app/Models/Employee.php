<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $table = 'employee';
    public $timestamps = true;
    protected $fillable = ['job_id','job_title', 'emp_name', 'emp_id', 'email', 'report_to_job_id', 'report_to_name', 'role_priority', 'job_level', 'is_root','created_at','updated_at'];



}


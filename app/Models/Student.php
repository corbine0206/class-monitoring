<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_number',
        'first_name',
        'last_name',
        'middle_name',
        'date_of_birth',
        'gender',
        'course',
        'user_id',
        'section_id',
        'subject_id',
    ];
    
    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}

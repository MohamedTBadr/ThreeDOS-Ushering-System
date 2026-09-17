<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $table = 'registrations';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'college',
        'level',
        'council',
        'event_type',
        'ushered_by',
        'rating',
        'notes',
        'interview_time',
        'interviewed_by',
        'interview_questions',
        'interview_notes',
    ];

    protected function casts(): array
    {
        return [
            'interview_time' => 'datetime',
            'interview_questions' => 'array',
            'interview_notes' => 'array',
        ];
    }
}

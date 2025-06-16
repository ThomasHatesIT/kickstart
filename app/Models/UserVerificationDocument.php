<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVerificationDocument extends Model
{
    use HasFactory;
    protected $fillable = [
    'user_id',
    'document_path',
    'document_type',
];


public function user()
{
    return $this->belongsTo(User::class);
}
}

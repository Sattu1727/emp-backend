<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    // Define the table associated with the model
    protected $table = 'new_data_final';  // Updated table name

    // Define the fillable fields for mass assignment
    protected $fillable = [
        'full_name',
        'email',
        'mobile',
        'alternate_mobile',
        'gender',
        'address',
        'guardian_name',
        'relation',
        'guardian_mobile',
        'g_address',
        'image',
        'id_prove',
        'dob',
        'token'
    ];

    // Optionally, you can define the hidden fields (e.g., password or sensitive data)
    protected $hidden = [
        'id_prove', // If you don't want to expose it in responses
    ];

    // You can also add custom attributes or methods if necessary
    // For example, formatting or handling file uploads
}

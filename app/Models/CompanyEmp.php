<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class companyEmp extends Model
{
    use HasFactory;

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
        'p_address',
        'image',
        'id_prove',
        'dob',
    ];
}

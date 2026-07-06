<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name'])]
class ApiClient extends Model
{
    use HasApiTokens;
}

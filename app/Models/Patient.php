<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
  use HasFactory;

  protected $table = 'patients';

  protected $fillable = [
    'id',
    'name',
    'phone',
    'gender',
    'birth_date',
    'address',
    'rt',
    'rw',
    'subdistrict_id',
  ];

  protected $with = [
    'subdistrict'
  ];

  public function subdistrict()
  {
    return $this->belongsTo(Subdistrict::class);
  }
}

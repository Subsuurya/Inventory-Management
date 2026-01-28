<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DummyTable extends Model
{
    use HasFactory;

    protected $table = 'dummy_tables';

    protected $fillable = [
        // Add your real fields here, e.g. 'name', 'description'
    ];
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Модель списков задач 
 */
class Task extends Model
{
    protected $table = "tasks";

    protected $fillable = [
        "title",
        "description",
        "status"
    ];
}

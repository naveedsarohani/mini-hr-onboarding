<?php

namespace App\Models;

use Core\Model;

class Employee extends Model
{
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function resume()
    {
        return $this->hasOne(Resume::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Todo extends Model
{
    use HasFactory;

    use Notifiable;

    protected $fillable = [
        'name', 'description', 'done'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'creator_id');
    }

    public function todoAffectedTo()
    {
        return $this->belongsTo('App\Models\User', 'affectedTo_id');

    }

    public function todoAffectedBy()
    {
        return $this->belongsTo('App\Models\User', 'affectedBy_id');

    }
}



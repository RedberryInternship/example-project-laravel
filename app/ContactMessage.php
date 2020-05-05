<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    /**
     * Fillable Fields.
     */
    protected $fillable = [
        'user_id',
        'message'
    ];

    /**
     * User who wrote the Contact Message;
     */
    public function user()
    {
        return $this -> belongsTo(User::class);
    }
}

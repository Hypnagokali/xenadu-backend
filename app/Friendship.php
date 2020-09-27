<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Friendship extends Model
{
    const ACCEPTED = 'accepted';
    const REJECTED = 'rejected';
    const PENDING = 'pending';

    protected $fillable = ['requester_id', 'addressee_id', 'state'];
}

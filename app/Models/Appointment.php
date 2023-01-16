<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin Builder
 */
class Appointment extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'agent_id',
        'customer_id',
        'datetime_begin',
        'datetime_end',
        'address',
        'distance',
        'datetime_to_leave',
    ];

    /**
     * Connects to the user model
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    /**
     * Connects to the customer model
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }


}
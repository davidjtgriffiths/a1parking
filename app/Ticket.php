<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'officer_id',
        'issued_at',
        'reg_no',
        'front_image',
        'rear_image',
        'dash_image',
        'location_image',
        'gps_lat',
        'gps_lon',
        'dvla_req_sent',
        'first_name',
        'last_name',
        'address1',
        'address2',
        'address3',
        'town',
        'postcode',
        'notice_sent',
        'reminder_sent',
        'client_access_code',
        'payment_made_amt',
        'payment_made_date',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'officer_id' => 'integer',
        'gps_lat' => 'decimal:7',
        'gps_lon' => 'decimal:7',
        'dvla_req_sent' => 'boolean',
        'notice_sent' => 'boolean',
        'reminder_sent' => 'boolean',
        'payment_made_amt' => 'decimal:2',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'issued_at',
    ];


    public function user()
    {
        return $this->hasOne(\App\User::class);
    }

    public function officer()
    {
        return $this->belongsTo(\App\User::class);
    }


}

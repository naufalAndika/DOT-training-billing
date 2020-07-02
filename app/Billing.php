<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class Billing extends Model
{
    /**
     * The attributes that aren't mass assignable.
     * 
     * @var array
     */
    protected $guarded = ['id', 'paid', 'billing_number'];

    /**
     * The attributes that should be hidden for arrays.
     * 
     * @var array
     */
    protected $hidden = ['updated_at'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['pay_before'];

    /**
     * Generate billing_number.
     * 
     * @return void
     */
    public function generateNumber()
    {
        $this->billing_number = md5(uniqid($this->id, true));
        $this->save();
    }
}

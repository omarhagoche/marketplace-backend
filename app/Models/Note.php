<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    public $table = 'notes';
    public $fillable = ['text','to_user_id','from_user_id','order_id'];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'from_user_id' => 'nullable|exists:users,id',
        'to_user_id' => 'nullable|exists:users,id',

        'order_id' => 'nullable|exists:order,id',
        'text' => 'required',
    ];
    
      /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function fromUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'from_user_id', 'id');
    }
          /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function toUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'to_user_id', 'id');
    }
  /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function order()
    {
        return $this->belongsTo(\App\Models\Order::class, 'order_id', 'id');
    }
}
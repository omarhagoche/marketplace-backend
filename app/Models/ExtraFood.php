<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExtraFood extends Model
{
    public $table = 'extra_foods';

    /**
     * Get the Food that owns the ExtraFood
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function food()
    {
        return $this->belongsTo(Food::class);
    }

    public function extra()
    {
        return $this->belongsTo(Extra::class);
    }

}

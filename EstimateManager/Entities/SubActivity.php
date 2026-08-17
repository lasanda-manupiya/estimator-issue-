<?php

namespace Modules\EstimateManager\Entities;

use Illuminate\Database\Eloquent\Model;

class SubActivity extends Model {

    protected $fillable = [
        'main_activity_id',
        'sub_code',
        'activity',
        'quantity',
        'rate',
        'total',
        'co',
        'totalco',
        'hr',
        'mhr',
        'total_hr',
        'total_mhr',
        'unit'
    ];

    /**
     * Get the project for the estimate.
     */
    public function mainActivity()
    {
        return $this->belongsTo('Modules\EstimateManager\Entities\MainActivity');
    }

    /**
     * Get the sctivities of the sub activity.
     */
    public function activities()
    {
        return $this->hasMany('Modules\EstimateManager\Entities\Activity');
    }

    /**
     * Get the activities display name attribute.
     */
    public function getActivityDisplayNameAttribute(){
        return $this->activity."(".$this->sub_code.")";
    }

}

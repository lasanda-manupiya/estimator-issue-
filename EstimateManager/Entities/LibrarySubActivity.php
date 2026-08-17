<?php

namespace Modules\EstimateManager\Entities;

use Illuminate\Database\Eloquent\Model;

class LibrarySubActivity extends Model {
    protected $table = 'library_sub_activities';
    protected $fillable = [
        'library_main_activity_id',
        'sub_code',
        'activity',
        'quantity',
        'rate',
        'total',
        'hr',
        'mhr',
        'total_hr',
        'total_mhr',
        'unit',
		'foot_print_value',
		'totalco'
    ];

    /**
     * Get the project for the estimate.
     */
    public function mainActivity()
    {
        return $this->belongsTo('Modules\EstimateManager\Entities\LibraryMainActivity');
    }

    /**
     * Get the sctivities of the sub activity.
     */
    public function activities()
    {
        return $this->hasMany('Modules\EstimateManager\Entities\LibraryActivity');
    }

    /**
     * Get the activities display name attribute.
     */
    public function getActivityDisplayNameAttribute(){
        return $this->activity."(".$this->sub_code.")";
    }

}

<?php

namespace Modules\EstimateManager\Entities;

use Illuminate\Database\Eloquent\Model;

class MainActivity extends Model {

    protected $fillable = [
        'project_id',
        'main_code',
        'activity',
        'area',
        'level',
        'quantity',
        'rate',
        'total',
		'co',
        'totalco',
        'hr',
        'mhr',
        'total_hr',
        'total_mhr',
        'unit_qty',
        'unit_rate',
        'unit'
    ];

    /**
     * Get the sub sctivities of the main activity.
     */
    public function subActivities()
    {
        return $this->hasMany('Modules\EstimateManager\Entities\SubActivity');
    }

    /**
     * Get the project for the estimate.
     */
    public function project()
    {
        return $this->belongsTo('Modules\ProjectManager\Entities\Project');
    }

    /**
     * Set the unit qty.
     *
     * @param  string  $value
     * @return void
     */
    public function setUnitQtyAttribute($value)
    {
        $this->attributes['unit_qty'] = $value ?? 0;
    }

    /**
     * Set the unit.
     *
     * @param  string  $value
     * @return void
     */
    public function setUnitAttribute($value)
    {
        $this->attributes['unit'] = $value ?? '';
    }

    /**
     * Get the activities area display name attribute.
     */
    public function getAreaDisplayNameAttribute(){
        return $this->area."(".$this->main_code.")";
    }

    /**
     * Get the activities level display name attribute.
     */
    public function getLevelDisplayNameAttribute(){
        return $this->level."(".$this->main_code.")";
    }

}

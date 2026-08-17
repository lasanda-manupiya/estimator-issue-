<?php

namespace Modules\PurchaseManager\Entities;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model {

    protected $fillable = [
        'project_id',
        'main_activity_id',
        'sub_activity_id',
        'user_id',
        'carriage_costs',
        'c_of_c',
        'other_costs',
        'delivery_address',
        'delivery_date',
        'delivery_time',
        'notes',
    ];

    /**
     * Get the materials of the quotation.
     */
    public function materials()
    {
        return $this->hasMany('Modules\PurchaseManager\Entities\QuotationMaterial');
    }

    /**
     * Get the quotation replies of the quotation.
     */
    public function replies()
    {
        return $this->hasMany('Modules\PurchaseManager\Entities\QuotationReply');
    }

    /**
     * Get the quotation reply materials of the quotation.
     */
    public function replyMaterials()
    {
        return $this->hasMany('Modules\PurchaseManager\Entities\QuotationReplyMaterial');
    }

    /**
     * Get the quotation reply materials of the quotation.
     */
    public function seenQuotations()
    {
        return $this->hasMany('Modules\PurchaseManager\Entities\SeenQuotation');
    }

    /**
     * The suppliers that belong to the quotation.
     */
    public function suppliers()
    {
        return $this->belongsToMany('App\User', 'quotation_supplier', 'quotation_id', 'supplier_id');
    }
   

    /**
     * Get the project for the quotation.
     */
    public function project()
    {
        return $this->belongsTo('Modules\ProjectManager\Entities\Project');
    }

    /**
     * Get the main activity for the quotation.
     */
    public function mainActivity()
    {
        return $this->belongsTo('Modules\EstimateManager\Entities\MainActivity');
    }

    /**
     * Get the sub activity for the quotation.
     */
    public function subActivity()
    {
        return $this->belongsTo('Modules\EstimateManager\Entities\SubActivity');
    }

    /**
     * Get the user for the quotation.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

}

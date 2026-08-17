<?php

namespace Modules\PurchaseManager\Policies;

use App\User;
use Modules\PurchaseManager\Entities\Quotation;

class QuotationPolicy{

    /**
     * Determine if the given post can be updated by the user.
     *
     * @param  User  $user
     * @param  Quotation  $quotation
     * @return bool
     */
    public function supplier_quotation(User $user, Quotation $quotation)
    {
        $quotation = $quotation->whereHas('suppliers', function($q) use($user){
            $q->where('id', $user->id);
        });
        if($quotation->exists()){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Determine if the given post can be updated by the user.
     *
     * @param  User  $user
     * @param  Quotation  $quotation
     * @return bool
     */
    public function admin_quotation(User $user, Quotation $quotation)
    {
        if($quotation->project->company_id == $user->id){
            return true;
        }else{
            return false;
        }
    }

}

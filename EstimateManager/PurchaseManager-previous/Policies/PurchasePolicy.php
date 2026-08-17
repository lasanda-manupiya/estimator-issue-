<?php

namespace Modules\PurchaseManager\Policies;

use App\User;
use Modules\PurchaseManager\Entities\Purchase;

class PurchasePolicy{

    /**
     * Determine if the given post can be updated by the user.
     *
     * @param  User  $user
     * @param  Purchase  $quotation
     * @return bool
     */
    public function supplier_purchase(User $user, Purchase $purchase)
    {
        if($purchase->supplier_id == $user->id){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Determine if the given post can be updated by the user.
     *
     * @param  User  $user
     * @param  Purchase  $purchase
     * @return bool
     */
    public function admin_purchase(User $user, Purchase $purchase)
    {
        if($purchase->project->company_id == $user->id){
            return true;
        }else{
            return false;
        }
    }

}

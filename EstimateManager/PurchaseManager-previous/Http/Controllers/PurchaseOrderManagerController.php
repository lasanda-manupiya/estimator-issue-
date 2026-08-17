<?php

namespace Modules\PurchaseManager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\ProjectManager\Entities\Project;
use Modules\PurchaseManager\Entities\Purchase;
use Modules\PurchaseManager\Entities\SeenPurchase;
use Modules\PurchaseManager\Entities\PurchaseStatus;
use Modules\PurchaseManager\Entities\PurchaseOrder;
use Modules\PurchaseManager\Entities\PurchaseHistory;
use Modules\PurchaseManager\Entities\PurchaseInvoice;
use Modules\PurchaseManager\Entities\PurchaseOrderHistory;
use Modules\PurchaseManager\Entities\PurchaseInvoiceHistory;
use Modules\PurchaseManager\Mail\PurchaseOrderMailToSupplier;
use Modules\PurchaseManager\Mail\QuotationMailToSupplier;
use Modules\PurchaseManager\Mail\PurchaseOrderInvoiceMailToSupplier;
use Modules\PurchaseManager\Http\Requests\SavePurchaseOrderRequest;
use Modules\PurchaseManager\Http\Requests\UpdatePurchaseOrderRequest;
use Modules\PurchaseManager\Http\Requests\SaveSeparatePurchaseOrderRequest;

class PurchaseOrderManagerController extends Controller {

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request) {
        if (!auth()->user()->can('access', 'purchase orders visible')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        
        $query = Project::whereStatus(1);
        if(!auth()->user()->isRole('Super Admin')){
            if(auth()->user()->isRole('Admin')){
                $query->where('company_id', auth()->id());
            }else{
               $query =DB::table('users_project')
            ->join('projects', 'projects.id', '=', 'users_project.project_id')
            ->select('projects.id As id','projects.company_id AS company_id','projects.project_title AS project_title','projects.status AS status','projects.version As version','users_project.users_id')
			->where([
			['company_id', auth()->user()->company_id],
			['users_project.users_id', auth()->id()],
			['status',1]
		   ]);
                //$query->where('company_id', auth()->user()->company_id);
            }
        }
		if((!auth()->user()->isRole('Admin')) && (!auth()->user()->isRole('Super Admin'))){
        $projects = $query->get(['id', 'project_title', 'version']);
        $projects = $projects->pluck('project_title','id');
        $projects->prepend('All', '');
		 return view('purchasemanager::purchase_orders.index', compact('projects'));
		}else{
		$projects = $query->get(['id', 'project_title', 'version']);
        $projects = $projects->pluck('display_project_title', 'id');
        $projects->prepend('All', '');
		 return view('purchasemanager::purchase_orders.index', compact('projects'));
		}
        
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create() {
        if (!auth()->user()->can('access', 'purchase orders add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        /*if(auth()->user()->isRole('Super Admin')){
            $projects = Project::whereStatus(1)->get(['id', 'project_title', 'version']);
        }else{
            $projects = Project::whereHas('users', function($q){
                $q->where('id', auth()->id());
            })
            ->whereStatus(1)
            ->get(['id', 'project_title', 'version']);
        }*/
        
        $query = Project::whereStatus(1);
        if(!auth()->user()->isRole('Super Admin')){
            if(auth()->user()->isRole('Admin')){
                $query->where('company_id', auth()->id());
            }else{
                $query =DB::table('users_project')
            ->join('projects', 'projects.id', '=', 'users_project.project_id')
            ->select('projects.id As id','projects.company_id AS company_id','projects.project_title AS project_title','projects.status AS status','projects.version As version','users_project.users_id')
			->where([
			['company_id', auth()->user()->company_id],
			['users_project.users_id', auth()->id()],
			['status',1]
		   ]);
                //$query->where('company_id', auth()->user()->company_id);
            }
        }
		if((!auth()->user()->isRole('Admin')) && (!auth()->user()->isRole('Super Admin'))){
        $projects = $query->get();
        $projects = $projects->pluck('project_title', 'id');
        //$projects->prepend('All', '');
		
		}else{
		$projects = $query->get(['id', 'project_title', 'version']);
        $projects = $projects->pluck('display_project_title', 'id');
        $projects->prepend('Select Project', '');
		 
		}
       

        $suppliers = \App\User::whereHas('roles', function($q){
            $q->whereName('Supplier');
        })->whereStatus(1);

        if(!auth()->user()->isRole('Super Admin')){
            if(auth()->user()->isRole('Admin')){
                $suppliers->where('company_id', auth()->id());
            }else{
                $suppliers->where('company_id', auth()->user()->company_id);
            }
        }
        //$suppliers = $suppliers->pluck('supplier_name', 'id');
        $suppliers = $suppliers->pluck('supplier_name', 'id');
        $suppliers->prepend('Select', '');

        return view('purchasemanager::purchase_orders.create', compact('projects', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(SavePurchaseOrderRequest $request) {
        if (!auth()->user()->can('access', 'purchase orders add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        try {
            \DB::beginTransaction();
            $purchase_no = Purchase::where('project_id', $request->project_id)->max('purchase_no');
            $purchase                      = new Purchase();
            $purchase->project_id          = $request->project_id;
            $purchase->main_activity_id    = $request->level;
            $purchase->sub_activity_id     = $request->sub_code;
            $purchase->supplier_id         = $request->supplier_id;
            $purchase->purchase_no         = $purchase_no ? ($purchase_no+1) : 1;
            $purchase->delivery_date       = $request->delivery_date;
            $purchase->delivery_time       = $request->delivery_time;
            $purchase->delivery_address    = $request->delivery_address;
            $purchase->carriage_costs      = $request->carriage_costs ?? 0;
            $purchase->c_of_c              = $request->c_of_c ?? 0;
            $purchase->other_costs         = $request->other_costs ?? 0;
            $purchase->grand_total         = $request->grand_total ?? 0;
            $purchase->notes               = $request->notes;
            $purchase->save();	

            if($request->has('activities')){
                $activies = (array)$request->activities;
                $selectedRows = (array)$request->selected_rows;
                foreach($selectedRows as $selectedRow){
                    $purchaseOrder = new PurchaseOrder;
                    $purchaseOrder->purchase_id = $purchase->id;
                    $purchaseOrder->activity_id = $activies[$selectedRow]['activity_id'];
                    $purchaseOrder->activity = $activies[$selectedRow]['activity'];
                    $purchaseOrder->unit = $activies[$selectedRow]['unit'];
                    $purchaseOrder->quantity = $activies[$selectedRow]['quantity'];
                    $purchaseOrder->rate = $activies[$selectedRow]['rate'];
                    $purchaseOrder->total = $activies[$selectedRow]['total'];
                    if(\Arr::has($activies[$selectedRow], 'file')){
                        $file = $activies[$selectedRow]['file'];
                        if ($file->isValid()) {
                            $extension = $file->getClientOriginalExtension();
                            $filename  = str_random(10).'-' . time() . '.' . $extension;
                            $file->storeAs('purchases', $filename, 'public');
                            $purchaseOrder->photo = $filename;
                        }
                    }
                    $purchaseOrder->save();
                }
            }
            \DB::commit();

            try{
                \Mail::to($purchase->suplier->email)->send(new QuotationMailToSupplier($purchase, $purchase->suplier));
            }catch(\Exception $e){
                \Log::info('Purchase order mail not sent to suplier');
            }
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->route('purchase.orders.index')->withError("Somthing went wrong. Please try again later")->withInput();
        }
        return redirect()->route('purchase.orders.index')->with('success', 'Purchase order has been saved successfully');
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show(Request $request, $id) {
        if (!auth()->user()->can('access', 'purchase orders visible')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $purchase = Purchase::findOrFail($id);
        if(auth()->user()->isRole('Supplier')){
            return redirect()->route('purchase.orders.supplier.purchase.order', $purchase->id);
        }
        $certificate_po = DB::table('purchase_certificate')->select('*')->where('purchase_no',$id)->orderBy('id')->get();
        
        $purchase_deliverynote = DB::table('purchase_deliverynote')->select('*')->where('purchase_no',$id)->orderBy('id')->get();
        
        return view('purchasemanager::purchase_orders.show', compact('purchase','certificate_po','purchase_deliverynote'));
    }

    
    /**
     * Show the specified resource.
     * @return Response
     */
    public function supplierPurchaseOrder(Request $request, $id){
        $purchase = Purchase::findOrFail($id);
        if (!auth()->user()->can('access', 'purchase orders visible') || 
            (!auth()->user()->can('supplier_purchase', $purchase))) {

            return redirect('dashboard')->withError('Not authroized to access!');
        }

        SeenPurchase::updateOrCreate([
            'user_id'=> auth()->id(),
            'purchase_id'=> $purchase->id,
        ], [
            'user_id'=> auth()->id(),
            'purchase_id'=> $purchase->id,
        ]);

        $purchaseStatus = $purchase->statuses()
                            ->where('supplier_id', auth()->id())
                            ->orderBy('id', 'DESC')
                            ->first();

        return view('purchasemanager::purchase_orders.supplier_purchase', compact(
            'purchase',
            'purchaseStatus'
        ));
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function history(Request $request, $id) {
        if (!auth()->user()->can('access', 'purchase orders visible')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $purchase = PurchaseHistory::where('purchase_id', $id)
                            ->where('revision_no', $request->query('revision_no'))
                            ->firstOrFail();
        $orders = PurchaseOrderHistory::where('purchase_id', $id)
                    ->where('revision_no', $request->query('revision_no'))
                    ->get();
        $invoices  = PurchaseInvoiceHistory::where('purchase_id', $id)
                        ->where('revision_no', $request->query('revision_no'))
                        ->get();

        return view('purchasemanager::purchase_orders.history', compact('purchase', 'orders', 'invoices'));
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function printView(Request $request, $id) {
        if (!auth()->user()->can('access', 'purchase orders visible')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $purchase = Purchase::findOrFail($id);
        
        return view('purchasemanager::purchase_orders.print', compact('purchase'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id) {
        if (!auth()->user()->can('access', 'purchase orders add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $purchase = Purchase::findOrFail($id);
        
        if($purchase->orders->isNotEmpty()){
            $selectedActivities = $purchase->orders->pluck('activity_id')->toArray();
        }else{
            $selectedActivities = [];
        }
        
        $unselectedActivities = $purchase->subActivity->activities()
                                    ->whereNotIn('id', $selectedActivities)
                                    ->where('activity', 'NOT LIKE', '%labour%')
                                    ->get();
        
        $certificate_po = DB::table('purchase_certificate')->select('*')->where('purchase_no',$id)->orderBy('id')->get(); 
         $purchase_deliverynote = DB::table('purchase_deliverynote')->select('*')->where('purchase_no',$id)->orderBy('id')->get();

        return view('purchasemanager::purchase_orders.edit', compact('purchase', 'unselectedActivities','certificate_po','purchase_deliverynote'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(UpdatePurchaseOrderRequest $request, $id) {
        
        
        if (!auth()->user()->can('access', 'purchase orders add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $purchase = Purchase::findOrFail($id);
        try {
            \DB::beginTransaction();
            // save purchase
            $purchase->revision_no         = ($purchase->revision_no+1);
            $purchase->delivery_date       = $request->delivery_date;
            $purchase->delivery_time       = $request->delivery_time;
            $purchase->delivery_address    = $request->delivery_address;
            $purchase->carriage_costs      = $request->carriage_costs ?? 0;
            $purchase->c_of_c              = $request->c_of_c ?? 0;
            $purchase->other_costs         = $request->other_costs ?? 0;
            $purchase->grand_total         = $request->grand_total ?? 0;
            $purchase->notes               = $request->notes;
            $purchase->save();	

            $selectedRows = (array)$request->selected_rows;
            // save old purchase order
            if($request->has('old_activities')){
                
                $old_activities = (array)$request->old_activities;
                foreach($old_activities as $old_activity){
                    if(in_array($old_activity['order_id'], $selectedRows)){
                        $oldPurchaseOrder               = PurchaseOrder::findOrFail($old_activity['order_id']);
                        $oldPurchaseOrder->activity     = $old_activity['activity'];
                        $oldPurchaseOrder->unit         = $old_activity['unit'];
                        $oldPurchaseOrder->quantity     = $old_activity['quantity'];
                        $oldPurchaseOrder->rate         = $old_activity['rate'];
                        $oldPurchaseOrder->total        = $old_activity['total'];

                        if(\Arr::has($old_activity, 'file')){
                            $file = $old_activity['file'];
                            if ($file->isValid()) {
                                $extension = $file->getClientOriginalExtension();
                                $filename  = str_random(10).'-' . time() . '.' . $extension;
                                $file->storeAs('purchases', $filename, 'public');
                                $oldPurchaseOrder->photo = $filename;
                            }
                        }
                        $oldPurchaseOrder->updated_at        = date('Y-m-d');
                        $oldPurchaseOrder->save();
                    }
                }
            }
            // save new purchase order
            if($request->has('new_activities')){
                $new_activities = (array)$request->new_activities;
                foreach($new_activities as $new_activity){
                    if(in_array($new_activity['activity_id'], $selectedRows)){
                        $newPurchaseOrder                  = new PurchaseOrder;
                        $newPurchaseOrder->purchase_id     = $purchase->id;
                        $newPurchaseOrder->activity_id     = $new_activity['activity_id'];
                        $newPurchaseOrder->activity        = $new_activity['activity'];
                        $newPurchaseOrder->unit            = $new_activity['unit'];
                        $newPurchaseOrder->quantity        = $new_activity['quantity'];
                        $newPurchaseOrder->rate            = $new_activity['rate'];
                        $newPurchaseOrder->total           = $new_activity['total'];

                        if(\Arr::has($new_activity, 'file')){
                            $file = $new_activity['file'];
                            if ($file->isValid()) {
                                $extension      = $file->getClientOriginalExtension();
                                $filename       = str_random(10).'-' . time() . '.' . $extension;
                                $file->storeAs('purchases', $filename, 'public');
                                $newPurchaseOrder->photo = $filename;
                            }
                        }
                        $newPurchaseOrder->save();
                    }
                }
            }
            
            //certificate
            if($request->has('old_cert')){
                $old_cert = (array)$request->old_cert;
                foreach($old_cert as $old){
                    $headshotName2 = "";   
                    if(array_key_exists('certificate', $old)){
                     
                        $headshotName2 = time() . '.' . $old['certificate']->getClientOriginalExtension();
                        $file = $old['certificate']->move(public_path('uploads/purchase_certificate'), $headshotName2);
                        $certificate_po_update1 = DB::table('purchase_certificate')->where('id', $old['id'])->update(['certificate' => $headshotName2]);
                    }elseif(array_key_exists('test_certificate', $old)){
                        $headshotName3 = $old['test_certificate'];
                        $certificate_po_update2 = DB::table('purchase_certificate')->where('id', $old['id'])->update(['certificate' => $headshotName3]);
                    }else{
                        $certificate_po_delete = DB::table('purchase_certificate')->where('id',$old['id'])->delete(); 
                    }
                }
            }
            if($request->has('certificates')){
                $certificate = (array)$request->certificates;
                $headshotName4="";
                foreach($certificate as $new){
                  
                    $headshotName4 = rand() . time() . '.' . $new->getClientOriginalExtension(); 
                  
                    $file = $new->move(public_path('uploads/purchase_certificate'), $headshotName4);
                    DB::table('purchase_certificate')->insert(['certificate' => $headshotName4,'purchase_no' => $id]);
                    
                }
            }
            
            //purchase note
            if($request->has('old_delivery')){
                $old_delivery = (array)$request->old_delivery;
                foreach($old_delivery as $oldd){
                    $headshotName8 = "";   
                    if(array_key_exists('delivery_note', $oldd)){
                     
                        $headshotName8 = time() . '.' . $oldd['delivery_note']->getClientOriginalExtension();
                        $file = $oldd['delivery_note']->move(public_path('uploads/purchase_deliverynote'), $headshotName8);
                        $delivery_po_update1 = DB::table('purchase_deliverynote')->where('id', $oldd['id'])->update(['delivery_note' => $headshotName8, 'note' => $oldd['note']]);
                    }elseif(array_key_exists('test_delivery_note', $oldd)){
                        $headshotName9 = $oldd['test_delivery_note'];
                        $delivery_po_update2 = DB::table('purchase_deliverynote')->where('id', $oldd['id'])->update(['delivery_note' => $headshotName9, 'note' => $oldd['note']]);
                    }else{
                        $delivery_po_delete = DB::table('purchase_deliverynote')->where('id',$oldd['id'])->delete(); 
                    }
                }
            }
            if($request->has('deliverynote')){
                $certificatess = (array)$request->deliverynote;
              
                $headshotName5="";
                foreach($certificatess as $new){
                 
                    $headshotName5 = rand() . time() . '.' . $new['file']->getClientOriginalExtension(); 
                  
                    $file = $new['file']->move(public_path('uploads/purchase_deliverynote'), $headshotName5);
                    DB::table('purchase_deliverynote')->insert(['delivery_note' => $headshotName5,'purchase_no' => $id,'note' => $new['note']]);
                    
                }
            }
            
            
            // save old purchase invoice
            if($request->has('old_invoices')){
                
                $old_invoices = (array)$request->old_invoices;
               //print_r($old_invoices);exit;
                foreach($old_invoices as $old_invoice){
                    if(count($old_invoice) == 1){
                        $oldInvoice                    = PurchaseInvoice::findOrFail($old_invoice['invoice_id']);
                        $oldInvoice->delete();
                    }else{
                    $headshotName1 = "";
                    if (array_key_exists("invoice_file", $old_invoice)) {
                    
                        $headshotName1 = time() . '.' . $old_invoice['invoice_file']->getClientOriginalExtension();
                        $file = $old_invoice['invoice_file']->move(public_path('uploads/purchase_invoice_file'), $headshotName1);
                    }elseif (array_key_exists("test", $old_invoice)) {
                        $headshotName1 = $old_invoice['test'];
                    }
                    
                    
                    $oldInvoice                    = PurchaseInvoice::findOrFail($old_invoice['invoice_id']);
                    $oldInvoice->invoice_no        = $old_invoice['invoice_no'];
                    $oldInvoice->invoice_amount    = $old_invoice['invoice_amount'];
                    $oldInvoice->invoice_file      = $headshotName1;
                    $oldInvoice->invoice_date      = $old_invoice['invoice_date'];
                    $oldInvoice->updated_at        = date('Y-m-d');
                    $oldInvoice->save();
                    }
                }
            }
            // save new purchase invoice
            if($request->has('new_invoices')){
                $new_invoices = (array)$request->new_invoices;
                foreach($new_invoices as $new_invoice){
                    
                    //print_r($new_invoice['invoice_file']->getClientOriginalExtension());exit;
                     
                    $headshotName = "";
                    if (!empty($new_invoice['invoice_file'])) {
                        $headshotName = time() . '.' . $new_invoice['invoice_file']->getClientOriginalExtension();
                        $file = $new_invoice['invoice_file']->move(public_path('uploads/purchase_invoice_file'), $headshotName);
                    } 
                     
                    $newInvoice                    = new PurchaseInvoice;
                    $newInvoice->purchase_id       = $purchase->id;
                    $newInvoice->invoice_no        = $new_invoice['invoice_no'];
                    $newInvoice->invoice_amount    = $new_invoice['invoice_amount'];
                    $newInvoice->invoice_file    = $headshotName;
                    $newInvoice->invoice_date      = $new_invoice['invoice_date'];
                    $newInvoice->save();
                }
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->route('purchase.orders.index')->withError($e->getMessage())->withInput();
        }
        return redirect()->route('purchase.orders.index')->with('success', 'Purchase order has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id) {
        if (!auth()->user()->can('access', 'purchase orders add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        try {
            $purchaseOrder = Purchase::findOrFail($id);
            DB::beginTransaction();
            $purchaseOrder->delete();
            DB::commit();
            return redirect()->route('purchase.orders.index')->with('success', 'The purchase order has been deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('purchase.orders.index')->with('error', 'Somthing went wrong. Please try again later.');
        }
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function approveInvoice($id) {
        if (!auth()->user()->can('access', 'purchase orders add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        try {
            $purchaseInvoice = PurchaseInvoice::findOrFail($id);
            DB::beginTransaction();
            $purchaseInvoice->approver_id = auth()->id();
            $purchaseInvoice->save();
            DB::commit();
            return redirect()->route('purchase.orders.view', $purchaseInvoice->purchase_id)->with('success', 'The purchase order invoice has been approved!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('purchase.orders.index')->with('error', 'Somthing went wrong. Please try again later.');
        }
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function sendInvoiceMailToSuppliers(Request $request, $id) {
        if (!auth()->user()->can('access', 'purchase orders add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $purchase = Purchase::findOrFail($id);
        $data = $request->all();
        try{
            \Mail::to($purchase->supplier->email)->send(new PurchaseOrderInvoiceMailToSupplier($purchase, $data));
        }catch(\Exception $e){
            return redirect()->route('purchase.orders.index')->with('error', 'Something we nt wrong. Please try again later.');
        }
        return redirect()->route('purchase.orders.index')->with('success', 'Purchase order mail has been sent to supplier.');
    }


    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function createSeparate() {
        
        
        if (!auth()->user()->can('access', 'purchase orders add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $query = Project::whereStatus(1);
        if(!auth()->user()->isRole('Super Admin')){
            if(auth()->user()->isRole('Admin')){
                $query->where('company_id', auth()->id());
            }else{
               $query =DB::table('users_project')
				->join('projects', 'projects.id', '=', 'users_project.project_id')
				->select('projects.id As id','projects.company_id AS company_id','projects.project_title AS project_title','projects.status AS status','projects.version As version','users_project.users_id')
				->where([
				['company_id', auth()->user()->company_id],
				['users_project.users_id', auth()->id()],
				['status',1]
			   ]);
                //$query->where('company_id', auth()->user()->company_id);
            }
        }
		if((!auth()->user()->isRole('Admin')) && (!auth()->user()->isRole('Super Admin'))){
        $projects = $query->get();
        $projects = $projects->pluck('project_title', 'id');
        //$projects->prepend('All', '');
		}else{
		$projects = $query->get(['id', 'project_title', 'version']);
        $projects = $projects->pluck('display_project_title', 'id');
        $projects->prepend('Select Project', '');
		}
       
        $suppliers = \App\User::whereHas('roles', function($q){
        $q->whereName('Supplier');
        })->whereStatus(1);
        
        if(!auth()->user()->isRole('Super Admin')){
        if(auth()->user()->isRole('Admin')){
        	$suppliers->where('company_id', auth()->id());
        }else{
        	$suppliers->where('company_id', auth()->user()->company_id);
        }
        }
        $suppliers = $suppliers->pluck('supplier_name', 'id');
        $suppliers->prepend('Select', '');
        return view('purchasemanager::purchase_orders.create_separate', compact('projects','suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function storeSeparate(SaveSeparatePurchaseOrderRequest $request) {
       
        if (!auth()->user()->can('access', 'purchase orders add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        

        
        try {
            \DB::beginTransaction();
            if($request->has('activities')){
                $activies = (array)$request->activities;
                $selectedRows = (array)$request->selected_rows;
                foreach($selectedRows as $selectedRow){
                    // create new purchase 
                    $purchase_no = Purchase::where('project_id', $request->project_id)->max('purchase_no');
                    $purchase                      = new Purchase();
                    $purchase->project_id          = $request->project_id;
                    $purchase->main_activity_id    = $request->level;
                    $purchase->sub_activity_id     = $request->sub_code;
                    $purchase->purchase_no         = $purchase_no ? ($purchase_no+1) : 1;
                    $purchase->delivery_date       = $request->delivery_date;
                    $purchase->delivery_time       = $request->delivery_time;
                    $purchase->delivery_address    = $request->delivery_address;
                    $purchase->supplier_id         = $activies[$selectedRow]['supplier_id'];
                    $purchase->carriage_costs      = $activies[$selectedRow]['carriage_costs'];
                    $purchase->c_of_c              = $activies[$selectedRow]['c_of_c'];
                    $purchase->other_costs         = $request->other_costs ?? 0;
                    $purchase->grand_total         = $activies[$selectedRow]['grand_total'];
                    $purchase->notes               = $activies[$selectedRow]['notes'];
                    $purchase->save();

                    // create purchase order
                    $purchaseOrder = new PurchaseOrder;
                    $purchaseOrder->purchase_id = $purchase->id;
                    $purchaseOrder->activity_id = $activies[$selectedRow]['activity_id'];
                    $purchaseOrder->activity = $activies[$selectedRow]['activity'];
                    $purchaseOrder->unit = $activies[$selectedRow]['unit'];
                    $purchaseOrder->quantity = $activies[$selectedRow]['quantity'];
                    $purchaseOrder->rate = $activies[$selectedRow]['rate'];
                    $purchaseOrder->total = $activies[$selectedRow]['total'];
                    if(\Arr::has($activies[$selectedRow], 'file')){
                        $file = $activies[$selectedRow]['file'];
                        if ($file->isValid()) {
                            $extension = $file->getClientOriginalExtension();
                            $filename  = str_random(10).'-' . time() . '.' . $extension;
                            $file->storeAs('purchases', $filename, 'public');
                            $purchaseOrder->photo = $filename;
                        }
                    }
                    $purchaseOrder->save();
                  
                    try{
                        \Mail::to($purchase->suplier->email)->send(new QuotationMailToSupplier($purchase, $purchase->suplier));
                    }catch(\Exception $e){
                        \Log::info('Purchase order mail not sent to suplier');
                    }
                    
                    
                }
            }	
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            //return $e;
            return redirect()->route('purchase.orders.index')->withError("Somthing went wrong. Please try again later")->withInput();
        }
        return redirect()->route('purchase.orders.index')->with('success', 'Purchase order has been saved successfully');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @param  Int $id
     * @return Response
     */
    public function updateStatus(Request $request, $id){
        $purchase = Purchase::findOrFail($id);
        if (!auth()->user()->can('access', 'purchase orders visible') || 
            (!auth()->user()->can('supplier_purchase', $purchase))) {

            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $request->validate([
            'status' => ['required', 'max:100']
        ]);
        try {
            \DB::beginTransaction();
            PurchaseStatus::updateOrCreate(
                [
                    'purchase_id'=> $purchase->id,
                    'supplier_id'=> $request->supplier_id,
                ], 
                [
                    'purchase_id'=> $purchase->id,
                    'supplier_id'=> $request->supplier_id,
                    'status' => $request->status
                ]
            );	
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->route('purchase.orders.supplier.purchase.order', $purchase->id)->withError("Somthing went wrong. Please try again later")->withInput();
        }
        return redirect()->route('purchase.orders.supplier.purchase.order', $purchase->id)->with('success', 'Purchase order status has been updated successfully');
    }

}

<?php

namespace Modules\PurchaseManager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\ProjectManager\Entities\Project;
use Modules\PurchaseManager\Entities\Purchase;
use Modules\PurchaseManager\Entities\Quotation;
use Modules\PurchaseManager\Entities\PurchaseOrder;
use Modules\PurchaseManager\Entities\SeenQuotation;
use Modules\PurchaseManager\Entities\QuotationReply;
use Modules\PurchaseManager\Entities\QuotationMaterial;
use Modules\PurchaseManager\Mail\QuotationMailToSupplier;
use Modules\PurchaseManager\Mail\QuotationReplyMailToAdmin;
use Modules\PurchaseManager\Entities\QuotationReplyMaterial;
use Modules\PurchaseManager\Mail\PurchaseOrderMailToSupplier;
use Modules\PurchaseManager\Mail\QuotationReplyMailToSupplier;
use Modules\PurchaseManager\Http\Requests\SendQuotationRequest;
use Modules\PurchaseManager\Http\Requests\SaveQuotationRequest;
use Modules\PurchaseManager\Http\Requests\UpdateQuotationRequest;

class QuotationManagerController extends Controller {

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
        $projects = $query->get();
        $projects = $projects->pluck('project_title', 'id');
        $projects->prepend('All', '');
		 return view('purchasemanager::quotations.index', compact('projects'));
		}else{
		$projects = $query->get(['id', 'project_title', 'version']);
        $projects = $projects->pluck('display_project_title', 'id');
        $projects->prepend('All', '');
		 return view('purchasemanager::quotations.index', compact('projects'));
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
        $projects->prepend('Select Project', '');
		return view('purchasemanager::quotations.create', compact('projects'));
		}else{
		$projects = $query->get(['id', 'project_title', 'version']);
        $projects = $projects->pluck('display_project_title', 'id');
        $projects->prepend('Select Project', '');
		return view('purchasemanager::quotations.create', compact('projects'));
		}
       
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(SaveQuotationRequest $request) {
        if (!auth()->user()->can('access', 'purchase orders add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        try {
            \DB::beginTransaction();
            $quotation                      = new Quotation();
            $quotation->project_id          = $request->project_id;
            $quotation->main_activity_id    = $request->level;
            $quotation->sub_activity_id     = $request->sub_code;
            $quotation->user_id             = auth()->id();
            $quotation->delivery_date       = $request->delivery_date;
            $quotation->delivery_time       = $request->delivery_time;
            $quotation->delivery_address    = $request->delivery_address;
            $quotation->notes               = $request->notes;
            $quotation->save();	

            if($request->has('activities')){
                $activies = (array)$request->activities;
                $selectedRows = (array)$request->selected_rows;
                foreach($selectedRows as $selectedRow){
                    $quotationMaterial = new QuotationMaterial;
                    $quotationMaterial->quotation_id = $quotation->id;
                    $quotationMaterial->activity_id = $activies[$selectedRow]['activity_id'];
                    $quotationMaterial->activity = $activies[$selectedRow]['activity'];
                    $quotationMaterial->unit = $activies[$selectedRow]['unit'];
                    $quotationMaterial->quantity = $activies[$selectedRow]['quantity'];
                    $quotationMaterial->rate = $activies[$selectedRow]['rate'];
                    $quotationMaterial->total = $activies[$selectedRow]['total'];
                    if(\Arr::has($activies[$selectedRow], 'file')){
                        $file = $activies[$selectedRow]['file'];
                        if ($file->isValid()) {
                            $extension = $file->getClientOriginalExtension();
                            $filename  = str_random(10).'-' . time() . '.' . $extension;
                            $file->storeAs('quotations', $filename, 'public');
                            $quotationMaterial->photo = $filename;
                        }
                    }
                    $quotationMaterial->save();
                }
            }

            \DB::commit();	
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->route('quotations.index')->withError($e->getMessage())->withInput();
        }
        return redirect()->route('quotations.index')->with('success', 'Quotation has been saved successfully');
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id) {
        if (!auth()->user()->can('access', 'purchase orders visible')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $quotation = Quotation::findOrFail($id);

        if(auth()->user()->isRole('Supplier')){
            return redirect()->route('quotations.supplier.quotation', $quotation->id);
        }
        
        return view('purchasemanager::quotations.show', compact('quotation'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id) {
        if (!auth()->user()->can('access', 'purchase orders add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $quotation = Quotation::findOrFail($id);
        
        if($quotation->materials->isNotEmpty()){
            $selectedActivities = $quotation->materials->pluck('activity_id')->toArray();
        }else{
            $selectedActivities = [];
        }
        
        $unselectedActivities = $quotation->subActivity->activities()
            ->whereNotIn('id', $selectedActivities)
            ->where(function($q){
                $q->where('activity', 'NOT LIKE', '%labour%');
                $q->where('activity', 'NOT LIKE', '%install%');
            })
            ->get();
        return view('purchasemanager::quotations.edit', compact('quotation', 'unselectedActivities'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(UpdateQuotationRequest $request, $id) {
        if (!auth()->user()->can('access', 'purchase orders add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $quotation = Quotation::findOrFail($id);
        try {
            \DB::beginTransaction();

            $quotation->delivery_date       = $request->delivery_date;
            $quotation->delivery_time       = $request->delivery_time;
            $quotation->delivery_address    = $request->delivery_address;
            $quotation->notes               = $request->notes;
            $quotation->save();	

            if($request->has('activities')){
                $activies = (array)$request->activities;
                $selectedRows = (array)$request->selected_rows;
                foreach($selectedRows as $selectedRow){
                    if(\Arr::has($activies[$selectedRow], 'material_id')){
                        $quotationMaterial = QuotationMaterial::findOrFail($activies[$selectedRow]['material_id']);
                    }else{
                        $quotationMaterial = new QuotationMaterial;
                        $quotationMaterial->quotation_id = $quotation->id;
                        $quotationMaterial->activity_id = $activies[$selectedRow]['activity_id'];
                    }
                    $quotationMaterial->activity = $activies[$selectedRow]['activity'];
                    $quotationMaterial->unit = $activies[$selectedRow]['unit'];
                    $quotationMaterial->quantity = $activies[$selectedRow]['quantity'];
                    $quotationMaterial->rate = $activies[$selectedRow]['rate'];
                    $quotationMaterial->total = $activies[$selectedRow]['total'];
                    if(\Arr::has($activies[$selectedRow], 'file')){
                        $file = $activies[$selectedRow]['file'];
                        if ($file->isValid()) {
                            $extension = $file->getClientOriginalExtension();
                            $filename  = str_random(10).'-' . time() . '.' . $extension;
                            $file->storeAs('quotations', $filename, 'public');
                            $quotationMaterial->photo = $filename;
                        }
                    }
                    $quotationMaterial->save();
                }
            }

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->route('quotations.index')->withError($e->getMessage())->withInput();
        }
        return redirect()->route('quotations.index')->with('success', 'Quotation has been saved successfully');
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function sendQuotation(Request $request, $id) {
        if (!auth()->user()->can('access', 'purchase orders add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $quotation = Quotation::findOrFail($id);

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

        return view('purchasemanager::quotations.send', compact('quotation', 'suppliers'));
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function sendQuotationToSuppliers(SendQuotationRequest $request, $id) {
        if (!auth()->user()->can('access', 'purchase orders add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $quotation = Quotation::findOrFail($id);
        try {
            DB::beginTransaction();

            if($request->has('supplier_ids')){
                $quotation->suppliers()->sync($request->supplier_ids);
            }

            DB::commit();

            $quotation->suppliers->each(function($suplier) use($quotation){
                try{
                    \Mail::to($suplier->email)->send(new QuotationMailToSupplier($quotation, $suplier));
                }catch(\Exception $e){
                    dd($e);
                    \Log::info('Quotation mail not sent to supplier');
                }
            });

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('quotations.index')->withError($e->getMessage())->withInput();
        }
        return redirect()->route('quotations.index')->with('success', 'Quotation has been sent successfully');
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function supplierQuotation(Request $request, $id) {
        $quotation = Quotation::findOrFail($id);
       /* if (!auth()->user()->can('access', 'purchase orders visible') || 
            (!auth()->user()->can('supplier_quotation', $quotation))) {

            return redirect('dashboard')->withError('Not authroized to access!');
        }*/

        $replyMaterialQuery = $quotation->replyMaterials()
                                ->where('supplier_id', auth()->id());
        
        if($replyMaterialQuery->exists()){
            $materials = $replyMaterialQuery->get();
        }else{
            $materials = $quotation->materials;
            foreach($materials as $key=>$all){
            
                
               $materials[$key]->rate = 0;
                
            }
           
        } 
        $allSuppliersQuotations = $quotation->replies()
                                    ->where('supplier_id', auth()->id())
                                    ->get();

        $conversations = $allSuppliersQuotations;

        $finalQuotation = $allSuppliersQuotations->last();
        
        SeenQuotation::updateOrCreate([
            'user_id'=> auth()->id(),
            'quotation_id'=> $quotation->id,
        ], [
            'user_id'=> auth()->id(),
            'quotation_id'=> $quotation->id,
        ]);
        
        $grandTotal = $materials->sum('total');
        
        $grandTotal = ($grandTotal + $quotation->carriage_costs + $quotation->c_of_c + $quotation->other_costs);
        
        return view('purchasemanager::quotations.supplier_quotation', compact(
            'quotation', 
            'materials',
            'finalQuotation',
            'conversations',
            'grandTotal'
        ));
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function sendQuotationReplyBySupplier(Request $request, $id){
        $quotation = Quotation::findOrFail($id);

        if (!auth()->user()->can('access', 'purchase orders visible') || 
            !auth()->user()->can('supplier_quotation', $quotation)) {

            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $request->validate([
            'notes' => ['required', 'max:100']
        ]);
        try {
            \DB::beginTransaction();
            $supplier = auth()->user();
            // Save quotation reply materials
            if($request->has('materials')){
                $materials = (array)$request->materials;
                $selectedRows = (array)$request->selected_rows;
                foreach($selectedRows as $selectedRow){
                    $filename = "";
                    if(\Arr::has($materials[$selectedRow], 'file')){
                        $file = $materials[$selectedRow]['file'];
                        if ($file->isValid()) {
                            $extension = $file->getClientOriginalExtension();
                            $filename  = str_random(10).'-' . time() . '.' . $extension;
                            $file->storeAs('quotations', $filename, 'public');
                            $quotationReplyMaterial->photo = $filename;
                        }
                    }
                    QuotationReplyMaterial::updateOrCreate([
                        'quotation_id' => $quotation->id,
                        'supplier_id' => $supplier->id,
                        'activity_id' => $materials[$selectedRow]['activity_id'],
                    ], [
                        'activity'=> $materials[$selectedRow]['activity'],
                        'unit'=> $materials[$selectedRow]['unit'],
                        'quantity'=> $materials[$selectedRow]['quantity'],
                        'rate'=> $materials[$selectedRow]['rate'],
                        'total'=> $materials[$selectedRow]['total'],
                        'photo'=> $filename,
                    ]);
                }
            }

            // Save quptation reply
            
            $quotationReply = new QuotationReply();
            $quotationReply->quotation_id = $quotation->id;
            $quotationReply->supplier_id = $supplier->id;
            $quotationReply->sender_id = $supplier->id;
            $quotationReply->notes = $request->notes;
            $quotationReply->carriage_costs = $request->carriage_costs;
            $quotationReply->c_of_c = $request->c_of_c;
            $quotationReply->other_costs = $request->other_costs;
            $quotationReply->save();
            
            \DB::commit();
            
            try{
                \Mail::to($suplier->email)->send(new QuotationReplyMailToAdmin($quotation, $supplier->company));
            }catch(\Exception $e){
                \Log::info('Quotation reply mail not sent to company');
            }
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->route('quotations.supplier.quotation', $quotation->id)->withError($e->getMessage())->withInput();
        }
        return redirect()->route('quotations.supplier.quotation', $quotation->id)->with('success', 'Quotation has been saved successfully');
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function sendQuotationReplyByAdmin(Request $request, $id){
        $quotation = Quotation::findOrFail($id);
         
        if (!auth()->user()->can('access', 'purchase orders add')/*|| !auth()->user()->can('admin_quotation', $quotation)*/) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $request->validate([
            'notes' => ['required', 'max:100']
        ]);
        try {
            \DB::beginTransaction();
            if($request->has('action')){
                // send quotation reply to supplier
                if($request->action == 'reply'){
                    // Save quptation reply
                    $quotationReply                 = new QuotationReply();
                    $quotationReply->quotation_id   = $quotation->id;
                    $quotationReply->supplier_id    = $request->supplier_id;
                    $quotationReply->sender_id      = auth()->id();
                    $quotationReply->notes          = $request->notes;
                    $quotationReply->carriage_costs = $request->carriage_costs;
                    $quotationReply->c_of_c         = $request->c_of_c;
                    $quotationReply->other_costs    = $request->other_costs;
                    $quotationReply->save();
                    \DB::commit();
                    
                    try{
                        \Mail::to($suplier->email)->send(new QuotationReplyMailToSupplier($quotation, $supplier->company));
                    }catch(\Exception $e){
                        \Log::info('Quotation reply mail not sent to supplier');
                    }
                    return redirect()->route('quotations.view', $quotation->id)->with('success', 'Quotation reply has been sent successfully');
                }
                // send purchase order to supplier
                if($request->action == 'purchase'){

                    $purchase_no                    = Purchase::where('project_id', $quotation->project_id)->max('purchase_no');
                    $purchase                      = new Purchase();
                    $purchase->project_id          = $quotation->project_id;
                    $purchase->main_activity_id    = $quotation->main_activity_id;
                    $purchase->sub_activity_id     = $quotation->sub_activity_id;
                    $purchase->supplier_id         = $request->supplier_id;
                    $purchase->purchase_no         = $purchase_no ? ($purchase_no+1) : 1;
                    $purchase->delivery_date       = $quotation->delivery_date;
                    $purchase->delivery_time       = $quotation->delivery_time;
                    $purchase->delivery_address    = $quotation->delivery_address;
                    $purchase->carriage_costs      = $request->carriage_costs ?? 0;
                    $purchase->c_of_c              = $request->c_of_c ?? 0;
                    $purchase->other_costs         = $request->other_costs ?? 0;
                    $purchase->grand_total         = $request->grand_total ?? 0;
                    $purchase->notes               = $request->notes;
                    $purchase->save();	

                    if($request->has('materials')){
                        $materials = (array)$request->materials;
                        foreach($materials as $material){
                            $purchaseOrder                  = new PurchaseOrder;
                            $purchaseOrder->purchase_id     = $purchase->id;
                            $purchaseOrder->activity_id     = $material['activity_id'];
                            $purchaseOrder->activity        = $material['activity'];
                            $purchaseOrder->unit            = $material['unit'];
                            $purchaseOrder->quantity        = $material['quantity'];
                            $purchaseOrder->rate            = $material['rate'];
                            $purchaseOrder->total           = $material['total'];
                            $purchaseOrder->photo           = $material['photo'];
                            $purchaseOrder->save(); 
                        }
                    }
                    \DB::commit();
    
                    try{
                        \Mail::to($purchase->suplier->email)->send(new PurchaseOrderMailToSupplier($purchase, $purchase->suplier));
                    }catch(\Exception $e){
                        \Log::info('Purchase order mail not sent to supplier');
                    }
                    return redirect()->route('quotations.view', $quotation->id)->with('success', 'Quotation purchase order has been sent successfully');
                }
            }
            throw new \Exception('error');
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->route('quotations.view', $quotation->id)->withError($e->getMessage())->withInput();
        }
    }

}

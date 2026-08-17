<?php

namespace Modules\PurchaseManager\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\ProjectManager\Entities\Project;
use Modules\PurchaseManager\Entities\Purchase;
use Modules\PurchaseManager\Entities\Quotation;
use Modules\EstimateManager\Entities\SubActivity;
use Modules\EstimateManager\Entities\MainActivity;
use Yajra\DataTables\Utilities\Request as DatatableRequest;


class CommanAjaxController extends Controller
{
    /**
     * Get the specified resource from storage.
     * @param DatatableRequest $request
     * @return Response
     */
    public function getQuotations(DatatableRequest $request) {

        if($request->ajax()){
            $query = Quotation::select(['id', 'user_id', 'project_id', 'notes', 'delivery_date', 'delivery_address']);
            if(!auth()->user()->isRole('Super Admin')){
                if(auth()->user()->isRole('Admin')){
                    $query->whereHas('project', function($q){
                        $q->where('company_id', auth()->id());
                    });
                }else if(auth()->user()->isRole('Supplier')){
                    $query->whereHas('suppliers', function($q){
                        $q->where('id', auth()->id());
                    });
                }else{
					
					$query =DB::table('quotations')
					->join('projects','projects.id', '=', 'quotations.project_id')
					->join('users','users.id', '=', 'quotations.user_id')
					->join('users_project','users_project.project_id', '=', 'quotations.project_id')
					->select('quotations.id', 'quotations.user_id', 'quotations.project_id', 'quotations.notes', 'quotations.delivery_date', 'quotations.delivery_address','users.full_name','projects.company_id','users_project.users_id')
					->where([
					    ['projects.company_id', auth()->user()->company_id],
						['users_project.users_id', auth()->id()]
					   ]);
                    /*$query->whereHas('project', function($q){
                        $q->where('company_id', auth()->user()->company_id);
                    });*/
                }
            }

			 if((!auth()->user()->isRole('Admin')) && (!auth()->user()->isRole('Super Admin')) && (!auth()->user()->isRole('Supplier'))){
						$query->when($request->project_filter_id, function($q) use($request){
						
							$q->where('quotations.project_id', $request->project_filter_id);
							
						});
				}else{
					  $query->when($request->project_filter_id, function($q) use($request){
							
								$q->where('project_id', $request->project_filter_id);
								
							});
				}
			 
            return datatables()->of($query)
                    ->addColumn('name', function ($quotation) {
						 if((!auth()->user()->isRole('Admin')) && (!auth()->user()->isRole('Super Admin')) && (!auth()->user()->isRole('Supplier'))){
                        return $quotation->full_name;
						}else{
						return $quotation->user->full_name;	
						}
                    })
                     ->addColumn('rfq', function ($quotation) {
                         $proj = DB::table('projects')->select('*')->where('id',$quotation->project_id)->first(); 
if(str_word_count($proj->project_title) >1){
    $words = explode(" ", $proj->project_title);
    $acronym = "";
    foreach ($words as $w) {
        $acronym .= $w[0];
    }

}else{
   $acronym = substr($proj->project_title,0,3);
    
}
                       
                         
                        return $acronym."-".str_pad($quotation->id, 5, "0", STR_PAD_LEFT);
                        //return "HS-".str_pad($quotation->id, 5, "0", STR_PAD_LEFT); 
                    })
                    ->addColumn('action', function ($quotation) {
                                $actions = "";
                                $actions .= "<a href=\"" . route('quotations.view', ['id' => $quotation->id]) . "\" class=\"btn btn-primary btn-sm\"><i class=\"fas fa-eye\"></i></a>";
                                if (auth()->user()->can('access', 'purchase orders add') ) {
                                    $actions .= "&nbsp;<a href=\"" . route('quotations.edit', ['id' => $quotation->id]) . "\" class=\"btn btn-info btn-sm\"><i class=\"fas fa-pencil-alt\"></i></a>";
                                    $actions .= "&nbsp;<a href=\"" . route('quotations.send.quotation', ['id' => $quotation->id]) . "\" class=\"btn btn-info btn-sm\"><i class=\"fas fa-paper-plane\"></i></a>";
                                }
                                return $actions;
                            })
                    ->make(true);
        }
    }

    /**
     * Get the specified resource from storage.
     * @param DatatableRequest $request
     * @return Response
     */
    public function getPurchaseOrders(DatatableRequest $request) {
       
        if($request->ajax()){
			
           $query = Purchase::select(['id', 'project_id', 'supplier_id', 'revision_no', 'purchase_no', 'grand_total', 'delivery_date', 'created_at']);
            if(!auth()->user()->isRole('Super Admin')){
                if(auth()->user()->isRole('Admin')){
                    $query->whereHas('project', function($q){
                        $q->where('company_id', auth()->id());
                    });
                }else if(auth()->user()->isRole('Supplier')){
                    $query->where('supplier_id', auth()->id());
                }else{
					$query =DB::table('purchases')
					->join('projects','projects.id', '=', 'purchases.project_id')
					->join('users_project','users_project.project_id', '=', 'purchases.project_id')
					->join('users','users.id', '=', 'purchases.supplier_id')
					->select('purchases.id', 'purchases.project_id', 'purchases.supplier_id AS supplier_id', 'purchases.revision_no AS revision_no', 'purchases.purchase_no', 'purchases.grand_total', 'purchases.delivery_date', 'purchases.created_at','users.supplier_name AS supplier_name','projects.unique_reference_no')
					->where([
					    ['projects.company_id', auth()->user()->company_id],
						['users_project.users_id', auth()->id()]
					   ]);
                    /*$query->whereHas('project', function($q){
                        $q->where('company_id', auth()->user()->company_id);
                    });*/
                }
            }
            if((!auth()->user()->isRole('Admin')) && (!auth()->user()->isRole('Super Admin')) && (!auth()->user()->isRole('Supplier'))){
            $query->when($request->project_filter_id, function($q) use($request){
                $q->where('purchases.project_id', $request->project_filter_id);
            });
            $query->when($request->delivery_date, function($q) use($request){
                $q->whereDate('purchases.delivery_date', $request->delivery_date);
            });
            }else{
				$query->when($request->project_filter_id, function($q) use($request){
                $q->where('project_id', $request->project_filter_id);
            });
            $query->when($request->delivery_date, function($q) use($request){
                $q->whereDate('delivery_date', $request->delivery_date);
            });
			}
            return datatables()->of($query)
                    ->addColumn('supplier', function ($purchase) {
						 if((!auth()->user()->isRole('Admin')) && (!auth()->user()->isRole('Super Admin')) && (!auth()->user()->isRole('Supplier'))){
                        return $purchase->supplier_name;
						 }else{
						 return @$purchase->supplier->supplier_name;
						 }
                    })
                    ->editColumn('revision_no', function ($purchase) {
                        $html = "<select onchange='location = this.value;'>";
                        $html .= "<option value='' disabled selected>Rev ".$purchase->revision_no."</option>";
                        for($rn = 1; $rn <= $purchase->revision_no; $rn++){
                            $html .= "<option value='".route('purchase.orders.history', ['id'=>$purchase->id, 'revision_no'=>$rn])."'>".$rn."</option>";
                        }
                        $html .= "</select>";
                        return $html;
                    })
                   ->editColumn('purchase_no', function ($purchase) {
					    if((!auth()->user()->isRole('Admin')) && (!auth()->user()->isRole('Super Admin')) && (!auth()->user()->isRole('Supplier'))){
                        return $purchase->unique_reference_no.'-'.str_pad($purchase->purchase_no, 3, '0', STR_PAD_LEFT);
						}else{
							 return @$purchase->project->unique_reference_no.'-'.str_pad($purchase->purchase_no, 3, '0', STR_PAD_LEFT);
						}
                    })
                     ->editColumn('grand_total', function ($purchase) {
                        
                        return '&pound;'.round($purchase->grand_total,2);
                    })
                    ->editColumn('created_at', function ($purchase) {
                        return $purchase->created_at;
                    })
                    ->editColumn('delivery_date', function ($purchase) {
						  if((!auth()->user()->isRole('Admin')) && (!auth()->user()->isRole('Super Admin')) && (!auth()->user()->isRole('Supplier'))){
                          return $purchase->delivery_date;
						  }else{
						  return $purchase->delivery_date->format('Y-m-d');
						  }
                    })
                    /*->editColumn('invoice_no', function ($purchase) {
                        $invoices_po = DB::table('purchase_invoices')->select('*')->where('purchase_id',$purchase->id)->orderBy('id')->get(); 
                        $inv_id="";
                        foreach($invoices_po as $inv){
                            //$inv_id.=$inv->invoice_no."\n\r"."\\";
                            $inv_id.=$inv->invoice_no."<\br>"."\n"."\r";
                                
                        }
                        
                        return $inv_id;
                    })
                    ->editColumn('invoice_amount', function ($purchase) {
                        $invoices_po = DB::table('purchase_invoices')->select('*')->where('purchase_id',$purchase->id)->orderBy('id')->get(); 
                        $inv_amount="";
               
                        foreach($invoices_po as $inv){
                            $inv_amount.=$inv->invoice_amount."\n"."\\";
                          
                        }
                        
                        return $inv_amount;
                    })
                    /*->editColumn('invoice_file', function ($purchase) {
                        $invoices_po = DB::table('purchase_invoices')->select('*')->where('purchase_id',$purchase->id)->orderBy('id')->get(); 
                        $inv_file="";
                        foreach($invoices_po as $inv){
                       
                       
                        if(!empty($inv->invoice_file)){
                            $urls=asset("uploads/purchase_invoice_file/$inv->invoice_file"); 
                            $inv_file.= '<img src='.$urls.' border="0" width="40" class="img-rounded" align="center" />';      
                            $url=asset("uploads/purchase_invoice_file/$inv->invoice_file"); 
                            return '<img src='.$url.' border="0" width="40" class="img-rounded" align="center" />';
                        }else{
                            $inv_file.= "<img src=''>";
                        } 
    

                        }
                        
                        return $inv_file;
                    })*/
                    ->addColumn('action', function ($purchase) {
                        $actions = "";
                        $actions .= "<a title=\"View purchase order\" href=\"" . route('purchase.orders.view', $purchase->id) . "\" class=\"btn btn-primary btn-sm\"><i class=\"fas fa-eye\"></i></a>";
                        if (auth()->user()->can('access', 'purchase orders add')) {
                            $actions .= "&nbsp;<a title=\"Print & View purchase order\" href=\"" . route('purchase.orders.print.view', $purchase->id) . "\" class=\"btn btn-warning btn-sm\"><i class=\"fas fa-print\"></i></a>";
                            $actions .= "&nbsp;<a title=\"Edit purchase order\" href=\"" . route('purchase.orders.edit', $purchase->id) . "\" class=\"btn btn-info btn-sm\"><i class=\"fas fa-pencil-alt\"></i></a>";
                            $actions .= "&nbsp;<a title=\"Delete purchase order\" onclick=\"return confirm('Are you sure want to remove the purchase order?')\" href=\"" . route('purchase.orders.delete', $purchase->id) . "\"  class=\"btn btn-danger btn-sm\"><i class=\"fas fa-trash\"></i></a>";
                        }
                        return $actions;
                    })
                    ->rawColumns(['revision_no', 'grand_total', 'action'])
                    ->make(true);
        }
    }

    /**
     * Get quotation from.
     * @param Request $request
     * @return Response
     */
    public function getQuotationForm(Request $request){
        $view = "";
         $project = Project::find($request->query('project_id'));
        if($request->ajax()){
            $subActivity = SubActivity::find($request->query('sub_activity_id'));
            if($subActivity){
                $query = $subActivity->activities();
                $query->where(function($q){
                    $q->where('activity', 'NOT LIKE', '%labour%');
                    $q->where('activity', 'NOT LIKE', '%install%');
                });
                $activities = $query->get();
                $activities = $activities->filter(function($item, $key){
                                    if(($item->activity != 'Labour') || ($item->activity != 'labour') || ($item->activity != 'Install') || ($item->activity != 'install')){
                                        return true;
                                    }
                                });

                $view = view('purchasemanager::quotations.add_form', compact('activities','project'))->render();
            }
        }
        return response()->json(['html'=> $view]);
    }

    /**
     * Get purchase order from.
     * @param Request $request
     * @return Response
     */
    public function getPurchaseOrderForm(Request $request){
        $view = "";
        $project = Project::find($request->query('project_id'));

        if($request->ajax()){
            $subActivity = SubActivity::find($request->query('sub_activity_id'));
            if($subActivity){
                $query = $subActivity->activities();
                $query->where('activity', 'NOT LIKE', '%labour%');
                $activities = $query->get();
                $activities = $activities->filter(function($item, $key){
                                    if(($item->activity != 'Labour') || ($item->activity != 'labour')){
                                        return true;
                                    }
                                });

                $view = view('purchasemanager::purchase_orders.add_form', compact('activities','project'))->render();
            }
        }
        return response()->json(['html'=> $view]);
    }

    /**
     * Get separate purchase order from.
     * @param Request $request
     * @return Response
     */
    public function getSeparatePurchaseOrderForm(Request $request){
        $view = "";
        $project = Project::find($request->query('project_id'));

        if($request->ajax()){
            $subActivity = SubActivity::find($request->query('sub_activity_id'));
            if($subActivity){
                $query = $subActivity->activities();
                $query->where('activity', 'NOT LIKE', '%labour%');
                $activities = $query->get();
                $activities = $activities->filter(function($item, $key){
                                    if(($item->activity != 'Labour') || ($item->activity != 'labour')){
                                        return true;
                                    }
                                });


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

                $view = view('purchasemanager::purchase_orders.add_form_separate', compact('activities', 'suppliers','project'))->render();
            }
        }
        return response()->json(['html'=> $view]);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Response
     */
    public function getAreasAndLevels(Request $request){
        $areas = [];
        $levels = [];
        if($request->ajax()){
            $projectId = $request->query('project_id');
            $project = Project::find($projectId);
            if($project){
                $areas = $project->mainActivities->pluck('area_display_name', 'id');
                $areas->prepend('Select area', '');

                $levels = $project->mainActivities->pluck('level_display_name', 'id');
                $levels->prepend('Select level', '');
            }
        }
        return response()->json([
            'areas'=> $areas,
            'levels' => $levels
        ]);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Response
     */
    public function getAreas(Request $request){
        $areas = [];
        if($request->ajax()){
            $projectId = $request->query('project_id');
            $project = Project::find($projectId);
            if($project){
                $areas = $project->mainActivities->pluck('area_display_name', 'id');
            }
        }
        return response()->json($areas);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Response
     */
    public function getLevels(Request $request){
        $levels = [];
        if($request->ajax()){
            $projectId = $request->query('project_id');
            $project = Project::find($projectId);
            if($project){
                $mainActivityId = $request->query('main_activity_id');
                $levels = $project->mainActivities->where('id', $mainActivityId)->pluck('level_display_name', 'id');
            }
        }
        return response()->json($levels);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Response
     */
    public function getSubCodes(Request $request){
        $subCodes = [];
        if($request->ajax()){
            $mainActivityId = $request->query('main_activity_id');
            $mainActivity = MainActivity::find($mainActivityId);
            if($mainActivity){
                $subActivities = $mainActivity->subActivities;
                $subCodes = $subActivities->pluck('activity_display_name', 'id');
            }
        }
        return response()->json($subCodes);
    }


}

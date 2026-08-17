<?php

namespace Modules\EstimateManager\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Imports\ImportActivity;
use Illuminate\Routing\Controller;
use App\Exports\ExportActivityExpanded;
use App\Exports\ExportActivityCollapsed;
use Modules\ProjectManager\Entities\Project;
use Modules\EstimateManager\Entities\MainActivity;
use Modules\EstimateManager\Http\Requests\ImportActivityRequest;
use Modules\EstimateManager\Http\Requests\UpdateProjectEstimateRequest;
use Illuminate\Support\Facades\DB;
use Auth;

class CarbonManagerController extends Controller
{

     /**
     * Display a listing of the resource.
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function projectEstimates(Request $request, $id = null,$default=null){
        if (!auth()->user()->can('access', 'carbontoolkit visible')) {
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
        $allProjects = $query->get();
        $allProjects = $allProjects->pluck('project_title', 'id');
        //$projects->prepend('All', '');
		}else{
		$allProjects = $query->get(['id', 'project_title', 'version']);
        $allProjects = $allProjects->pluck('display_project_title', 'id');
        $allProjects->prepend('Select Project', '');
		}
        
        $user=auth()->user();
        if(isset($user->default_project) && !isset($id) )
        {
       $project = Project::find($user->default_project);

       if($project)
        $id=$user->default_project;

        }

    
       

        if($id){
            $project = Project::findOrFail($id);
            $mainActivities = $project->mainActivities()->paginate(20);
            $roles = Role::select('name')
                    ->whereNotIn('slug', ['super_admin', 'admin', 'supplier'])
                    ->where('status', 1)
                    ->get();
            $roles = $roles->sortBy('name');
            $roles = $roles->mapWithKeys(function ($item) {
                return [$item['name'] => $item['name']];
            });

            $roles->prepend('Select role', '');
           
            

            return view('estimatemanager::project3.activities', compact('allProjects', 'roles', 'project', 'mainActivities','user'));
        } 
        
      return view('estimatemanager::project3.activities', compact(['allProjects','user']));
    }

    /**
     * Display project estimate import view.
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function importProjectEstimateView(Request $request){
        if (!auth()->user()->can('access', 'estimates add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $query = Project::whereStatus(1);
        if(!auth()->user()->isRole('Super Admin')){
            if(auth()->user()->isRole('Admin')){
                $query->where('company_id', auth()->id());
            }else{
                $query->where('company_id', auth()->user()->company_id);
            }
        }
        $allProjects = $query->get(['id', 'project_title', 'version']);
        $allProjects = $allProjects->pluck('display_project_title', 'id');
        $allProjects->prepend('Select project', '');

        return view('estimatemanager::project.importEstimate', compact('allProjects'));
    }

    /**
     * Expand project estimate.
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function projectEstimateExcelExpanded(Request $request, $id){
        ob_end_clean(); 
        //ob_start();
        return (new ExportActivityExpanded($id))->download('estimate.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
    /**
     * Collaps project estimate.
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function projectEstimateExcelCollapsed(Request $request, $id){
        ob_end_clean(); 
        //ob_start();
        return (new ExportActivityCollapsed($id))->download('estimate.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    /**
     * Display project estimate import view.
     * @param ImportActivityRequest $request
     * @param $id
     * @return Response
     */
    public function importProjectEstimate(ImportActivityRequest $request){
        if (!auth()->user()->can('access', 'estimates add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        try {
            $path = $request->file('file')->store('temp'); 
            //$path = storage_path('app').'/'.$path;  
            $import = new ImportActivity($request->project_id);
            $import->import($path);
        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }

        return redirect()->route('estimates.projects', $request->project_id)->with("success", "Estimates has been imported successfully!");
    }

    /**
     * Copy project in to db
     * @param Request $request
     * @param $projectId
     * @return Response
     */
  public function copyProject(Request $request, $projectId){
        if (!auth()->user()->can('access', 'estimates add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $project = Project::findOrFail($projectId);
        try{
            \DB::beginTransaction();
            
            $project->mainActivities->each(function($mainActivity){
                if($mainActivity->project->mainActivities->isNotEmpty()){
                    $mainCode = ($mainActivity->project->mainActivities->last()->main_code+1);
                }else{
                    $mainCode = 1;
                }
                $newMainActivity = $mainActivity->replicate();
                $newMainActivity->main_code = $mainCode;
                $newMainActivity->save();
                $mainActivity->subActivities->each(function($subActivity) use($mainActivity, $newMainActivity){
                    if($mainActivity->subActivities->isNotEmpty()){
                        $subCodes = explode('.', $mainActivity->subActivities->last()->sub_code);
                        $subCode = (end($subCodes)+1);
                    }else{
                        $subCode = 1;
                    }
                    $newSubActivity = $subActivity->replicate();
                    $newSubActivity->main_activity_id = $newMainActivity->id;
                    $newSubActivity->sub_code = $mainActivity->main_code.'.'.$subCode;
                    $newSubActivity->save();
                    $subActivity->activities->each(function($activity) use($subActivity, $newSubActivity){
                        if($subActivity->activities->isNotEmpty()){
                            $itemCodes = explode('.', $subActivity->activities->last()->item_code);
                            $itemCode = (end($itemCodes)+1);
                        }else{
                            $itemCode = 1;
                        }
                        $newActivity = $activity->replicate();
                        $newActivity->sub_activity_id = $newSubActivity->id;
                        $newActivity->item_code = $subActivity->sub_code.'.'.$itemCode;
                        $newActivity->save();
                    });
                });
            });
            \DB::commit();
        }catch(\Exception $e){
            \DB::rollBack();
            return back()->withError($e->getMessage())->withInput();
        }
        return redirect()->route('estimates.projects', $project->id)->with('success', 'Project has been coppied successfully');
    }
  

    /**
     * Get the specified resource from storage.
     * @param Request $request
     * @return Response
     */
  /*  public function saveProject(Request $request, $id){
        if (!auth()->user()->can('access', 'estimates add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        try {
            \DB::beginTransaction();

            $project = Project::findOrFail($id);

            $project_total = 0;
            $thr_main = 0;
            $tmhr_main = 0;
            $base_margin = $project->base_margin;
            $base_labour = $project->labour_value;
            $formulas = $project->formulas->where('keyword', '!=', '');
            if($project->mainActivities->isNotEmpty()){
                foreach($project->mainActivities as $mainActivity){
                    $sub_activity_total = 0;
                    $thr_sub = 0;
	                $tmhr_sub = 0;
                    if($mainActivity->subActivities->isNotEmpty()){
                        foreach($mainActivity->subActivities as $subActivity){
                            $activity_total = 0;
                            $thr = 0;
							$tmhr = 0;
                            if($subActivity->activities->isNotEmpty()){
                                foreach($subActivity->activities as $activity){

                                    $rate = $activity->rate;
                                    $unit_trim = preg_replace('/\s+/', '', $activity->unit);

                                    if($unit_trim == "hr"){
                                        $rate = $base_labour;
                                    }
                                    $selling_cost = $rate/(1-($base_margin/100));
                                    if($formulas->isNotEmpty()){
                                        foreach($formulas as $formula){
                                            $str = $formula->formula;
                                            $pt1 = "/x/i";
                                            $str = preg_replace($pt1, "*", $str);
                                            $pt2 = "/([a-z])+/i";
                                            $str = preg_replace($pt2, "\$$0", $str);
                                            $pt3 = "/([0-9])+%/";
                                            $str = preg_replace($pt3, "($0/100)", $str);
                                            $pt4 = "/%/";
                                            $str = preg_replace($pt4, "", $str);
                                            if($unit_trim == $formula->keyword){
                                                $rate = $base_labour;
                                                $comm = $rate;
                                                $e = "\$comm = $str;";
                                                eval($e);  
                                                $rate = $comm; 
                                                $selling_cost = $comm/(1-($base_margin/100));
                                            }
                                        }
                                    }
                                    $total = ($selling_cost * $activity->quantity);
                                    $totalco = ($activity->co * $activity->quantity);
                                    $activity_total = $activity_total + $total;

                                    if($unit_trim == "hr"){
                                        $thr = $thr + $total;	
                                    }
                                    if($unit_trim == "mhr"){
                                        $tmhr = $tmhr + $total;	 
                                    }
                                    $activity->activity = $activity->activity;
                                    $activity->unit = $activity->unit;
                                    $activity->quantity = $activity->quantity;
                                    $activity->rate = $rate;
                                    $activity->selling_cost = $selling_cost;
                                    $activity->total = $total;
                                    $activity->totalco = $totalco;
                                   
                                    // save activity
                                    $activity->save();
                                }
                            }
                            $rate = $activity_total;
                            $total = $rate * $subActivity->quantity;
                            $sub_activity_total = $sub_activity_total + $total;
                            
                            $hr = $subActivity->hr;
                            $total_hr = $subActivity->total_hr;
                            $mhr = $subActivity->mhr;
                            $total_mhr = $subActivity->total_mhr;
                            
                            $hr = $thr;
                            $mhr = $tmhr;

                            $total_hr = $hr * $subActivity->quantity;
                            $total_mhr = $mhr * $subActivity->quantity;
                            
                            $thr_sub = $thr_sub + $total_hr;
                            $tmhr_sub = $tmhr_sub + $total_mhr;
			                $subActivity->activity = $subActivity->activity;
			                $subActivity->quantity = $subActivity->quantity;
			                $subActivity->rate = $rate;
			                $subActivity->total = $total;
							$subActivity->hr = $hr;
							$subActivity->mhr = $mhr;
							$subActivity->total_hr = $total_hr;
                            $subActivity->total_mhr = $total_mhr;
                            // save sub activity
                            $subActivity->save();
                        }
                    }
                    $rate = $sub_activity_total;
                    $total = $rate * $mainActivity->quantity;
                    $project_total =  $project_total + $total;	

                    $hr = $mainActivity->hr;
                    $total_hr = $mainActivity->total_hr;
                    $mhr = $mainActivity->mhr;
                    $total_mhr = $mainActivity->total_mhr;
                                            
                    $hr = $thr_sub;
                    $mhr = $tmhr_sub;

                    $total_hr = $hr * $mainActivity->quantity;
                    $total_mhr = $mhr * $mainActivity->quantity;                   
                    $thr_main = $thr_main + $total_hr;
                    $tmhr_main = $tmhr_main + $total_mhr;
                    if(($mainActivity->unit_qty > 0) && ($total > 0)){
                        $unit_rate = ($total/$mainActivity->unit_qty);
                    }else{
                        $unit_rate = 0;
                    }	
                    $mainActivity->area = $mainActivity->area;
                    $mainActivity->level = $mainActivity->level;
                    $mainActivity->activity = $mainActivity->activity;
                    $mainActivity->quantity = $mainActivity->quantity;
                    $mainActivity->rate = $rate;
                    $mainActivity->total = $total;
                    $mainActivity->hr = $hr;
                    $mainActivity->mhr = $mhr;
                    $mainActivity->total_hr = $total_hr;
                    $mainActivity->total_mhr = $total_mhr;
                    $mainActivity->unit_qty = $mainActivity->unit_qty;
                    $mainActivity->unit_rate = $unit_rate;
                    $mainActivity->unit = $mainActivity->unit;
                    // save main activity
                    $mainActivity->save();
                }
            }
            $project->project_total = $project_total;
            // save project
            $project->save();
            // commit database
            \DB::commit();
           return '<div class="col-md-12"><div class="row"><h2 style="color:red;text-align: center;padding: 100px;">Estimate has been Created Successfully<br><br><a href="../'.$id.'" style="color:red;text-align: center;padding: 100px;">Cancel</a><a href="../../../suppliers/add" style="color:red;text-align: center;padding: 100px;">Create Supplier </a>(OR)<a href="../../../timesheets/staff" style="color:red;text-align: center;padding: 100px;">Create Timesheets</a></h2></div></div>';
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->route('estimates.projects', $id)->with('error', 'Somthing went wrong. Please try again later.');
        }
    }


    /**
     * Get the specified resource from storage.
     * @param UpdateProjectEstimateRequest $request
     * @return Response
     */
    public function updateEstimateAndProject(UpdateProjectEstimateRequest $request) {
        if (!auth()->user()->can('access', 'estimates add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        try {
            \DB::beginTransaction();

            $project = Project::findOrFail($request->project_id);
            if($request->base_margin){
                $project->base_margin = $request->base_margin;
            }
            if($request->base_labour){
                $project->labour_value = $request->base_labour;
            }
            $project_total = 0;
            $thr_main = 0;
            $tmhr_main = 0;
            $base_margin = $project->base_margin;
            $base_labour = $project->labour_value;

            $formulas = $project->formulas->where('keyword', '!=', '');

            if($project->mainActivities->isNotEmpty()){
                foreach($project->mainActivities as $mainActivity){
                    $sub_activity_total = 0;
                    $thr_sub = 0;
	                $tmhr_sub = 0;
                    if($mainActivity->subActivities->isNotEmpty()){
                        foreach($mainActivity->subActivities as $subActivity){
                            $activity_total = 0;
                            $thr = 0;
							$tmhr = 0;
                            if($subActivity->activities->isNotEmpty()){
                                foreach($subActivity->activities as $activity){

                                    $rate = $activity->rate;
                                    $unit_trim = preg_replace('/\s+/', '', $activity->unit);

                                    if($unit_trim == "hr"){
                                        $rate = $base_labour;
                                    }
                                    $selling_cost = $rate/(1-($base_margin/100));
                                    if($formulas->isNotEmpty()){
                                        foreach($formulas as $formula){
                                            $str = $formula->formula;
                                            $pt1 = "/x/i";
                                            $str = preg_replace($pt1, "*", $str);
                                            $pt2 = "/([a-z])+/i";
                                            $str = preg_replace($pt2, "\$$0", $str);
                                            $pt3 = "/([0-9])+%/";
                                            $str = preg_replace($pt3, "($0/100)", $str);
                                            $pt4 = "/%/";
                                            $str = preg_replace($pt4, "", $str);
                                            if($unit_trim == $formula->keyword){
                                                $rate = $base_labour;
                                                $comm = $rate;
                                                $e = "\$comm = $str;";
                                                eval($e);  
                                                $rate = $comm; 
                                                $selling_cost = $comm/(1-($base_margin/100));
                                            }
                                        }
                                    }
                                    $total = ($selling_cost * $activity->quantity);
                                    $activity_total = $activity_total + $total;

                                    if($unit_trim == "hr"){
                                        $thr = $thr + $total;	
                                    }
                                    if($unit_trim == "mhr"){
                                        $tmhr = $tmhr + $total;	 
                                    }
                                    $activity->activity = $activity->activity;
                                    $activity->unit = $activity->unit;
                                    $activity->quantity = $activity->quantity;
                                    $activity->rate = $rate;
                                    $activity->selling_cost = $selling_cost;
                                    $activity->total = $total;
                                    // save activity
                                    $activity->save();
                                }
                            }
                            $rate = $activity_total;
                            $total = $rate * $subActivity->quantity;
                            $sub_activity_total = $sub_activity_total + $total;
                            
                            $hr = $subActivity->hr;
                            $total_hr = $subActivity->total_hr;
                            $mhr = $subActivity->mhr;
                            $total_mhr = $subActivity->total_mhr;
                            
                            $hr = $thr;
                            $mhr = $tmhr;

                            $total_hr = $hr * $subActivity->quantity;
                            $total_mhr = $mhr * $subActivity->quantity;
                            
                            $thr_sub = $thr_sub + $total_hr;
                            $tmhr_sub = $tmhr_sub + $total_mhr;
			                $subActivity->activity = $subActivity->activity;
			                $subActivity->quantity = $subActivity->quantity;
			                $subActivity->rate = $rate;
			                $subActivity->total = $total;
							$subActivity->hr = $hr;
							$subActivity->mhr = $mhr;
							$subActivity->total_hr = $total_hr;
                            $subActivity->total_mhr = $total_mhr;
                            // save sub activity
                            $subActivity->save();
                        }
                    }
                    $rate = $sub_activity_total;
                    $total = $rate * $mainActivity->quantity;
                    $project_total =  $project_total + $total;	

                    $hr = $mainActivity->hr;
                    $total_hr = $mainActivity->total_hr;
                    $mhr = $mainActivity->mhr;
                    $total_mhr = $mainActivity->total_mhr;
                                            
                    $hr = $thr_sub;
                    $mhr = $tmhr_sub;

                    $total_hr = $hr * $mainActivity->quantity;
                    $total_mhr = $mhr * $mainActivity->quantity;                   
                    $thr_main = $thr_main + $total_hr;
                    $tmhr_main = $tmhr_main + $total_mhr;
                    if(($mainActivity->unit_qty > 0) && ($total > 0)){
                        $unit_rate = ($total/$mainActivity->unit_qty);
                    }else{
                        $unit_rate = 0;
                    }	
                    $mainActivity->area = $mainActivity->area;
                    $mainActivity->level = $mainActivity->level;
                    $mainActivity->activity = $mainActivity->activity;
                    $mainActivity->quantity = $mainActivity->quantity;
                    $mainActivity->rate = $rate;
                    $mainActivity->total = $total;
                    $mainActivity->hr = $hr;
                    $mainActivity->mhr = $mhr;
                    $mainActivity->total_hr = $total_hr;
                    $mainActivity->total_mhr = $total_mhr;
                    $mainActivity->unit_qty = $mainActivity->unit_qty;
                    $mainActivity->unit_rate = $unit_rate;
                    $mainActivity->unit = $mainActivity->unit;
                    // save main activity
                    $mainActivity->save();
                }
            }
            $project->project_total = $project_total;
            // save project
            $project->save();
            // commit database
            \DB::commit();
            return redirect()->route('estimates.projects', $request->project_id)->with('success', 'Base margin and base labour has been updated successfully!');
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->route('estimates.projects', $request->project_id)->with('error', 'Somthing went wrong. Please try again later.');
        }
    }
    /**
     * Get the specified resource from storage.
     * @param Request $request
     * @return Response
     */
    public function updateEstimateAndProjectFormula(Request $request) {
        if (!auth()->user()->can('access', 'estimates add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        try {
            \DB::beginTransaction();

            $project = Project::findOrFail($request->project_id);
            if($request->base_margin){
                $project->base_margin = $request->base_margin;
            }
            if($request->base_labour){
                $project->labour_value = $request->base_labour;
            }
            $project_total = 0;
            $thr_main = 0;
            $tmhr_main = 0;
            $base_margin = $project->base_margin;
            $base_labour = $project->labour_value;

            $formulas = $project->formulas->where('keyword', '!=', '');

            if($project->mainActivities->isNotEmpty()){
                foreach($project->mainActivities as $mainActivity){
                    $sub_activity_total = 0;
                    $thr_sub = 0;
	                $tmhr_sub = 0;
                    if($mainActivity->subActivities->isNotEmpty()){
                        foreach($mainActivity->subActivities as $subActivity){
                            $activity_total = 0;
                            $thr = 0;
							$tmhr = 0;
                            if($subActivity->activities->isNotEmpty()){
                                foreach($subActivity->activities as $activity){

                                    $rate = $activity->rate;
                                    $unit_trim = preg_replace('/\s+/', '', $activity->unit);

                                    if($unit_trim == "hr"){
                                        $rate = $base_labour;
                                    }
                                    $selling_cost = $rate/(1-($base_margin/100));
                                    if($formulas->isNotEmpty()){
                                        foreach($formulas as $formula){
                                            $str = $formula->formula;
                                            $pt1 = "/x/i";
                                            $str = preg_replace($pt1, "*", $str);
                                            $pt2 = "/([a-z])+/i";
                                            $str = preg_replace($pt2, "\$$0", $str);
                                            $pt3 = "/([0-9])+%/";
                                            $str = preg_replace($pt3, "($0/100)", $str);
                                            $pt4 = "/%/";
                                            $str = preg_replace($pt4, "", $str);
                                            if($unit_trim == $formula->keyword){
                                                $rate = $base_labour;
                                                $comm = $rate;
                                                $e = "\$comm = $str;";
                                                eval($e);  
                                                $rate = $comm; 
                                                $selling_cost = $comm/(1-($base_margin/100));
                                            }
                                        }
                                    }
                                    $total = ($selling_cost * $activity->quantity);
                                    $activity_total = $activity_total + $total;

                                    if($unit_trim == "hr"){
                                        $thr = $thr + $total;	
                                    }
                                    if($unit_trim == "mhr"){
                                        $tmhr = $tmhr + $total;	 
                                    }
                                    $activity->activity = $activity->activity;
                                    $activity->unit = $activity->unit;
                                    $activity->quantity = $activity->quantity;
                                    $activity->rate = $rate;
                                    $activity->selling_cost = $selling_cost;
                                    $activity->total = $total;
                                    // save activity
                                    $activity->save();
                                }
                            }
                            $rate = $activity_total;
                            $total = $rate * $subActivity->quantity;
                            $sub_activity_total = $sub_activity_total + $total;
                            
                            $hr = $subActivity->hr;
                            $total_hr = $subActivity->total_hr;
                            $mhr = $subActivity->mhr;
                            $total_mhr = $subActivity->total_mhr;
                            
                            $hr = $thr;
                            $mhr = $tmhr;

                            $total_hr = $hr * $subActivity->quantity;
                            $total_mhr = $mhr * $subActivity->quantity;
                            
                            $thr_sub = $thr_sub + $total_hr;
                            $tmhr_sub = $tmhr_sub + $total_mhr;
			                $subActivity->activity = $subActivity->activity;
			                $subActivity->quantity = $subActivity->quantity;
			                $subActivity->rate = $rate;
			                $subActivity->total = $total;
							$subActivity->hr = $hr;
							$subActivity->mhr = $mhr;
							$subActivity->total_hr = $total_hr;
                            $subActivity->total_mhr = $total_mhr;
                            // save sub activity
                            $subActivity->save();
                        }
                    }
                    $rate = $sub_activity_total;
                    $total = $rate * $mainActivity->quantity;
                    $project_total =  $project_total + $total;	

                    $hr = $mainActivity->hr;
                    $total_hr = $mainActivity->total_hr;
                    $mhr = $mainActivity->mhr;
                    $total_mhr = $mainActivity->total_mhr;
                                            
                    $hr = $thr_sub;
                    $mhr = $tmhr_sub;

                    $total_hr = $hr * $mainActivity->quantity;
                    $total_mhr = $mhr * $mainActivity->quantity;                   
                    $thr_main = $thr_main + $total_hr;
                    $tmhr_main = $tmhr_main + $total_mhr;
                    if(($mainActivity->unit_qty > 0) && ($total > 0)){
                        $unit_rate = ($total/$mainActivity->unit_qty);
                    }else{
                        $unit_rate = 0;
                    }	
                    $mainActivity->area = $mainActivity->area;
                    $mainActivity->level = $mainActivity->level;
                    $mainActivity->activity = $mainActivity->activity;
                    $mainActivity->quantity = $mainActivity->quantity;
                    $mainActivity->rate = $rate;
                    $mainActivity->total = $total;
                    $mainActivity->hr = $hr;
                    $mainActivity->mhr = $mhr;
                    $mainActivity->total_hr = $total_hr;
                    $mainActivity->total_mhr = $total_mhr;
                    $mainActivity->unit_qty = $mainActivity->unit_qty;
                    $mainActivity->unit_rate = $unit_rate;
                    $mainActivity->unit = $mainActivity->unit;
                    // save main activity
                    $mainActivity->save();
                }
            }
            $project->project_total = $project_total;
            // save project
            $project->save();
            // commit database
            \DB::commit();
            return redirect()->route('estimates.projects', $request->project_id)->with('success', 'Base margin and base labour has been updated successfully!');
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->route('estimates.projects', $request->project_id)->with('error', 'Somthing went wrong. Please try again later.');
        }
    }
    
    public function library_by_project($id, Request $request){
        
        
       
        
        if(Auth::attempt(['email'=>trim($request->email),'password'=>trim($request->password)]))
        {
            
            
            
            
            $check_status = \DB::table('users')->where('email', $request->email)->first();
            
            
            $query = Project::whereStatus(1);
            if(!auth()->user()->isRole('Super Admin')){
            if(auth()->user()->isRole('Admin')){
            $query->where('company_id', auth()->id());
            }else{
            $query->where('company_id', auth()->user()->company_id);
            }
            }
            $projects = $query->get(['id', 'project_title', 'version']);
            $projects = $projects->pluck('id');
            $_array =array();
            if(count($projects) ){
                foreach($projects as $dat){
                    
                    array_push($_array, $dat);
                }
            }
            
            if (!in_array($id, $_array) && (!auth()->user()->isRole('Super Admin')))
            {
                return response()->json(["msg"=>"Invalid Company Project Id!"]);
            }
            
            if ($check_status->start_date != 0 && $check_status->end_date != 0) {
            
                $paymentDate = date('Y-m-d');
                $paymentDate=date('Y-m-d', strtotime($paymentDate));
                $contractDateBegin =$check_status->start_date;
                $contractDateEnd = $check_status->end_date;
                
                if (($paymentDate >= $contractDateBegin) && ($paymentDate <= $contractDateEnd)){
                    /*$live_task = \DB::table('library_main_activities')->select('main_code','activity','unit','rate','area')->where('project_id',$id)->get()->toArray();
                    if(count($live_task) > 0){
                        foreach($live_task as $k=>$ids){
              
                        }
                        
                        return response()->json(["msg"=>"true", "data" => $live_task]);
                    }else{
                        return response()->json(["msg"=>"No record found!"]);
                    }*/
                    
                }else{
                    return response()->json(["msg"=>"Plan Expired!"]); 
                }
            }
            
            /*else{*/
                $live_task = \DB::table('library_main_activities')->select('main_code','activity','unit','rate','area')->where('project_id',$id)->get()->toArray();
                if(count($live_task) > 0){
                    foreach($live_task as $k=>$ids){
          
                    }
                    
                    return response()->json(["msg"=>"true", "data" => $live_task]);
                }else{
                    return response()->json(["msg"=>"No record found!"]);
                }
                
            /*}*/
        
        
        }else{
            return response()->json(["msg"=>"Invalid Username or Password!"]); 
        }
               
       
       
    }
    
    public function expanded_library_by_project($id, Request $request){
        
        
        if(Auth::attempt(['email'=>trim($request->email),'password'=>trim($request->password)]))
        {
            
            
            
            
            $check_status = \DB::table('users')->where('email', $request->email)->first();
            
            
            $query = Project::whereStatus(1);
            if(!auth()->user()->isRole('Super Admin')){
            if(auth()->user()->isRole('Admin')){
            $query->where('company_id', auth()->id());
            }else{
            $query->where('company_id', auth()->user()->company_id);
            }
            }
            $projects = $query->get(['id', 'project_title', 'version']);
            $projects = $projects->pluck('id');
            $_array =array();
            if(count($projects) ){
                foreach($projects as $dat){
                    
                    array_push($_array, $dat);
                }
            }
            
            if (!in_array($id, $_array) && (!auth()->user()->isRole('Super Admin')))
            {
                return response()->json(["msg"=>"Invalid Company Project Id!"]);
            }
            
            if ($check_status->start_date != 0 && $check_status->end_date != 0) {
            
                $paymentDate = date('Y-m-d');
                $paymentDate=date('Y-m-d', strtotime($paymentDate));
                $contractDateBegin =$check_status->start_date;
                $contractDateEnd = $check_status->end_date;
                
                if (($paymentDate >= $contractDateBegin) && ($paymentDate <= $contractDateEnd)){
                    /*$live_task = \DB::table('library_main_activities')->select('main_code','activity','unit','rate','area')->where('project_id',$id)->get()->toArray();
                    if(count($live_task) > 0){
                        foreach($live_task as $k=>$ids){
              
                        }
                        
                        return response()->json(["msg"=>"true", "data" => $live_task]);
                    }else{
                        return response()->json(["msg"=>"No record found!"]);
                    }*/
                    
                }else{
                    return response()->json(["msg"=>"Plan Expired!"]); 
                }
            }
            
            /*else{*/
                $live_task = \DB::table('library_main_activities')->select('id','main_code AS code','activity','unit','rate','area')->where('project_id',$id)->get()->toArray();
                if(count($live_task) > 0){
                    $dat=array();
                    foreach($live_task as $k=>$ids){
                 
                       $sub_act = \DB::table('library_sub_activities')->join('library_main_activities', 'library_sub_activities.library_main_activity_id', '=', 'library_main_activities.id')->select('library_sub_activities.id','library_sub_activities.sub_code AS code','library_sub_activities.activity','library_sub_activities.unit','library_sub_activities.rate','library_main_activities.area')->where('library_main_activity_id',$ids->id)->get()->toArray();
                       
                        $live_task[$k]->sub_activity = $sub_act; 
                        foreach($sub_act as $subk=>$subv){
                           
                            $act = \DB::table('library_activities')->select('item_code AS code','activity','unit','quantity')->where('library_sub_activity_id',$subv->id)->get()->toArray();
                           
                            foreach($act as $x=>$y){
                                $act[$x]->area = $subv->area;
                                
                                
                            }
                            $live_task[$k]->sub_activity[$subk]->activities = $act; 
                            
                        }
          
                    }
                    // print_r($dat);exit;
                    return response()->json(["msg"=>"true", "data" => $live_task]);
                }else{
                    return response()->json(["msg"=>"No record found!"]);
                }
                
            /*}*/
        
        
        }else{
            return response()->json(["msg"=>"Invalid Username or Password!"]); 
        }
               
       
       
    }
    
    public function copytolibrary(Request $request){
        if (!auth()->user()->can('access', 'estimates add')) {
            return redirect('dashboard')->withError('Not authroized to access!');
        }
        $selected_values = $request->selected_values;
        $selected = explode(",",$selected_values);
        $project_id=$request->project_id;
        $main_act = \DB::table('main_activities')->select('*')->whereIn('id', $selected)->get();
        foreach($main_act as $row)  
        { 
		  $main_activityid= $row->id;
		  $area= $row->area;
		  $level= $row->level;
		  $main_code= $row->main_code;
		  $activity= $row->activity;
		  $quantity= $row->quantity;
		  $rate= $row->rate;
		  $total= $row->total;
		  $hr= $row->hr;
		  $total_hr= $row->total_hr;
		  $mhr= $row->mhr;
		  $total_mhr= $row->total_mhr;
		  $unit_qty = $row->unit_qty;
		  $unit_rate = $row->unit_rate;
		  $unit_main = $row->unit;
        
          $inserted_main=  \DB::table('library_main_activities')->insertGetId(
            [
           
            'area'=>$area,
			'level'=>$level,
			'main_code'=>$main_code,
			'activity'=>$activity,
			'quantity'=>$quantity,
			'rate'=>$rate,
			'total'=>$total,
			'project_id'=>$project_id,
			'hr'=>$hr,
			'total_hr'=>$total_hr,
			'mhr'=>$mhr,
			'total_mhr'=>$total_mhr,
			'unit_qty'=>$unit_qty,
			'unit_rate'=>$unit_rate,
			'unit'=>$unit_main]);
         
            //sub
          
            $sub_act = \DB::table('sub_activities')->select('*')->where('main_activity_id', $main_activityid)->get();
            foreach($sub_act as $row_sub){
                $sub_activity_id=$row_sub->id;
				$sub_code= $row_sub->sub_code;
                $activity= $row_sub->activity;
                $quantity= $row_sub->quantity;
                $rate= $row_sub->rate;
                $total= $row_sub->total;
                $hr= $row_sub->hr;
                $total_hr= $row_sub->total_hr;
                $mhr= $row_sub->mhr;
                $total_mhr= $row_sub->total_mhr;
				 
				 $inserted_sub=  \DB::table('library_sub_activities')->insertGetId(
                [
               
                'sub_code'=>$sub_code,
                 'activity'=>$activity,
                 'quantity'=>$quantity,
                 'rate'=>$rate,
                 'total'=>$total,
                 'library_main_activity_id'=>$inserted_main,
				 'hr'=>$hr,
                 'total_hr'=>$total_hr,
                 'mhr'=>$mhr,
                 'total_mhr'=>$total_mhr
                ]);    
            
            $act = \DB::table('activities')->select('*')->where('sub_activity_id', $sub_activity_id)->get();
            foreach($act as $row_act){
                 $item_code= $row_act->item_code;
                 $activity= $row_act->activity;
                 $unit= $row_act->unit;
                 $quantity= $row_act->quantity;
                 $rate= $row_act->rate;
        		 $selling_cost= $row_act->selling_cost;
                 $total= $row_act->total;
				 
				 $inserted_act=  \DB::table('library_activities')->insertGetId(
                [
               
                'item_code'=>$item_code,
                 'activity'=>$activity,
                 'unit'=>$unit,
                 'quantity'=>$quantity,
                 'rate'=>$rate,
				 'selling_cost'=>$selling_cost,
                 'total'=>$total,
                 'library_sub_activity_id'=>$inserted_sub
                ]);    
            }
            
            
            
            
            
            
                
                
                
                
                
            }
        
         }
        
       
        
        return $request->all();
    }

}

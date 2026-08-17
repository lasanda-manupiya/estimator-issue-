<?php

namespace Modules\EstimateManager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\User\Entities\Role;
use Illuminate\Routing\Controller;
use Modules\ProjectManager\Entities\Project;
use Modules\EstimateManager\Entities\LibraryActivity;
use Modules\EstimateManager\Entities\LibrarySubActivity;
use Modules\EstimateManager\Entities\LibraryMainActivity;

use Modules\FormulaManager\Entities\Formula;

class LibraryManagerAjaxController extends Controller
{

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function addMainActivityRow(Request $request, $id){
        $view = "";
        if($request->ajax()){
            $project = Project::findOrFail($id);
            if($project->LibrarymainActivities->isNotEmpty()){
                $mainCode = ($project->librarymainActivities->last()->main_code+1);
            }else{
                $mainCode = 1;
            }
            $mainActivity = new LibraryMainActivity([
                'project_id'=> $project->id,
                'main_code'=> $mainCode,
                'rate' => 0,
                'total' => 0,
                'hr' => 0,
                'mhr' => 0,
                'total_hr' => 0,
                'total_mhr' => 0
                
            ]);
            $mainActivity->save();

            $view = view('estimatemanager::project1.add_main_activity_row', compact('mainActivity'))->render();
        }
        
        return response()->json(['html'=> $view]);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function addSubActivityRow(Request $request, $id){
      
        $view = "";
        if($request->ajax()){

            $mainActivity = LibraryMainActivity::findOrFail($id);
            if($mainActivity->subActivities->isNotEmpty()){
                $subCodes = explode('.', $mainActivity->subActivities->last()->sub_code);
                $subCode = (end($subCodes)+1);
            }else{
                $subCode = 1;
            }

            $subActivity = new LibrarySubActivity([
                'library_main_activity_id' => $mainActivity->id,
                'sub_code'=> $mainActivity->main_code.'.'.$subCode,
                'rate' => 0,
                'total' => 0,
                'hr' => 0,
                'mhr' => 0,
                'total_hr' => 0,
                'total_mhr' => 0
            ]);

            $subActivity->save();

            $view = view('estimatemanager::project1.add_sub_activity_row', compact('subActivity'))->render();
        }
        
        return response()->json(['html'=> $view]);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function addActivityRow(Request $request, $id){
        $view = "";
        if($request->ajax()){
            $subActivity = LibrarySubActivity::findOrFail($id);
            if($subActivity->activities->isNotEmpty()){
                $itemCodes = explode('.', $subActivity->activities->last()->item_code);
                $itemCode = (end($itemCodes)+1);
            }else{
                $itemCode = 1;
            }
            $activity = new LibraryActivity([
                'library_sub_activity_id'=> $subActivity->id,
                'item_code'=> $subActivity->sub_code.'.'.$itemCode,
                'rate'=> 0,
                'selling_cost'=> 0,
                'total' => 0,
                'profit' => 0,
                'mhr_role' => '',
                'mhr_status' => 0
            ]);
            $activity->save();

            $view = view('estimatemanager::project1.add_activity_row', compact('activity'))->render();
        }
        
        return response()->json(['html'=> $view]);
    }

    /**
     * Update the specified resource from storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function updateProjectMainActivityRow(Request $request, $id){
       
        
        if($request->ajax()){
            try {
                $mainActivity = LibraryMainActivity::findOrFail($id);
                \DB::beginTransaction();
                $mainActivity->update([
                    $request->column => $request->value
                ]);
                if($request->column == "quantity" || $request->column == "rate" || $request->column == "total" || $request->column == "unit" || $request->column == "unit_qty")
	            {
                    $mainActivity->quantity = $request->quantity;
                    $mainActivity->rate = $request->rate;
                    $mainActivity->total = $request->total;
                    $mainActivity->hr = $request->hr;
                    $mainActivity->total_hr = $request->total_hr;
                    $mainActivity->mhr = $request->mhr;
                    $mainActivity->total_mhr = $request->total_mhr;
                    $mainActivity->unit_qty = $request->unit_qty;
                    $mainActivity->unit_rate = $request->unit_rate;

                    $mainActivity->save();
                }
                \DB::commit();
                return response()->json(['status'=> true, 'message'=> 'The main activity has been updated successfully!']);
            } catch (\Exception $e) {
                \DB::rollBack();
            }
        }
        return response()->json(['status'=> false, 'message'=> 'Somthing went wrong. Please try again later.']);
    }

    /**
     * Update the specified resource from storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function updateProjectSubActivityRow(Request $request, $id){
     
        if($request->ajax()){
            /*try {*/
                 $subActivity = LibrarySubActivity::findOrFail($id);
                
                \DB::beginTransaction();
                $subActivity->update([
                    $request->column => $request->value
                ]);
               // return $subActivity;
                if($request->column == "quantity" || $request->column == "rate" || $request->column == "total" || $request->column == "unit" || $request->column == "unit_qty")
	            {
	                
                    $subActivity->quantity = $request->quantity_sub;
                    $subActivity->rate = $request->rate_sub;
                    $subActivity->total = $request->total_sub;
                    $subActivity->hr = $request->hr_sub;
                    $subActivity->total_hr = $request->total_hr_sub;
                    $subActivity->mhr = $request->mhr_sub;
                    $subActivity->total_mhr = $request->total_mhr_sub;
                    
                    $subActivity->save();


                    //$mainActivity = $subActivity->mainActivity;
                    $mainActivity = LibraryMainActivity::findOrFail($subActivity->library_main_activity_id);

                    $mainActivity->quantity = $request->quantity_main;
                    $mainActivity->rate = $request->rate_main;
                    $mainActivity->total = $request->total_main;
                    $mainActivity->hr = $request->hr_main;
                    $mainActivity->total_hr = $request->total_hr_main;
                    $mainActivity->mhr = $request->mhr_main;
                    $mainActivity->total_mhr = $request->total_mhr_main;
                    $mainActivity->unit_qty = $request->unit_qty_main;
                    $mainActivity->unit_rate = $request->unit_rate_main;

                    $mainActivity->save();
                }
                \DB::commit();
                return response()->json(['status'=> true, 'message'=> 'The sub activity has been updated successfully!']);
            /*} catch (\Exception $e) {
                \DB::rollBack();
            }*/
        }
        return response()->json(['status'=> false, 'message'=> 'Somthing went wrong. Please try again later.']);
    }

    /**
     * Update the specified resource from storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function updateProjectActivityRow(Request $request, $id){
        if($request->ajax()){
            try {
                $activity = LibraryActivity::findOrFail($id);
                \DB::beginTransaction();
                $activity->update([
                    $request->column => $request->value
                ]);
                if($request->column == "quantity" || $request->column == "rate" || $request->column == "total" || $request->column == "unit" || $request->column == "unit_qty")
	            {
                    $activity->quantity = $request->quantity;
                    $activity->rate = $request->rate;
                    $activity->selling_cost = $request->selling_cost;
                    $activity->total = $request->total;

                    $activity->save();
                    
                    $mainActivity = LibrarySubActivity::findOrFail($activity->library_sub_activity_id);
                    //$subActivity = $activity->subActivity;
                    $subActivity->quantity = $request->quantity_sub;
                    $subActivity->rate = $request->rate_sub;
                    $subActivity->total = $request->total_sub;
                    $subActivity->hr = $request->hr_sub;
                    $subActivity->total_hr = $request->total_hr_sub;
                    $subActivity->mhr = $request->mhr_sub;
                    $subActivity->total_mhr = $request->total_mhr_sub;
                    
                    $subActivity->save();
                    
                    $mainActivity = LibraryMainActivity::findOrFail($subActivity->library_main_activity_id);
                    //$mainActivity = $subActivity->mainActivity;

                    $mainActivity->quantity = $request->quantity_main;
                    $mainActivity->rate = $request->rate_main;
                    $mainActivity->total = $request->total_main;
                    $mainActivity->hr = $request->hr_main;
                    $mainActivity->total_hr = $request->total_hr_main;
                    $mainActivity->mhr = $request->mhr_main;
                    $mainActivity->total_mhr = $request->total_mhr_main;
                    $mainActivity->unit_qty = $request->unit_qty_main;
                    $mainActivity->unit_rate = $request->unit_rate_main;

                    $mainActivity->save();


                }
                \DB::commit();
                return response()->json(['status'=> true, 'message'=> 'The activity has been updated successfully!']);
            } catch (\Exception $e) {
                \DB::rollBack();
            }
        }
        return response()->json(['status'=> false, 'message'=> 'Somthing went wrong. Please try again later.']);
    }

    /**
     * Update the specified resource from storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function updateProjectActivityRole(Request $request){
        if($request->ajax()){
            try {
                $activity = Activity::findOrFail($request->id);
                \DB::beginTransaction();
                $activity->update([
                    'mhr_role' => $request->role
                ]);
                \DB::commit();
                return response()->json(['status'=> true, 'message'=> 'The activity role has been updated successfully!']);
            } catch (\Exception $e) {
                \DB::rollBack();
            }
        }
        return response()->json(['status'=> false, 'message'=> 'Somthing went wrong. Please try again later.']);
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function removeMainActivityRow(Request $request, $id)
    {
        if($request->ajax()){
            try {
                $mainActivity = LibraryMainActivity::findOrFail($id);
                \DB::beginTransaction();
                $mainActivity->delete();
                \DB::commit();
                return response()->json(['status'=> true, 'message'=> 'The main activity has been deleted successfully!']);
            } catch (\Exception $e) {
                \DB::rollBack();
            }
        }
        return response()->json(['status'=> false, 'message'=> 'Somthing went wrong. Please try again later.']);
    }
    
    public function removeMainActivity(Request $request)
    {
        
        $id=$request->id;
        $table=$request->table;
        $all_row = explode(",",$id);
        if($request->ajax()){
            try {
                foreach($all_row as $id){
                    $mainActivity = LibraryMainActivity::findOrFail($id);
                    \DB::beginTransaction();
                    $mainActivity->delete();
                    \DB::commit();
                }
            }catch (\Exception $e) {
                \DB::rollBack();
            }
        }
        return response()->json(['status'=> false, 'message'=> 'Somthing went wrong. Please try again later.']);
       
    }
    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function removeSubActivityRow(Request $request, $id)
    {
        if($request->ajax()){
            try {
                \DB::beginTransaction();

                $subActivity = LibrarySubActivity::findOrFail($id);
                $subActivity->delete();

                \DB::commit();

                return response()->json(['status'=> true, 'message'=> 'This sub activity has been deleted successfully!']);
            } catch (\Exception $e) {
                \DB::rollBack();
            }
        }
        return response()->json(['status'=> false, 'message'=> 'Somthing went wrong. Please try again later.']);
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function removeActivityRow(Request $request, $id)
    {
        if($request->ajax()){
            try {
                \DB::beginTransaction();

                $activity = LibraryActivity::findOrFail($id);
                $activity->delete();

                \DB::commit();

                return response()->json(['status'=> true, 'message'=> 'This activity has been deleted successfully!']);
            } catch (\Exception $e) {
                \DB::rollBack();
            }
        }
        return response()->json(['status'=> false, 'message'=> 'Somthing went wrong. Please try again later.']);
    }

    /**
     * Show the specified resource.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function showProjectEstimateDetail(Request $request, $id){
        $view = "";
        if($request->ajax()){
            $project = Project::findOrFail($id);

            $project_total = 0;
            $project_hr_total = 0;
            $project_mhr_total = 0;
            $project_dhr_total = 0;
                
            $labour_total = 0;
            $labour_total_hour = 0;	
            $manager_total = 0;
            $material_cost = 0;
            $plant_cost = 0;
            $design_total = 0;	
                
            $manager_total_hour = 0;
            $material_cost_hour = 0;
            $plant_cost_hour = 0;	
            $design_total_hour = 0;	
            $labour_total_formula = "";
            $fq = [];
            $fk = [];
            $formulas = $project->formulas->where('keyword', '!=', '');
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
                    $rate = $project->hr_rate;
                    $e = "\$comm = $str;";
                    eval($e);  
                    $formula_value = $comm;
                    $labour_total_formula.=" + ".$formula->keyword;
                }
            }

            if($project->librarymainActivities->isNotEmpty()){
                foreach($project->librarymainActivities as $mainActivity){
                    $project_total =  $project_total + $mainActivity->total;
                    $project_hr_total =  $project_hr_total + $mainActivity->total_hr;
                    $project_mhr_total =  $project_mhr_total + $mainActivity->total_mhr;
                    if($mainActivity->subActivities->isNotEmpty()){
                        foreach($mainActivity->subActivities as $subActivity){

                            if($subActivity->activities->isNotEmpty()){
                                foreach($subActivity->activities as $activity){
                                    $unit_trim = preg_replace('/\s+/', '', $activity->unit);
                                    
                                    if($formulas->isNotEmpty()){
                                        foreach($formulas as $formula){
                                            if($unit_trim == $formula->keyword){
                                                $labour_total = $labour_total + (($activity->total * $subActivity->quantity) * $mainActivity->quantity);
                                                $labour_total_hour = $labour_total_hour + (($activity->quantity * $subActivity->quantity) * $mainActivity->quantity);
                                                $fq[$unit_trim] = @$fq[$unit_trim]+($activity->quantity * $subActivity->quantity * $mainActivity->quantity);
                                                $fk[$unit_trim] = @$fk[$unit_trim] + (($activity->total * $subActivity->quantity) * $mainActivity->quantity);
                                            }
                                        }
                                    }
                                    if($unit_trim == "mhr"){		
                                        $manager_total = $manager_total + (($activity->total * $subActivity->quantity) * $mainActivity->quantity);
                                        $manager_total_hour = $manager_total_hour + (($activity->quantity * $subActivity->quantity) * $mainActivity->quantity);
                                    }
                                    if($unit_trim == "dhr"){
                                        $design_total = $design_total + (($activity->total * $subActivity->quantity) * $mainActivity->quantity);
                                        $design_total_hour = $design_total_hour + (($activity->quantity * $subActivity->quantity) * $mainActivity->quantity);
                                    }
                                    if($unit_trim == "nr"){
                                        $material_cost = $material_cost + (($activity->total * $subActivity->quantity) * $mainActivity->quantity);
                                        $material_cost_hour = $material_cost_hour + (($activity->quantity * $subActivity->quantity) * $mainActivity->quantity);
                                        
                                    }
                                    if($unit_trim == "nrp"){
                                        $plant_cost = $plant_cost + (($activity->total * $subActivity->quantity) * $mainActivity->quantity);
                                        $plant_cost_hour = $plant_cost_hour + (($activity->quantity * $subActivity->quantity) * $mainActivity->quantity);
                                    }
                                }
                            }
                        }
                    }	
                }
            }
            $view =  view('estimatemanager::project.project_estimate_detail', compact(
                'project',
                'project_total',
                'project_hr_total',
                'project_mhr_total',
                'project_dhr_total',
                'labour_total',
                'labour_total_hour',	
                'manager_total',
                'material_cost',
                'plant_cost',
                'design_total',	
                'manager_total_hour',
                'material_cost_hour',
                'plant_cost_hour',	
                'design_total_hour',	
                'labour_total_formula',
                'fq',
                'fk'
            ))->render();
        }
        return response()->json(['html'=> $view]);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function showProjectFormulaDetail(Request $request, $id){
        $view = "";
        if($request->ajax()){
            $project = Project::findOrFail($id);
            $view = view('estimatemanager::project.project_formulas_detail', compact('project'))->render();
        }
        
        return response()->json(['html'=> $view]);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function addProjectFormulaRow(Request $request, $id){
        $view = "";
        if($request->ajax()){
            $project = Project::findOrFail($id);
            $formula = new Formula([
                'project_id'=> $project->id,
                'keyword'=> "",
                'description' => "",
                'formula' => "",
                'value' => "",
                'status' => 1
            ]);
            $formula->save();

            $view = view('estimatemanager::project.add_formula_row', compact('formula'))->render();
        }
        
        return response()->json(['html'=> $view]);
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function removeProjectFormulaRow(Request $request, $id)
    {
        if($request->ajax()){
            try {
                $formula = Formula::findOrFail($id);
                \DB::beginTransaction();
                $formula->delete();
                \DB::commit();
                return response()->json(['status'=> true, 'message'=> 'The formula has been deleted successfully!']);
            } catch (\Exception $e) {
                \DB::rollBack();
            }
        }
        return response()->json(['status'=> false, 'message'=> 'Somthing went wrong. Please try again later.']);
    }

    /**
     * Update the specified resource from storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function updateProjectFormulaRow(Request $request, $id){
        if($request->ajax()){
            try {
                $formula = Formula::findOrFail($id);
                \DB::beginTransaction();
                $formula->update([
                    $request->column => $request->value
                ]);
                \DB::commit();
                return response()->json(['status'=> true, 'message'=> 'The formula has been deleted successfully!']);
            } catch (\Exception $e) {
                \DB::rollBack();
            }
        }
        return response()->json(['status'=> false, 'message'=> 'Somthing went wrong. Please try again later.']);
    }

    

}


@php
    $project_reference = $project->unique_reference_no;
    $base_margin = $project->base_margin; 
    $hr_rate = $project->hr_rate;	
    $mhr_rate = $project->mhr_rate;
    $i = 1;
    $project_total = 0;
    $project_hr_total = 0;
    $project_mhr_total = 0;
        
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

    ///////////////////////////////////////////////////////////////
    $project_totalwb = 0;
    $project_hr_totalwb = 0;
    $project_mhr_totalwb = 0;
        
    $labour_totalwb = 0;
    $labour_total_hourwb = 0;	
    $manager_totalwb = 0;
    $material_costwb = 0;
    $plant_costwb = 0;
    $design_totalwb = 0;	
        
    $manager_total_hourwb = 0;
    $material_cost_hourwb = 0;
    $plant_cost_hourwb = 0;	
    $design_total_hourwb = 0;

    $labour_total_formula = "";
    $count_td = 21;
    
    if($project->formulas->isNotempty()){
        foreach($project->formulas as $formula){
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
           
            $labour_total_formula.=" + ".$formula->keyword;	
            $count_td = $count_td + 2;
        }
    }
    $srno = 0;
    $main = 0;
    $sub = 0;
    $act = 0;
    if($project->mainActivities->isNotEmpty()){
        foreach($project->mainActivities as $mainActivity){
            $project_total =  $project_total + $mainActivity->total;
            $project_hr_total =  $project_hr_total + $mainActivity->total_hr;
            $project_mhr_total =  $project_mhr_total + $mainActivity->total_mhr;	 
                
            $project_totalwb =  $project_totalwb + $mainActivity->total;
            $project_hr_totalwb =  $project_hr_totalwb + $mainActivity->total_hr;
            $project_mhr_totalwb =  $project_mhr_totalwb + $mainActivity->total_mhr;
            
            if($mainActivity->subActivities->isNotEmpty()){
                foreach($mainActivity->subActivities as $subActivity){                     
                    $expr = '/(?<=\s|^)[a-z]/i';	                        
                    $string_sub_act = $subActivity->activity;
                    preg_match_all($expr, $string_sub_act, $matches_sub_act);
                    $result_sub_act = implode('', $matches_sub_act[0]);
                    $result_sub_act = strtoupper($result_sub_act);
                    $result_sub_act = substr($result_sub_act, 0, 2);  

                    $sact = 0;
                    if($subActivity->activities->isNotEmpty()){
                        foreach($subActivity->activities as $activity){
                            $sact = $sact + 1;        
                            //  substr($row['area'], 0, 1)
                            $expr = '/(?<=\s|^)[a-z]/i';	
                                                        
                            $string_area = $activity->area;
                            preg_match_all($expr, $string_area, $matches_area);
                            $result_area = implode('', $matches_area[0]);
                            $result_area = strtoupper($result_area);

                            $string_level = $activity->level;
                            preg_match_all($expr, $string_level, $matches_level);
                            $result_level = implode('', $matches_level[0]);
                            $result_level = strtoupper($result_level); 
                            $act = $act+1;
                            $w=0;

                            $unit_trim = preg_replace('/\s+/', '', $activity->unit);

                            if($project->formulas->isNotempty()){
                                foreach($project->formulas as $formula){
                                    if($unit_trim == $formula->keyword) {
                                        $labour_total = $labour_total + (($activity->total * $subActivity->quantity) * $mainActivity->quantity);
                                        $labour_total_hour = $labour_total_hour + (($activity->quantity * $subActivity->quantity) * $mainActivity->quantity);
                                        
                                        @$fq[$unit_trim] = $fq[$unit_trim]+($activity->quantity * $subActivity->quantity * $mainActivity->quantity);
                                        @$fk[$unit_trim] = $fk[$unit_trim] + (($activity->total * $subActivity->quantity) * $mainActivity->quantity);
                                        
                                        /////////////////////////////////////////////////////////////////////////////
                                        
                                        $labour_totalwb = $labour_totalwb + ((($activity->rate * $activity->quantity) * $subActivity->quantity) * $mainActivity->quantity);
                                        $labour_total_hourwb = $labour_total_hourwb + (($activity->quantity * $subActivity->quantity) * $mainActivity->quantity);
                                        
                                        @$fqwb[$unit_trim] =$fqwb[$unit_trim]+($activity->quantity * $subActivity->quantity * $mainActivity->quantity);
                                        @$fkwb[$unit_trim] = $fkwb[$unit_trim] + ((($activity->rate * $activity->quantity) * $subActivity->quantity) * $mainActivity->quantity);
                                        
                                                                                            
                                        /////////////////////////////////////////////////////////////////////////////
                                        $w=$w+1;
                                    }
                                }
                            }
                            if($unit_trim == "mhr"){                 
                                $manager_total = $manager_total + (($activity->total * $subActivity->quantity) * $mainActivity->quantity);
                                $manager_total_hour = $manager_total_hour + (($activity->quantity * $subActivity->quantity) * $mainActivity->quantity);
                                ///////////////////////////////////////////////////////////////
                                $manager_totalwb = $manager_totalwb + ((($activity->rate * $activity->quantity) * $subActivity->quantity) * $mainActivity->quantity);
                                $manager_total_hourwb = $manager_total_hourwb + (($activity->quantity * $subActivity->quantity) * $mainActivity->quantity);
                                /////////////////////////////////////////////////////////////// 
                            }
                            
                            if($unit_trim == "dhr"){
                                $design_total = $design_total + (($activity->total * $subActivity->quantity) * $mainActivity->quantity);
                                $design_total_hour = $design_total_hour + (($activity->quantity * $subActivity->quantity) * $mainActivity->quantity);
                                /////////////////////////////////////////////////////////////////////////////
                                $design_totalwb = $design_totalwb + ((($activity->rate * $activity->quantity) * $subActivity->quantity) * $mainActivity->quantity);
                                $design_total_hourwb = $design_total_hourwb + (($activity->quantity * $subActivity->quantity) * $mainActivity->quantity);
                                //////////////////////////////////////////////////////////////////////////////
                            }
                            if($unit_trim == "nr"){
                                $material_cost = $material_cost + (($activity->total * $subActivity->quantity) * $mainActivity->quantity);
                                $material_cost_hour = $material_cost_hour + (($activity->quantity * $subActivity->quantity) * $mainActivity->quantity);
                                /////////////////////////////////////////////////////////////////////////
                                $material_costwb = $material_costwb + ((($activity->rate * $activity->quantity) * $subActivity->quantity) * $mainActivity->quantity);
                                $material_cost_hourwb = $material_cost_hourwb + (($activity->quantity * $subActivity->quantity) * $mainActivity->quantity);
                                /////////////////////////////////////////////////////////////////////////
                            }
                            if($unit_trim == "nrp"){
                                $plant_cost = $plant_cost + (($activity->total * $subActivity->quantity) * $mainActivity->quantity);   
                                $plant_cost_hour = $plant_cost_hour + (($activity->quantity * $subActivity->quantity) * $mainActivity->quantity);
                                ////////////////////////////////////////////////////////////////////////////////
                                $plant_costwb = $plant_costwb + ((($activity->rate * $activity->quantity) * $subActivity->quantity) * $mainActivity->quantity);
                                $plant_cost_hourwb = $plant_cost_hourwb + (($activity->quantity * $subActivity->quantity) * $mainActivity->quantity);  
                                /////////////////////////////////////////////////////////////////////////////// 
                            }

                        }
                    }
                    $sub = $sub+1;
                }
            }
            $i++;	
            $srno++;
        }
    }
    $project_totalwb = $project_totalwb * (1-($base_margin/100));
@endphp

<table class="table1" rules="all">
    <thead>
        <tr>
            <th colspan="9">Estimate Table #ID : {{ $project_reference }} , Project : {{ $project->project_title }}</th>
            <th colspan="9">Project Total : &pound;{{ number_format((float) $project_total, 2) }}</th>
            <th></th>
            <!--<th>Base Labour</th>
            <th>Base Margin</th>
            <th></th>
            @isset($fq)
                @foreach($fq as $key=>$value)
                    <th colspan="2"> 
                        {{ $key }}<br/>
                        <b>{{ $key }}</b> &nbsp; &nbsp; &nbsp; <b>cost</b>
                    </th>
                @endforeach
            @endisset
            <th colspan="2"><b>Labour Total<br><small>{{ $labour_total_formula }}</small></b></th>
            <th colspan="2"><b>Manager Total<br><small>mhr</small></b></th>
            <th colspan="2"><b>Design Total<br><small>dhr</small></b></th>
            <th><b>Material Cost<br><small>nr</small></b></th>
            <th><b>Plant Cost<br><small>nrp</small></b></th>-->
        </tr>
    </thead>
    <tbody>
        <tr>
             <!-- <td colspan="9"></td>
            <td></td>
            <td></td>
            <td>{{ $hr_rate }}</td>
            <td>{{ $base_margin }}</td>
            <td></td>
            @isset($fq)
                @foreach($fq as $key=>$value)
                    <td> {{ $value }}hrs </td>
			        <td> &pound;{{ @$fk[$key] }}</td>
                @endforeach
            @endisset
            <td>{{ number_format((float) $labour_total_hour) }}hrs</td>
            <td>&pound;{{ number_format((float) $labour_total, 2) }}</td>
            <td>{{  number_format((float) $manager_total_hour) }}hrs</td>
            <td>&pound;{{ number_format((float) $manager_total, 2) }}</td>
            <td>{{ number_format((float) $design_total_hour) }}hrs</td>
            <td>&pound;{{ number_format((float) $design_total, 2) }}</td>
            <td>&pound;{{ number_format((float) $material_cost, 2) }}</td>
            <td>&pound;{{ number_format((float) $plant_cost, 2) }}</td>-->
        </tr>
    </tbody>
</table>
<!--<table class="table1" style="width:100%;" rules="all">
    <thead>
        <tr>
            <th colspan="9"></th>
            <th>Project Total</th>
            <th></th>
            <th>Base Labour</th>
            <th>Base Margin</th>
            <th></th>
            @isset($fqwb)
                @foreach($fqwb as $key=>$value)
                    <th colspan="2">
                        {{ $key }}<br/>
                        <b>{{ $key }}</b> &nbsp; &nbsp; &nbsp; <b>cost</b>
                    </th>
                @endforeach
            @endisset
            <th colspan="2"><b>Labour Total<br><small>{{ $labour_total_formula }}</small></b></th>
            <th colspan="2"><b>Manager Total<br><small>mhr</small></b></th>
            <th colspan="2"><b>Design Total<br><small>dhr</small></b></th>
            <th><b>Material Cost<br><small>nr</small></b></th>
            <th><b>Plant Cost<br><small>nrp</small></b></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="9"></td>
            <td>&pound;{{ number_format((float) $project_totalwb, 2) }}</td>
            <td></td>
            <td>{{ $hr_rate }}</td>
            <td>0</td>
            <td></td>
            @isset($fqwb)
                @foreach($fqwb as $key=>$value)
                    <td> {{ $value }}hrs </td>
			        <td> &pound;{{ @$fkwb[$key] }}</td>
                @endforeach
            @endisset
			<td> &pound;{{ @$fkwb[$key] }}</td>
            <td>{{ number_format((float) $labour_total_hourwb) }}hrs</td>
            <td>&pound;{{ number_format((float) $labour_totalwb, 2) }}</td>
            <td>{{ number_format((float) $manager_total_hourwb) }}hrs</td>
            <td>&pound;{{ number_format((float) $manager_totalwb, 2) }}</td>
            <td>{{ number_format((float) $design_total_hourwb) }}hrs</td>
            <td>&pound;{{ number_format((float) $design_totalwb, 2) }}</td>
            <td>&pound;{{ number_format((float) $material_costwb, 2) }}</td>
            <td>&pound;{{ number_format((float) $plant_costwb, 2) }}</td>
        </tr>
    </tbody>
</table>-->
@php
    $i = 1;
    $project_total = 0;
    $project_hr_total = 0;
    $project_mhr_total = 0;
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
@endphp
<table rules="all">
    <thead>
        <tr>
          
            <th style="background-color: #000000; !important">Main Code</th>
            <th><b>Area</b></th>
            <th><b>Level</b></th>
            <th><b>Activity</b></th>
            <th><b>Quantity</b></th>
            <th><b>Rate</b></th>
            <th><b>Total</b></th>
            <th><b>Unit Qty</b></th>
            <th><b>Unit Rate</b></th>
            <th><b>Unit</b></th>
            <th></th>
            <th><b></b></th>
            <th><b></b></th>
            <th></th>
            <!--@isset($fq)
                @foreach($fq as $key=>$value)
				    <th><b>{{ $key }}</b></th>
				    <th><b>cost</b></th>						   
                @endforeach
            @endisset 
            <th><b>{{ $labour_total_formula }}</b></th>
            <th><b>cost</b></th>
            <th><b>mhr</b></th>
            <th><b>cost</b></th>
            <th><b>dhr</b></th>
            <th><b>cost</b></th>
            <th><b>nr cost</b></th>
            <th><b>nrp cost</b></th>-->
        </tr>
    </thead>
    <tbody>
        @php
            $srno = 0;
            $main = 0;
            $sub = 0;
            $act = 0;
            $sub_activity_id = 0;
            $main_code_sr = 0;
        @endphp
        @if($project->mainActivities->isNotEmpty())
            @foreach($project->mainActivities as $mainActivity)
                @php
                    $main_code_sr = $main_code_sr + 1;
                    $main_activity_id = $mainActivity->id;
                    $project_total =  $project_total + $mainActivity->total;
                    $project_hr_total =  $project_hr_total + $mainActivity->total_hr;
                    $project_mhr_total =  $project_mhr_total + $mainActivity->total_mhr;
                @endphp
                <tr>
                  
                    <td>{{ Arr::get($mainActivity, 'main_code', $main_code_sr) }}</td>
                    <td>{{ Arr::get($mainActivity, 'area', '') }}</td>
                    <td>{{ Arr::get($mainActivity, 'level', '') }}</td>
                    <td>{{ Arr::get($mainActivity, 'activity', '') }}</td>
                    <td>{{ Arr::get($mainActivity, 'quantity', '') }}</td>
                    <td>{{ Arr::get($mainActivity, 'rate', '') }}</td>
                    <td>{{ Arr::get($mainActivity, 'total', '') }}</td>
                    <td>{{ Arr::get($mainActivity, 'unit_qty', '') }}</td>
                    <td>{{ Arr::get($mainActivity, 'unit_rate', '') }}</td>
                    <td>{{ Arr::get($mainActivity, 'unit', '') }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                   <!-- @php
                        $labour_totalm = 0;
                        $labour_total_hourm = 0;
                        $manager_totalm = 0;
                        $material_costm = 0;
                        $plant_costm = 0;
                        $design_totalm = 0;
                        $manager_total_hourm = 0;
                        $material_cost_hourm = 0;
                        $plant_cost_hourm = 0;
                        $design_total_hourm = 0;
                        if($mainActivity->subActivities->isNotEmpty()){
                            foreach($mainActivity->subActivities as $subActivity){
                                $sub_activity_id = $subActivity->id;
                                $expr = '/(?<=\s|^)[a-z]/i';
                                $string_sub_act = $subActivity->activity;
                                preg_match_all($expr, $string_sub_act, $matches_sub_act);
                                $result_sub_act = implode('', $matches_sub_act[0]);
                                $result_sub_act = strtoupper($result_sub_act);
                                $result_sub_act = substr($result_sub_act, 0, 2);
                                $sact = 0;
                                if($subActivity->activities->isNotEmpty()){
                                    foreach($subActivity->activities as $activity){
                                        $sact = $sact + 1;
                                        $expr = '/(?<=\s|^)[a-z]/i';
                                        $string_area = $activity->area;
                                        preg_match_all($expr, $string_area, $matches_area);
                                        $result_area = implode('', $matches_area[0]);
                                        $result_area = strtoupper($result_area);
                                        $string_level = $activity->level;
                                        preg_match_all($expr, $string_level, $matches_level);
                                        $result_level = implode('', $matches_level[0]);
                                        $result_level = strtoupper($result_level);
                                        $act = $act + 1;
                                        $w = 0;
                                        $unit_trim = preg_replace('/\s+/', '', $activity->unit);
                                        if($project->formulas->isNotEmpty()){
                                            foreach($project->formulas as $formula){
                                                if ($unit_trim == $formula->keyword) {
                                                    $labour_totalm = $labour_totalm + ((($activity->rate * $activity->quantity) * $subActivity->quantity) * $mainActivity->quantity);
                                                    $labour_total_hourm = $labour_total_hourm + (($activity->quantity * $subActivity->quantity) * $mainActivity->quantity);
                                                    @$fqm[$unit_trim] = $fqm[$unit_trim] + ($activity->quantity * $subActivity->quantity * $mainActivity->quantity);
                                                    @$fkm[$unit_trim] = $fkm[$unit_trim] + ((($activity->rate * $activity->quantity) * $subActivity->quantity) * $mainActivity->quantity);
                                                    $w = $w + 1;
                                                }
                                            }
                                            if ($unit_trim == "mhr") {
                                                $manager_totalm = $manager_totalm + ((($activity->rate * $activity->quantity) * $subActivity->quantity) * $mainActivity->quantity);
                                                $manager_total_hourm = $manager_total_hourm + (($activity->quantity * $subActivity->quantity) * $mainActivity->quantity);
                                            }
                                            if($unit_trim == "dhr") {
                                                $design_totalm = $design_totalm + ((($activity->rate * $activity->quantity) * $subActivity->quantity) * $mainActivity->quantity);
                                                $design_total_hourm = $design_total_hourm + (($activity->quantity * $subActivity->quantity) * $mainActivity->quantity);
                                            }
                                            if ($unit_trim == "nr") {
                                                $material_costm = $material_costm + ((($activity->rate * $activity->quantity) * $subActivity->quantity) * $mainActivity->quantity);
                                                $material_cost_hourm = $material_cost_hourm + (($activity->quantity * $subActivity->quantity) * $mainActivity->quantity);
                                            }
                                            if ($unit_trim == "nrp") {
                                                $plant_costm = $plant_costm + ((($activity->rate * $activity->quantity) * $subActivity->quantity) * $mainActivity->quantity);
                                                $plant_cost_hourm = $plant_cost_hourm + (($activity->quantity * $subActivity->quantity) * $mainActivity->quantity);
                                            }
                                        }
                                    }
                                }
                                $sub = $sub + 1;
                            }
                        }
                    @endphp
                    @isset($fq)
                        @foreach($fq as $key=>$value)
                            <td>{{ @$fqm[$key] }}</td>
                            <td>{{ @$fkm[$key] }}</td>
                        @endforeach
                    @endisset
                    @php
                        $fqm=array();
                        $fkm=array();
                    @endphp
                    <td>{{ $labour_total_hourm }}</td>
                    <td>{{ $labour_totalm }}</td>
                    <td>{{ $manager_total_hourm }}</td>
                    <td>{{ $manager_totalm }}</td>
                    <td>{{ $design_total_hourm }}</td>
                    <td>{{ $design_totalm }}</td>
                    <td>{{ $material_costm }}</td>
                    <td>{{ $plant_costm }}</td>-->
                </tr>
                <tr>
                    <td></td>
                </tr>
              
                <tr>
                    <td>
                        <table rules="all" rowspan="2">
                            <thead style="background-color:grey;">
                                <tr>
                                    
                                    <th><b >Sub Code</b></th>
                                    <th><b></b></th>
                                    <th><b></b></th>
                                    <th><b>Activity</b></th>
                                    <th><b>Quantity</b></th>
                                    <th><b>Rate</b></th>
                                    <th><b>Total</b></th>
                                    <th><b></b></th>
                                    <th><b></b></th>
                                    <th><b></b></th>
                                    <th></th>
                                    <th><b></b></th>
                                    <th><b></b></th>
                                    <th></th>
                                    <!--@isset($fq)
                                        @foreach($fq as $key=>$value)
                                            <th><b>{{ $key }}</b></th>
                                            <th><b>cost</b></th>
                                        @endforeach
                                    @endisset
                                    <th><b>{{ $labour_total_formula }}</b></th>
                                    <th><b>cost</b></th>
                                    <th><b>mhr</b></th>
                                    <th><b>cost</b></th>
                                    <th><b>dhr</b></th>
                                    <th><b>cost</b></th>
                                    <th><b>nr cost</b></th>
                                    <th><b>nrp cost</b></th>-->
                                </tr>
                            </thead>
                            <tbody>
                                @if($mainActivity->subActivities->isNotEmpty())
                                    @foreach($mainActivity->subActivities as $subActivity)
                                        @php
                                            $expr = '/(?<=\s|^)[a-z]/i';
                                            $string_sub_act = $subActivity->activity;
                                            preg_match_all($expr, $string_sub_act, $matches_sub_act);
                                            $result_sub_act = implode('', $matches_sub_act[0]);
                                            $result_sub_act = strtoupper($result_sub_act);
                                            $result_sub_act = substr($result_sub_act, 0, 2);
                                        @endphp
                                        <tr>
                                            
                                            <td style=" height:35px;">{{ Arr::get($subActivity, 'sub_code', $result_sub_act) }} </td>
                                            <td></td>
                                            <td></td>
                                            <td >{{ Arr::get($subActivity, 'activity', '') }}</td>
                                            <td>{{ Arr::get($subActivity, 'quantity', '') }}</td>
                                            <td>{{ Arr::get($subActivity, 'rate', '') }}</td>
                                            <td>{{ Arr::get($subActivity, 'total', '') }}</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                         <!--   @php
                                                $labour_totals = 0;
                                                $labour_total_hours = 0;	 
                                                $manager_totals = 0;
                                                $material_costs = 0;
                                                $plant_costs = 0;
                                                $design_totals = 0;	
                                                    
                                                $manager_total_hours = 0;
                                                $material_cost_hours = 0;
                                                $plant_cost_hours = 0;	
                                                $design_total_hours = 0;
                                                $sact=0;
                                                if($subActivity->activities->isNotEmpty()){
                                                    foreach($subActivity->activities as $activity){
                                                        $sact = $sact + 1;
                                                        $expr = '/(?<=\s|^)[a-z]/i';
                                                        $string_area = $mainActivity->area;
                                                        preg_match_all($expr, $string_area, $matches_area);
                                                        $result_area = implode('', $matches_area[0]);
                                                        $result_area = strtoupper($result_area);
                                                        $string_level = $mainActivity->level;
                                                        preg_match_all($expr, $string_level, $matches_level);
                                                        $result_level = implode('', $matches_level[0]);
                                                        $result_level = strtoupper($result_level);
                                                        $act = $act+1;
                                                        $w = 0;
                                                        $unit_trim = preg_replace('/\s+/', '', $activity->unit);
                                                        if($project->formulas->isNotEmpty()){
                                                            foreach($project->formulas as $furmula){
                                                                if($unit_trim == $furmula->keyword) {
                                                                    $labour_totals = $labour_totals + (($activity->rate * $activity->quantity) * $subActivity->quantity);
                                                                    $labour_total_hours = $labour_total_hours + ($activity->quantity * $subActivity->quantity);
                                                                    @$fqs[$unit_trim]=$fqs[$unit_trim]+($activity->quantity * $subActivity->quantity);
                                                                    @$fks[$unit_trim]=$fks[$unit_trim] + (($activity->rate * $activity->quantity) * $subActivity->quantity);
                                                                    $w = $w+1;
                                                                }
                                                            }
                                                        }
                                                        if($unit_trim == "mhr"){
                                                            $manager_totals = $manager_totals + (($activity->rate * $activity->quantity) * $subActivity->quantity);
                                                            $manager_total_hours = $manager_total_hours + ($activity->quantity * $subActivity->quantity);
                                                        }
                                                        if($unit_trim == "dhr"){
                                                            $design_totals = $design_totals + (($activity->rate * $activity->quantity) * $subActivity->quantity);
                                                            $design_total_hours = $design_total_hours + ($activity->quantity * $subActivity->quantity);
                                                        }
                                                        if($unit_trim == "nr"){
                                                            $material_costs = $material_costs + (($activity->rate * $activity->quantity) * $subActivity->quantity);
                                                            $material_cost_hours = $material_cost_hours + ($activity->quantity * $subActivity->quantity);
                                                        }
                                                        if($unit_trim == "nrp"){
                                                            $plant_costs = $plant_costs + (($activity->rate * $activity->quantity) * $subActivity->quantity);
                                                            $plant_cost_hours = $plant_cost_hours + ($activity->quantity * $subActivity->quantity);
                                                        }
                                                    }
                                                }	 
                                            @endphp
                                            @isset($fq)
                                                @foreach($fq as $key=>$value)
                                                    <td>{{ @$fqs[$key] }}</td>
                                                    <td>{{ @$fks[$key] }}</td>
                                                @endforeach
                                            @endisset
                                            @php
                                                $fqs=array();
                                                $fks=array();
                                            @endphp
                                            <td>{{ $labour_total_hours }}</td>
                                            <td>{{ $labour_totals }}</td>
                                            <td>{{ $manager_total_hours }}</td>
                                            <td>{{ $manager_totals }}</td>
                                            <td>{{ $design_total_hours }}</td>
                                            <td>{{ $design_totals }}</td>
                                            <td>{{ $material_costs }}</td>
                                            <td>{{ $plant_costs }}</td>-->
                                        </tr>
                                        <tr>
                                            <td></td>
                                        </tr>
                                        
                                        <tr>
                                            <td>
                                                <table style="width:90%;" rules="all">
                                                    <thead>
                                                        <tr>
                                                           
                                                            <th ><b>Sr No.</b></th>
                                                            <th></th>
                                                            <th></th>	
                                                            <th><b>Activity</b></th>
                                                            <th><b>Quantity</b></th>
                                                            <th><b>Rate</b></th>
                                                            <th><b>Total</b></th>
                                                            <th><b>Unit</b></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                          <!--  <th><b>Cost Rate</b></th>
                                                            <th><b>Profit</b></th>											           
                                                            <th></th>
                                                            @isset($fq)
                                                                @foreach($fq as $key=>$value)
                                                                    <th><b>{{ $key }}</b></th>
                                                                    <th><b>cost</b></th>
                                                                @endforeach
                                                            @endisset
                                                            <th><b>{{ $labour_total_formula }}</b></th>
                                                            <th><b>cost</b></th>
                                                            <th><b>mhr</b></th>
                                                            <th><b>cost</b></th>
                                                            <th><b>dhr</b></th>
                                                            <th><b>cost</b></th>
                                                            <th><b>nr cost</b></th>
                                                            <th><b>nrp cost</b></th>-->
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $sact = 0;
                                                        @endphp
                                                        @if($subActivity->activities->isNotEmpty()){
                                                            @foreach($subActivity->activities as $activity){
                                                                @php
                                                                    $sact = 0;
                                                                    $sact = $sact + 1; 
                                                                    $expr = '/(?<=\s|^)[a-z]/i';                       
                                                                    $string_area = $mainActivity->area;
                                                                    preg_match_all($expr, $string_area, $matches_area);
                                                                    $result_area = implode('', $matches_area[0]);
                                                                    $result_area = strtoupper($result_area);
                                                                    $string_level = $mainActivity->level;
                                                                    preg_match_all($expr, $string_level, $matches_level);
                                                                    $result_level = implode('', $matches_level[0]);
                                                                    $result_level = strtoupper($result_level);
                                                                @endphp    
                                                                <tr>
                                                                  
                                                                    <td>{{ Arr::get($activity, 'item_code', $result_area.'-'.$mainActivity->main_code.'-'.$result_level.'-'.$subActivity->sub_code.'-'.$sact) }}</td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td>{{ Arr::get($activity, 'activity', '') }}</td>
                                                                    <td>{{ Arr::get($activity, 'quantity', '') }}</td>
                                                                    <td>{{ Arr::get($activity, 'selling_cost', '') }}</td>
                                                                    <td>{{ Arr::get($activity, 'total', '') }}</td>
                                                                    <td>{{ Arr::get($activity, 'unit', '') }}</td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <!--<td>{{ Arr::get($activity, 'rate', '') }}</td>
                                                                    <td>{{ $activity->selling_cost-$activity->rate }}</td>
                                                                    @php
                                                                        $labour_totala = 0;
                                                                        $labour_total_houra = 0;	 
                                                                        $manager_totala = 0;
                                                                        $material_costa = 0;
                                                                        $plant_costa = 0;
                                                                        $design_totala = 0;
                                                                        $manager_total_houra = 0;
                                                                        $material_cost_houra = 0;
                                                                        $plant_cost_houra = 0;	
                                                                        $design_total_houra = 0;
                                                                        $act = $act+1;
                                                                        $w = 0;
                                                                        $unit_trim = preg_replace('/\s+/', '', $activity->unit);
                                                                        if($project->formulas->isNotempty()){
                                                                            foreach($project->formulas as $formula){
                                                                                if($unit_trim == $formula->keyword){
                                                                                    $labour_totala = $labour_totala + ($activity->rate * $activity->quantity); 
                                                                                    $labour_total_houra = $labour_total_houra + ($activity->quantity);
                                                                                    @$fqa[$unit_trim] = $fqa[$unit_trim]+($activity->quantity);
                                                                                    @$fka[$unit_trim] = $fka[$unit_trim] + ($activity->rate * $activity->quantity);
                                                                                    $w = $w+1;
                                                                                }
                                                                            }
                                                                        }
                                                                        if($unit_trim == "mhr"){          
                                                                            $manager_totala = $manager_totala + ($activity->rate * $activity->quantity);
                                                                            $manager_total_houra = $manager_total_houra + ($activity->quantity);
                                                                        }
                                                                        if($unit_trim == "dhr"){ 
                                                                            $design_totala = $design_totala + ($activity->rate * $activity->quantity);
                                                                            $design_total_houra = $design_total_houra + ($activity->quantity);
                                                                        }
                                                                        if($unit_trim == "nr"){
                                                                            $material_costa = $material_costa + ($activity->rate * $activity->quantity);
                                                                            $material_cost_houra = $material_cost_houra + ($activity->quantity);
                                                                        }
                                                                        if($unit_trim == "nrp"){
                                                                            $plant_costa = $plant_costa + ($activity->rate * $activity->quantity);
                                                                            $plant_cost_houra = $plant_cost_houra + ($activity->quantity);
                                                                        }
                                                                    @endphp
                                                                    @isset($fq)
                                                                        @foreach($fq as $key=>$value)
                                                                            <td>{{ @$fqa[$key] }}</td>
                                                                            <td>{{ @$fka[$key] }}</td>
                                                                        @endforeach
                                                                    @endisset
                                                                    @php
                                                                        $fqa=array();
                                                                        $fka=array();	
                                                                    @endphp
                                                                    <td>{{ $labour_total_houra }}</td>
                                                                    <td>{{ $labour_totala }}</td>
                                                                    <td>{{ $manager_total_houra }}</td>
                                                                    <td>{{ $manager_totala }}</td>
                                                                    <td>{{ $design_total_houra }}</td>
                                                                    <td>{{ $design_totala }}</td>
                                                                    <td>{{ $material_costa }}</td>
                                                                    <td>{{ $plant_costa }}</td>										
                                                                </tr>-->
                                                            @endforeach
                                                        @endif	
                                                    </tbody>
                                                </table>            
                                            </td>            
                                        </tr>
                                        @php
                                            $sub = $sub+1;
                                        @endphp
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </td>
                </tr>
                @php
                    $i++;	
                    $srno++;
                @endphp
            @endforeach
        @endif
    </tbody>
</table>
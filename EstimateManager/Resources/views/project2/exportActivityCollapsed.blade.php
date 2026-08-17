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
            $formula_value = $comm;
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
<table class="table1" style="width:100%;" rules="all">
    <thead>
        <tr>
            <th colspan="9">Estimate Table #ID : {{ $project_reference }} , Project : {{ $project->project_title }}</th>
            <th>Project Total</th>
            <th></th>
            <th align="center">Base Labour</th>
            <th align="center">Base Margin</th>
            <th></th>
            @isset($fq)
                @foreach($fq as $key=>$value)
                    <th colspan="2"> 
                        {{ $key }}<br/>
                        <b align="left">{{ $key }}</b> &nbsp; &nbsp; &nbsp; <b align="right">cost</b>
                    </th>
                @endforeach
            @endisset
            <th colspan="2"><b align="center">Labour Total<br><small>{{ $labour_total_formula }}</small></b></th>
            <th colspan="2"><b align="center">Manager Total<br><small>mhr</small></b></th>
            <th colspan="2"><b align="center">Design Total<br><small>dhr</small></b></th>
            <th><b align="center">Material Cost<br><small>nr</small></b></th>
            <th><b align="center">Plant Cost<br><small>nrp</small></b></th>
        </tr>
    </thead>
    <tbody>
        <tr align="center">
            <td colspan="9"></td>
            <td>&pound;{{ number_format((float) $project_total, 2) }}</td>
            <td></td>
            <td align="center">{{ $hr_rate }}</td>
            <td align="center">{{ $base_margin }}</td>
            <td></td>
            @isset($fq)
                @foreach($fq as $key=>$value)
                    <td align="center"> {{ $value }}hrs </td>
			        <td align="center"> &pound;{{ @$fk[$key] }}</td>
                @endforeach
            @endisset
            <td>{{ number_format((float) $labour_total_hour) }}hrs</td>
            <td>&pound;{{ number_format((float) $labour_total, 2) }}</td>
            <td>{{  number_format((float) $manager_total_hour) }}hrs</td>
            <td>&pound;{{ number_format((float) $manager_total, 2) }}</td>
            <td>{{ number_format((float) $design_total_hour) }}hrs</td>
            <td>&pound;{{ number_format((float) $design_total, 2) }}</td>
            <td>&pound;{{ number_format((float) $material_cost, 2) }}</td>
            <td>&pound;{{ number_format((float) $plant_cost, 2) }}</td>
        </tr>
    </tbody>
</table>
<table class="table1" style="width:100%;" rules="all">
    <thead>
        <tr>
            <th colspan="9"></th>
            <th>Project Total</th>
            <th></th>
            <th align="center">Base Labour</th>
            <th align="center">Base Margin</th>
            <th></th>
            @isset($fqwb)
                @foreach($fqwb as $key=>$value)
                    <th colspan="2">
                        {{ $key }}<br/>
                        <b align="left">{{ $key }}</b> &nbsp; &nbsp; &nbsp; <b align="right">cost</b>
                    </th>
                @endforeach
            @endisset
            <th colspan="2"><b align="center">Labour Total<br><small>{{ $labour_total_formula }}</small></b></th>
            <th colspan="2"><b align="center">Manager Total<br><small>mhr</small></b></th>
            <th colspan="2"><b align="center">Design Total<br><small>dhr</small></b></th>
            <th><b align="center">Material Cost<br><small>nr</small></b></th>
            <th><b align="center">Plant Cost<br><small>nrp</small></b></th>
        </tr>
    </thead>
    <tbody>
        <tr align="center">
            <td colspan="9"></td>
            <td>&pound;{{ number_format((float) $project_totalwb, 2) }}</td>
            <td></td>
            <td align="center">{{ $hr_rate }}</td>
            <td align="center">0</td>
            <td></td>
            @isset($fqwb)
                @foreach($fqwb as $key=>$value)
                    <td align="center"> {{ $value }}hrs </td>
			        <td align="center"> &pound;{{ @$fkwb[$key] }}</td>
                @endforeach
            @endisset
			<td align="center"> &pound;{{ @$fkwb[$key] }}</td>
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
</table>
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
            <th align="center"><b>Main Code</b></th>
            <th style=" height:35px;" align="center"><b>Area</b></th>
            <th align="center"><b>Level</b></th>
            <th align="center"><b>Activity</b></th>
            <th align="right"><b>Quantity</b></th>
            <th align="right"><b>Rate</b></th>
            <th align="right"><b>Total</b></th>
            <th align="right"><b>Unit Qty</b></th>
            <th align="right"><b>Unit Rate</b></th>
            <th align="center"><b>Unit</b></th>
            <th></th>
            <th align="center"><b></b></th>
            <th align="center"><b></b></th>
            <th></th>
            @isset($fq)
                @foreach($fq as $key=>$value)
				    <th align="center"><b>{{ $key }}</b></th>
				    <th align="center"><b>cost</b></th>						   
                @endforeach
            @endisset 
            <th align="center"><b>{{ $labour_total_formula }}</b></th>
            <th align="center"><b>cost</b></th>
            <th align="center"><b>mhr</b></th>
            <th align="center"><b>cost</b></th>
            <th align="center"><b>dhr</b></th>
            <th align="center"><b>cost</b></th>
            <th align="center"><b>nr cost</b></th>
            <th align="center"><b>nrp cost</b></th>
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
                <tr align="center">
                    <td>{{ Arr::get($mainActivity, 'main_code', $main_code_sr) }}</td>
                    <td>{{ Arr::get($mainActivity, 'area', '') }}</td>
                    <td>{{ Arr::get($mainActivity, 'level', '') }}</td>
                    <td>{{ Arr::get($mainActivity, 'activity', '') }}</td>
                    <td align="right">{{ Arr::get($mainActivity, 'quantity', '') }}</td>
                    <td align="right">{{ Arr::get($mainActivity, 'rate', '') }}</td>
                    <td align="right">{{ Arr::get($mainActivity, 'total', '') }}</td>
                    <td align="right">{{ Arr::get($mainActivity, 'unit_qty', '') }}</td>
                    <td align="right">{{ Arr::get($mainActivity, 'unit_rate', '') }}</td>
                    <td>{{ Arr::get($mainActivity, 'unit', '') }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    @php
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
                    <td>{{ $plant_costm }}</td>
                </tr>
                @php
                    $i++;	
                    $srno++;
                @endphp
            @endforeach
        @endif
    </tbody>
</table>
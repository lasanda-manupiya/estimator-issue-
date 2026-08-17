@extends('user::layouts.master')

@section('content')
<style type="text/css">
    .aa {
        margin-bottom: 1%;
    
    }
    .bb{
        margin-left: 5%; 
    }
    div.ex2 {
        margin: 12px;
        left: 15px;
        outline: 1px solid black;
        outline-offset: 12px;
        float: right;
    } 

    .select2.select2-container{
        width: 100% !important;
    }
    
    
    #main_activity_table tr td input[type="text"]:focus {
    	-webkit-box-shadow: 0 0 10px 0px rgba(66, 111, 156, 0.7) !important;
    	box-shadow: 0 0 10px 0px rgba(66, 111, 156, 0.7) !important;
    	-webkit-transition: all ease 0.5s;
    	transition: all ease 0.5s;
    	border-color: #84c1ff;
    	background: white !important;
    }
</style>
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      
       @php /* <div class="col-sm-12">
        <ol class="breadcrumb float-sm-right">

          <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
          <li class="breadcrumb-item active">Project Library</li>
        </ol>
      </div> */ @endphp
    </div>
  </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <div class="card">
        <div class="card-body">
            <div class="row">
                @php
             /*   <div class="col-md-3">
                    <div class="form-group">
                        {{ Form::select('project_id', $allProjects, @$project->id, [
                            'class' => "form-control multiselect-dropdown",
                            'id' => "project_id",
                            'data-live-search'=>'true',
                            'onchange'=> 'changeProject(this.value)'
                        ]) }}
                    </div>
                </div>
                
             */   @endphp
               
                
                
                <div class="col-md-12">
                    <div class="pull-rigth">
                        @if(@$project)
                        


                            <button type="button" href="javascript:;" class="btn btn-primary btn-sm" onClick="printDiv('printable_estimate_div')">Print Project</button>&nbsp;
                            @if (auth()->user()->can('access', 'estimates add'))
                                <!--<a href="{{ route('library.copy.project', $project->id) }}" class="btn btn-info btn-sm">Copy Project</a>&nbsp;-->
                                
                                
                                <!--<a href="{{ route('library.save.project', $project->id) }}" class="btn btn-secondary btn-sm">Save Project</a>&nbsp;-->
                            @endif
                            <a href="{{ route('library.excel.expanded', $project->id) }}" class="btn btn-warning btn-sm">Excel Expanded</a>&nbsp;
                            <a href="{{ route('library.excel.collapsed', $project->id) }}" class="btn btn-danger btn-sm">Excel Collapsed</a>&nbsp;
                            <a href="javascript:;" data-project-id="{{ $project->id }}" data-route="{{ route('library.ajax.project.estimate.detail', $project->id) }}" class="btn btn-success btn-sm show-project-info">Show Detail</a>&nbsp;
                            <a href="javascript:;" data-project-id="{{ $project->id }}" data-route="{{ route('library.ajax.project.formula.detail', $project->id) }}" class="btn btn-dark btn-sm show-project-formula">Formula</a>&nbsp;
                        @else
                            <button type="button" onclick="return alert('Select any project first to print the project')" class="btn btn-primary btn-sm">Print Project</button>&nbsp;
                            <!--<button type="button" onclick="return alert('Select any project first to copy the project')" class="btn btn-info btn-sm">Copy Project</button>&nbsp;
                            <button type="button" onclick="return alert('Select any project first to save the project')" class="btn btn-secondary btn-sm">Save Project</button>&nbsp;
                            <button type="button" onclick="return alert('Select any project first to excel expand the project')" class="btn btn-warning btn-sm">Excel Expand</button>&nbsp;
                            <button type="button" onclick="return alert('Select any project first to excel collaspe the project')" class="btn btn-danger btn-sm">Excel Collasped</button>&nbsp;
                            <button type="button" onclick="return alert('Select any project first to view the project')" class="btn btn-success btn-sm">Show Detail</button>
                            <button type="button" onclick="return alert('Select any project first to view the formula')" class="btn btn-dark btn-sm">Formula</button>-->
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @isset($project)
        <div class="printable_estimate_div" id="printable_estimate_div">
            <div class="card project-estimate-detail" style="display:none">
                {{ Form::open(['route' => 'estimates.projects.update.detail']) }}
                    <input type="hidden" name="project_id" value="{{ $project->id }}" >
                    <div class="card-header">
                        <h3 class="card-title">Project Library Detail</h3><hr>
                        <div class="card-tools">
                            <a href="javascript:;" class="btn btn-danger btn-sm hide-project-info">
                                <i class="fas fa-times" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                                    
                    </div>
                    <div class="card-footer">
                        @if(auth()->user()->can('access', 'estimates add'))
                            {{ Form::submit('Update', [ 'class' => "btn btn-primary btn-flat" ]) }}
                        @endif
                    </div>
                {{ Form::close() }}
            </div>
            <div class="card project-formula-keyword" style="display: none;">
                <div class="card-header">
                    <h3 class="card-title">Formula and Keyword</h3><hr>
                    <div class="card-tools">
                        <a href="javascript:;" class="btn btn-danger btn-sm hide-project-formula">
                            <i class="fas fa-times" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-9">
                            <table class="table table-bordered formula-table">
                                <thead class="table-success">
                                    <tr>
                                        <th>Keyword or Unit</th>
                                        <th>Description</th>
                                        <th>Formula</th>
                                        <th>Value</th>
                                        <th>
                                            @if (auth()->user()->can('access', 'estimates add'))
                                                <a title="Add new formula" href="javascript:;" class="btn btn-sm btn-success add-formula-row" data-project-id="{{ $project->id }}" data-route="{{ route('estimates.ajax.add.project.formula.row', $project->id) }}">
                                                    <i class="fas fa-plus"></i>
                                                </a>
                                            @endif
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="formula-wrapper">
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <h2>Terms</h2>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 border">rate</div>
                                <div class="col-md-2 border">+</div>
                                <div class="col-md-2 border">-</div>
                                <div class="col-md-2 border">/</div>
                                <div class="col-md-2 border">*</div>
                                <div class="col-md-2 border">(</div>
                                <div class="col-md-2 border">)</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Default box -->
            <div class="card">
                
                <div class=" border col-md-3 float-right p-3 ml-2 mt-0">
    
    <div class="form-group ">
    <input type = 'hidden' value={{$project->id}} name='pro_id' id='pro_id'>                            
    <input type="button" class="btn btn-primary btn-sm aa" value="Copy to Estimate"  id="copy_multiple_to_estimate" style="background-color:#0033FF; color:#FFFFFF; cursor:pointer; font-size:16px; border: none;">                     
    <!--<input type="button" class="btn btn-primary btn-sm bb" value="Copy to Library"  id="copy_multiple_to_estimate" style="background-color:#0033FF; color:#FFFFFF; cursor:pointer; font-size:16px; border: none;">                       -->
    
 
    <br>
        {{ Form::select('project_ids', $allProjects,$user->default_project, [
            'class' => "form-control multiselect-dropdown",
            'id' => "project_ids",
            'data-live-search'=>'true', 
            
        ]) }}
        
       
    
        </div>
</div>
                
                <hr><br><div class="col-md-12">
                        
                         <a href="javascript:" class="mb-2 mr-2 btn btn-danger btn-sm" value="Delete Multiple"  id="delete_multiple" >Delete Multiple</a>
                  

                         @if(auth()->user()->can('access', 'estimates add'))
                            <a href="{{ route('library.projects.import.view') }}" class="mb-2 mr-2 btn btn-info btn-sm">
                                Import Library
                            </a> 
                        @endif
                        <input type="button" class="mb-2 mr-2 btn btn-primary btn-sm" value="Expand all" title="Expand all" id="expand_all">
                        <input type="button" class="mb-2 mr-2 btn btn-success btn-sm" value="Collaps all" title="Collaps all" id="collaps_all">
                    </div><hr>
                
                <div class="card-header">
                    <h3 class="card-title">Project Library</h3><hr>

                         </div> 
             
                <input type="hidden" name="base_margin" id="base_margin" value="{{ $project->base_margin }}">
                <div class="card-body table-responsive">
                    @include('layouts.flash.alert')
                     <div class="col-md-6">
                                <div class="form-group">
								<label>Search</label><br>
						   <input id="myInput" type="text" onKeyUp="myFunction()">&nbsp&nbsp&nbsp<a href="{{ route('library.projects') }}" class="mb-2 mr-2 btn btn-info btn-sm">
                                Back&nbsp
                            </a>
						   
						  </div>
                            </div> 
                    <table class=" table table-bordered main-activity-table" id="main_activity_table" style="width:150%">
                        <thead class="table-success" style="background-color: #84c1ff;" >
                            <tr>
                                <th width="1%"> <input type="checkbox" name="all" id="all" ></th>
                                <th width="1%">View</th>
                                <th width="5%">Main Code</th>
                                <th width="8%">Area</th>
                                <th width="5%">Level</th>
                                <th width="24%">Activity</th>
                                <th width="6%">Quantity</th>
                                <th width="6%">Rate</th>
                                <th width="6%">Total</th>
                                <th width="5%">Unit Qty</th>
                                <th width="5%">Unit Rate</th>
                                <th width="4%">Unit</th>
                                <th width="1%">
                                    @if (auth()->user()->can('access', 'estimates add'))
                                        <a title="Add main activity" href="javascript:;" class="btn btn-sm btn-success add-main-activity-row" data-project-id="{{ $project->id }}" data-route="{{ route('library.ajax.add.main.activity.row', $project->id) }}">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                    @endif
                                </th>
                            </tr>
                        </thead>
                        <tbody class="main-activity-wrapper">
                            @if($mainActivities->isNotEmpty())
                                @foreach($mainActivities as $mainActivity)
                                    <tr class="main-activity-row-{{ $mainActivity->id }} expandable" data-row-id="{{ $mainActivity->id }}">
                                        <td><input type="checkbox" class="emp_checkbox" data-emp-id="{{ $mainActivity->id }}" ></td>
                                        <td><input type="button" class="btn btn-primary btn-sm expandable-input" value="+"></td>
                                        <td>
                                            <input title="Main Code - {{ $mainActivity->main_code }}"  type="text" name="main_activity[{{ $mainActivity->id }}][main_code]" value="{{ $mainActivity->main_code }}" id="main_code{{ $mainActivity->id }}" class="form-control main_code" disabled="disabled" onchange="update_main_activity_row('{{ route('library.ajax.update.project.main.activity.row', $mainActivity->id) }}', 'main_code', this.value, '{{ $mainActivity->id }}');">
                                        </td>
                                        <td>
                                            <input  title="Area- {{ $mainActivity->area }}" type="text" name="main_activity[{{ $mainActivity->id }}][area]" value="{{ $mainActivity->area }}" id="area_main{{ $mainActivity->id }}" class="form-control area_main" onchange="update_main_activity_row('{{ route('library.ajax.update.project.main.activity.row', $mainActivity->id) }}', 'area', this.value, '{{ $mainActivity->id }}');">
                                        </td>
                                        <td>
                                            <input title="Level- {{ $mainActivity->level }}" type="text" name="main_activity[{{ $mainActivity->id }}][level]" value="{{ $mainActivity->level }}" id="level_main{{ $mainActivity->id }}" class="form-control level_main" onchange="update_main_activity_row('{{ route('library.ajax.update.project.main.activity.row', $mainActivity->id) }}', 'level', this.value, '{{ $mainActivity->id }}');">
                                        </td>
                                        <td>
                                            <input title="Activity- {{ $mainActivity->activity }}"  type="text" name="main_activity[{{ $mainActivity->id }}][activity]" value="{{ $mainActivity->activity }}" id="activity_main{{ $mainActivity->id }}" class="form-control activity_main" onchange="update_main_activity_row('{{ route('library.ajax.update.project.main.activity.row', $mainActivity->id) }}', 'activity', this.value, '{{ $mainActivity->id }}');">
                                        </td>
                                        <td>
                                            <input title="Quantity- {{ round($mainActivity->quantity,2) }}" type="text" name="main_activity[{{ $mainActivity->id }}][quantity]" value="{{ round($mainActivity->quantity,2) }}" id="quantity_main{{ $mainActivity->id }}" class="form-control quantity_main" onkeypress="javascript:return isNumber(event)" onchange="update_main_activity_row('{{ route('library.ajax.update.project.main.activity.row', $mainActivity->id) }}', 'quantity', this.value, '{{ $mainActivity->id }}');">
                                        </td>
                                        <td>
                                            <input title="Rate- {{ round($mainActivity->rate,2) }}" type="text" name="main_activity[{{ $mainActivity->id }}][rate]" value="{{ round($mainActivity->rate,2) }}" id="rate_main{{ $mainActivity->id }}" class="form-control rate_main" disabled="disabled" onkeypress="javascript:return isNumber(event)" onchange="update_main_activity_row('{{ route('library.ajax.update.project.main.activity.row', $mainActivity->id) }}', 'rate', this.value, '{{ $mainActivity->id }}');">
                                        </td>
                                        <td>
                                            <input title="Total- {{ round($mainActivity->total,2) }}" type="text" name="main_activity[{{ $mainActivity->id }}][total]" value="{{ round($mainActivity->total,2) }}" id="total_main{{ $mainActivity->id }}" class="form-control total_main" disabled="disabled" onkeypress="javascript:return isNumber(event)" onchange="update_main_activity_row('{{ route('library.ajax.update.project.main.activity.row', $mainActivity->id) }}', 'total', this.value, '{{ $mainActivity->id }}');">
                                        </td>
                                        <td>
                                            <input title="Unit Qty- {{ $mainActivity->unit_qty }}" type="text" name="main_activity[{{ $mainActivity->id }}][unit_qty]" value="{{ $mainActivity->unit_qty }}" id="unit_qty_main{{ $mainActivity->id }}" class="form-control unit_qty_main" onkeypress="javascript:return isNumber(event)" onchange="update_main_activity_row('{{ route('library.ajax.update.project.main.activity.row', $mainActivity->id) }}', 'unit_qty', this.value, '{{ $mainActivity->id }}');">
                                        </td>
                                        <td>
                                            <input title="Unit Rate- {{ round($mainActivity->unit_rate,2) }}"  type="text" name="main_activity[{{ $mainActivity->id }}][unit_rate]" value="{{ round($mainActivity->unit_rate,2) }}" id="unit_rate_main{{ $mainActivity->id }}" class="form-control unit_rate_main" onkeypress="javascript:return isNumber(event)" disabled="disabled" onchange="update_main_activity_row('{{ route('library.ajax.update.project.main.activity.row', $mainActivity->id) }}', 'unit_rate', this.value, '{{ $mainActivity->id }}');">
                                        </td>
                                        <td>
                                            <input title="Unit- {{ $mainActivity->unit }}" type="text" name="main_activity[{{ $mainActivity->id }}][unit]" value="{{ $mainActivity->unit }}" id="unit_main{{ $mainActivity->id }}" class="form-control unit_main" onchange="update_main_activity_row('{{ route('library.ajax.update.project.main.activity.row', $mainActivity->id) }}', 'unit', this.value, '{{ $mainActivity->id }}');">
                                            
                                            <input type="hidden" name="main_activity[{{ $mainActivity->id }}][id]" value="{{ $mainActivity->id }}">
                                            <input type="hidden" name="main_activity[{{ $mainActivity->id }}][hr]" value="{{ $mainActivity->hr }}" id="hr_main{{ $mainActivity->id }}" class="form-control hr_main" onchange="update_main_activity_row('{{ route('library.ajax.update.project.main.activity.row', $mainActivity->id) }}', 'hr', this.value, '{{ $mainActivity->id }}');">
                                            <input type="hidden" name="main_activity[{{ $mainActivity->id }}][mhr]" value="{{ $mainActivity->mhr }}" id="mhr_main{{ $mainActivity->id }}" class="form-control mhr_main" onchange="update_main_activity_row('{{ route('library.ajax.update.project.main.activity.row', $mainActivity->id) }}', 'mhr', this.value, '{{ $mainActivity->id }}');">
                                            <input type="hidden" name="main_activity[{{ $mainActivity->id }}][total_hr]" value="{{ $mainActivity->total_hr }}" id="total_hr_main{{ $mainActivity->id }}" class="form-control total_hr_main" onchange="update_main_activity_row('{{ route('library.ajax.update.project.main.activity.row', $mainActivity->id) }}', 'total_hr', this.value, '{{ $mainActivity->id }}');">
                                            <input type="hidden" name="main_activity[{{ $mainActivity->id }}][total_mhr]" value="{{ $mainActivity->total_mhr }}" id="total_mhr_main{{ $mainActivity->id }}" class="form-control total_mhr_main" onchange="update_main_activity_row('{{ route('library.ajax.update.project.main.activity.row', $mainActivity->id) }}', 'total_mhr', this.value, '{{ $mainActivity->id }}');">
                                        </td>
                                        <td>
                                            @if(auth()->user()->can('access', 'estimates add'))
                                                <a title="Delete main activity" href="javascript:;" class="btn btn-sm btn-danger remove-main-activity-row" data-main-activity-id="{{ $mainActivity->id }}" data-route="{{ route('library.ajax.delete.main.activity.row', $mainActivity->id) }}">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="12" class="expandable">
                                            <table class="table table-bordered sub-activity-table" id="sub_activity_table{{ $mainActivity->id }}">
                                                <thead class="table-info" style="background-color: #014dff; color: white;">
                                                    <tr>
                                                        <th>View</th>
                                                        <th width="10%">Sub Code</th>
                                                        <th>Activity</th>
                                                        <th width="7%">Quantity</th>
                                                        <th>Rate</th>
                                                        <th>Total</th>
                                                        <th width="7%">Unit</th>
                                                        <th>
                                                            @if(auth()->user()->can('access', 'estimates add'))
                                                                <a title="Add sub activity" href="javascript:;" class="btn btn-sm btn-success add-sub-activity-row" data-main-activity-id="{{ $mainActivity->id }}" data-route="{{ route('library.ajax.add.sub.activity.row', $mainActivity->id) }}">
                                                                    <i class="fas fa-plus"></i>
                                                                </a>
                                                            @endif
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="sub-activity-wrapper-{{ $mainActivity->id }}">
                                                    @if($mainActivity->subActivities->isNotEmpty())
                                                        @foreach($mainActivity->subActivities as $subActivity)
                                                            <tr class="sub-activity-row-{{ $subActivity->id }} expandable" data-row-id="{{ $subActivity->id }}">
                                                                
                                                                <td><input type="button" class="btn btn-primary btn-sm expandable-input" value="+"></td>
                                                                <td>
                                                                <input title="Sub Code- {{ $subActivity->sub_code }}" type="text" name="sub_activity[{{ $subActivity->id }}][sub_code]" value="{{ $subActivity->sub_code }}" id="sub_code{{ $subActivity->id }}" class="form-control sub_code" disabled="disabled" onchange="update_sub_activity_row('{{ route('library.ajax.update.project.sub.activity.row', $subActivity->id) }}', 'sub_code', this.value, '{{ $subActivity->id }}', '{{ $subActivity->library_main_activity_id }}');">
                                                                </td>
                                                                
                                                                <td>
                                                                <input title="Activity- {{ $subActivity->activity }}" type="text" name="sub_activity[{{ $subActivity->id }}][activity]" value="{{ $subActivity->activity }}" id="activity_sub{{ $subActivity->id }}" class="form-control activity_sub" onchange="update_sub_activity_row('{{ route('library.ajax.update.project.sub.activity.row', $subActivity->id) }}', 'activity', this.value, '{{ $subActivity->id }}', '{{ $subActivity->library_main_activity_id }}');">
                                                                </td>
                                                                <td>
                                                                 <input title="Quantity- {{ $subActivity->quantity }}"  type="text" name="sub_activity[{{ $subActivity->id }}][quantity]" value="{{ $subActivity->quantity }}" id="quantity_sub{{ $subActivity->id }}" class="form-control quantity_sub" onkeypress="javascript:return isNumber(event)" onchange="update_sub_activity_row('{{ route('library.ajax.update.project.sub.activity.row', $subActivity->id) }}', 'quantity', this.value, '{{ $subActivity->id }}', '{{ $subActivity->library_main_activity_id }}');">
                                                                </td>
                                                                <td>
                                                                <input title="Rate- {{ round($subActivity->rate,2) }}" type="text" name="sub_activity[{{ $subActivity->id }}][rate]" value="{{ round($subActivity->rate,2) }}" id="rate_sub{{ $subActivity->id }}" class="form-control rate_sub" onkeypress="javascript:return isNumber(event)" onchange="update_sub_activity_row('{{ route('library.ajax.update.project.sub.activity.row', $subActivity->id) }}', 'rate', this.value, '{{ $subActivity->id }}', '{{ $subActivity->library_main_activity_id }}');">
                                                                </td>
                                                                <td>
                                                                <input title="Total- {{ round($subActivity->total,2) }}"  type="text" name="sub_activity[{{ $subActivity->id }}][total]" value="{{ round($subActivity->total,2) }}" id="total_sub{{ $subActivity->id }}" class="form-control total_sub" onkeypress="javascript:return isNumber(event)" disabled="disabled" onchange="update_sub_activity_row('{{ route('library.ajax.update.project.sub.activity.row', $subActivity->id) }}', 'total', this.value, '{{ $subActivity->id }}', '{{ $subActivity->library_main_activity_id }}');">
                                                                </td>
                                                                <td>
                                                                <input title="Unit- {{ $subActivity->unit }}" type="text" name="sub_activity[{{ $subActivity->id }}][unit]" value="{{ $subActivity->unit }}" id="unit_sub{{ $subActivity->id }}" class="form-control unit_sub" onchange="update_sub_activity_row('{{ route('library.ajax.update.project.sub.activity.row', $subActivity->id) }}', 'unit', this.value, '{{ $subActivity->id }}', '{{ $subActivity->library_main_activity_id }}');">
                                                                
                                                                <input type="hidden" name="sub_activity[{{ $subActivity->id }}][id]" value="{{ $subActivity->id }}">
                                                                <input type="hidden" name="sub_activity[{{ $subActivity->id }}][hr]" value="{{ $subActivity->hr }}" id="hr_sub{{ $subActivity->id }}" class="form-control hr_sub" onchange="update_sub_activity_row('{{ route('library.ajax.update.project.sub.activity.row', $subActivity->id) }}', 'hr', this.value, '{{ $subActivity->id }}', '{{ $subActivity->library_main_activity_id }}');">
                                                                <input type="hidden" name="sub_activity[{{ $subActivity->id }}][mhr]" value="{{ $subActivity->mhr }}" id="mhr_sub{{ $subActivity->id }}" class="form-control mhr_sub" onchange="update_sub_activity_row('{{ route('library.ajax.update.project.sub.activity.row', $subActivity->id) }}', 'mhr', this.value, '{{ $subActivity->id }}', '{{ $subActivity->library_main_activity_id }}');">
                                                                <input type="hidden" name="sub_activity[{{ $subActivity->id }}][total_hr]" value="{{ $subActivity->total_hr }}" id="total_hr_sub{{ $subActivity->id }}" class="form-control total_hr_sub" onchange="update_sub_activity_row('{{ route('library.ajax.update.project.sub.activity.row', $subActivity->id) }}', 'total_hr', this.value, '{{ $subActivity->id }}', '{{ $subActivity->library_main_activity_id }}');">
                                                                <input type="hidden" name="sub_activity[{{ $subActivity->id }}][total_mhr]" value="{{ $subActivity->total_mhr }}" id="total_mhr_sub{{ $subActivity->id }}" class="form-control total_mhr_sub" onchange="update_sub_activity_row('{{ route('library.ajax.update.project.sub.activity.row', $subActivity->id) }}', 'total_mhr', this.value, '{{ $subActivity->id }}', '{{ $subActivity->library_main_activity_id }}');">
                                                                </td>
                                                                <td>
                                                                @if(auth()->user()->can('access', 'estimates add'))
                                                                <a title="Delete sub activity" href="javascript:;" class="btn btn-sm btn-danger remove-sub-activity-row" data-sub-activity-id="{{ $subActivity->id }}" data-route="{{ route('library.ajax.delete.sub.activity.row', $subActivity->id) }}">
                                                                <i class="fas fa-trash"></i>
                                                                </a>
                                                                @endif
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="8" class="expandable">
                                                                    <table class="table table-bordered activity-table" id="activity-table{{ $subActivity->id }}">
                                                                        <thead class="table-warning" style="background-color: #285481; color: white;">
                                                                            <th width="10%">Item Code</th>
                                                                            <th>Activity</th>
                                                                            <th width="7%">Level/Unit</th>
                                                                            <th width="7%">Quantity</th>
                                                                            <th>Cost Rate</th>
                                                                            <th>Selling Rate</th>
                                                                            <th>Profit</th>
                                                                            <th>Total</th>
                                                                            <th>
                                                                                @if(auth()->user()->can('access', 'estimates add'))
                                                                                    <a href="javascript:;" class="btn btn-sm btn-success add-activity-row" data-sub-activity-id="{{ $subActivity->id }}" data-route="{{ route('library.ajax.add.activity.row', $subActivity->id) }}">
                                                                                        <i class="fas fa-plus"></i>
                                                                                    </a>
                                                                                @endif
                                                                            </th>
                                                                        </thead>
                                                                        <tbody class="activity-wrapper-{{ $subActivity->id }}">
                                                                            @if($subActivity->activities->isNotEmpty())
                                                                                @foreach($subActivity->activities as $activity)
                                                                          
                                                                                
                                                                                  
                                                                                    <tr class="activity-row-{{ $activity->id }}" data-row-id="{{ $activity->id }}">
                                                                                        <td>
                                                                                            <input title="Item Code- {{ $activity->item_code }}" type="text" name="activity[{{ $activity->id }}][item_code]"  value="{{ $activity->item_code }}" id="item_code{{ $activity->id }}" class="form-control activity-item_code item_code" disabled="disabled" onchange="update_activity_row('{{ route('library.ajax.update.project.activity.row', $activity->id) }}', 'item_code', this.value, '{{ $activity->id }}', '{{ $activity->library_sub_activity_id }}', '{{ $activity->library_main_activity_id }}');">
                                                                                        </td>
                                                                                            
<td>
<input title="Activity- {{ $activity->activity }}" type="text" name="activity[{{ $activity->id }}][activity]" value="{{ $activity->activity }}" id="activity{{ $activity->id }}" class="form-control activity-activity activity" onchange="update_activity_row('{{ route('library.ajax.update.project.activity.row', $activity->id) }}', 'activity', this.value, '{{ $activity->id }}', '{{ $activity->library_sub_activity_id }}', '{{ $activity->library_main_activity_id }}');">
</td>
<td>
<input title="Level/Unit- {{ $activity->unit }}" type="text" name="activity[{{ $activity->id }}][unit]" value="{{ $activity->unit }}" id="unit{{ $activity->id }}" class="form-control activity-unit unit" onchange="update_activity_row('{{ route('library.ajax.update.project.activity.row', $activity->id) }}', 'unit', this.value, '{{ $activity->id }}', '{{ $activity->library_sub_activity_id }}', '{{ $activity->library_main_activity_id }}');">
</td>
<td>
<input title="Quantity- {{ $activity->quantity }}" type="text" name="activity[{{ $activity->id }}][quantity]" value="{{ $activity->quantity }}" id="quantity{{ $activity->id }}" class="form-control activity-quantity quantity" onkeypress="javascript:return isNumber(event)" onchange="update_activity_row('{{ route('library.ajax.update.project.activity.row', $activity->id) }}', 'quantity', this.value, '{{ $activity->id }}', '{{ $activity->library_sub_activity_id }}', '{{ $activity->library_main_activity_id }}');">
</td>
<td>
<input title="Cost Rate- {{ round($activity->rate,2) }}" type="text" name="activity[{{ $activity->id }}][rate]" value="{{ round($activity->rate,2) }}" id="rate{{ $activity->id }}" class="form-control activity-rate" onkeypress="javascript:return isNumber(event)" onchange="update_activity_row('{{ route('library.ajax.update.project.activity.row', $activity->id) }}', 'rate', this.value, '{{ $activity->id }}', '{{ $activity->library_sub_activity_id }}', '{{ $activity->library_main_activity_id }}');">
</td>
<td>
<input title="Selling Rate- {{ round($activity->selling_cost,2) }}" type="text" name="activity[{{ $activity->id }}][selling_cost]" value="{{ round($activity->selling_cost,2) }}" id="selling_cost{{ $activity->id }}" onkeypress="javascript:return isNumber(event)" class="form-control activity-selling_cost selling_cost" disabled="disabled" onchange="update_activity_row('{{ route('library.ajax.update.project.activity.row', $activity->id) }}', 'selling_cost', this.value, '{{ $activity->id }}', '{{ $activity->library_sub_activity_id }}', '{{ $activity->library_main_activity_id }}');">
</td>
<td>
<input title="Profit- {{ round($activity->profit,2) }}" type="text" name="activity[{{ $activity->id }}][profit]" value="{{ round($activity->profit,2) }}" id="profit{{ $activity->id }}" class="form-control activity-profit profit" onkeypress="javascript:return isNumber(event)" disabled="disabled" onchange="update_activity_row('{{ route('library.ajax.update.project.activity.row', $activity->id) }}', 'profit', this.value, '{{ $activity->id }}', '{{ $activity->library_sub_activity_id }}', '{{ $activity->library_main_activity_id }}');">
</td>
<td>
<input title="Total- {{ round($activity->total,2) }}"  type="text" name="activity[{{ $activity->id }}][total]" value="{{ round($activity->total,2) }}" id="total{{ $activity->id }}" class="form-control activity-total total" onkeypress="javascript:return isNumber(event)" disabled="disabled" onchange="update_activity_row('{{ route('library.ajax.update.project.activity.row', $activity->id) }}', 'total', this.value, '{{ $activity->id }}', '{{ $activity->library_sub_activity_id }}', '{{ $activity->library_main_activity_id }}');">
<input type="hidden" name="activity[{{ $activity->id }}][id]" value="{{ $activity->id }}">
</td>
<td>
@if(auth()->user()->can('access', 'estimates add'))
<a title="Delete activity" href="javascript:;" class="btn btn-sm btn-danger remove-activity-row" data-activity-id="{{ $activity->id }}" data-route="{{ route('library.ajax.delete.activity.row', $activity->id) }}">
<i class="fas fa-trash"></i>
</a>
@endif
</td>

                                                                                    </tr>
                                                                                @endforeach
                                                                            @endif
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="card-footer">
                    {{ $mainActivities->links() }}
                </div>
            </div>
        </div>
        <div class="modal fade modal-role" id="modal-role">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h6 class="modal-title">Select Role</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @isset($roles)
                            <div class="form-group">
                                <input type="hidden" name="hr_id" id="hr_id" value="">
                                {{ Form::select('role', @$roles, old('role'), [
                                    'class' => "form-control multiselect-dropdown role",
                                    'id' => "role",
                                    'onchange' => "save_activty_role(this.value)"
                                ]) }}
                            </div>
                        @endisset
                    </div>
                </div>
            </div>
        </div>
    @endisset
</section>
@stop
@push('scripts')
<script type="text/javascript">
    
    function changeProject(projectId) {
        window.location = "{{ route('library.projects') }}/" + projectId+"/";
    }
    $(function(){
        $('.expandable').next().hide();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
    $(document).ready(function(){
        /* Expand all*/
        $('#expand_all').click(function() { // show all
            $('.expandable input[type=button]').val("-");
            $('.expandable').nextAll('tr').each(function() {
                if (!($(this).is('.expandable')))
                    $(this).show();
            });
        });
        /* Collaps all*/
        $('#collaps_all').click(function() { // hide all
            $('.expandable input[type=button]').val("+");
            $('.expandable').nextAll('tr').each(function() {
                if (!($(this).is('.expandable')))
                    $(this).hide();
            });
        });

        $(document).on("click", ".expandable-input", function( event ) {
            event.preventDefault();
            if($(this).closest('td input[type=button]').val() == "+"){
                $(this).closest('td input[type=button]').val("-");
            }else if($(this).closest('td input[type=button]').val() == "-"){
                $(this).closest('td input[type=button]').val("+");
            }
            var trElem = $(this).closest("tr");
            trElem.nextAll('tr').each(function() {
                if ($(this).is('.expandable')) {
                    return false;
                }
                $(this).toggle();
            });
        });
        /* show project info */
        $(document).on('click', '.show-project-info', function(){
            var el = $(this);
            var route = el.attr('data-route');
            $.get(route, function(data){
                $(document).find('.project-estimate-detail').find('.card-body').html(data.html);
                $(document).find('.project-formula-keyword').hide();
                $(document).find('.project-estimate-detail').show();
            });
        });

        /* show project info */
        $(document).on('click', '.hide-project-info', function(){
            $(document).find('.project-estimate-detail').hide();
        });

        $(document).on('click', '.show-project-formula', function(){
            var el = $(this);
            var route = el.attr('data-route');
            $.get(route, function(data){
                $(document).find('.project-formula-keyword').find('.formula-wrapper').html(data.html);
                $(document).find('.project-estimate-detail').hide();
                $(document).find('.project-formula-keyword').show();
            });
        });

        /* show project info */
        $(document).on('click', '.hide-project-formula', function(){
            $(document).find('.project-formula-keyword').hide();
        });

        /* add main activity row */
        $(document).on('click', '.add-main-activity-row', function(){
            var el = $(this);
            var route = el.attr('data-route');
            $.get(route, function(data){
                $(document).find('.main-activity-wrapper').append(data.html);
            });
        });
        /* add formula row */
        $(document).on('click', '.add-formula-row', function(){
            var el = $(this);
            var route = el.attr('data-route');
            $.get(route, function(data){
                $(document).find('.formula-wrapper').append(data.html);
            });
        });
        /* remove formula row */
        $(document).on('click', '.remove-formula-row', function(){
            var el = $(this);
            if(confirm('Are you sure want to remove the formula?')){
                var route = el.attr('data-route');
                var formulaId = el.attr('data-formula-id');
                $.get(route, function(data){
                    if(data.status == true){
                        $(document).find('.formula-row-'+formulaId).remove();
                    }else{
                        alert(data.message);
                    }
                });
            }
        });
        /* add sub activity row */
        $(document).on('click', '.add-sub-activity-row', function(){
            var el = $(this);
            var route = el.attr('data-route');
            var mainActivityId = el.attr('data-main-activity-id');
            $.get(route, function(data){
                $(document).find('.sub-activity-wrapper-'+mainActivityId).append(data.html);
            });
        });
        /* add activity row */
        $(document).on('click', '.add-activity-row', function(){
            var el = $(this);
            var route = el.attr('data-route');
            var subActivityId = el.attr('data-sub-activity-id');
            $.get(route, function(data){
                $(document).find('.activity-wrapper-'+subActivityId).append(data.html);
            });
        });
        /* remove main activity row */
        $(document).on('click', '.remove-main-activity-row', function(){
            var el = $(this);
            if(confirm('Are you sure want to remove the main activity?')){
                var route = el.attr('data-route');
                var mainActivityId = el.attr('data-main-activity-id');

                $('#rate_main'+mainActivityId).val('0'); 
                $('#quantity_main'+mainActivityId).val('0');
                $('#total_main'+mainActivityId).val('0');

                $('#rate_main'+mainActivityId).trigger('keyup', [{preventDefault:function(){},keyCode:37}]);

                $.get(route, function(data){
                    if(data.status == true){
                        $(document).find('.main-activity-row-'+mainActivityId).remove();
                    }else{
                        alert(data.message);
                    }
                });
            }
        });
        /* remove sub activity row */
        $(document).on('click', '.remove-sub-activity-row', function(){
            var el = $(this);
            if(confirm('Are you sure want to remove the sub activity?')){
                var route = el.attr('data-route');
                var subActivityId = el.attr('data-sub-activity-id');
                $('#rate_sub'+subActivityId).val('0'); 
                $('#quantity_sub'+subActivityId).val('0');
                $('#total_sub'+subActivityId).val('0');

                $('#rate_sub'+subActivityId).trigger('keyup', [{preventDefault:function(){},keyCode:37}]);

                $.get(route, function(data){
                    if(data.status == true){
                        $(document).find('.sub-activity-row-'+subActivityId).remove();
                    }else{
                        alert(data.message);
                    }
                });
            }
        });
        /* remove activity row */
        $(document).on('click', '.remove-activity-row', function(){
            var el = $(this);
            if(confirm('Are you sure want to remove the activity?')){
                var route = el.attr('data-route');
                var activityId = el.attr('data-activity-id');

                $('#rate'+activityId).val('0'); 
                $('#quantity'+activityId).val('0');
                $('#total'+activityId).val('0');
                $('#rate'+activityId).trigger('keyup', [{preventDefault:function(){},keyCode:37}]);

                $.get(route, function(data){
                    if(data.status == true){
                        $(document).find('.activity-row-'+activityId).remove();
                    }else{
                        alert(data.message);
                    }
                });
            }
        });

        $(document).on('keyup', '.activity-unit', function(){
            var tr = $(this).parent().parent();  
            var index = tr.attr('data-row-id');
            var unit = $(this).val(); 	
            unit = unit.trim();	
            if(unit == "mhr" || unit == "dhr"){
                $('#hr_id').val(index);
                $('.modal-role').modal('show');
            }else{
                $('.modal-role').modal('hide');
            }
        });


        $(document).on('keyup', '.activity-rate, .activity-quantity, .activity-total', function()  {  
            var tr = $(this).parent().parent();  
            var index = tr.attr('data-row-id');
            var unit = $('#unit'+index).val(); 	
            var rate = $('#rate'+index).val(); 
            var quantity = $('#quantity'+index).val();
            var total = $('#total'+index).val();
            unit = unit.trim();	
            if(unit == "hr"){
                //alert("hr");  
            }
            var selling_cost = $('#selling_cost'+index).val(); 
            var profit = $('#profit'+index).val();	
            var base_margin = $('#base_margin').val(); 	

            rate = isNaN(parseFloat(rate)) ? 0 : parseFloat(rate);
            quantity = isNaN(parseFloat(quantity)) ? 0 : parseFloat(quantity);
            total = isNaN(parseFloat(total)) ? 0 : parseFloat(total);
                
            selling_cost = isNaN(parseFloat(selling_cost)) ? 0 : parseFloat(selling_cost);	
            profit = isNaN(parseFloat(profit)) ? 0 : parseFloat(profit);	
            base_margin = isNaN(parseFloat(base_margin)) ? 0 : parseFloat(base_margin);

            rate = parseFloat(rate).toFixed(2);
            quantity = parseFloat(quantity).toFixed(2);
            total = parseFloat(total).toFixed(2);
            
            base_margin = parseFloat(base_margin).toFixed(2);		

            selling_cost = rate/(1-(base_margin/100));	

            selling_cost = parseFloat(selling_cost).toFixed(2);	
            
            profit = selling_cost - rate;
            var total=selling_cost*quantity;

            total = parseFloat(total).toFixed(2);

            profit = parseFloat(profit).toFixed(2);	

            tr.find('#selling_cost'+index).val(selling_cost);	
            tr.find('#profit'+index).val(profit);	
            tr.find('#total'+index).val(total);

            var trate = 0;
            var tquantity = 0;
            var ttotal = 0;

            var thr = 0;	
            var tmhr = 0;	
            
            var thr1 = 0;	
            var tmhr1 = 0;		
            
            var trate1 = 0;
            var tquantity1 = 0;
            var ttotal1 = 0;

            var t = this.parentNode;

            tagName = "table";

            var totals = 0;
            var totals1 = 0;

            var c = 1;
            var c1 = 1;

            while (t) {
                if (t.tagName && t.tagName.toLowerCase() == tagName) {
                    console.log(str1)
                    var  str1 = t.id.replace ( /[^\d.]/g, '' ); 
                    totals = parseInt(str1);
                    
                    var tables = document.getElementById(t.id);
                        var rows = tables.getElementsByTagName("tr");
                        var l = rows.length;
                        for(i = 1; i < rows.length; i++)
                        {
                            var indexs= $(rows[i]).attr('data-row-id');

                            var rate = $('#rate'+indexs).val(); 
                            var quantity = $('#quantity'+indexs).val();
                            var total = $('#total'+indexs).val();
                            var unit = $('#unit'+indexs).val();

                            unit = unit.trim();
                            
                            rate = parseFloat(rate);
                            quantity = parseFloat(quantity);
                            total = parseFloat(total);
                            trate = trate+rate;
                            tquantity = tquantity+quantity;
                            ttotal = ttotal+total;

                            if(unit == "hr")
                            {
                                thr = thr + total;
                            }
            
                            if(unit == "mhr")
                            {
                                tmhr = tmhr + total;
                            }	
                            c++;

                            if(l == c)
                            {
                                ttotal = parseFloat(ttotal).toFixed(2);	
                                $('#rate_sub'+totals).val(ttotal);
                                var qs = $('#quantity_sub'+totals).val();
                                qs=parseFloat(qs);
                                var tttotal=qs*ttotal;
                                tttotal = parseFloat(tttotal).toFixed(2);		
                                $('#total_sub'+totals).val(tttotal);
                                

                                thr = parseFloat(thr).toFixed(2);		
                                $('#hr_sub'+totals).val(thr);

                                var totalhr=qs*thr;
                                totalhr = parseFloat(totalhr).toFixed(2);		
                                $('#total_hr_sub'+totals).val(totalhr);	
                                    
                                tmhr = parseFloat(tmhr).toFixed(2);		
                                $('#mhr_sub'+totals).val(tmhr);	
                                    
                                var totalmhr=qs*tmhr;
                                totalmhr = parseFloat(totalmhr).toFixed(2);		
                                $('#total_mhr_sub'+totals).val(totalmhr);	
                            }
                        }
            
                        var tdd = t.parentNode;

                        while (tdd) {
                            if (tdd.tagName && tdd.tagName.toLowerCase() == tagName) {
                            var  str11 = tdd.id.replace ( /[^\d.]/g, '' ); 

                            totals1 = parseInt(str11);
    
                            var tables1 = document.getElementById(tdd.id);
            
                            var rows1 = tables1.getElementsByTagName("tr");
                            var l1 = rows1.length;
                            for(j = 1; j < rows1.length; j++)
                            { 
                                var indexs1 = $(rows1[j]).attr('data-row-id');

                                if(indexs1 === undefined){

                                }else{
                                    var rate1 = $('#rate_sub'+indexs1).val(); 
                                    var quantity1 = $('#quantity_sub'+indexs1).val();
                                    var total1 = $('#total_sub'+indexs1).val();

                                    var hr1 = $('#hr_sub'+indexs1).val();
                                    var mhr1 = $('#mhr_sub'+indexs1).val();	
                                        
                                    var total_hr1 = $('#total_hr_sub'+indexs1).val();
                                    var total_mhr1 = $('#total_mhr_sub'+indexs1).val();		

                                    if(rate1 === undefined && quantity1 === undefined && total1 === undefined){

                                    }else{
                                        rate1 = parseFloat(rate1);
                                        quantity1 = parseFloat(quantity1);
                                        total1 = parseFloat(total1);

                                        hr1 = parseFloat(hr1);
                                        mhr1 = parseFloat(mhr1);	
                                            
                                        total_hr1 = parseFloat(total_hr1);
                                        total_mhr1 = parseFloat(total_mhr1);	

                                        trate1 = trate1+rate1;
                                        tquantity1 = tquantity1+quantity1;
                                        ttotal1 = ttotal1+total1;

                                        thr1 = thr1+total_hr1;
                                        tmhr1 = tmhr1+total_mhr1;
                                    }



                                }
                                c1++;

                                if(l1 == c1){
                                    ttotal1 = parseFloat(ttotal1).toFixed(2);		
                                    $('#rate_main'+totals1).val(ttotal1);
                                    var qm =$('#quantity_main'+totals1).val();
                                    qm=parseFloat(qm);
                                    var tttotal1=ttotal1*qm;
                                    tttotal1 = parseFloat(tttotal1).toFixed(2);		
                                    $('#total_main'+totals1).val(tttotal1);

                                    thr1 = parseFloat(thr1).toFixed(2);	
                                    $('#hr_main'+totals1).val(thr1);
                                        
                                    var tthr1=thr1*qm;
                                    tthr1 = parseFloat(tthr1).toFixed(2);		
                                    $('#total_hr_main'+totals1).val(tthr1);
                                        
                                    tmhr1 = parseFloat(tmhr1).toFixed(2);	
                                    $('#mhr_main'+totals1).val(tmhr1);
                                        
                                    var ttmhr1=tmhr1*qm;
                                    ttmhr1 = parseFloat(ttmhr1).toFixed(2);		
                                    $('#total_mhr_main'+totals1).val(ttmhr1);	
                                        
                                    var uqm =$('#unit_qty_main'+totals1).val();
                                    uqm=parseFloat(uqm);	
                                    var unitratemain=tttotal1/uqm;
                                    if(unitratemain=="Infinity"){
                                         unitratemain=0;
                                    }	
                                    unitratemain = parseFloat(unitratemain).toFixed(2);		
                                    $('#unit_rate_main'+totals1).val(unitratemain);
                                }
                            }
                            return;
                        }else{
                            tdd = tdd.parentNode;
                        }
                    }	
                    return;
                }else{
                    t = t.parentNode;
                }
            }
        }); 

        $(document).on('keyup','.rate_sub, .quantity_sub, .total_sub', function(){  

            var tr = $(this).parent().parent();  
            var index=tr.attr('data-row-id');

            var rate_sub = $('#rate_sub'+index).val(); 
            var quantity_sub = $('#quantity_sub'+index).val();
            var total_sub = $('#total_sub'+index).val();
            
            var hr_sub = $('#hr_sub'+index).val();
            var mhr_sub = $('#mhr_sub'+index).val();

            var total_hr_sub = $('#total_hr_sub'+index).val();
            var total_mhr_sub = $('#total_mhr_sub'+index).val();

            rate_sub = isNaN(parseFloat(rate_sub)) ? 0 : parseFloat(rate_sub);
            quantity_sub = isNaN(parseFloat(quantity_sub)) ? 0 : parseFloat(quantity_sub);
            total_sub = isNaN(parseFloat(total_sub)) ? 0 : parseFloat(total_sub);

            hr_sub = isNaN(parseFloat(hr_sub)) ? 0 : parseFloat(hr_sub);
            mhr_sub = isNaN(parseFloat(mhr_sub)) ? 0 : parseFloat(mhr_sub);
                
            total_hr_sub = isNaN(parseFloat(total_hr_sub)) ? 0 : parseFloat(total_hr_sub);
            total_mhr_sub = isNaN(parseFloat(total_mhr_sub)) ? 0 : parseFloat(total_mhr_sub);	

            rate_sub = parseFloat(rate_sub).toFixed(2);
            quantity_sub = parseFloat(quantity_sub).toFixed(2);
            total_sub = parseFloat(total_sub).toFixed(2);
                
            hr_sub = parseFloat(hr_sub).toFixed(2);
            mhr_sub = parseFloat(mhr_sub).toFixed(2);
                
            total_hr_sub = parseFloat(total_hr_sub).toFixed(2);
            total_mhr_sub = parseFloat(total_mhr_sub).toFixed(2);	

            var total_sub=rate_sub*quantity_sub;

            var total_hr_sub=hr_sub*quantity_sub;
            var total_mhr_sub=mhr_sub*quantity_sub;

            total_sub = parseFloat(total_sub).toFixed(2);

            total_hr_sub = parseFloat(total_hr_sub).toFixed(2);	
            total_mhr_sub = parseFloat(total_mhr_sub).toFixed(2);
                
            tr.find('#total_sub'+index).val(total_sub);

            tr.find('#total_hr_sub'+index).val(total_hr_sub);	
            tr.find('#total_mhr_sub'+index).val(total_mhr_sub);

            var trate = 0;
            var tquantity = 0;
            var ttotal = 0;

            var thr = 0;
            var tmhr = 0;

            var t = this.parentNode;
            tagName = "table";
            var totals = 0;
            var c = 1;

            while (t) {
                if (t.tagName && t.tagName.toLowerCase() == tagName) {
                    var  str1 = t.id.replace ( /[^\d.]/g, '' ); 
                    totals = parseInt(str1);
                    var tables = document.getElementById(t.id);
                    var rows = tables.getElementsByTagName("tr");
                    var l = rows.length;
                    for(i = 1; i < rows.length; i++){
                        var indexs = $(rows[i]).attr('data-row-id');
                        if(indexs === undefined){
                        }else{
                            var rate=$('#rate_sub'+indexs).val(); 
                            var quantity=$('#quantity_sub'+indexs).val();
                            var total=$('#total_sub'+indexs).val();
                                
                            var hr_sub=$('#hr_sub'+indexs).val();
                            var mhr_sub=$('#mhr_sub'+indexs).val();

                            var total_hr_sub=$('#total_hr_sub'+indexs).val();
                            var total_mhr_sub=$('#total_mhr_sub'+indexs).val();	
                            if(rate === undefined && quantity === undefined && total === undefined){ 

                            }else{
                                rate=parseFloat(rate);
                                quantity=parseFloat(quantity);
                                total=parseFloat(total);
                                    
                                hr_sub = parseFloat(hr_sub);
                                mhr_sub = parseFloat(mhr_sub);
                                    
                                total_hr_sub = parseFloat(total_hr_sub);
                                total_mhr_sub = parseFloat(total_mhr_sub);	
                            
                                trate = trate+rate;
                                tquantity = tquantity+quantity;
                                ttotal = ttotal+total;
                                    
                                thr = thr+total_hr_sub;
                                tmhr = tmhr+total_mhr_sub;	
            
                            }

                        }

                        c++;
                        if(l == c){
                            ttotal = parseFloat(ttotal).toFixed(2);		
                            $('#rate_main'+totals).val(ttotal);
                            var qm = $('#quantity_main'+totals).val();
                            qm=parseFloat(qm);
                            var ttotals = qm*ttotal;
                            ttotals = parseFloat(ttotals).toFixed(2);		
                            $('#total_main'+totals).val(ttotals);
                                
                            thr = parseFloat(thr).toFixed(2);		
                            $('#hr_main'+totals).val(thr);
                                
                            tmhr = parseFloat(tmhr).toFixed(2);		
                            $('#mhr_main'+totals).val(tmhr);	
                                
                            var tthr = qm*thr;
                            tthr = parseFloat(tthr).toFixed(2);		
                            $('#total_hr_main'+totals).val(tthr);
                                
                            var ttmhr = qm*tmhr;
                            ttmhr = parseFloat(ttmhr).toFixed(2);		
                            $('#total_mhr_main'+totals).val(ttmhr);	
                                
                            var tuqm =$('#unit_qty_main'+totals).val();
                            tuqm=parseFloat(tuqm);	
                            var tunitratemain=ttotals/tuqm;
                            if(tunitratemain=="Infinity"){
                                tunitratemain=0;
                            }	
                            tunitratemain = parseFloat(tunitratemain).toFixed(2);		
                            $('#unit_rate_main'+totals).val(tunitratemain);	
            
                        }
                    }
                    return;
                }else{
                    t = t.parentNode;
                }
            }

        });

        $(document).on('keyup', '.rate_main, .quantity_main, .total_main, .unit_qty_main', function(){  

            var tr = $(this).parent().parent();  
            var index=tr.attr('data-row-id');

            var rate_main=$('#rate_main'+index).val(); 
            var quantity_main=$('#quantity_main'+index).val();
            var total_main=$('#total_main'+index).val();
            
            var hr_main=$('#hr_main'+index).val(); 
            var mhr_main=$('#mhr_main'+index).val(); 	
            var total_hr_main=$('#total_hr_main'+index).val();
            var total_mhr_main=$('#total_mhr_main'+index).val();
            
            var unit_qty_main=$('#unit_qty_main'+index).val();	

            rate_main = isNaN(parseFloat(rate_main)) ? 0 : parseFloat(rate_main);
            quantity_main = isNaN(parseFloat(quantity_main)) ? 0 : parseFloat(quantity_main);
            total_main = isNaN(parseFloat(total_main)) ? 0 : parseFloat(total_main);
            
            hr_main = isNaN(parseFloat(hr_main)) ? 0 : parseFloat(hr_main);
            mhr_main = isNaN(parseFloat(mhr_main)) ? 0 : parseFloat(mhr_main);	
            total_hr_main = isNaN(parseFloat(total_hr_main)) ? 0 : parseFloat(total_hr_main);
            total_mhr_main = isNaN(parseFloat(total_mhr_main)) ? 0 : parseFloat(total_mhr_main);
            
            unit_qty_main = isNaN(parseFloat(unit_qty_main)) ? 0 : parseFloat(unit_qty_main);	


            rate_main = parseFloat(rate_main).toFixed(2);
            quantity_main = parseFloat(quantity_main).toFixed(2);
            total_main = parseFloat(total_main).toFixed(2);

            hr_main = parseFloat(hr_main).toFixed(2);
            mhr_main = parseFloat(mhr_main).toFixed(2);	
            total_hr_main = parseFloat(total_hr_main).toFixed(2);
            total_mhr_main = parseFloat(total_mhr_main).toFixed(2);
            
            unit_qty_main = parseFloat(unit_qty_main).toFixed(2);	

            var total_main=rate_main*quantity_main;
            
            var total_hr_main=hr_main*quantity_main;	
            var total_mhr_main=mhr_main*quantity_main;

            var unit_rate_main=total_main/unit_qty_main;
            if(unit_rate_main=="Infinity"){
                unit_rate_main=0;
            }
            total_main = parseFloat(total_main).toFixed(2);

            total_hr_main = parseFloat(total_hr_main).toFixed(2);	
            total_mhr_main = parseFloat(total_mhr_main).toFixed(2);
            
            unit_rate_main = parseFloat(unit_rate_main).toFixed(2);	
            
            tr.find('#total_main'+index).val(total_main);

            tr.find('#total_hr_main'+index).val(total_hr_main);	
            tr.find('#total_mhr_main'+index).val(total_mhr_main);
            
            tr.find('#unit_rate_main'+index).val(unit_rate_main);
            
        }); 

    });

    /* print div */
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = originalContents;
        window.print();
    }

    /* save hr */
    function save_activty_role(role){
        var id = $('#hr_id').val(); 	
        var role = role; 	
        $.ajax({  
            url:"{{ route('estimates.ajax.update.project.activity.role') }}",  
            method:"POST",  
            data:{
                id: id,
                role: role
            },  
            success:function(data){  
                $('.modal-role').modal('hide');
            }
        });

    }

    /* update formula row */
    function update_formula_row(route, colomn_name, colomn_value){
        $.ajax({  
            url : route,  
            method :"POST",  
            data : {
                column: colomn_name,
                value: colomn_value
            },  
            success:function(data)  {  
                if(data.status == false){
                    alert(data.message);
                }
                console.log(data.message);
            }
        }); 
    }
    /* update main activity row */
    function update_main_activity_row(route, colomn_name, colomn_value, row_id){
        var quantity = "";
        var rate = "";
        var total = "";
        var hr = "";
        var total_hr = "";
        var mhr = "";
        var total_mhr = "";
        var unit_qty = "";	
        var unit_rate = "";
        if(colomn_name=="quantity" || colomn_name=="rate" || colomn_name=="total" || colomn_name=="unit" || colomn_name=="unit_qty"){
            quantity = $("#quantity_main"+row_id).val();
            rate = $("#rate_main"+row_id).val();
            total = $("#total_main"+row_id).val();
            hr = $("#hr_main"+row_id).val();
            total_hr = $("#total_hr_main"+row_id).val(); 
            mhr = $("#mhr_main"+row_id).val();
            total_mhr = $("#total_mhr_main"+row_id).val(); 
            unit_qty = $("#unit_qty_main"+row_id).val();
            unit_rate = $("#unit_rate_main"+row_id).val();
        }
        $.ajax({  
            url : route,  
            method :"POST",  
            data : {
                column: colomn_name,
                value: colomn_value,
                quantity : quantity,
                rate : rate,
                total : total,
                hr : hr,
                total_hr : total_hr,
                mhr : mhr,
                total_mhr : total_mhr,
                unit_qty : unit_qty,	
                unit_rate : unit_rate,
            },  
            success:function(data)  {  
                if(data.status == false){
                    alert(data.message);
                }
                console.log(data.message);
            }
        }); 
    }
    /* update main activity row */
    function update_sub_activity_row(route, colomn_name, colomn_value, row_id, main_row_id){

        var quantity_sub ="";
        var rate_sub ="";
        var total_sub ="";
        var hr_sub ="";
        var total_hr_sub ="";
        var mhr_sub ="";
        var total_mhr_sub ="";
        var quantity_main ="";
        var rate_main ="";
        var total_main ="";
        var hr_main ="";
        var total_hr_main ="";
        var mhr_main ="";
        var total_mhr_main ="";	
        var unit_qty_main ="";	
        var unit_rate_main ="";

        if(colomn_name=="quantity" || colomn_name=="rate" || colomn_name=="total" || colomn_name=="unit" || colomn_name=="unit_qty"){
            quantity_sub = $("#quantity_sub"+row_id).val();
            rate_sub = $("#rate_sub"+row_id).val();
            total_sub = $("#total_sub"+row_id).val();
            hr_sub = $("#hr_sub"+row_id).val();
            total_hr_sub = $("#total_hr_sub"+row_id).val(); 
            mhr_sub = $("#mhr_sub"+row_id).val();
            total_mhr_sub = $("#total_mhr_sub"+row_id).val();    
        
            quantity_main = $("#quantity_main"+main_row_id).val();
            rate_main = $("#rate_main"+main_row_id).val();
            total_main = $("#total_main"+main_row_id).val();
            hr_main = $("#hr_main"+main_row_id).val();
            total_hr_main = $("#total_hr_main"+main_row_id).val(); 
            mhr_main = $("#mhr_main"+main_row_id).val();
            total_mhr_main = $("#total_mhr_main"+main_row_id).val();   
            
            unit_qty_main = $("#unit_qty_main"+main_row_id).val();
            unit_rate_main = $("#unit_rate_main"+main_row_id).val(); 
        }
        $.ajax({  
            url : route,  
            method :"POST",  
            data : {
                column: colomn_name,
                value: colomn_value,
                quantity_sub : quantity_sub,
                rate_sub : rate_sub,
                total_sub : total_sub,
                hr_sub : hr_sub,
                total_hr_sub : total_hr_sub,
                mhr_sub : mhr_sub,
                total_mhr_sub : total_mhr_sub,
                quantity_main : quantity_main,
                rate_main : rate_main,
                total_main : total_main,
                hr_main : hr_main,
                total_hr_main : total_hr_main,
                mhr_main : mhr_main,
                total_mhr_main : total_mhr_main,
                unit_qty_main : unit_qty_main,	
                unit_rate_main : unit_rate_main
            },  
            success:function(data)  {  
                if(data.status  == false){
                    alert(data.message);
                }
                console.log(data.message);
            }
        }); 
    }
    /* update main activity row */
    function update_activity_row(route, colomn_name, colomn_value, row_id, sub_row_id, main_row_id){
        var item_code="";
        var unit = "";
        var quantity= "";
        var rate = "";
        var total = "";
        var selling_cost = "";
        var quantity_sub ="";
        var rate_sub ="";
        var total_sub ="";
        var hr_sub ="";
        var total_hr_sub ="";
        var mhr_sub ="";
        var total_mhr_sub ="";
        var quantity_main ="";
        var rate_main ="";
        var total_main ="";
        var hr_main ="";
        var total_hr_main ="";
        var mhr_main ="";
        var total_mhr_main ="";	
        var unit_qty_main ="";	
        var unit_rate_main ="";

        if(colomn_name=="quantity" || colomn_name=="rate" || colomn_name=="total" || colomn_name=="unit" || colomn_name=="unit_qty"){
            unit = $("#unit"+row_id).val();  
            unit = unit.trim();
            quantity = $("#quantity"+row_id).val();
            rate = $("#rate"+row_id).val();
            selling_cost = $("#selling_cost"+row_id).val();  
            total = $("#total"+row_id).val(); 

            quantity_sub=$("#quantity_sub"+sub_row_id).val();
            rate_sub=$("#rate_sub"+sub_row_id).val();
            total_sub=$("#total_sub"+sub_row_id).val();
            hr_sub=$("#hr_sub"+sub_row_id).val();
            total_hr_sub=$("#total_hr_sub"+sub_row_id).val(); 
            total_mhr_sub=$("#total_mhr_sub"+sub_row_id).val();   
        
            quantity_main=$("#quantity_main"+main_row_id).val();
            rate_main=$("#rate_main"+main_row_id).val();
            total_main=$("#total_main"+main_row_id).val();
            hr_main=$("#hr_main"+main_row_id).val();
            total_hr_main=$("#total_hr_main"+main_row_id).val(); 
            mhr_main=$("#mhr_main"+main_row_id).val();
            total_mhr_main=$("#total_mhr_main"+main_row_id).val(); 
            
            unit_qty_main=$("#unit_qty_main"+main_row_id).val();
            unit_rate_main=$("#unit_rate_main"+main_row_id).val();
        }

        $.ajax({  
            url : route,  
            method :"POST",  
            data : {
                column: colomn_name,
                value: colomn_value,
                unit: unit,
                quantity: quantity,
                rate: rate,
                total: total,
                selling_cost: selling_cost,
                quantity_sub : quantity_sub,
                rate_sub : rate_sub,
                total_sub : total_sub,
                hr_sub : hr_sub,
                total_hr_sub : total_hr_sub,
                mhr_sub : mhr_sub,
                total_mhr_sub : total_mhr_sub,
                quantity_main : quantity_main,
                rate_main : rate_main,
                total_main : total_main,
                hr_main : hr_main,
                total_hr_main : total_hr_main,
                mhr_main : mhr_main,
                total_mhr_main : total_mhr_main,	
                selling_cost : selling_cost,
                unit_qty_main : unit_qty_main,	
                unit_rate_main : unit_rate_main
            },  
            success:function(data)  {  
                if(data.status == false){
                    alert(data.message);
                }
                console.log(data.message);
            }
        }); 
    }

    function isNumber(evt) {
        var iKeyCode = (evt.which) ? evt.which : evt.keyCode
        if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
            return false;

        return true;
    }
    
    $('#all').click(function () {   
        $(':checkbox.emp_checkbox').prop('checked', this.checked);    
    });
    
    
    $('#delete_multiple').click(function(e){
    var employee = [];	
	$(".emp_checkbox:checked").each(function() {
		employee.push($(this).data('emp-id'));
	});

	if(employee.length <=0) {
		alert("Please select atlease one row."); 
	} else { 
		
	
		if(confirm('Are you sure you want to delete?')) {
		rd="delete";
		}
		else
		{
		rd="del";
		return;
		}
		//var id = 1;
		var table = "library_main_activities";
			var selected_values = employee.join(",");
			
		 
           
           
			$.ajax({

				
				url:"{{ route('library.ajax.delete.main.activity') }}",
				method:"POST",  
				data:{id:selected_values, table : table},  
				success:function(data)  
				{    
					alert("Selected rows deleted successfully.");
					window.location.reload(true);
					/*setTimeout(function () {
						window.location.reload(true);
					}, 3000);	*/

				} 
				
			});
    	} 
    });	
    
    $('#copy_multiple_to_estimate').click(function(e){
    var employee = [];	
	$(".emp_checkbox:checked").each(function() {
		employee.push($(this).data('emp-id'));
	});

    	if(employee.length <=0) {
    		alert("Please select atlease one row."); 
    	} else { 
	    
        	var proj = $('select[name=project_ids] option:selected').val();
        	if(proj =="") {
        		alert("Please select Project."); 
        	} else { 	
    
        		//var id = 1;
        		var table = "library_main_activities";
        			var selected_values = employee.join(",");
        			$.ajax({

        				url:"{{ route('library.copy.lib_estimate') }}",
        				method:"POST",  
        				data:{selected_values:selected_values, project_id : proj},  
        				success:function(data)  
        				{    //alert(JSON.stringify(data));
        					alert("Selected rows Copied successfully.");
        					//window.location.reload(true);
        					/*setTimeout(function () {
        						window.location.reload(true);
        					}, 3000);	*/
        
        				} 
        				
        			});
        		 
                   
                   
        	
        	} 
	    }   
    });	
    
   
</script>
<script>
	function myFunction() {
    var input, filter, found, table, tr, td, i, j;
    input = document.getElementById("myInput");
    filter = input.value.toUpperCase();
    table = document.getElementById("main_activity_table");
    tr = table.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td");
        for (j = 0; j < td.length; j++) {
            if (td[j].innerHTML.toUpperCase().indexOf(filter) > -1) {
                found = true;
            }
        }
        if (found) {
            tr[i].style.display = "";
            found = false;
        } else {
            tr[i].style.display = "none";
        }
    }
}
</script>
@endpush
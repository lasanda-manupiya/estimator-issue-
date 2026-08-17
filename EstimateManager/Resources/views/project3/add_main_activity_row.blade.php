<tr class="main-activity-row-{{ $mainActivity->id }} expandable" data-row-id="{{ $mainActivity->id }}">
    <td></td>
    <td><input type="button" class="btn btn-primary btn-sm expandable-input" value="-"></td>
    <td>
        <input type="text" name="main_activity[{{ $mainActivity->id }}][main_code]" value="{{ $mainActivity->main_code }}" id="main_code{{ $mainActivity->id }}" class="form-control main_code" disabled="disabled" onchange="update_main_activity_row('{{ route('estimates.ajax.update.project.main.activity.row', $mainActivity->id) }}', 'main_code', this.value, '{{ $mainActivity->id }}');">
    </td>
    <td>
        <input type="text" name="main_activity[{{ $mainActivity->id }}][area]" value="{{ $mainActivity->area }}" id="area_main{{ $mainActivity->id }}" class="form-control area_main" onchange="update_main_activity_row('{{ route('estimates.ajax.update.project.main.activity.row', $mainActivity->id) }}', 'area', this.value, '{{ $mainActivity->id }}');">
    </td>
    <td>
        <input type="text" name="main_activity[{{ $mainActivity->id }}][level]" value="{{ $mainActivity->level }}" id="level_main{{ $mainActivity->id }}" class="form-control level_main" onchange="update_main_activity_row('{{ route('estimates.ajax.update.project.main.activity.row', $mainActivity->id) }}', 'level', this.value, '{{ $mainActivity->id }}');">
    </td>
    <td>
        <input type="text" name="main_activity[{{ $mainActivity->id }}][activity]" value="{{ $mainActivity->activity }}" id="activity_main{{ $mainActivity->id }}" class="form-control activity_main" onchange="update_main_activity_row('{{ route('estimates.ajax.update.project.main.activity.row', $mainActivity->id) }}', 'activity', this.value, '{{ $mainActivity->id }}');">
    </td>
    <td>
        <input type="text" name="main_activity[{{ $mainActivity->id }}][quantity]" value="{{ $mainActivity->quantity }}" id="quantity_main{{ $mainActivity->id }}" class="form-control quantity_main" onkeypress="javascript:return isNumber(event)" onchange="update_main_activity_row('{{ route('estimates.ajax.update.project.main.activity.row', $mainActivity->id) }}', 'quantity', this.value, '{{ $mainActivity->id }}');">
    </td>
    <td>
        <input type="text" name="main_activity[{{ $mainActivity->id }}][rate]" value="{{ $mainActivity->rate }}" id="rate_main{{ $mainActivity->id }}" class="form-control rate_main" disabled="disabled" onkeypress="javascript:return isNumber(event)" onchange="update_main_activity_row('{{ route('estimates.ajax.update.project.main.activity.row', $mainActivity->id) }}', 'rate', this.value, '{{ $mainActivity->id }}');">
    </td>
    <td>
        <input type="text" name="main_activity[{{ $mainActivity->id }}][total]" value="{{ $mainActivity->total }}" id="total_main{{ $mainActivity->id }}" class="form-control total_main" disabled="disabled" onkeypress="javascript:return isNumber(event)" onchange="update_main_activity_row('{{ route('estimates.ajax.update.project.main.activity.row', $mainActivity->id) }}', 'total', this.value, '{{ $mainActivity->id }}');">
    </td>
    <td>
        <input type="text" name="main_activity[{{ $mainActivity->id }}][unit_qty]" value="{{ $mainActivity->unit_qty }}" id="unit_qty_main{{ $mainActivity->id }}" class="form-control unit_qty_main" onkeypress="javascript:return isNumber(event)" onchange="update_main_activity_row('{{ route('estimates.ajax.update.project.main.activity.row', $mainActivity->id) }}', 'unit_qty', this.value, '{{ $mainActivity->id }}');">
    </td>
    <td>
        <input type="text" name="main_activity[{{ $mainActivity->id }}][unit_rate]" value="{{ $mainActivity->unit_rate }}" id="unit_rate_main{{ $mainActivity->id }}" class="form-control unit_rate_main" disabled="disabled" onkeypress="javascript:return isNumber(event)" onchange="update_main_activity_row('{{ route('estimates.ajax.update.project.main.activity.row', $mainActivity->id) }}', 'unit_rate', this.value, '{{ $mainActivity->id }}');">
    </td>
    <td>
        <input type="text" name="main_activity[{{ $mainActivity->id }}][unit]" value="{{ $mainActivity->unit }}" id="unit_main{{ $mainActivity->id }}" class="form-control unit_main" onchange="update_main_activity_row('{{ route('estimates.ajax.update.project.main.activity.row', $mainActivity->id) }}', 'unit', this.value, '{{ $mainActivity->id }}');">
        
        <input type="hidden" name="main_activity[{{ $mainActivity->id }}][id]" value="{{ $mainActivity->id }}">
        <input type="hidden" name="main_activity[{{ $mainActivity->id }}][hr]" value="{{ $mainActivity->hr }}" id="hr_main{{ $mainActivity->id }}" class="form-control hr_main" onchange="update_main_activity_row('{{ route('estimates.ajax.update.project.main.activity.row', $mainActivity->id) }}', 'hr', this.value, '{{ $mainActivity->id }}');">
        <input type="hidden" name="main_activity[{{ $mainActivity->id }}][mhr]" value="{{ $mainActivity->mhr }}" id="mhr_main{{ $mainActivity->id }}" class="form-control mhr_main" onchange="update_main_activity_row('{{ route('estimates.ajax.update.project.main.activity.row', $mainActivity->id) }}', 'mhr', this.value, '{{ $mainActivity->id }}');">
        <input type="hidden" name="main_activity[{{ $mainActivity->id }}][total_hr]" value="{{ $mainActivity->total_hr }}" id="total_hr_main{{ $mainActivity->id }}" class="form-control total_hr_main" onchange="update_main_activity_row('{{ route('estimates.ajax.update.project.main.activity.row', $mainActivity->id) }}', 'total_hr', this.value, '{{ $mainActivity->id }}');">
        <input type="hidden" name="main_activity[{{ $mainActivity->id }}][total_mhr]" value="{{ $mainActivity->total_mhr }}" id="total_mhr_main{{ $mainActivity->id }}" class="form-control total_mhr_main" onchange="update_main_activity_row('{{ route('estimates.ajax.update.project.main.activity.row', $mainActivity->id) }}', 'total_mhr', this.value, '{{ $mainActivity->id }}');">
    </td>
    <td>
        
            <a title="Delete main activity" href="javascript:;" class="btn btn-sm btn-danger remove-main-activity-row" data-main-activity-id="{{ $mainActivity->id }}" data-route="{{ route('estimates.ajax.delete.main.activity.row', $mainActivity->id) }}">
                <i class="fas fa-trash"></i>
            </a>
        
    </td>
</tr>
<tr>
    <td colspan="12" class="expandable">
        <table class="table table-bordered sub-activity-table" id="sub_activity_table{{ $mainActivity->id }}">
            <thead class="table-info">
                <tr>
                    <th>View</th>
                    <th width="10%">Sub Code</th>
                    <th>Activity</th>
                    <th width="7%">Quantity</th>
                    <th>Rate</th>
                    <th>Total</th>
                    <th width="7%">Unit</th>
                    <th>
                       
                            <a title="Add sub activity" href="javascript:;" class="btn btn-sm btn-success add-sub-activity-row" data-main-activity-id="{{ $mainActivity->id }}" data-route="{{ route('estimates.ajax.add.sub.activity.row', $mainActivity->id) }}">
                                <i class="fas fa-plus"></i>
                            </a>
                       
                    </th>
                </tr>
            </thead>
            <tbody class="sub-activity-wrapper-{{ $mainActivity->id }}">
                
            </tbody>
        </table>
    </td>
</tr>
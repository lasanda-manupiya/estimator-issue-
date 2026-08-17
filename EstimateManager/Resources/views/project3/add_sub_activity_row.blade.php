<tr class="sub-activity-row-{{ $subActivity->id }} expandable" data-row-id="{{ $subActivity->id }}">
    <td><input type="button" class="btn btn-primary btn-sm expandable-input" value="+"></td>
    <td>
        <input type="text" name="sub_activity[{{ $subActivity->id }}][sub_code]" value="{{ $subActivity->sub_code }}" id="sub_code{{ $subActivity->id }}" class="form-control sub_code" disabled="disabled" onchange="update_sub_activity_row('{{ route('estimates.ajax.update.project.sub.activity.row', $subActivity->id) }}', 'sub_code', this.value, '{{ $subActivity->id }}', '{{ $subActivity->mainActivity->id }}');">
    </td>
    <td>
        <input type="text" name="sub_activity[{{ $subActivity->id }}][activity]" value="{{ $subActivity->activity }}" id="activity_sub{{ $subActivity->id }}" class="form-control activity_sub" onchange="update_sub_activity_row('{{ route('estimates.ajax.update.project.sub.activity.row', $subActivity->id) }}', 'activity', this.value, '{{ $subActivity->id }}', '{{ $subActivity->mainActivity->id }}');">
    </td>
    <td>
        <input type="text" name="sub_activity[{{ $subActivity->id }}][quantity]" value="{{ $subActivity->quantity }}" id="quantity_sub{{ $subActivity->id }}" class="form-control quantity_sub" onkeypress="javascript:return isNumber(event)" onchange="update_sub_activity_row('{{ route('estimates.ajax.update.project.sub.activity.row', $subActivity->id) }}', 'quantity', this.value, '{{ $subActivity->id }}', '{{ $subActivity->mainActivity->id }}');">
    </td>
    <td>
        <input type="text" name="sub_activity[{{ $subActivity->id }}][rate]" value="{{ $subActivity->rate }}" id="rate_sub{{ $subActivity->id }}" class="form-control rate_sub" onkeypress="javascript:return isNumber(event)" onchange="update_sub_activity_row('{{ route('estimates.ajax.update.project.sub.activity.row', $subActivity->id) }}', 'rate', this.value, '{{ $subActivity->id }}', '{{ $subActivity->mainActivity->id }}');">
    </td>
    <td>
        <input type="text" name="sub_activity[{{ $subActivity->id }}][total]" value="{{ $subActivity->total }}" id="total_sub{{ $subActivity->id }}" class="form-control total_sub" onkeypress="javascript:return isNumber(event)" disabled="disabled" onchange="update_sub_activity_row('{{ route('estimates.ajax.update.project.sub.activity.row', $subActivity->id) }}', 'total', this.value, '{{ $subActivity->id }}', '{{ $subActivity->mainActivity->id }}');">
    </td>
    <td>
        <input type="text" name="sub_activity[{{ $subActivity->id }}][unit]" value="{{ $subActivity->unit }}" id="unit_sub{{ $subActivity->id }}" class="form-control unit_sub" onchange="update_sub_activity_row('{{ route('estimates.ajax.update.project.sub.activity.row', $subActivity->id) }}', 'unit', this.value, '{{ $subActivity->id }}', '{{ $subActivity->mainActivity->id }}');">
        
        <input type="hidden" name="sub_activity[{{ $subActivity->id }}][id]" value="{{ $subActivity->id }}">
        <input type="hidden" name="sub_activity[{{ $subActivity->id }}][hr]" value="{{ $subActivity->hr }}" id="hr_sub{{ $subActivity->id }}" class="form-control hr_sub" onchange="update_sub_activity_row('{{ route('estimates.ajax.update.project.sub.activity.row', $subActivity->id) }}', 'hr', this.value, '{{ $subActivity->id }}', '{{ $subActivity->mainActivity->id }}');">
        <input type="hidden" name="sub_activity[{{ $subActivity->id }}][mhr]" value="{{ $subActivity->mhr }}" id="mhr_sub{{ $subActivity->id }}" class="form-control mhr_sub" onchange="update_sub_activity_row('{{ route('estimates.ajax.update.project.sub.activity.row', $subActivity->id) }}', 'mhr', this.value, '{{ $subActivity->id }}', '{{ $subActivity->mainActivity->id }}');">
        <input type="hidden" name="sub_activity[{{ $subActivity->id }}][total_hr]" value="{{ $subActivity->total_hr }}" id="total_hr_sub{{ $subActivity->id }}" class="form-control total_hr_sub" onchange="update_sub_activity_row('{{ route('estimates.ajax.update.project.sub.activity.row', $subActivity->id) }}', 'total_hr', this.value, '{{ $subActivity->id }}', '{{ $subActivity->mainActivity->id }}');">
        <input type="hidden" name="sub_activity[{{ $subActivity->id }}][total_mhr]" value="{{ $subActivity->total_mhr }}" id="total_mhr_sub{{ $subActivity->id }}" class="form-control total_mhr_sub" onchange="update_sub_activity_row('{{ route('estimates.ajax.update.project.sub.activity.row', $subActivity->id) }}', 'total_mhr', this.value, '{{ $subActivity->id }}', '{{ $subActivity->mainActivity->id }}');">
    </td>
    <td>
        
            <a title="Delete sub activity" href="javascript:;" class="btn btn-sm btn-danger remove-sub-activity-row" data-sub-activity-id="{{ $subActivity->id }}" data-route="{{ route('estimates.ajax.delete.sub.activity.row', $subActivity->id) }}">
                <i class="fas fa-trash"></i>
            </a>
      
    </td>
</tr>
<tr>
    <td colspan="8" class="expandable">
        <table class="table table-bordered activity-table" id="activity-table{{ $subActivity->id }}">
            <thead class="table-warning">
                <th width="10%">Item Code</th>
                <th>Activity</th>
                <th width="7%">Level/Unit</th>
                <th width="7%">Co<sub>2</sub></th>
                <th width="7%">Quantity</th>
                <th>Cost Rate</th>
                <th>Selling Rate</th>
                <th>Profit</th>
                <th>Total</th>
                <th>Total Co<sub>2</sub></th>
                <th>
                    <a href="javascript:;" class="btn btn-sm btn-success add-activity-row" data-sub-activity-id="{{ $subActivity->id }}" data-route="{{ route('estimates.ajax.add.activity.row', $subActivity->id) }}">
                        <i class="fas fa-plus"></i>
                    </a>
                </th>
            </thead>
            <tbody class="activity-wrapper-{{ $subActivity->id }}">
                
            </tbody>
        </table>
    </td>
</tr>
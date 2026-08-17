<tr class="activity-row-{{ $activity->id }}" data-row-id="{{ $activity->id }}">
    <td>
        <input type="text" name="activity[{{ $activity->id }}][item_code]"  value="{{ $activity->item_code }}" id="item_code{{ $activity->id }}" class="form-control activity-item_code item_code" disabled="disabled" onchange="update_activity_row('{{ route('carbon.ajax.update.project.activity.row', $activity->id) }}', 'item_code', this.value, '{{ $activity->id }}', '{{ $activity->library_sub_activity_id }}', '{{ $activity->library_main_activity_id }}');">
    </td>
    <td>
        <input type="text" name="activity[{{ $activity->id }}][activity]" value="{{ $activity->activity }}" id="activity{{ $activity->id }}" class="form-control activity-activity activity" onchange="update_activity_row('{{ route('carbon.ajax.update.project.activity.row', $activity->id) }}', 'activity', this.value, '{{ $activity->id }}', '{{ $activity->library_sub_activity_id }}', '{{ $activity->library_main_activity_id }}');">
    </td>
    <td>
        <input type="text" name="activity[{{ $activity->id }}][unit]" value="{{ $activity->unit }}" id="unit{{ $activity->id }}" class="form-control activity-unit unit" onchange="update_activity_row('{{ route('carbon.ajax.update.project.activity.row', $activity->id) }}', 'unit', this.value, '{{ $activity->id }}', '{{ $activity->library_sub_activity_id }}', '{{ $activity->library_main_activity_id }}');">
    </td>
	<td>
        <input type="text" name="activity[{{ $activity->id }}][foot_print_value]" value="{{ $activity->foot_print_value }}" id="foot_print_value{{ $activity->id }}" class="form-control activity-foot_print_value foot_print_value" onchange="update_activity_row('{{ route('carbon.ajax.update.project.activity.row', $activity->id) }}', 'foot_print_value', this.value, '{{ $activity->id }}', '{{ $activity->library_sub_activity_id }}', '{{ $activity->library_main_activity_id }}');">
    </td>
    <td>
        <input type="text" name="activity[{{ $activity->id }}][quantity]" value="{{ $activity->quantity }}" id="quantity{{ $activity->id }}" class="form-control activity-quantity quantity" onkeypress="javascript:return isNumber(event)" onchange="update_activity_row('{{ route('carbon.ajax.update.project.activity.row', $activity->id) }}', 'quantity', this.value, '{{ $activity->id }}', '{{ $activity->library_sub_activity_id }}', '{{ $activity->library_main_activity_id }}');">
    </td>
    <td>
        <input type="text" name="activity[{{ $activity->id }}][rate]" value="{{ $activity->rate }}" id="rate{{ $activity->id }}" class="form-control activity-rate rate" onkeypress="javascript:return isNumber(event)" onchange="update_activity_row('{{ route('carbon.ajax.update.project.activity.row', $activity->id) }}', 'rate', this.value, '{{ $activity->id }}', '{{ $activity->library_sub_activity_id }}', '{{ $activity->library_main_activity_id }}');">
    </td>
    <td>
        <input type="text" name="activity[{{ $activity->id }}][selling_cost]" value="{{ $activity->selling_cost }}" id="selling_cost{{ $activity->id }}" class="form-control activity-selling_cost selling_cost" onkeypress="javascript:return isNumber(event)" disabled="disabled" onchange="update_activity_row('{{ route('carbon.ajax.update.project.activity.row', $activity->id) }}', 'selling_cost', this.value, '{{ $activity->id }}', '{{ $activity->library_sub_activity_id }}', '{{ $activity->library_main_activity_id }}');">
    </td>
    <td>
        <input type="text" name="activity[{{ $activity->id }}][profit]" value="{{ $activity->profit }}" id="profit{{ $activity->id }}" class="form-control activity-profit profit" disabled="disabled" onkeypress="javascript:return isNumber(event)" onchange="update_activity_row('{{ route('carbon.ajax.update.project.activity.row', $activity->id) }}', 'profit', this.value, '{{ $activity->id }}', '{{ $activity->library_sub_activity_id }}', '{{ $activity->library_main_activity_id }}');">
    </td>
    <td>
        <input type="text" name="activity[{{ $activity->id }}][total]" value="{{ $activity->total }}" id="total{{ $activity->id }}" class="form-control activity-total total" disabled="disabled" onkeypress="javascript:return isNumber(event)" onchange="update_activity_row('{{ route('carbon.ajax.update.project.activity.row', $activity->id) }}', 'total', this.value, '{{ $activity->id }}', '{{ $activity->library_sub_activity_id }}', '{{ $activity->library_main_activity_id }}');">
        <input type="hidden" name="activity[{{ $activity->id }}][id]" value="{{ $activity->id }}">
    </td>
	<td>
        <input type="text" name="activity[{{ $activity->id }}][totalco]" value="{{ $activity->totalco }}" id="totalco{{ $activity->id }}" class="form-control activity-totalco totalco" disabled="disabled" onkeypress="javascript:return isNumber(event)" onchange="update_activity_row('{{ route('carbon.ajax.update.project.activity.row', $activity->id) }}', 'totalco', this.value, '{{ $activity->id }}', '{{ $activity->library_sub_activity_id }}', '{{ $activity->library_main_activity_id }}');">
    </td>
	
    <td>
        @if(auth()->user()->can('access', 'carbontoolkit add'))
            <a title="Delete activity" href="javascript:;" class="btn btn-sm btn-danger remove-activity-row" data-activity-id="{{ $activity->id }}" data-route="{{ route('carbon.ajax.delete.activity.row', $activity->id) }}">
                <i class="fas fa-trash"></i>
            </a>
        @endif
    </td>
</tr>
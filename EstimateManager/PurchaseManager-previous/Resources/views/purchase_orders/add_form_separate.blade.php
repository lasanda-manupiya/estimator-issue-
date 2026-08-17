<table class="table table-bordered table-active">
    <tr>
        <td>
            <div class="form-group @if($errors->has('delivery_date')) has-error @endif">
                <label for="delivery_date">Delivery Date</label><span class="asterisk">*</span>
                <input value="{{ old('delivery_date') ?? date('Y-m-d') }}" type="date" name="delivery_date" class="form-control delivery_date" id="delivery_date" />
                @if($errors->has('delivery_date'))
                    <span class="invalid-feedback">{{ $errors->first('delivery_date') }}</span>
                @endif
            </div>
        </td>
        <td>
            <div class="form-group @if($errors->has('delivery_time')) has-error @endif">
                <label for="delivery_time">Delivery Time</label><span class="asterisk">*</span>
                <input value="{{ old('delivery_time') ?? date('H:i:s') }}" type="time" name="delivery_time" class="form-control delivery_time" id="delivery_time" />
                @if($errors->has('delivery_time'))
                    <span class="invalid-feedback">{{ $errors->first('delivery_time') }}</span>
                @endif
            </div>
        </td>
        <td>
            <div class="form-group @if($errors->has('delivery_address')) has-error @endif">
                <label for="delivery_address">Delivery Address</label><span class="asterisk">*</span>
                <input value="{{ old('delivery_address') ?? $project->project_address }}" type="text" name="delivery_address" class="form-control delivery_address" id="delivery_address" />
                @if($errors->has('delivery_address'))
                    <span class="invalid-feedback">{{ $errors->first('delivery_address') }}</span>
                @endif
            </div>
        </td>
    </tr>
</table>
<table class="table table-bordered">
    <thead>
        <tr class="table-success">
            <th width="10%">Activity Code</th>
            <th width="15%">Activity</th>
            <th width="10%">Image</th>
            <th width="10%">Unit</th>
            <th width="10%">Quantity</th>
            <th width="10%">Rate</th>
            <th width="10%">Total</th>
            <th width="15%">Supplier</th>
            <th width="5%">Expand</th>
            <th width="5%">
                <input type="checkbox" class="select_all" id="select_all" checked>
            </th>
        </tr>
    </thead>
    @if($activities->isNotEmpty())
        @foreach($activities as $activity)
            <tr>
                <input type="hidden" name="activities[{{ $activity->id }}][activity_id]" value="{{ $activity->id }}">
                <td class="tr{{ $activity->id }}">
                    <input type="hidden" name="activities[{{ $activity->id }}][item_code]" value="{{ $activity->item_code }}">
                    {{ $activity->item_code }}
                </td>
                <td class="tr{{ $activity->id }}">
                    <div class="form-group @if($errors->has('activities.*.activity')) has-error @endif">
                        <input type="text" name="activities[{{ $activity->id }}][activity]" value="{{ $activity->activity }}" data-id="{{ $activity->id }}" id="activity{{ $activity->id }}"  class="form-control activity">
                        @if($errors->has('activities.*.activity'))
                            <span class="invalid-feedback">{{ $errors->first('activities.*.activity') }}</span>
                        @endif
                    </div>
                </td>
                <td class="tr{{ $activity->id }}">
                    <div id="image-preview{{ $activity->id }}" class="image-previews">
                        <label  id="b1up{{ $activity->id }}"  style="text-align:center;">
                            Choose Image
                        </label>
                    </div>
                    <input type="file" name="activities[{{ $activity->id }}][file]" data-id="{{ $activity->id }}" id="file{{ $activity->id }}"  class="form-control file" style="display:none">
                </td>
                <td class="tr{{ $activity->id }}">
                    <div class="form-group @if($errors->has('activities.*.unit')) has-error @endif">
                        <input  type="text" name="activities[{{ $activity->id }}][unit]" value="{{ $activity->unit }}" data-id="{{ $activity->id }}" id="unit{{ $activity->id }}"  class="form-control unit">
                        @if($errors->has('activities.*.unit'))
                            <span class="invalid-feedback">{{ $errors->first('activities.*.unit') }}</span>
                        @endif
                    </div>
                </td>
                <td class="tr{{ $activity->id }}">
                    <div class="form-group @if($errors->has('activities.*.quantity')) has-error @endif">
                        <input  type="text" name="activities[{{ $activity->id }}][quantity]" value="{{ $activity->quantity }}" data-id="{{ $activity->id }}" id="quantity{{ $activity->id }}"  class="form-control quantity" onkeypress="javascript:return isNumber(event)">
                        @if($errors->has('activities.*.quantity'))
                            <span class="invalid-feedback">{{ $errors->first('activities.*.quantity') }}</span>
                        @endif
                    </div>
                </td> 
                <td class="tr{{ $activity->id }}">
                    <div class="form-group @if($errors->has('activities.*.rate')) has-error @endif">
                        <input type="text"  name="activities[{{ $activity->id }}][rate]" value="{{ round($activity->rate,2) }}" data-id="{{ $activity->id }}" id="rate{{ $activity->id }}" class="form-control rate" onkeypress="javascript:return isNumber(event)">
                        @if($errors->has('activities.*.rate'))
                            <span class="invalid-feedback">{{ $errors->first('activities.*.rate') }}</span>
                        @endif
                    </div>
                </td>
                <td class="tr{{ $activity->id }}">
                    <div class="form-group @if($errors->has('activities.*.total')) has-error @endif">
                        <input type="text"  name="activities[{{ $activity->id }}][total]" value="{{ round($activity->quantity*$activity->rate,2) }}" data-id="{{ $activity->id }}" id="total{{ $activity->id }}" class="form-control total" readonly>
                        @if($errors->has('activities.*.total'))
                            <span class="invalid-feedback">{{ $errors->first('activities.*.total') }}</span>
                        @endif
                    </div>
                </td>
                <td class="tr{{ $activity->id }}">
                    <div class="form-group @if($errors->has('activities.*.supplier_id')) has-error @endif">
                        <select name="activities[{{ $activity->id }}][supplier_id]" data-id="{{ $activity->id }}" id="supplier_id{{ $activity->id }}" class="form-control multiselect-dropdown supplier_id">
                            @foreach($suppliers as $sk=>$supplier)
                                <option value="{{ $sk }}">{{ $supplier }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('activities.*.supplier_id'))
                            <span class="invalid-feedback">{{ $errors->first('activities.*.supplier_id') }}</span>
                        @endif
                    </div>
                </td>
                <td class="tr{{ $activity->id }}">
                    <a href="javascript:;" id="expand{{ $activity->id }}" onclick="expand({{ $activity->id }});">More</a>
                </td>
                <td class="tr{{ $activity->id }}">
                    <input name="selected_rows[]" type="checkbox" class="checkbox selected_rows" data-id="{{ $activity->id }}" id="selected_rows{{ $activity->id }}" value="{{ $activity->id }}" checked>
                </td>
                <td class="tr{{ $activity->id }} expandable_row{{ $activity->id }}" style="display: none;">
                    <tr class="expandable_row{{ $activity->id }} text-right" style="display: none;">
                        <td colspan="7" class="tr{{ $activity->id }}">Carriage costs</td>
                        <td colspan="3" class="tr{{ $activity->id }}">
                            <div class="form-group @if($errors->has('carriage_costs')) has-error @endif">
                                <input type="text"  name="activities[{{ $activity->id }}][carriage_costs]" data-id="{{ $activity->id }}"  id="carriage_costs{{ $activity->id }}" class="form-control carriage_costs" value="0" onkeypress="javascript:return isNumber(event)">
                                @if($errors->has('carriage_costs'))
                                    <span class="invalid-feedback">{{ $errors->first('carriage_costs') }}</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    <tr class="expandable_row{{ $activity->id }} text-right" style="display: none;">
                        <td colspan="7" class="tr{{ $activity->id }}">C of C</td>
                        <td colspan="3" class="tr{{ $activity->id }}">
                            <div class="form-group @if($errors->has('c_of_c')) has-error @endif">
                                <input type="text"  name="activities[{{ $activity->id }}][c_of_c]" data-id="{{ $activity->id }}"  id="c_of_c{{ $activity->id }}" class="form-control c_of_c" value="0" onkeypress="javascript:return isNumber(event)">
                                @if($errors->has('c_of_c'))
                                    <span class="invalid-feedback">{{ $errors->first('c_of_c') }}</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    <tr class="expandable_row{{ $activity->id }} text-right" style="display: none;">
                        <td colspan="7" class="tr{{ $activity->id }}">Other costs</td>
                        <td colspan="3" class="tr{{ $activity->id }}">
                            <div class="form-group @if($errors->has('other_costs')) has-error @endif">
                                <input type="text"  name="activities[{{ $activity->id }}][other_costs]" data-id="{{ $activity->id }}"  id="other_costs{{ $activity->id }}" class="form-control other_costs" value="0" onkeypress="javascript:return isNumber(event)">
                                @if($errors->has('other_costs'))
                                    <span class="invalid-feedback">{{ $errors->first('other_costs') }}</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    <tr class="expandable_row{{ $activity->id }} text-right" style="display: none;">
                        <td colspan="7" class="tr{{ $activity->id }}">Total Value</td>
                        <td colspan="3" class="tr{{ $activity->id }}">
                            <div class="form-group @if($errors->has('grand_total')) has-error @endif">
                                <input type="text"  name="activities[{{ $activity->id }}][grand_total]" data-id="{{ $activity->id }}"  id="grand_total{{ $activity->id }}" class="form-control grand_total" value="{{ $activity->quantity*$activity->rate }}" readonly>
                                @if($errors->has('grand_total'))
                                    <span class="invalid-feedback">{{ $errors->first('grand_total') }}</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    <tr class="expandable_row{{ $activity->id }}" style="display: none;">
                        <td colspan="10" class="tr{{ $activity->id }}">
                            <div class="form-group @if($errors->has('notes')) has-error @endif">
                                <label>Notes</label>
                                <textarea class="form-control" rows="2" name="activities[{{ $activity->id }}][notes]" data-id="{{ $activity->id }}" id="notes{{ $activity->id }}"></textarea>
                                @if($errors->has('notes'))
                                    <span class="invalid-feedback">{{ $errors->first('notes') }}</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                </td> 
            </tr>
        @endforeach
    @endif
</table>
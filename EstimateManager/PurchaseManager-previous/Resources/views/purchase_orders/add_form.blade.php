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
            <th width="20%">Activity</th>
            <th width="15%">Image</th>
            <th width="10%">Unit</th>
            <th width="10%">Quantity</th>
            <th width="15%">Rate</th>
            <th width="15%">Total</th>
            <th width="5%">
                <input type="checkbox" class="select_all" id="select_all" checked>
            </th>
        </tr>
    </thead>
    @php 
        $grand_total = 0;
    @endphp
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
                    <div class="form-group">
                        <input name="selected_rows[]" type="checkbox" class="checkbox selected_rows" data-id="{{ $activity->id }}" id="selected_rows{{ $activity->id }}" value="{{ $activity->id }}" checked>
                    </div>
                </td>  
            </tr>
            @php
                $grand_total = $grand_total + ($activity->quantity*$activity->rate);
            @endphp
        @endforeach
    @endif
    <tr>
        <td colspan="5">&nbsp;</td>
        <td>Carriage costs</td>
        <td colspan="2">
            <div class="form-group @if($errors->has('carriage_costs')) has-error @endif">
                <input type="text"  name="carriage_costs"  id="carriage_costs" class="form-control carriage_costs" value="0" onkeypress="javascript:return isNumber(event)">
                @if($errors->has('carriage_costs'))
                    <span class="invalid-feedback">{{ $errors->first('carriage_costs') }}</span>
                @endif
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="5">&nbsp;</td>
        <td>C of C</td>
        <td colspan="2">
            <div class="form-group @if($errors->has('c_of_c')) has-error @endif">
                <input type="text"  name="c_of_c"  id="c_of_c" class="form-control c_of_c" value="0" onkeypress="javascript:return isNumber(event)">
                @if($errors->has('c_of_c'))
                    <span class="invalid-feedback">{{ $errors->first('c_of_c') }}</span>
                @endif
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="5">&nbsp;</td>
        <td>Other costs</td>
        <td colspan="2">
            <div class="form-group @if($errors->has('other_costs')) has-error @endif">
                <input type="text"  name="other_costs"  id="other_costs" class="form-control other_costs" value="0" onkeypress="javascript:return isNumber(event)">
                @if($errors->has('other_costs'))
                    <span class="invalid-feedback">{{ $errors->first('other_costs') }}</span>
                @endif
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="5">&nbsp;</td>
        <td>Total Value</td>
        <td colspan="2">
            <div class="form-group @if($errors->has('grand_total')) has-error @endif">
                <input type="text"  name="grand_total"  id="grand_total" class="form-control grand_total" value="{{ $grand_total }}" readonly>
                @if($errors->has('grand_total'))
                    <span class="invalid-feedback">{{ $errors->first('grand_total') }}</span>
                @endif
            </div>
        </td>
    </tr>
</table>
<table class="table table-secondary">
    <tr>
        <td>
            <div class="form-group">
                <label for="notes">Notes</label>
                <textarea class="form-control" rows="2" name="notes" id="notes"></textarea>
            </div>
        </td>
    </tr>
</table>
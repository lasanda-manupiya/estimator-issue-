<div class="row">
    <div class="col-md-4">
        <div class="form-group @if($errors->has('delivery_date')) has-error @endif">
            <label for="delivery_date">Delivery Date</label><span class="asterisk">*</span>
            <input value="{{ old('delivery_date') ?? date('Y-m-d') }}" type="date" name="delivery_date" class="form-control delivery_date" id="delivery_date" />
            @if($errors->has('delivery_date'))
                <span class="invalid-feedback">{{ $errors->first('delivery_date') }}</span>
            @endif
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group @if($errors->has('delivery_time')) has-error @endif">
            <label for="delivery_time">Delivery Time</label><span class="asterisk">*</span>
            <input value="{{ old('delivery_time') ?? date('H:i:s') }}" type="time" name="delivery_time" class="form-control delivery_time" id="delivery_time" />
            @if($errors->has('delivery_time'))
                <span class="invalid-feedback">{{ $errors->first('delivery_time') }}</span>
            @endif
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group @if($errors->has('delivery_address')) has-error @endif">
            <label for="delivery_address">Delivery Address</label><span class="asterisk">*</span>
            <input value="{{ old('delivery_address') ?? $project->project_address }}" type="text" name="delivery_address" class="form-control delivery_address" id="delivery_address" />
            @if($errors->has('delivery_address'))
                <span class="invalid-feedback">{{ $errors->first('delivery_address') }}</span>
            @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered">
            <thead>
                <tr class="table-success">
                    <th width="20%">Activity Code</th>
                    <th width="25%">Activity</th>
                    <th width="15%">Upload Files</th>
                    <th width="15%">Unit</th>
                    <th width="15%">Quantity</th>
                    <th width="10%">
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
                                    <span style="margin: 2px;"> Choose Files</span>
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
                            <input type="hidden"  name="activities[{{ $activity->id }}][rate]" value="{{ $activity->rate }}" data-id="{{ $activity->id }}" id="rate{{ $activity->id }}" class="rate">
                            <input type="hidden"  name="activities[{{ $activity->id }}][total]" value="0" data-id="{{ $activity->id }}" id="total{{ $activity->id }}" class="total">
                        </td> 
                        <td class="tr{{ $activity->id }}">
                            <input name="selected_rows[]" type="checkbox" class="checkbox selected_rows" data-id="{{ $activity->id }}" id="selected_rows{{ $activity->id }}" value="{{ $activity->id }}" checked>
                        </td>  
                    </tr>
                @endforeach
            @endif
        </table>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label for="notes">Notes</label>
            <textarea class="form-control" rows="2" name="notes" id="notes"></textarea>
        </div>
    </div>
</div>
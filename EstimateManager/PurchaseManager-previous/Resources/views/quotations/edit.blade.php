@extends('user::layouts.master')
@push('styles')
    <style>
        .image-previews {
            width: 80px;
            height: 60px;
            background-position: center center;
            background-size: cover;
            -webkit-box-shadow: 0 0 1px 1px rgba(0, 0, 0, .3);
            display: inline-block;
        }
    </style>
@endpush
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
     
      <div class="col-sm-12">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{ route('quotations.index') }}">Quotation</a></li>
          <li class="breadcrumb-item active">Edit Quotation</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content printable_div">
     <div class="card-body">

<button onclick="window.location.href='{{ route('quotations.index') }}'"  type="button" class="mb-2 mr-2 btn-icon-vertical btn btn-info">
<i class="pe-7s-back btn-icon-wrapper"></i>Back
</button>

     </div>
    <div class="card">
        {{ Form::open(['route' => ['quotations.update', $quotation->id], 'method' => 'patch', 'enctype'=> 'multipart/form-data']) }}
        <div class="card-header">
            <h3 class="card-title">Edit Quotation</h3>
           
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group @if($errors->has('delivery_date')) has-error @endif">
                        <label for="delivery_date">Delivery Date</label><span class="asterisk">*</span>
                        <input type="date" name="delivery_date" class="form-control delivery_date" id="delivery_date" value="{{ $quotation->delivery_date }}" />
                        @if($errors->has('delivery_date'))
                            <span class="invalid-feedback">{{ $errors->first('delivery_date') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group @if($errors->has('delivery_time')) has-error @endif">
                        <label for="delivery_time">Delivery Time</label><span class="asterisk">*</span>
                        <input type="time" name="delivery_time" class="form-control delivery_time" id="delivery_time" value="{{ $quotation->delivery_time }}" />
                        @if($errors->has('delivery_time'))
                            <span class="invalid-feedback">{{ $errors->first('delivery_time') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group @if($errors->has('delivery_address')) has-error @endif">
                        <label for="delivery_address">Delivery Address</label><span class="asterisk">*</span>
                        <input type="text" name="delivery_address" class="form-control delivery_address" id="delivery_address" value="{{ $quotation->delivery_address }}" />
                        @if($errors->has('delivery_address'))
                            <span class="invalid-feedback">{{ $errors->first('delivery_address') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered text-center">
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
                        @if($quotation->materials->isNotEmpty())
                            @foreach($quotation->materials as $material)
                                <tr>
                                    <input type="hidden" name="activities[{{ $material->id }}][material_id]" value="{{ $material->id }}">
                                    <td class="tr{{ $material->id }}">
                                        {{ $material->activityOfMaterial->item_code }}
                                    </td>
                                    <td class="tr{{ $material->id }}">
                                        <div class="form-group @if($errors->has('activities.*.activity')) has-error @endif">
                                            <input type="text" name="activities[{{ $material->id }}][activity]" value="{{ $material->activity }}" data-id="{{ $material->id }}" id="activity{{ $material->id }}"  class="form-control activity">
                                            @if($errors->has('activities.*.activity'))
                                                <span class="invalid-feedback">{{ $errors->first('activities.*.activity') }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="tr{{ $material->id }}">
                                        <div id="image-preview{{ $material->id }}" class="image-previews">
                                            <label  id="b1up{{ $material->id }}"  style="text-align:center;">
                                                @if(\Storage::disk('public')->has('quotations/'.$material->photo))
                                                    <img src="../../../storage/app/public/quotations/{{ $material->photo }}" alt="" height="50px" width="50px">
                                                @else
                                                <span style="margin: 2px;"> Choose Files</span>
                                                @endif
                                            </label>
                                        </div>
                                        <input type="file" name="activities[{{ $material->id }}][file]" data-id="{{ $material->id }}" id="file{{ $material->id }}"  class="form-control file" style="display:none">
                                    </td>
                                    <td class="tr{{ $material->id }}">
                                        <div class="form-group @if($errors->has('activities.*.unit')) has-error @endif">
                                            <input  type="text" name="activities[{{ $material->id }}][unit]" value="{{ $material->unit }}" data-id="{{ $material->id }}" id="unit{{ $material->id }}"  class="form-control unit">
                                            @if($errors->has('activities.*.unit'))
                                                <span class="invalid-feedback">{{ $errors->first('activities.*.unit') }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="tr{{ $material->id }}">
                                        <div class="form-group @if($errors->has('activities.*.quantity')) has-error @endif">
                                            <input  type="text" name="activities[{{ $material->id }}][quantity]" value="{{ $material->quantity }}" data-id="{{ $material->id }}" id="quantity{{ $material->id }}"  class="form-control quantity" onkeypress="javascript:return isNumber(event)">
                                            @if($errors->has('activities.*.quantity'))
                                                <span class="invalid-feedback">{{ $errors->first('activities.*.quantity') }}</span>
                                            @endif
                                        </div>
                                        <input type="hidden"  name="activities[{{ $material->id }}][rate]" value="0" data-id="{{ $material->id }}" id="rate{{ $material->id }}" class="rate">
                                        <input type="hidden"  name="activities[{{ $material->id }}][total]" value="0" data-id="{{ $material->id }}" id="total{{ $material->id }}" class="total">
                                    </td> 
                                    <td class="tr{{ $material->id }}">
                                        <input name="selected_rows[]" type="checkbox" class="checkbox selected_rows" data-id="{{ $material->id }}" id="selected_rows{{ $material->id }}" value="{{ $material->id }}" checked>
                                    </td>  
                                </tr>
                            @endforeach
                        @endif
                        @if($unselectedActivities->isNotEmpty())
                            @foreach($unselectedActivities as $activity)
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
                                        <input type="hidden"  name="activities[{{ $activity->id }}][rate]" value="0" data-id="{{ $activity->id }}" id="rate{{ $activity->id }}" class="rate">
                                        <input type="hidden"  name="activities[{{ $activity->id }}][total]" value="0" data-id="{{ $activity->id }}" id="total{{ $activity->id }}" class="total">
                                    </td> 
                                    <td class="tr{{ $activity->id }}">
                                        <input name="selected_rows[]" type="checkbox" class="checkbox selected_rows" data-id="{{ $activity->id }}" id="selected_rows{{ $activity->id }}" value="{{ $activity->id }}">
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
                        <textarea class="form-control" rows="2" name="notes" id="notes">{{ $quotation->notes }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            {{ Form::submit('Update quotation', [ 'class' => "btn btn-primary btn-flat" ]) }}
        </div>
        {{ Form::close() }}
    </div>
</section>
@stop
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function(){

            $(document).on('click', '.select_all', function(){
                if (this.checked) {
                    $('.checkbox').each(function() {
                        this.checked = true;
                        $(".tr" + this.value).css("opacity", "1");
                    });
                } else {
                    $('.checkbox').each(function() {
                        this.checked = false;
                        $(".tr" + this.value).css("opacity", "0.2");
                    });
                }
            });
            $(document).on('click', '.selected_rows', function(){
                if (this.checked == true) {
                    $(".tr" + this.value).css("opacity", "1");
                } else {
                    $(".tr" + this.value).css("opacity", "0.2");
                }
                if ($('.checkbox:checked').length == $('.checkbox').length) {
                    $('#select_all').prop('checked', true);
                } else {
                    $('#select_all').prop('checked', false);
                }
            });

            $(document).on( 'click', '.image-previews', function()  {  
                var tr = $(this).parent().parent();  
                var index = tr.find('.file').attr('data-id');
                $('#file'+index).click();
            }); 

            $(document).on( 'change', '.file', function() {  
                var tr = $(this).parent().parent();  
                var index = tr.find('.file').attr('data-id');
                var uploadFile = $('#file'+index).files; 
                var files = !!this.files ? this.files : [];
                if (!files.length || !window.FileReader) return;
        
                if (/^image/.test( files[0].type)){ 
                    var reader = new FileReader();
                    reader.readAsDataURL(files[0]);
                    reader.onloadend = function(){
                        $("#image-preview"+index).css("background-image", "url("+this.result+")");
                        $('#b1up'+index).hide();
                    }
                }else{
                    $('#b1up'+index).show();
                    //alert('Please choose only image.');
                }
            }); 
            
            $(document).on('keyup', '.quantity, .rate, .total, .selected_rows', function(){  
                var index = $(this).attr('data-id');
                var quantity = $('#quantity'+index).val(); 
                var rate = $('#rate'+index).val();
                var total = $('#total'+index).val();
                total = quantity * rate;  
                $('#total'+index).val(total);
            });  
        });
        function isNumber(evt) {
            var iKeyCode = (evt.which) ? evt.which : evt.keyCode
            if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
                return false;

            return true;
        }
    </script>
@endpush
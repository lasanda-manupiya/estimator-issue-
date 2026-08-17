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
      <div class="col-sm-6">
        <h1>Manage Quotation</h1>
      </div>
      <div class="col-sm-6">
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
<section class="content">
    @include('layouts.flash.alert')
    <!-- Default box -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Quotation</h3>
            <div class="card-tools">
                <div class="box-tools pull-right">
                  <a href="{{ route('quotations.index') }}" class="btn btn-default btn-sm">Back</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-active">
                <tr>
                    <th>Delivery Date:</th>
                    <td>{{ $quotation->delivery_date }}</td>
                    <th>Delivery Time:</th>
                    <td>{{ $quotation->delivery_time }}</td>
                    <th>Delivery Address:</th>
                    <td>{{ $quotation->delivery_address }}</td>
                </tr>
            </table>
            <table class="table table-bordered text-center">
                <thead>
                    <tr class="table-success">
                        <th width="20%">Activity Code</th>
                        <th width="30%">Activity</th>
                        <th width="10%">Image</th>
                        <th width="15%">Unit</th>
                        <th width="15%">Quantity</th>
                    </tr>
                </thead>
                @if($quotation->materials->isNotEmpty())
                    @foreach($quotation->materials as $material)
                        <tr>
                            <td>
                                {{ $material->activityOfMaterial->item_code }}
                            </td>
                            <td>
                                {{ $material->activity }}
                            </td>
                            <td>
                                @if(\Storage::disk('public')->has('quotations/'.$material->photo))
                                  <img src="{{ asset('storage/quotations/'.$material->photo) }}" alt="" height="50px" width="50px">
                                @else
                                  <img src="{{ asset('images/no-img-100x92.jpg') }}" alt="" height="50px" width="50px">
                                @endif	
                            </td>
                            <td>
                                {{ $material->unit }}
                            </td>
                            <td>
                                {{ $material->quantity }}
                            </td>  
                        </tr>
                    @endforeach
                @endif
            </table>
            <table class="table table-bordered">
                <tr>
                    <th width="15%">Note:</th>
                    <td>{{ $quotation->notes }}</td>
                </tr>
            </table>
        </div>
    </div>
    {{ Form::open(['route' => ['quotations.supplier.quotation.reply', $quotation->id], 'method' => 'post', 'enctype'=> 'multipart/form-data']) }}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Quotation Reply</h3>
            <div class="card-tools">
                <a class="btn btn-default btn-sm" href="{{ route('quotations.index') }}" title="Back">Back</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered text-center">
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
                        
                        @if($materials->isNotEmpty())
                            @foreach($materials as $material)
                                <input type="hidden" name="materials[{{ $material->id }}][activity_id]" value="{{ $material->activityOfMaterial->id }}">
                                <tr>
                                    <td class="tr{{ $material->id }}">
                                        {{ $material->activityOfMaterial->item_code }}
                                    </td>
                                    <td class="tr{{ $material->id }}">
                                        <div class="form-group @if($errors->has('materials.*.activity')) has-error @endif">
                                            <input type="text" name="materials[{{ $material->id }}][activity]" value="{{ $material->activity }}" data-id="{{ $material->id }}" id="activity{{ $material->id }}"  class="form-control activity">
                                            @if($errors->has('materials.*.activity'))
                                                <span class="invalid-feedback">{{ $errors->first('materials.*.activity') }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="tr{{ $material->id }}">
                                        <div id="image-preview{{ $material->id }}" class="image-previews">
                                            <label  id="b1up{{ $material->id }}"  style="text-align:center;">
                                                @if(\Storage::disk('public')->has('quotations/'.$material->photo))
                                                    <img src="{{ asset('storage/quotations/'.$material->photo) }}" alt="" height="50px" width="50px">
                                                @else
                                                    Choose Image
                                                @endif
                                            </label>
                                        </div>
                                        <input type="file" name="materials[{{ $material->id }}][file]" data-id="{{ $material->id }}" id="file{{ $material->id }}"  class="form-control file" style="display:none">
                                    </td>
                                    <td class="tr{{ $material->id }}">
                                        <div class="form-group @if($errors->has('materials.*.unit')) has-error @endif">
                                            <input  type="text" name="materials[{{ $material->id }}][unit]" value="{{ $material->unit }}" data-id="{{ $material->id }}" id="unit{{ $material->id }}"  class="form-control unit">
                                            @if($errors->has('materials.*.unit'))
                                                <span class="invalid-feedback">{{ $errors->first('materials.*.unit') }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="tr{{ $material->id }}">
                                        <div class="form-group @if($errors->has('materials.*.quantity')) has-error @endif">
                                            <input  type="text" name="materials[{{ $material->id }}][quantity]" value="{{ $material->quantity }}" data-id="{{ $material->id }}" id="quantity{{ $material->id }}"  class="form-control quantity" onkeypress="javascript:return isNumber(event)">
                                            @if($errors->has('materials.*.quantity'))
                                                <span class="invalid-feedback">{{ $errors->first('materials.*.quantity') }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="tr{{ $material->id }}">
                                        <div class="form-group @if($errors->has('materials.*.rate')) has-error @endif">
                                            <input type="text"  name="materials[{{ $material->id }}][rate]" value="{{ $material->rate }}" data-id="{{ $material->id }}" id="rate{{ $material->id }}" class="form-control rate" onkeypress="javascript:return isNumber(event)">
                                            @if($errors->has('materials.*.rate'))
                                                <span class="invalid-feedback">{{ $errors->first('materials.*.rate') }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="tr{{ $material->id }}">
                                        <div class="form-group @if($errors->has('materials.*.total')) has-error @endif">
                                        <input type="text"  name="materials[{{ $material->id }}][total]" value="{{ $material->total }}" data-id="{{ $material->id }}" id="total{{ $material->id }}" class="form-control total" onkeypress="javascript:return isNumber(event)" readonly>
                                            @if($errors->has('materials.*.total'))
                                                <span class="invalid-feedback">{{ $errors->first('materials.*.total') }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="tr{{ $material->id }}">
                                        <input name="selected_rows[]" type="checkbox" class="checkbox selected_rows" data-id="{{ $material->id }}" id="selected_rows{{ $material->id }}" value="{{ $material->id }}" checked>
                                    </td>  
                                </tr>
                            @endforeach
                        @endif
                        <tr>
                            <td colspan="5">&nbsp;</td>
                            <td><strong>Carriage costs</strong></td>
                            <td colspan="2">
                                <div class="form-group @if($errors->has('carriage_costs')) has-error @endif">
                                    <input type="text"  name="carriage_costs"  id="carriage_costs" class="form-control carriage_costs" value="{{ $finalQuotation->carriage_costs ?? 0 }}" onkeypress="javascript:return isNumber(event)">
                                    @if($errors->has('carriage_costs'))
                                        <span class="invalid-feedback">{{ $errors->first('carriage_costs') }}</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5">&nbsp;</td>
                            <td><strong>C of C</strong></td>
                            <td colspan="2">
                                <div class="form-group @if($errors->has('c_of_c')) has-error @endif">
                                    <input type="text"  name="c_of_c"  id="c_of_c" class="form-control c_of_c" value="{{ $finalQuotation->c_of_c ?? 0 }}" onkeypress="javascript:return isNumber(event)">
                                    @if($errors->has('c_of_c'))
                                        <span class="invalid-feedback">{{ $errors->first('c_of_c') }}</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5">&nbsp;</td>
                            <td><strong>Other costs</strong></td>
                            <td colspan="2">
                                <div class="form-group @if($errors->has('other_costs')) has-error @endif">
                                    <input type="text"  name="other_costs"  id="other_costs" class="form-control other_costs" value="{{ $finalQuotation->other_costs ?? 0 }}" onkeypress="javascript:return isNumber(event)">
                                    @if($errors->has('other_costs'))
                                        <span class="invalid-feedback">{{ $errors->first('other_costs') }}</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5">&nbsp;</td>
                            <td><strong>Total Value</strong></td>
                            <td colspan="2">
                                <div class="form-group @if($errors->has('grand_total')) has-error @endif">
                                    <input type="text"  name="grand_total"  id="grand_total" class="form-control grand_total" value="{{ $grandTotal }}" readonly>
                                    @if($errors->has('grand_total'))
                                        <span class="invalid-feedback">{{ $errors->first('grand_total') }}</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="card card card-prirary cardutline direct-chat direct-chat-success">
        <div class="card-header">
            <h3>Conversation</h3>
        </div>
        <div class="card-body">
            @if($conversations->isNotEmpty())
                <div class="direct-chat-messages">
                @foreach($conversations as $conversation)
                    <!-- Message. Default to the left -->
                    <div class="direct-chat-msg @if($conversation->sender_id == auth()->id()) right @endif">
                        <div class="direct-chat-infos clearfix">
                            <span class="direct-chat-name float-left">
                                @if($conversation->sender_id == auth()->id())
                                    You
                                @else
                                    {{ $conversation->sender->company_name }}
                                @endif
                            </span>
                            <span class="direct-chat-timestamp float-right">{{ $conversation->created_at->format('d M Y g:i A') }}</span>
                        </div>
                        <!-- /.direct-chat-infos -->
                        @if(\Storage::disk('public')->has($conversation->sender->avatar))
                            <img src="{{ asset('storage/'.$conversation->sender->avatar) }}" alt="" class="direct-chat-img">
                        @else
                            <img src="{{ asset('images/no-img-100x92.jpg') }}" alt="" class="direct-chat-img">
                        @endif
                        <!-- /.direct-chat-img -->
                        <div class="direct-chat-text">
                            {{ $conversation->notes }}
                        </div>
                        <!-- /.direct-chat-text -->
                    </div>
                @endforeach
                </div>
            @else
                <p>No conversations yet!</p>
            @endif
        </div>
        <div class="card-footer">
            <div class="input-group @if($errors->has('notes')) has-error @endif">
                <textarea type="text" name="notes" placeholder="Type note ..." class="form-control"></textarea>
                @if($errors->has('notes'))
                    <span class="invalid-feedback">{{ $errors->first('notes') }}</span>
                @endif
                <span class="input-group-append">
                    <button type="submit" class="btn btn-primary btn-flat">Submit Detail</button>
                </span>
            </div>
        </div>
    </div>
    {{ Form::close() }}
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
                    alert('Please choose only image.');
                }
            }); 

            $(document).on('keyup', '.quantity, .rate, .total', function() { 	
                var index = $(this).attr('data-id');
                var quantity = $('#quantity'+index).val(); 
                var rate = $('#rate'+index).val();
                var total = $('#total'+index).val();

                var grand_total = $('#grand_total').val();	
                grand_total = parseFloat(grand_total).toFixed(4);	
                    
                quantity = parseFloat(quantity).toFixed(4);
                rate = parseFloat(rate).toFixed(4);
                total = parseFloat(total).toFixed(4);
                total = quantity * rate;  
                total = parseFloat(total).toFixed(4);	
                    
                $('#total'+index).val(total);	
                    
                calculation();	
            }); 

            $(document).on('click', '.select_all, .selected_rows', function()  {  
                calculation();
            }); 

            $(document).on('keyup', '.carriage_costs, .c_of_c, .other_costs', function(){  
                calculation();
            });   
        });
        function calculation(){
            var carriage_costs = 0; 	
            var c_of_c = 0;	
            var other_costs = 0;
            var grand_total = 0;	
            var rates = document.getElementsByClassName("rate");
            var arrayLength = rates.length;
            for (var index = 0; index < arrayLength; index++) {
                var id = $(rates[index]).attr('data-id');
                var selected_res = $('#selected_rows'+id); 
                if (selected_res[0].checked === true) {
                    var total = $('#total'+id).val(); 
                    grand_total = +grand_total+ +total;	
                }
            }	
            carriage_costs = $('#carriage_costs').val(); 
            c_of_c = $('#c_of_c').val();
            other_costs = $('#other_costs').val();
            grand_total = +grand_total+ +carriage_costs+ +c_of_c+ +other_costs;

            grand_total = parseFloat(grand_total).toFixed(4);
                
            $('#grand_total').val(grand_total);  
        }
        function isNumber(evt) {
            var iKeyCode = (evt.which) ? evt.which : evt.keyCode
            if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
                return false;

            return true;
        }
    </script>
@endpush
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
          <li class="breadcrumb-item"><a href="{{ route('purchase.orders.index') }}">Purchase Orders</a></li>
          <li class="breadcrumb-item active">Edit Purchase Order</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content printable_div">
    
      <div class="card-body">

            <button onclick="window.location.href='{{ route('purchase.orders.index') }}'"  type="button" class="mb-2 mr-2 btn-icon-vertical btn btn-info">
            <i class="pe-7s-back btn-icon-wrapper"></i>Back
            </button>                       

      </div>
    <div class="card">
        {{ Form::open(['route' => ['purchase.orders.update', $purchase->id], 'method' => 'patch', 'enctype'=> 'multipart/form-data']) }}
        <div class="card-header">
            <h3 class="card-title">Edit Purchase Order</h3>
           
        </div>
        <div class="card-body">
            <table class="table table-primary">
                <tr>
                    <td>
                        <address>
                            <strong>Company :</strong>Design Rationale Ltd<br/>
                            <strong>Phone :</strong> 01707 272771<br/>
                            <strong>Address :</strong> : 4 Bury Road, Hatfield, Hertfordshire, AL10 8BJ
                        </address>
                    </td>
                    <td>
                        <address class="text-right">
                            <strong>Supplier : </strong> {{ $purchase->supplier->secud_fname }}<br/>
                            <strong>Phone :</strong> {{ $purchase->supplier->secud_mobile }}<br/>
                            <strong>Address :</strong> {{ $purchase->supplier->secud_address }}
                                        {!! $purchase->supplier->secud_address1 ? ', '.$purchase->supplier->secud_address1 : '' !!}
                                        {!! $purchase->supplier->secud_suburb ? ', '.$purchase->supplier->secud_suburb : '' !!}
                                        {!! $purchase->supplier->secud_postcode ? ', '.$purchase->supplier->secud_postcode : '' !!}
                            <br/>
                            <strong>Project Code :</strong> {{ $purchase->project->unique_reference_no }}
                        </address>
                    </td>
                </tr>
            </table>
            <table class="table table-bordered table-active">
                <tr>
                    <th>Area:</th>
                    <td>{{ $purchase->mainActivity->area }}</td>
                    <th>Level:</th>
                    <td>{{ $purchase->mainActivity->level }}</td>
                    <th>Sub Code:</th>
                    <td>{{ $purchase->subActivity->sub_code }}</td>
                </tr>
            </table>
            <table class="table table-bordered table-active">
                <tr>
                    <td>
                        <div class="form-group @if($errors->has('delivery_date')) has-error @endif">
                            <label for="delivery_date">Delivery Date</label><span class="asterisk">*</span>
                            <input type="date" value="{{ $purchase->delivery_date->format('Y-m-d') }}" name="delivery_date" class="form-control delivery_date" id="delivery_date" />
                            @if($errors->has('delivery_date'))
                                <span class="invalid-feedback">{{ $errors->first('delivery_date') }}</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="form-group @if($errors->has('delivery_time')) has-error @endif">
                            <label for="delivery_time">Delivery Time</label><span class="asterisk">*</span>
                            <input type="time" value="{{ $purchase->delivery_time }}" name="delivery_time" class="form-control delivery_time" id="delivery_time" />
                            @if($errors->has('delivery_time'))
                                <span class="invalid-feedback">{{ $errors->first('delivery_time') }}</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="form-group @if($errors->has('delivery_address')) has-error @endif">
                            <label for="delivery_address">Delivery Address</label><span class="asterisk">*</span>
                            <input type="text" value="{{ $purchase->delivery_address }}" name="delivery_address" class="form-control delivery_address" id="delivery_address" />
                            @if($errors->has('delivery_address'))
                                <span class="invalid-feedback">{{ $errors->first('delivery_address') }}</span>
                            @endif
                        </div>
                    </td>
                </tr>
            </table>
            <table id="invoice_table" name="invoice_table" class="table table-warning" >
                <thead>
                    <tr >    
                        <th>Invoice No</th>
                        <th>Invoice Amount</th>
                        <th>Invoice File</th>
                        <th>Invoice Date</th>
                        <th><a href="javascript:;" class="btn btn-sm btn-success add_row_invoice" id="add_row_invoice">+</a></th>    
                    </tr>
                </thead>
                <tbody>
                    @if($purchase->invoices->isNotEmpty())
                        @foreach($purchase->invoices as $invoice)
                            <input type="hidden" name="old_invoices[{{ $invoice->id }}][invoice_id]" value="{{ $invoice->id }}">
                            <tr>
                                <td>
                                    <input type="text" jas="{{ $invoice->id }}"  id ="invoice_no{{ $invoice->id }}" name="old_invoices[{{ $invoice->id }}][invoice_no]" value="{{ $invoice->invoice_no }}" class="form-control invoice_no">
                                </td>
                                <td>
                                    <input type="text" jas="{{ $invoice->id }}"  id ="invoice_amount{{ $invoice->id }}" name="old_invoices[{{ $invoice->id }}][invoice_amount]" value="{{ round($invoice->invoice_amount,2) }}" class="form-control invoice_amount">
                                </td>                                                                                                       
                                <td>
                                    <div id="imagePreview" class="imagePreviews"><?php if($invoice->invoice_file){ ?><img src="{{ asset('uploads/purchase_invoice_file/'.$invoice->invoice_file) }}" style="height: 50px;"> 
                                    <?php } ?>
                                    </div>
                                    <?php if($invoice->invoice_file){ ?> <a style="font-size:9px;" href="{{ asset('uploads/purchase_invoice_file/'.$invoice->invoice_file) }}" download>Download</a> <?php } ?> 
                                    <input jas="{{ $invoice->id }}"  id ="invoice_file{{ $invoice->id }}" name="old_invoices[{{ $invoice->id }}][invoice_file]"  type="file" class="form-control invoice_file" value="{{$invoice->invoice_file}}" accept="image/*">
                                    <input jas="{{ $invoice->id }}"  id ="test{{ $invoice->id }}" name="old_invoices[{{$invoice->id }}][test]"  type="hidden" class="form-control js-example-basic-single test" value="{{$invoice->invoice_file}}">
                                </td>
                                <td>
                                    <input type="date" jas="{{ $invoice->id }}"  id ="invoice_date{{ $invoice->id }}" name="old_invoices[{{ $invoice->id }}][invoice_date]" value="{{ $invoice->invoice_date->format('Y-m-d') }}" class="form-control invoice_date">
                                </td>
                                <td>
                                    <a href="javascript:;" class="btn btn-sm btn-danger remove-invoice">x</a>
                                </td>
                            </tr>  
                        @endforeach
                    @endif
                </tbody>
            </table>
<br/>           
<div class="col-md-12 no-padding">
<div class="responsive-table">		
<table id="certifcate_table" name="certifcate_table" class="table" style="width:400px" align="center" >
<thead >
<tr style="background:#ffdf7e" >
<th class="col-md-5"><b><h6 align="center" style="color:#000000;">Certificate</h6></b></th>
<th class="col-md-3" style="color:#FFFFFF;"><b><h6 align="center" style="color:#000000">
<th class="col-md-1" style="color:#FFFFFF;"><b><h6 align="center" style="color:#000000">
<p style="background-color:#55CD01; color:#FFFFFF; cursor:pointer; font-size:16px; font-weight:bold; border: none;" id="add_row_certificate" class="btn btn-success btn-sm pull-right">+</p></h6></b>
</th>
</tr>
</thead>
<tbody>
@if($certificate_po->isNotEmpty())
@foreach($certificate_po as $cert)
<input type="hidden" value="{{ $cert->id }}" name="old_cert[{{ $cert->id }}][id]" >
<tr> 

<td>   
    <input jas="{{ $cert->id }}"  id ="certificate{{ $cert->id }}" name="old_cert[{{ $cert->id }}][certificate]"  type="file" class="form-control certificate" value="{{$cert->certificate}}" accept="image/*">
    <input jas="{{ $cert->id }}"  id ="test_certificate{{ $cert->id }}" name="old_cert[{{$cert->id }}][test_certificate]"  type="hidden" class="form-control js-example-basic-single test_certificate" value="{{$cert->certificate}}">
    
</td>
<td>
    <div id="imagePreview" class="imagePreviews"><?php if($cert->certificate){ ?><img src="{{ asset('uploads/purchase_certificate/'.$cert->certificate) }}" style="height: 50px;"><?php } ?></div>
    <?php if($cert->certificate){ ?> <a style="font-size:9px;" href="{{ asset('uploads/purchase_certificate/'.$cert->certificate) }}" download>Download</a> <?php } ?> 
</td>
<td><p style="cursor:pointer; font-weight:bold;" class="btn btn-danger btn-sm remove pull-right">X</p></td> 
</tr>  

@endforeach
@endif

</tbody>
</table>
</div>
</div>




<!--<div class="col-md-12 no-padding">-->
<div class="responsive-table">	

  
<table id="deliverynote_table" name="deliverynote_table" class="table" style="width:700px" align="center" >
    
<thead >
<tr style="background:#ffdf7e" >
<th  ><b><h6 align="center" style="color:#000000;">Delivery note</h6></b></th>
<th  style="color:#FFFFFF;"><b><h6 align="center" style="color:#000000">
<th  style="color:#FFFFFF;"><b><h6 align="center" style="color:#000000">
<th  style="color:#FFFFFF;"><b><h6 align="center" style="color:#000000">
<p style="background-color:#55CD01; color:#FFFFFF; cursor:pointer; font-size:16px; font-weight:bold; border: none;" id="add_row_delivernote" class="btn btn-success btn-sm pull-right">+</p></h6></b>
</th>
</tr>
</thead>
<tbody>
@if($purchase_deliverynote->isNotEmpty())
@foreach($purchase_deliverynote as $note)



<input type="hidden" value="{{ $note->id }}" name="old_delivery[{{ $note->id }}][id]" >

<tr > 

<td >   
 
  
<input jas="{{ $note->id }}"  id ="delivery{{ $note->id }}" name="old_delivery[{{ $note->id }}][delivery_note]"  type="file" class="form-control delivery" value="{{$note->delivery_note}}" accept="image/*">
<input jas="{{ $note->id }}"  id ="test_delivery{{ $note->id }}" name="old_delivery[{{$note->id }}][test_delivery_note]"  type="hidden" class="form-control js-example-basic-single test_delivery" value="{{$note->delivery_note}}">  


</td>
<td >
    <div id="imagePreview" class="imagePreviews"><?php if($note->delivery_note){ ?><img src="{{ asset('uploads/purchase_deliverynote/'.$note->delivery_note) }}" style="height: 50px;"><?php } ?></div>
    <?php if($note->delivery_note){ ?> <a style="font-size:9px;" href="{{ asset('uploads/purchase_deliverynote/'.$note->delivery_note) }}" download>Download</a> <?php } ?> 
</td>
<td  ><textarea style=" width:200px;" jas="{{ $note->id }}"  id ="note{{ $note->id }}" name="old_delivery[{{$note->id }}][note]"  class="form-control js-example-basic-single note" >{{$note->note}}</textarea></td>
<td ><p style="cursor:pointer; font-weight:bold;" class="btn btn-danger btn-sm remove pull-right">X</p></td> 
</tr>  

@endforeach
@endif

</tbody>
</table>
</div>
<!--</div>-->
            
            
            
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
                @php 
                    $grand_total = 0;
                @endphp
                @if($purchase->orders->isNotEmpty())
                    @foreach($purchase->orders as $order)
                        <tr>
                            <input type="hidden" name="old_activities[{{ $order->id }}][order_id]" value="{{ $order->id }}">
                            <td class="tr{{ $order->id }}">
                                {{ $order->activityOfOrder->item_code }}
                            </td>
                            <td class="tr{{ $order->id }}">
                                <div class="form-group @if($errors->has('activities.*.activity')) has-error @endif">
                                    <input type="text" name="old_activities[{{ $order->id }}][activity]" value="{{ $order->activity }}" data-id="{{ $order->id }}" id="activity{{ $order->id }}"  class="form-control activity">
                                    @if($errors->has('activities.*.activity'))
                                        <span class="invalid-feedback">{{ $errors->first('activities.*.activity') }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="tr{{ $order->id }}">
                                <div id="image-preview{{ $order->id }}" class="image-previews">
                                    <label  id="b1up{{ $order->id }}"  style="text-align:center;">
                                        @if(\Storage::disk('public')->has('quotations/'.$order->photo))
                                            <img src="{{ asset('storage/quotations/'.$order->photo) }}" alt="" height="50px" width="50px">
                                        @else
                                            Choose Image
                                        @endif
                                    </label>
                                </div>
                                <input type="file" name="old_activities[{{ $order->id }}][file]" data-id="{{ $order->id }}" id="file{{ $order->id }}"  class="form-control file" style="display:none">
                            </td>
                            <td class="tr{{ $order->id }}">
                                <div class="form-group @if($errors->has('activities.*.unit')) has-error @endif">
                                    <input  type="text" name="old_activities[{{ $order->id }}][unit]" value="{{ $order->unit }}" data-id="{{ $order->id }}" id="unit{{ $order->id }}"  class="form-control unit">
                                    @if($errors->has('activities.*.unit'))
                                        <span class="invalid-feedback">{{ $errors->first('activities.*.unit') }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="tr{{ $order->id }}">
                                <div class="form-group @if($errors->has('activities.*.quantity')) has-error @endif">
                                    <input  type="text" name="old_activities[{{ $order->id }}][quantity]" value="{{ $order->quantity }}" data-id="{{ $order->id }}" id="quantity{{ $order->id }}"  class="form-control quantity" onkeypress="javascript:return isNumber(event)">
                                    @if($errors->has('activities.*.quantity'))
                                        <span class="invalid-feedback">{{ $errors->first('activities.*.quantity') }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="tr{{ $order->id }}">
                                <div class="form-group @if($errors->has('activities.*.rate')) has-error @endif">
                                    <input type="text"  name="old_activities[{{ $order->id }}][rate]" value="{{ $order->rate }}" data-id="{{ $order->id }}" id="rate{{ $order->id }}" class="form-control rate" onkeypress="javascript:return isNumber(event)">
                                    @if($errors->has('activities.*.rate'))
                                        <span class="invalid-feedback">{{ $errors->first('activities.*.rate') }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="tr{{ $order->id }}">
                                <div class="form-group @if($errors->has('activities.*.total')) has-error @endif">
                                    <input type="text"  name="old_activities[{{ $order->id }}][total]" value="{{ $order->quantity*$order->rate }}" data-id="{{ $order->id }}" id="total{{ $order->id }}" class="form-control total" readonly>
                                    @if($errors->has('activities.*.total'))
                                        <span class="invalid-feedback">{{ $errors->first('activities.*.total') }}</span>
                                    @endif
                                </div>
                            </td> 
                            <td class="tr{{ $order->id }}">
                                <input name="selected_rows[]" type="checkbox" class="checkbox selected_rows" data-id="{{ $order->id }}" id="selected_rows{{ $order->id }}" value="{{ $order->id }}" checked>
                            </td>  
                        </tr>
                        @php
                            $grand_total = $grand_total + ($order->quantity*$order->rate);
                        @endphp
                    @endforeach
                @endif
                @if($unselectedActivities->isNotEmpty())
                    @foreach($unselectedActivities as $activity)
                        <tr>
                            <input type="hidden" name="new_activities[{{ $activity->id }}][activity_id]" value="{{ $activity->id }}">
                            <td class="tr{{ $activity->id }}">
                                <input type="hidden" name="new_activities[{{ $activity->id }}][item_code]" value="{{ $activity->item_code }}">
                                {{ $activity->item_code }}
                            </td>
                            <td class="tr{{ $activity->id }}">
                                <div class="form-group @if($errors->has('activities.*.activity')) has-error @endif">
                                    <input type="text" name="new_activities[{{ $activity->id }}][activity]" value="{{ $activity->activity }}" data-id="{{ $activity->id }}" id="activity{{ $activity->id }}"  class="form-control activity">
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
                                <input type="file" name="new_activities[{{ $activity->id }}][file]" data-id="{{ $activity->id }}" id="file{{ $activity->id }}"  class="form-control file" style="display:none">
                            </td>
                            <td class="tr{{ $activity->id }}">
                                <div class="form-group @if($errors->has('activities.*.unit')) has-error @endif">
                                    <input  type="text" name="new_activities[{{ $activity->id }}][unit]" value="{{ $activity->unit }}" data-id="{{ $activity->id }}" id="unit{{ $activity->id }}"  class="form-control unit">
                                    @if($errors->has('activities.*.unit'))
                                        <span class="invalid-feedback">{{ $errors->first('activities.*.unit') }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="tr{{ $activity->id }}">
                                <div class="form-group @if($errors->has('activities.*.quantity')) has-error @endif">
                                    <input  type="text" name="new_activities[{{ $activity->id }}][quantity]" value="{{ $activity->quantity }}" data-id="{{ $activity->id }}" id="quantity{{ $activity->id }}"  class="form-control quantity" onkeypress="javascript:return isNumber(event)">
                                    @if($errors->has('activities.*.quantity'))
                                        <span class="invalid-feedback">{{ $errors->first('activities.*.quantity') }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="tr{{ $activity->id }}">
                                <div class="form-group @if($errors->has('activities.*.rate')) has-error @endif">
                                <input type="hidden"  name="new_activities[{{ $activity->id }}][rate]" value="0" data-id="{{ $activity->id }}" id="rate{{ $activity->id }}" class="rate" onkeypress="javascript:return isNumber(event)">
                                    @if($errors->has('activities.*.rate'))
                                        <span class="invalid-feedback">{{ $errors->first('activities.*.rate') }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="tr{{ $activity->id }}">
                                <div class="form-group @if($errors->has('activities.*.total')) has-error @endif">
                                <input type="hidden"  name="new_activities[{{ $activity->id }}][total]" value="0" data-id="{{ $activity->id }}" id="total{{ $activity->id }}" class="total" readonly>
                                    @if($errors->has('activities.*.total'))
                                        <span class="invalid-feedback">{{ $errors->first('activities.*.total') }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="tr{{ $activity->id }}">
                                <input name="selected_rows[]" type="checkbox" class="checkbox selected_rows" data-id="{{ $activity->id }}" id="selected_rows{{ $activity->id }}" value="{{ $activity->id }}">
                            </td>  
                        </tr>
                    @endforeach
                @endif
                @php 
                    $grand_total = $grand_total + $purchase->carriage_costs + $purchase->c_of_c + $purchase->other_costs;
                @endphp
                <tr>
                    <td colspan="5">&nbsp;</td>
                    <td>Carriage costs</td>
                    <td colspan="2">
                        <div class="form-group @if($errors->has('carriage_costs')) has-error @endif">
                            <input type="text"  name="carriage_costs"  id="carriage_costs" class="form-control carriage_costs" value="{{ $purchase->carriage_costs }}" onkeypress="javascript:return isNumber(event)">
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
                            <input type="text"  name="c_of_c"  id="c_of_c" class="form-control c_of_c" value="{{ $purchase->c_of_c }}" onkeypress="javascript:return isNumber(event)">
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
                            <input type="text"  name="other_costs"  id="other_costs" class="form-control other_costs" value="{{ $purchase->other_costs }}" onkeypress="javascript:return isNumber(event)">
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
                            <textarea class="form-control" rows="2" name="notes" id="notes">{{ $purchase->notes }}</textarea>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="card-footer">
            {{ Form::submit('Update purchase order', [ 'class' => "btn btn-primary btn-flat" ]) }}
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
                    alert('Please choose only image.');
                }
            });

            $(document).on('keyup', '.quantity, .rate, .total', function() { 	

                var index = $(this).attr('data-id');
                var quantity = $('#quantity'+index).val(); 
                var rate = $('#rate'+index).val();
                var total = $('#total'+index).val();	

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
            var jas = 1;
            $(document).on('click', '.add_row_invoice', function(){
                $('#invoice_table').append('<tr><td><input type="text" jas="'+jas+'" id="invoice_no'+jas+'" name="new_invoices['+jas+'][invoice_no]" class="form-control invoice_no"></td><td><input type="text" jas="'+jas+'" id ="invoice_amount'+jas+'" name="new_invoices['+jas+'][invoice_amount]" class="form-control invoice_amount"></td><td><input type="file"  jas="'+jas+'" id ="invoice_file'+jas+'" name="new_invoices['+jas+'][invoice_file]" class="form-control invoice_file" accept="image/*"></td><td><input type="date" jas="'+jas+'" id ="invoice_date'+jas+'" name="new_invoices['+jas+'][invoice_date]" value="<?php echo date("Y-m-d");?>" class="form-control invoice_date"></td><td><a href="javascript:;" class="btn btn-sm btn-danger remove-invoice">x</a></td></tr>');
                var i = 1;
                $('.row-num').each(function(){
                    $(this).html(i);
                    i++;
                });
                jas = jas + 1;
            });
            $(document).on('click', '.remove-invoice', function(){ 
                $(this).parent().parent().remove();
            });	
            
            //certificate
            var jas1 = 1;
            $(document).on('click', '#add_row_certificate', function(){
            	$('#certifcate_table').append('<tr><td><input type="file"  jas1="'+jas1+'" id ="certificate'+jas1+'" name="certificates[]" class="form-control certificate" ></td><td></td><td><p style=" cursor:pointer; font-weight:bold;" class="btn btn-danger pull-right btn-sm remove">X</p></td></tr>');
            	var i = 1;
            	$('.row-num').each(function(){
            		$(this).html(i);
            		i++;
            	});
            	jas1 = jas1 + 1;
            });
            $('body').delegate('.remove','click',function()  
            { 
                $(this).parent().parent().remove();
            });	
            var jas2 = 1;
            $(document).on('click', '#add_row_delivernote', function(){
            	$('#deliverynote_table').append('<tr><td><input type="file"  jas2="'+jas2+'" id ="delivery'+jas2+'" name="deliverynote[][file]" class="form-control delivery" ></td><td></td><td><textarea style=" width:200px;" jas2="'+jas2+'" id ="note'+jas2+'" name="deliverynote[][note]" class="form-control note"></textarea> </td><td><p style=" cursor:pointer; font-weight:bold;" class="btn btn-danger pull-right btn-sm remove">X</p></td></tr>');
            	var i = 1;
            	$('.row-num').each(function(){
            		$(this).html(i);
            		i++;
            	});
            	jas2 = jas2 + 1;
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
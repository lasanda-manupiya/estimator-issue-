@extends('user::layouts.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
          
            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('purchase.orders.index') }}"><i class="fa fa-dashboard"></i> Purchase Orders</a></li>
                    <li class="breadcrumb-item active">Purchase Order</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    
      <div class="card-body">

<button onclick="window.location.href='{{ route('purchase.orders.index') }}'"  type="button" class="mb-2 mr-2 btn-icon-vertical btn btn-info">
<i class="pe-7s-back btn-icon-wrapper"></i>Back
</button>

     </div>
    <!-- Default box -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Purchase Order</h3>
            
        </div>
        <div class="card-body">
            <table class="table table-hover table-striped table-danger">
                <tr>
                    <td>
                        <address>
                            <strong>Company :</strong>{{ @$purchase->project->company->company_name }}<br/>
                            <strong>Phone :</strong> {{ @$purchase->project->company->phone }}<br/>
                            <strong>Address :</strong> : {{ @$purchase->project->company->address_line1 }}, {{ @$purchase->project->company->address_line2 }}
                        </address>
                    </td>
                    <td>
                        <address class="text-right">
                            <strong>Supplier : </strong> {{ @$purchase->supplier->supplier_name }}<br/>
                            <strong>Phone :</strong> {{ @$purchase->supplier->phone }}<br/>
                            <strong>Address :</strong> {{ @$purchase->supplier->address_line1 }}
                                        {!! @$purchase->supplier->address_line2 ? ', '.@$purchase->supplier->address_line2 : '' !!}
                                        {!! @$purchase->supplier->suburb ? ', '.@$purchase->supplier->suburb : '' !!}
                                        {!! @$purchase->supplier->postcode ? ', '.@$purchase->supplier->postcode : '' !!}
                            <br/>
                            <strong>Project Code :</strong> {{ @$purchase->project->unique_reference_no }}
                        </address>
                    </td>
                </tr>
            </table>
            <table class="table table-hover table-striped table-bordered table-warning">
                <tr>
                    <th>Delivery Date:</th>
                    <td>{{ $purchase->delivery_date->format('d-m-Y') }}</td>
                    <th>Delivery Time:</th>
                    <td>{{ $purchase->delivery_time }}</td>
                    <th>Delivery Address:</th>
                    <td>{{ $purchase->delivery_address }}</td>
                </tr>
                <tr>
                    <th>Area:</th>
                    <td>{{ $purchase->mainActivity->area }}</td>
                    <th>Level:</th>
                    <td>{{ $purchase->mainActivity->level }}</td>
                    <th>Sub Code:</th>
                    <td>{{ $purchase->subActivity->sub_code }}</td>
                </tr>
            </table>
            @if($purchase->invoices->isNotEmpty())
            <table id="invoice_table" name="invoice_table" class="table table-hover table-striped table-bordered table-warning" >
                <thead>
                    <tr >    
                        <th>Invoice No</th>
                        <th>Invoice Amount</th>
                        <th>Invoice File</th>
                        <th>Invoice Date</th>
                        <th>Approval</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchase->invoices as $invoice)
                        <input type="hidden" name="old_invoices[{{ $invoice->id }}][invoice_id]" value="{{ $invoice->id }}">
                        <tr>
                            <td>
                                {{ $invoice->invoice_no }}
                            </td>
                            <td>
                                &pound;{{ round($invoice->invoice_amount,2) }}
                            </td>
                            <td>
                                {{ $invoice->invoice_date->format('d-m-Y') }}
                            </td>
                            <td>
                                <div id="imagePreview" class="imagePreviews"><?php if($invoice->invoice_file){ ?><img src="{{ asset('uploads/purchase_invoice_file/'.$invoice->invoice_file) }}" style="height: 50px;"> 
                                <?php } ?>
                                </div>
                                <?php if($invoice->invoice_file){ ?> <a style="font-size:9px;" href="{{ asset('uploads/purchase_invoice_file/'.$invoice->invoice_file) }}" download>Download</a> <?php } ?> 
                               
                            </td>
                            <td>
                                @if(auth::user()->isRole('Super Admin') || auth::user()->isRole('Admin') || auth::user()->isRole('Project Manager') || auth::user()->isRole('Project Manager'))
                                    <input type="checkbox" id="invoice_approval{{ $invoice->invoice_no }}" name="invoice_approval[]" onchange="approveInvoice({{ $invoice->id }});"  @if($invoice->approver) checked @endif />
                                @endif
                                @if($invoice->approver)
                                    By {{ $invoice->approver->secud_fname }}({{ $invoice->approver->roles->first()->name }})
                                @endif
                            </td>
                        </tr>  
                    @endforeach
                </tbody>
            </table>
            @endif
            
            
<br/>           
<div class="col-md-12 no-padding">
<div class="responsive-table">		
<table id="certifcate_table" name="certifcate_table" class="table table-hover table-striped table-bordered" style="width:400px" align="center" >
<thead >
<tr style="background:#ffdf7e" >
<th class="col-md-3"><b><h6 align="center" style="color:#000000;">Certificate</h6></b></th>

</th>
</tr>
</thead>
<tbody>
@if($certificate_po->isNotEmpty())
@foreach($certificate_po as $cert)
<input type="hidden" value="{{ $cert->id }}" name="old_cert[{{ $cert->id }}][id]" >
<tr> 


<td>
    <center>
        <div id="imagePreview" class="imagePreviews"><?php if($cert->certificate){ ?><img src="{{ asset('uploads/purchase_certificate/'.$cert->certificate) }}" style="height: 50px;"><?php } ?></div>
        <?php if($cert->certificate){ ?> <a style="font-size:9px;" href="{{ asset('uploads/purchase_certificate/'.$cert->certificate) }}" download>Download</a> <?php } ?> 
    </center>
    
</td>

</tr>  

@endforeach
@endif

</tbody>
</table>
</div>
</div>            
       
       
<br/>           

<div class="responsive-table">		
<table id="deliverynote_table" name="deliverynote_table" class="table" style="width:500px" align="center" >
<thead >
<tr style="background:#ffdf7e" >
<th  ><b><h6 align="center" style="color:#000000;"></h6></b></th>
<th  style="color:#FFFFFF;"><b><h6 align="center" style="color:#000000">Delivery note</h6></b></th>


</tr>
</thead>
<tbody>
@if($purchase_deliverynote->isNotEmpty())
@foreach($purchase_deliverynote as $note)



<input type="hidden" value="{{ $note->id }}" name="old_delivery[{{ $note->id }}][id]" >

<tr > 


<td ><center>
    <div id="imagePreview" class="imagePreviews"><?php if($note->delivery_note){ ?><img src="{{ asset('uploads/purchase_deliverynote/'.$note->delivery_note) }}" style="height: 50px;"><?php } ?></div>
    <?php if($note->delivery_note){ ?> <a style="font-size:9px;" href="{{ asset('uploads/purchase_deliverynote/'.$note->delivery_note) }}" download>Download</a> <?php } ?> </center>
</td>
<td  ><textarea style=" width:300px;" jas="{{ $note->id }}"  id ="note{{ $note->id }}" name="old_delivery[{{$note->id }}][note]"  class="form-control js-example-basic-single note" >{{$note->note}}</textarea></td>

</tr>  

@endforeach
@endif

</tbody>
</table>

</div>
            
            
            
            
            
            <table class="table table-bordered text-center">
                <thead>
                    <tr class="table-success">
                        <th width="10%">Activity Code</th>
                        <th width="20%">Activity</th>
                        <th width="10%">Image</th>
                        <th width="15%">Unit</th>
                        <th width="15%">Quantity</th>
                        <th width="15%">Rate</th>
                        <th width="15%">Total</th>
                    </tr>
                </thead>
                @if($purchase->orders->isNotEmpty())
                    @foreach($purchase->orders as $order)
                        <tr>
                            <td>
                                {{ $order->activityOfOrder->item_code }}
                            </td>
                            <td>
                                {{ $order->activity }}
                            </td>
                            <td>
                                @if(\Storage::disk('public')->has('purchases/'.$order->photo))
                                    <img src="{{ asset('storage/purchases/'.$order->photo) }}" alt="" height="50px" width="50px">
                                @else
                                    <img src="{{ asset('images/no-img-100x92.jpg') }}" alt="" height="50px" width="50px">
                                @endif
                            </td>
                            <td>
                                {{ $order->unit }}
                            </td>
                            <td>
                                {{ $order->quantity }}
                            </td>
                            <td>
                                &pound;{{ $order->rate }}
                            </td>
                            <td>
                                &pound;{{ $order->total }}
                            </td>
                        </tr>
                    @endforeach
                @endif
                <tr class="table-active">
                    <td colspan="5">&nbsp;</td>
                    <th>Carriage costs</th>
                    <td colspan="2">
                        &pound;{{ $purchase->carriage_costs }}
                    </td>
                </tr>
                <tr class="table-active">
                    <td colspan="5">&nbsp;</td>
                    <th>C of C</th>
                    <td colspan="2">
                        &pound;{{ $purchase->c_of_c }}
                    </td>
                </tr>
                <tr class="table-active">
                    <td colspan="5">&nbsp;</td>
                    <th>Other costs</th>
                    <td colspan="2">
                        &pound;{{ $purchase->other_costs }}
                    </td>
                </tr>
                <tr class="table-active">
                    <td colspan="5">&nbsp;</td>
                    <th>Total Value</th>
                    <td colspan="2">
                        &pound;{{ $purchase->grand_total }}
                    </td>
                </tr>
            </table>
            

            <table class="table table-secondary">
                <tr>
                    <th width="15%">Note:</th>
                    <td>{{ $purchase->notes }}</td>
                </tr>
            </table>
        </div>
    </div>
    <!-- /.card -->

</section>

@stop
@push('scripts')
    <script type="text/javascript">
        function approveInvoice(id){
          if(!id) return;
          window.location = "{{ route('purchase.orders.approve.invoice') }}/"+id;
        }
    </script>
@endpush
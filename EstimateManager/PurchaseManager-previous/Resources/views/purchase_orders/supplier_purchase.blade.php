@extends('user::layouts.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Manage Purchase Orders</h1>
            </div>
            <div class="col-sm-6">
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
    @include('layouts.flash.alert')
    <!-- Default box -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Purchase Order</h3>
            <div class="card-tools">
              <div class="box-tools pull-right">
                    <a href="javascript:;" class="btn btn-success btn-sm" onclick="printDiv('printable_div')">Print</a>
                    <a href="{{ route('purchase.orders.index') }}" class="btn btn-default btn-sm">Back</a>
              </div>
            </div>
        </div>
        <div class="card-body printable_div" id="printable_div">
            <table class="table table-primary">
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
            <table class="table table-bordered table-active">
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
            <table id="invoice_table" name="invoice_table" class="table table-bordered table-warning" >
                <thead>
                    <tr >    
                        <th>Invoice No</th>
                        <th>Invoice Amount</th>
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
                                &pound;{{ $invoice->invoice_amount }}
                            </td>
                            <td>
                                {{ $invoice->invoice_date->format('d-m-Y') }}
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
        <div class="card-footer">
            {{ Form::open(['route' => ['purchase.orders.update.status', $purchase->id], 'method' => 'post', 'class'=>'status-form']) }}
                <input type="hidden" name="purchase_id" value="{{ $purchase->id }}">
                <input type="hidden" name="supplier_id" value="{{ $purchase->supplier_id }}">
                <div class="input-group @if($errors->has('status')) has-error @endif">
                    <input type="text" name="status" value="{{ $purchaseStatus->status ?? '' }}" placeholder="For example : Delivered" class="form-control">
                    @if($errors->has('status'))
                        <span class="invalid-feedback">{{ $errors->first('status') }}</span>
                    @endif
                    <span class="input-group-append">
                        <button type="submit" class="btn btn-primary btn-flat">Set Status</button>
                    </span>
                </div>
            {{ Form::close() }}
        </div>
    </div>
    <!-- /.card -->

</section>

@stop
@push('scripts')
    <script type="text/javascript">
        function printDiv(divName) {
            $(".status-form").hide();
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            $('.status-form').show();
        }
    </script>
@endpush
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
    <!-- Default box -->
    <div class="card">
        {{ Form::open(['route' => ['purchase.orders.send.invoice', $purchase->id], 'method' => 'post']) }}
        
                 <div class="card-header">
         <h3 class="card-title">Purchase Order</h3><br><br>
                 </div>
        
        
        <div class="card-body">

                {{ Form::submit('Send mail', [ 'class' => "mb-2 mr-2 btn-icon-vertical btn btn-success" ]) }}
                        <a href="javascript:;" class="mb-2 mr-2 btn-icon-vertical btn btn-warning" onclick="printDiv('invoice')">Print</a>
                        <a href="{{ route('purchase.orders.index') }}" class="mb-2 mr-2 btn-icon-vertical btn btn-info">Back</a>

     </div>
          
            <div class="card-body invoice" id="invoice">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-active">
                            <tr>
                                <td width="36%">
                                    <address>
                                        <strong>Company :</strong>{{ @$purchase->project->company->company_name }}<br/>
                                        <strong>Phone :</strong> {{ @$purchase->project->company->phone }}<br/>
                                        <strong>Address :</strong> : {{ @$purchase->project->company->address_line1 }}, 
                                        <br>{{ @$purchase->project->company->address_line2 }}
                                    </address>
                                </td>
                                <td width="32%">
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
                                <td width="32%">
                                    <p class="text-right">
                                        <strong>Order No : </strong> {{ $purchase->project->unique_reference_no }}-{{ str_pad($purchase->purchase_no, 3, '0', STR_PAD_LEFT) }}<br/>
                                        <strong>Order Date :</strong> {{ $purchase->supplier->created_at->format('d-m-Y') }}<br/>
                                        <strong>Delivery Date :</strong> {{ $purchase->delivery_date->format('d-m-Y') }}<br/>
                                        <strong>Location :</strong> {{ $purchase->delivery_address }}
                                    </p>
                                </td>
                            </tr>
                        </table>
                        <table class="table table-bordered table-warning"> 
                            <tbody>
                                <tr class="text-center">
                                    <td> 
                                        Please : &nbsp;<strong>Supply Only</strong> 
                                        <input type="checkbox" name="supply_only" value="Supply Only">
                                    </td>
                                    <td>
                                        <strong>Supply & Deliver</strong>
                                        <input type="checkbox" name="supply_deliver" value="Supply & Deliver">
                                    </td>
                                    <td>
                                        <strong>Supply & Install</strong> 
                                        <input type="checkbox" name="supply_install" value="Supply & Install"> &nbsp; the following:
                                    </td>
                                </tr> 
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr class="table-success">
                                    <th width="10%">Sr No.</th>
                                    <th width="40%">Description</th>
                                    <th width="10%">Unit</th>
                                    <th width="15%">Quantity</th>
                                    <th width="15%">Rate</th>
                                    <th width="15%">Sub-total</th>
                                </tr>
                            </thead>
                            @if($purchase->orders->isNotEmpty())
                                @foreach($purchase->orders as $order)
                                    <tr>
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            {{ $order->activity }}
                                        </td>
                                        <td>
                                            {{ $order->unit }}
                                        </td>
                                        <td>
                                            {{ number_format((float)$order->quantity, 4) }}
                                        </td>
                                        <td>
                                            &pound;{{ number_format((float)$order->rate, 4) }}
                                        </td>
                                        <td>
                                            &pound;{{ number_format((float)$order->total, 4) }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            <tr>
                                <td colspan="5" class="text-right"><strong>Sub Total:</strong></td>
                                <td class="text-right">&pound;{{ number_format((float)$purchase->orders->sum('total'), 4) }}</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-right"><strong>Carriage costs:</strong></td>
                                <td class="text-right">&pound;{{ number_format((float)$purchase->carriage_costs, 4) }}</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-right"><strong>C of C:</strong></td>
                                <td class="text-right">&pound;{{ number_format((float)$purchase->c_of_c, 4) }}</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-right"><strong>Other costs:</strong></td>
                                <td class="text-right">&pound;{{ number_format((float)$purchase->other_costs, 4) }}</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-right"><strong>Grand Total:</strong></td>
                                <td class="text-right">&pound;{{ number_format((float)$purchase->grand_total, 4) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="callout callout-info">
                            <strong>Important Note:</strong>In accordance with our clients requirements please provide the following documents releative to our order and your products and send to us by email (Document control) at the time of dispatch to Document control
                        </div> 
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-active text-right">
                            <tr>
                                <td widht="25%">
                                    Certificate of conformance required upon order 
                                    <input type="checkbox" name="certificate_of_conformance" value="Certificate of conformance required upon order">
                                </td>
                                <td widht="25%">
                                    CE certificates 
                                    <input type="checkbox" name="ce_certificates" value="CE certificates">
                                </td>
                                <td widht="25%">
                                    Welder & test certs 
                                    <input type="checkbox" name="welder_test_certs" value="Welder & test certs">
                                </td>
                                <td widht="25%">
                                    Any relevant COSHH Data required 
                                    <input type="checkbox" name="any_relevant" value="Any relevant COSHH Data required">
                                </td>
                            </tr>
                            <tr>
                                <td widht="25%">
                                    W.P.Q.R 
                                    <input type="checkbox" name="w_p_q_r" value="W.P.Q.R">
                                </td>
                                <td widht="25%">
                                    Kitemark certs 
                                    <input type="checkbox" name="kitemark_certs" value="Kitemark certs">
                                </td>
                                <td widht="25%">
                                    Glass bonding certs 
                                    <input type="checkbox" name="glass_bonding_certs" value="Glass bonding certs">
                                </td>
                                <td widht="25%">
                                    Structural calcs <input type="checkbox" name="structural_calcs" value="Structural calcs">
                                </td>
                            </tr>
                            <tr>
                                <td widht="25%">
                                    Galvanising certs 
                                    <input type="checkbox" name="galvanising_certs" value="Galvanising certs">
                                </td>
                                <td widht="25%">
                                    Paint certs 
                                    <input type="checkbox" name="paint_certs" value="Paint certs">
                                </td>
                                <td widht="25%">
                                    Mill certs 
                                    <input type="checkbox" name="mill_certs" value="Mill certs">
                                </td>
                                <td widht="25%">
                                    Copy of shipping / delivery note 
                                    <input type="checkbox" name="copy_of_shipping" value="Copy of shipping / delivery note">
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <p><strong>Additional Notes:</strong>{{ nl2br(ucfirst($purchase->note)) }}</p> 
                        <p><b>Please acknowledge receipt of this purchase order document.	</b></p>																				
                        <p>The above purchase order number must be stated on your delivery notes and invoices.	</p>																				
                        <p>Please submit a copy of all signed delivery notes with your invoice.	</p>
                        <p><b>Should this date not be met, {{ @$purchase->project->company->company_name }} reserves the right to pass on any liability to the manufacturer / supplier.</b></p>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card -->
    {{ Form::close() }}
</section>

@stop
@push('scripts')
    <script type="text/javascript">
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
       }
    </script>
@endpush
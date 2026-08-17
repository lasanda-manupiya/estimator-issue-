<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
</head>
<body>
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
                            <label>Please : &nbsp;<strong>Supply Only</strong></label>
                            <input type="checkbox" name="supply_only" value="Supply Only" @if(@$data['supply_only']) checked @endif>
                        </td>
                        <td>
                            <label><strong>Supply & Deliver</strong></label>
                            <input type="checkbox" name="supply_deliver" value="Supply & Deliver" @if(@$data['supply_deliver']) checked @endif>
                        </td>
                        <td> 
                            <label><strong>Supply & Install</strong></label>
                            <input type="checkbox" name="supply_install" value="Supply & Install" @if(@$data['supply_install']) checked @endif> 
                            <br/> the following:
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
                <strong>Important Note:</strong>In accordance with our clients requirements please provide the following documents releative to our order and your products and send to us by email (doccontrol@designrationale.co.uk) at the time of dispatch to doccontrol@designrationale.co.uk
            </div> 
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-active text-right">
                <tr>
                    <td>
                        <label>of conformance required upon order</label>
                        <input type="checkbox" checked name="certificate_of_conformance" value="Certificate of conformance required upon order" @if(@$data['certificate_of_conformance']) checked @endif>
                    </td>
                    <td> 
                        <label>CE certificates</label>
                        <input type="checkbox" name="ce_certificates" value="CE certificates" @if(@$data['ce_certificates']) checked @endif>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Welder & test certs</label>
                        <input type="checkbox" name="welder_test_certs" value="Welder & test certs" @if(@$data['welder_test_certs']) checked @endif>
                    </td>
                    <td>
                        <label>Any relevant COSHH Data required</label>
                        <input type="checkbox" name="any_relevant" value="Any relevant COSHH Data required" @if(@$data['any_relevant']) checked @endif>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>W.P.Q.R</label>
                        <input type="checkbox" name="w_p_q_r" value="W.P.Q.R" @if(@$data['w_p_q_r']) checked @endif>
                    </td>
                    <td>
                        <label>Kitemark certs</label>
                        <input type="checkbox" name="kitemark_certs" value="Kitemark certs" @if(@$data['kitemark_certs']) checked @endif>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Glass bonding certs</label>
                        <input type="checkbox" name="glass_bonding_certs" value="Glass bonding certs" @if(@$data['glass_bonding_certs']) checked @endif>
                    </td>
                    <td>
                        <label>Structural calcs</label>
                        <input type="checkbox" name="structural_calcs" value="Structural calcs" @if(@$data['structural_calcs']) checked @endif>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Galvanising certs</label>
                        <input type="checkbox" name="galvanising_certs" value="Galvanising certs" @if(@$data['galvanising_certs']) checked @endif>
                    </td>
                    <td>
                        <label>Paint certs</label>
                        <input type="checkbox" name="paint_certs" value="Paint certs" @if(@$data['paint_certs']) checked @endif>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Mill certs</label>
                        <input type="checkbox" name="mill_certs" value="Mill certs" @if(@$data['mill_certs']) checked @endif>
                    </td>
                    <td>
                        <label>Copy of shipping / delivery note</label> 
                        <input type="checkbox" name="copy_of_shipping" value="Copy of shipping / delivery note" @if(@$data['copy_of_shipping']) checked @endif>
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
            <p><b>Should this date not be met, Design Rationale reserves the right to pass on any liability to the manufacturer / supplier.</b></p>
        </div>
    </div>
</body>

</html>
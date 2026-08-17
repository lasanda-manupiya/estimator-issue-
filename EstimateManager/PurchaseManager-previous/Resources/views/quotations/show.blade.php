@extends('user::layouts.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            
            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('quotations.index') }}"><i class="fa fa-dashboard"></i> Quotations</a></li>
                    <li class="breadcrumb-item active">Quotation</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    @include('layouts.flash.alert')
    
     <div class="card-body">

<button onclick="window.location.href='{{ route('quotations.index') }}'"  type="button" class="mb-2 mr-2 btn-icon-vertical btn btn-info">
<i class="pe-7s-back btn-icon-wrapper"></i>Back
</button>

     </div>
    <!-- Default box -->
    <div class="card">
        <div class="card-header">
         <div class="btn btn-info btn-block"><b>Request for Quotation</b></div><br><br><br>
            
        </div>
        
        
        <div class="card-body">
            <table class="table table-hover table-striped table-bordered">
                <tr>
                    <th>Delivery Date:</th>
                    <td>{{ $quotation->delivery_date }}</td>
                    <th>Delivery Time:</th>
                    <td>{{ $quotation->delivery_time }}</td>
                    <th>Delivery Address:</th>
                    <td>{{ $quotation->delivery_address }}</td>
                </tr>
                <tr>
                    <th>Area:</th>
                    <td>{{ $quotation->mainActivity->area }}</td>
                    <th>Level:</th>
                    <td>{{ $quotation->mainActivity->level }}</td>
                    <th>Sub Code:</th>
                    <td>{{ $quotation->subActivity->sub_code }}</td>
                </tr>
            </table>
            <table class="table table-hover table-striped table-bordered text-center">
                <thead>
                    <tr class="table-success">
                        <th width="20%">Activity Code</th>
                        <th width="30%">Activity</th>
                        <th width="20%">Upload Files</th>
                        <th width="15%">Unit</th>
                        <th width="15%">Rate</th>
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
                                @if($material->photo !='')
                                  <a href="../../../storage/app/public/quotations/{{ $material->photo }}" height="50px" width="50px"><i class="fas fa-eye"></i></a>
                                @else
									<a href="#" height="50px" width="50px"><i class="fas fa-eye"></i></a>
                                  
                                @endif	
                            </td>
                            <td>
                                {{ $material->unit }}
                            </td>
                            <td>
                                {{ $material->rate }}
                            </td>
                            <td>
                                {{ $material->quantity }}
                            </td>  
                        </tr>
                    @endforeach
                @endif
            </table>
            <table class="table table-hover table-striped table-bordered">
                <tr>
                    <th width="15%">Note:</th>
                    <td>{{ $quotation->notes }}</td>
                </tr>
            </table>
        </div>
    </div><br><br>
    <!-- /.card -->
    @if($quotation->replyMaterials->isNotEmpty())<div class="btn btn-info btn-block"><b>Quotation received</b></div><br><br><br>
    @php
            $tabs = "";
            $panes = "";
            $i = 1;
            $allSupplierMaterials = $quotation->replyMaterials->unique('supplier_id');
            foreach($allSupplierMaterials as $supplierMaterial){
                if($i == 1){
                    $tabs .= '<li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#supplier-tab'.$i.'">
                            '.ucfirst($supplierMaterial->supplier->supplier_name).'
                        </a>
                    </li>';
                    $panes .= '<div class="tab-pane active" id="supplier-tab'.$i.'">';
                }else{
                    $tabs .= '<li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#supplier-tab'.$i.'">
                            '.ucfirst($supplierMaterial->supplier->supplier_name).'
                        </a>
                    </li>';
                    $panes .= '<div class="tab-pane" id="supplier-tab'.$i.'">';
                }
                $quotations = \Modules\PurchaseManager\Entities\QuotationReply::where('quotation_id', $supplierMaterial->quotation_id)->where('supplier_id', $supplierMaterial->supplier_id)->get();
                $materials = \Modules\PurchaseManager\Entities\QuotationReplyMaterial::where('quotation_id', $supplierMaterial->quotation_id)->where('supplier_id', $supplierMaterial->supplier_id)->get();
                $finalQuotation = $quotations->last();
                $route = route('quotations.admin.quotation.reply', $quotation->id);
                $panes .= '<form method="post" action="'.$route.'">
                        <input type="hidden" name="_token" value="'.csrf_token().'">
                        <input type="hidden" name="quotation_id" value="'.$finalQuotation->id.'">
                        <input type="hidden" name="supplier_id" value="'.$finalQuotation->supplier_id.'">';
                                  
                $panes .= '<table class="table table-hover table-striped table-bordered text-center">
                        <thead>
                            <tr class="table-info">
                                <th width="10%">Activity Code</th>
                                <th width="20%">Activity</th>
                                <th width="15%">Image</th>
                                <th width="10%">Unit</th>
                                <th width="10%">Quantity</th>
                                <th width="15%">Rate</th>
                                <th width="15%">Total</th>
                            </tr>
                        </thead>';
                        if($materials->isNotEmpty()){
                            foreach($materials as $material){
                                $panes .= '<tr>
                                    <td class="tr'.$material->id.'">
                                        <input type="hidden" name="materials['.$material->id.'][activity_id]" value="'.$material->activity_id.'" class="form-control">
                                        '.$material->activityOfMaterial->item_code.'
                                    </td>
                                    <td class="tr'.$material->id.'">
                                        <input type="hidden" name="materials['.$material->id.'][activity]" value="'.$material->activity.'" class="form-control activity">
                                        '.$material->activity.'
                                    </td>
                                    <td class="tr'.$material->id.'">
                                        <input type="hidden" name="materials['.$material->id.'][photo]" value="'.$material->photo.'" class="form-control photo">
                                        <img src="'.$material->photo.'" alt="" height="50px" width="50px">
                                    </td>
                                    <td class="tr'.$material->id.'">
                                        <input type="hidden" name="materials['.$material->id.'][unit]" value="'.$material->unit.'" class="form-control unit">
                                        '.$material->unit.'
                                    </td>
                                    <td class="tr'.$material->id.'">
                                        <input type="hidden" name="materials['.$material->id.'][quantity]" value="'.$material->quantity.'" class="form-control quantity">
                                        '.$material->quantity.'
                                    </td>
                                    <td class="tr'.$material->id.'">
                                        <input type="hidden" name="materials['.$material->id.'][rate]" value="'.$material->rate.'" class="form-control rate">
                                        &pound;'.$material->rate.'
                                    </td>
                                    <td class="tr'.$material->id.'">
                                        <input type="hidden" name="materials['.$material->id.'][total]" value="'.$material->total.'" class="form-control total">
                                        &pound;'.$material->total.'
                                    </td> 
                                </tr>';
                            }
                        }
                        $panes .= '<tr>
                            <td colspan="5">&nbsp;</td>
                            <td><strong>Carriage costs</strong></td>
                            <td colspan="2">
                                <input type="hidden" name="carriage_costs" value="'.$finalQuotation->carriage_costs.'">
                                &pound;'.($finalQuotation->carriage_costs ?? 0).'
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5">&nbsp;</td>
                            <td><strong>C of C</strong></td>
                            <td colspan="2">
                                <input type="hidden" name="c_of_c" value="'.$finalQuotation->c_of_c.'">
                                &pound;'.($finalQuotation->c_of_c ?? 0).'
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5">&nbsp;</td>
                            <td><strong>Other costs</strong></td>
                            <td colspan="2">
                                <input type="hidden" name="other_costs" value="'.$finalQuotation->other_costs.'">
                                &pound;'.($finalQuotation->other_costs ?? 0).'
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5">&nbsp;</td>
                            <td><strong>Total Value</strong></td>
                            <td colspan="2">
                                <input type="hidden" name="grand_total" value="'.($materials->sum('total') + $finalQuotation->carriage_costs + $finalQuotation->c_of_c + $finalQuotation->other_costs).'">
                                &pound;'.($materials->sum('total') + $finalQuotation->carriage_costs + $finalQuotation->c_of_c + $finalQuotation->other_costs).'
                            </td>
                        </tr>
                    </table>';
                    $panes .= '<div class="card card card-prirary cardutline direct-chat direct-chat-success">
                        <div class="card-header">
                            <h3>Conversation</h3>
                        </div>
                        <div class="card-body">';
                            
                            if($quotations->isNotEmpty())
                            {
                                $panes .= '<div class="direct-chat-messages">';
                                foreach($quotations as $reply){
                                        if($reply->sender_id == auth()->id()){
                                            $panes .= '<div class="direct-chat-msg right float-right">';
                                        }else{
                                            $panes .= '<div class="direct-chat-msg">';
                                        }
                                        
                                        $panes .= '<div  class="direct-chat-infos clearfix">
                                                    <span class="direct-chat-name float-right">'.$reply->sender->full_name.'</span>
                                                    <span class="direct-chat-timestamp float-right">'.$reply->created_at->format('d M Y g:i A').'</span>
                                                </div>';
                                        if(\Storage::disk('public')->has($reply->sender->avatar)){
                                            $panes .= '<img src="'.asset('storage/'.$reply->sender->avatar).'" alt="" class="direct-chat-img">';
                                        }else{
                                            $panes .= '<img src="'.asset('images/no-img-100x92.jpg').'" alt="" class="direct-chat-img">';
                                        }
                                        $panes .= '<div  class="direct-chat-text">'.$reply->notes.'</div>';
                                    $panes .= '</div>';
                                }
                                $panes .= '</div><br>';
                            }     
                            $panes .= '</div><br>
                        <div class="card-footer">  
                            <div class="input-group">
                                <textarea type="text" name="notes" placeholder="Type Message ..." class="form-control" required></textarea>
                                <span class="input-group-append">
                                    <button type="submit" name="action" value="reply" class="btn btn-primary btn-flat">Submit Detail</button>
                                </span>
                            </div>
                            <br/>
                            <br>
                            <div class="form-group">
                                
                            </div>
                        </div>
                          <button type="submit" name="action" value="purchase"  class="btn btn-primary btn-flat">Submit Purchase Order</button>
                    </div>
                    </form>
                </div>';
                $i++;
            }
        @endphp
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card card-primary card-outline card-outline-tabs">
                    <div class="card-header p-0 border-bottom-0">
                        <ul class="nav nav-tabs">
                            {!! $tabs !!}
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="supplier-content">
                            {!! $panes !!}
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    @endif
    
 @php /*   <div class="app-inner-layout chat-layout">
                            <div class="app-inner-layout__header text-white bg-premium-dark">
                                <div class="app-page-title">
                                    <div class="page-title-wrapper">
                                        <div class="page-title-heading">
                                            <div class="page-title-icon">
                                                <i class="pe-7s-umbrella icon-gradient bg-sunny-morning"></i>
                                            </div>
                                            <div>
                                                Conversation
                                               
                                            </div>
                                        </div>
                                      
                                    </div>
                                </div>
                            </div>
                            <div class="app-inner-layout__wrapper">
                                <div class="app-inner-layout__content card">
                                    <div class="table-responsive">
                                        
                                        <div class="chat-wrapper">
                                            <div class="chat-box-wrapper">
                                                <div>
                                                    <div class="avatar-icon-wrapper mr-1">
                                                        <div class="badge badge-bottom btn-shine badge-success badge-dot badge-dot-lg"></div>
                                                        <div class="avatar-icon avatar-icon-lg rounded">
                                                            <img src="images/avatars/2.jpg" alt="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="chat-box">
                                                        But I must explain to you how all this mistaken idea of
                                                        denouncing pleasure and praising pain was born and I will give you a complete
                                                        account of the system.
                                                    </div>
                                                    <small class="opacity-6">
                                                        <i class="fa fa-calendar-alt mr-1"></i>
                                                        11:01 AM | Yesterday
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="float-right">
                                                <div class="chat-box-wrapper chat-box-wrapper-right">
                                                    <div>
                                                        <div class="chat-box">
                                                            Expound the actual teachings of the great explorer of the
                                                        truth, the master-builder of human happiness.
                                                        </div>
                                                        <small class="opacity-6">
                                                            <i class="fa fa-calendar-alt mr-1"></i>
                                                            11:01 AM | Yesterday
                                                        </small>
                                                    </div>
                                                    <div>
                                                        <div class="avatar-icon-wrapper ml-1">
                                                            <div class="badge badge-bottom btn-shine badge-success badge-dot badge-dot-lg"></div>
                                                            <div class="avatar-icon avatar-icon-lg rounded">
                                                                <img src="images/avatars/2.jpg" alt="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="chat-box-wrapper">
                                                <div>
                                                    <div class="avatar-icon-wrapper mr-1">
                                                        <div class="badge badge-bottom btn-shine badge-success badge-dot badge-dot-lg"></div>
                                                        <div class="avatar-icon avatar-icon-lg rounded">
                                                            <img src="images/avatars/2.jpg" alt="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="chat-box">
                                                        But I must explain to you how all this mistaken idea of
                                                        denouncing pleasure and praising pain was born and I will give you a complete
                                                        account of the system.
                                                    </div>
                                                    <small class="opacity-6">
                                                        <i class="fa fa-calendar-alt mr-1"></i>
                                                        11:01 AM | Yesterday
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="float-right">
                                                <div class="chat-box-wrapper chat-box-wrapper-right">
                                                    <div>
                                                        <div class="chat-box">
                                                            Denouncing pleasure and praising pain was born and I will
                                                            give you a complete account.
                                                        </div>
                                                        <small class="opacity-6">
                                                            <i class="fa fa-calendar-alt mr-1"></i>
                                                            11:01 AM | Yesterday
                                                        </small>
                                                    </div>
                                                    <div>
                                                        <div class="avatar-icon-wrapper ml-1">
                                                            <div class="badge badge-bottom btn-shine badge-success badge-dot badge-dot-lg"></div>
                                                            <div class="avatar-icon avatar-icon-lg rounded">
                                                                <img src="images/avatars/3.jpg" alt="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="chat-box-wrapper">
                                                <div>
                                                    <div class="avatar-icon-wrapper mr-1">
                                                        <div class="badge badge-bottom btn-shine badge-success badge-dot badge-dot-lg"></div>
                                                        <div class="avatar-icon avatar-icon-lg rounded">
                                                            <img src="images/avatars/2.jpg" alt="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="chat-box">
                                                        Born and I will give you a complete account of the system.
                                                    </div>
                                                    <small class="opacity-6">
                                                        <i class="fa fa-calendar-alt mr-1"></i>
                                                        11:01 AM | Yesterday
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="float-right">
                                                <div class="chat-box-wrapper chat-box-wrapper-right">
                                                    <div>
                                                        <div class="chat-box">The master-builder of human happiness.</div>
                                                        <small class="opacity-6">
                                                            <i class="fa fa-calendar-alt mr-1"></i>
                                                            11:01 AM | Yesterday
                                                        </small>
                                                    </div>
                                                    <div>
                                                        <div class="avatar-icon-wrapper ml-1">
                                                            <div class="badge badge-bottom btn-shine badge-success badge-dot badge-dot-lg"></div>
                                                            <div class="avatar-icon avatar-icon-lg rounded">
                                                                <img src="images/avatars/3.jpg" alt="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="chat-box-wrapper">
                                                <div>
                                                    <div class="avatar-icon-wrapper mr-1">
                                                        <div class="badge badge-bottom btn-shine badge-success badge-dot badge-dot-lg"></div>
                                                        <div class="avatar-icon avatar-icon-lg rounded">
                                                            <img src="images/avatars/2.jpg" alt="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="chat-box">
                                                        Mistaken idea of denouncing pleasure and praising pain was
                                                        born and I will give you
                                                    </div>
                                                    <small class="opacity-6">
                                                        <i class="fa fa-calendar-alt mr-1"></i>
                                                        11:01 AM | Yesterday
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="app-inner-layout__bottom-pane d-block text-center">
                                            <div class="mb-0 position-relative row form-group">
                                                <div class="col-sm-12">
                                                    <input placeholder="Write here and hit enter to send..." type="text" class="form-control-lg form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                               
                            </div>
                        </div>  */ @endphp
    
    
</section>

@stop
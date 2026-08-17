@extends('user::layouts.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
    
      <div class="col-sm-12">
        <ol class="breadcrumb float-sm-right">

          <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{route('quotations.index')}}"><i class="fa fa-dashboard"></i> Quotations</a></li>
          <li class="breadcrumb-item active">Send Quotation</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
  <!-- Default box -->
  
    <div class="card-body">

<button onclick="window.location.href='{{ route('quotations.index') }}'"  type="button" class="mb-2 mr-2 btn-icon-vertical btn btn-info">
<i class="pe-7s-back btn-icon-wrapper"></i>Back
</button>

     </div>
  <div class="card">
    {{ Form::open(['route' => ['quotations.send.quotation.to.supplier', $quotation->id], 'method' => 'post']) }}
    <div class="card-header">
      <h3 class="card-title">Send Quotation</h3>
     
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group @if($errors->has('supplier_ids.*')) has-error @endif">
                    {{ Form::label('supplier_ids', 'Suppliers') }}<span class="asterisk">*</span>
                    {{ Form::select('supplier_ids[]', $suppliers, '', [
                        'class' => "form-control multiselect-dropdown supplier_ids",
                        'id' => "supplier_ids",
                        'data-live-search'=>'true',
                        
                        'multiple'=> 'multiple','required'
                    ]) }}
                    @if($errors->has('supplier_ids.*'))
                    <span style="color:red" class="error">{{ $errors->first('supplier_ids.*') }}</span>
                    @endif
                </div>
            </div>
        </div>
        <table class="table table-hover table-striped table-bordered ">
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
        <table class="table table-hover table-striped table-bordered  text-center">
            <thead>
                <tr class="table-warning">
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
      <div class="card-footer">
          {{ Form::submit('Send quotation', [ 'class' => "btn btn-primary btn-flat" ]) }}
      </div>
      {{ Form::close() }}
    </div>
    <!-- /.card -->

</section>

@stop
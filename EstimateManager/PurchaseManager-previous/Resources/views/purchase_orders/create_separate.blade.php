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
          <li class="breadcrumb-item"><a href="{{ route('purchase.orders.index') }}">Purchase Order</a></li>
          <li class="breadcrumb-item active">Create Separate Purchase Order</li>
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
        {{ Form::open(['route' => ['purchase.orders.store.separate'], 'method' => 'post', 'enctype'=> 'multipart/form-data']) }}
        <div class="card-header">
            <h3 class="card-title">Create Separate Purchase Order</h3><hr>
           
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group @if($errors->has('project_id')) has-error @endif">
                        {{ Form::label('project_id', 'Project') }}<span class="asterisk">*</span>
                        {{ Form::select('project_id', $projects, auth()->user()->default_project, [
                            'class' => "form-control multiselect-dropdown projects",
                            'id' => "projects",
                            'data-live-search'=>'true',
                            'onchange'=> 'getAreas(this.value)'
                        ]) }}
                        @if($errors->has('project_id'))
                            <span class="invalid-feedback">{{ $errors->first('project_id') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group @if($errors->has('area')) has-error @endif">
                        {{ Form::label('area', 'Area') }}<span class="asterisk">*</span>
                        {{ Form::select('area', [''=>'Select area'], '', [
                            'class' => "form-control multiselect-dropdown areas",
                            'id' => "areas",
                            'data-live-search'=>'true',
                            'onchange'=> 'getLevels(this.value)'
                        ]) }}
                        @if($errors->has('area'))
                            <span class="invalid-feedback">{{ $errors->first('area') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group @if($errors->has('level')) has-error @endif">
                        {{ Form::label('level', 'Level') }}<span class="asterisk">*</span>
                        {{ Form::select('level', [''=>'Select level'], old('level'), [
                            'class' => "form-control multiselect-dropdown levels",
                            'id' => "levels",
                            'data-live-search'=>'true',
                            'onchange'=> 'getSubCodes(this.value)'
                        ]) }}
                        @if($errors->has('level'))
                            <span class="invalid-feedback">{{ $errors->first('level') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group @if($errors->has('sub_code')) has-error @endif">
                        {{ Form::label('sub_code', 'Sub code') }}<span class="asterisk">*</span>
                        {{ Form::select('sub_code', [''=>'Select sub code'], old('sub_code'), [
                            'class' => "form-control multiselect-dropdown sub_code",
                            'id' => "sub_code",
                            'data-live-search'=>'true',
                            'onchange'=> 'prepareTimesheetForm(this.value)'
                        ]) }}
                        @if($errors->has('sub_code'))
                            <span class="invalid-feedback">{{ $errors->first('sub_code') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 purchase-form-wrapper">

                </div>
            </div>
        </div>
        <div class="card-footer">
            {{ Form::submit('Save purchase order', [ 'class' => "btn btn-primary btn-flat" ]) }}
        </div>
        {{ Form::close() }}
    </div>
</section>
@stop
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function(){

            var projectId = $(document).find('.projects').val();
            var mainActivityId = $(document).find('.areas').val();
            var subActivityId = $(document).find('.sub_code').val();

            if(projectId){
                getAreas(projectId);
            }
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

            $(document).on('keyup', '.quantity, .rate, .carriage_costs, .c_of_c, .other_costs', function() { 
                var tr = $(this).parent().parent();  
                var index = $(this).attr('data-id');
                var quantity = $('#quantity'+index).val(); 
                var rate = $('#rate'+index).val();
                var total = $('#total'+index).val();
                
                var carriage_costs = $('#carriage_costs'+index).val(); 
                var c_of_c = $('#c_of_c'+index).val();
                var other_costs = $('#other_costs'+index).val();

                var grand_total = 0;	
                
                quantity = parseFloat(quantity).toFixed(4);
                rate = parseFloat(rate).toFixed(4);
                total = parseFloat(total).toFixed(4);
                
                carriage_costs = parseFloat(carriage_costs).toFixed(4);
                c_of_c = parseFloat(c_of_c).toFixed(4);
                other_costs = parseFloat(other_costs).toFixed(4);
                total = quantity * rate; 
                grand_total = total;
                grand_total = +grand_total + +carriage_costs + +c_of_c + +other_costs;
                total = parseFloat(total).toFixed(4);
                $('#total'+index).val(total);	
                grand_total = parseFloat(grand_total).toFixed(4);
                $('#grand_total'+index).val(grand_total); 
            }); 
        });

        function getAreas(projectId){
            var html = "<option value=''>Select area</option>";
            var route = "{{ route('ajax.areas.list') }}";
            route +="?project_id="+projectId;
            $.get(route, function(data){
                for (var key of Object.keys(data)) {
                    var mainId = "{{ old('area') }}";
                    if(mainId == key){
                        html = html+"<option value='"+key+"' selected>"+data[key]+"</option>";
                    }else{
                        html = html+"<option value='"+key+"'>"+data[key]+"</option>";
                    }
                }
                $(document).find('.areas').html(html);
                $(document).find('.areas').trigger('change');
            });
        }
        function getLevels(mainActivityId){

            projectId = $(document).find('.projects').val();

            var html = "<option value=''>Select level</option>";
            var route = "{{ route('ajax.levels.list') }}";
            route +="?project_id="+projectId+"&main_activity_id="+mainActivityId;
            $.get(route, function(data){
                for (var key of Object.keys(data)) {
                    var mainId = "{{ old('area') }}";
                    if(mainId == key){
                        html = html+"<option value='"+key+"' selected>"+data[key]+"</option>";
                    }else{
                        html = html+"<option value='"+key+"' selected>"+data[key]+"</option>";
                    }
                }
                $(document).find('.levels').html(html);
                $(document).find('.levels').trigger('change');
            });
        }

        function getSubCodes(mainActivityId){
            var html = "<option value=''>Select sub code</option>";
            var route = "{{ route('ajax.sub.codes.list') }}";
            route +="?main_activity_id="+mainActivityId;
            $.get(route, function(data){
                if(Object.keys(data).length ==1){
                    for (var key of Object.keys(data)) {
                        var subId = "{{ old('sub_code') }}";
                        if(subId == key){
                            html = html+"<option value='"+key+"' selected>"+data[key]+"</option>";
                        }else{
                            html = html+"<option value='"+key+"' selected>"+data[key]+"</option>";
                            
                        }
                    }
                }else{
                    for (var key of Object.keys(data)) {
                        var subId = "{{ old('sub_code') }}";
                        if(subId == key){
                            html = html+"<option value='"+key+"' selected>"+data[key]+"</option>";
                        }else{
                            html = html+"<option value='"+key+"'>"+data[key]+"</option>";
                            
                        }
                    }
                    
                }
                /*for (var key of Object.keys(data)) {
                    var subId = "{{ old('sub_code') }}";
                    if(subId == key){
                        html = html+"<option value='"+key+"' selected>"+data[key]+"</option>";
                    }else{
                        html = html+"<option value='"+key+"'>"+data[key]+"</option>";
                    }
                }*/
                $(document).find('.sub_code').html(html);
                $(document).find('.sub_code').trigger('change');
            });
        }

        function prepareTimesheetForm(subActivityId){
            var route = "{{ route('ajax.purchase.orders.separate.purchase.order.form') }}";
            route +="?sub_activity_id="+subActivityId+"&project_id="+projectId;;
            $.get(route, function(data){
                $(document).find('.purchase-form-wrapper').html(data.html);
            });
        }
    
        function expand(id){
            var s = $('#expand'+id).html();
            if(s == "More"){
                $('.expandable_row'+id).show();
                $('#expand'+id).html("Less");
            }
            if(s == "Less"){
                $('.expandable_row'+id).hide();
                $('#expand'+id).html("More");
            }
        }
        function isNumber(evt) {
            var iKeyCode = (evt.which) ? evt.which : evt.keyCode
            if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
                return false;

            return true;
        }
    </script>
@endpush
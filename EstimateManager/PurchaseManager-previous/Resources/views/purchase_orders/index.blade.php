@extends('user::layouts.masterlist')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
               
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Dashboard</a>
                        </li>                 
                        <li class="breadcrumb-item active">Purchase Orders</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        @include('layouts.flash.alert')
        
        <div class="card-body">


            
             @if (auth()->user()->can('access', 'purchase orders add') || (auth()->user()->isRole('Super Admin') ))
                            <!--@if (auth()->user()->can('access', 'purchase orders add') && (auth()->user()->isRole('Super Admin') || auth()->user()->isRole('Admin'))) @endif--> 
            <a class="mb-2 mr-2 btn-icon-vertical btn btn-success" href="{{ route('purchase.orders.create.separate') }}"><i class="pe-7s-plus btn-icon-wrapper"></i>Create Separate Purchase Order</a>
            <a class="mb-2 mr-2 btn-icon-vertical btn btn-success" href="{{ route('purchase.orders.create') }}"><i class="pe-7s-plus btn-icon-wrapper"></i>Create Purchase Order</a>
        @endif

     </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Purchase Orders</h3>
                        
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    {{ Form::label('project_filter_id', 'Project') }}
                                    {{ Form::select('project_filter_id', $projects, auth()->user()->default_project, [
                                        'class' => "form-control multiselect-dropdown",
                                        'id' => "project_filter",
                                        'data-live-search'=>'true',
                                    ]) }}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    {{ Form::label('delivery_date_filter', 'Delivery Date') }}
                                    {{ Form::date('delivery_date_filter',old('delivery_date_filter'), [
                                        'class' => "form-control delivery_date_filter",
                                        'id' => "delivery_date_filter",
                                       
                                    ]) }}
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                        <table  class="table table-hover table-striped table-bordered" id="purchase-orders-datatable" data-table="purchase-orders">
                            <thead>
                                <tr>
                                    <th width="15%" class="no-sort">Supplier</th>
                                    <th width="15%" class="no-sort">Revision</th>
                                    <th width="10%">Purchase No.</th>
                                    <th width="15%">Amount</th>
                                    <th width="15%">Purchase Date</th>
                                    <th width="15%">Delivery Date</th>
                                   <!-- <th width="10%">Inv No</th>
                                    <th width="10%">Inv Amouont</th>-->
                                    <!--<th width="10%">Inv File</th>-->
                                    <th class="project-actions no-sort">Actions</th>
                                </tr>
                            </thead>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var t = $('#purchase-orders-datatable').DataTable({
                dom: 'Bfrtip',
                "paging": true,
                "autoWidth": false,
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "searchable": true,
                "pageLength": 25,
                "order": [[0, 'asc']],
                ajax: {
                    url: "{{ route('ajax.purchase.orders.list.all') }}",
                    type: 'GET',
                    data: function(d) {
                        d.project_filter_id = $('#project_filter').val();
                        d.delivery_date = $('#delivery_date_filter').val();
                    }
                },
                columns: [
                    {data: 'supplier', name: 'supplier'},
                    {data: 'revision_no', name: 'revision_no'},
                    {data: 'purchase_no', name: 'purchase_no'},
                    {data: 'grand_total', name: 'grand_total'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'delivery_date', name: 'delivery_date'},
                    /*{data: 'invoice_no', name: 'invoice_no'},
                    {data: 'invoice_amount', name: 'invoice_amount'},*/
                    /*{data: 'invoice_file', name: 'invoice_file',
                        
                        render: function( data, type, full, meta ) {
                            return "<img src=\"/path/" + data + "\" height=\"50\"/>";
                        }
                    },*/
                    {data: 'action', name: 'action'}
                ],
                "deferRender": true,
                'columnDefs': [{
                    "targets": 'no-sort',
                    "orderable": false,
                }]
            });
            $('#project_filter, #delivery_date_filter').on('change', function() {
                $('#purchase-orders-datatable').DataTable().draw(true);
            });
        });
    </script>
@endpush
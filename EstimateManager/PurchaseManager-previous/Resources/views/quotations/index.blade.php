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
                        <li class="breadcrumb-item active">Quotations</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        @include('layouts.flash.alert')
         @if (auth()->user()->can('access', 'purchase orders add') )
        <div class="card-body">

<button onclick="window.location.href='{{ route('quotations.create') }}'"  type="button" class="mb-2 mr-2 btn-icon-vertical btn btn-success">
<i class="pe-7s-plus btn-icon-wrapper"></i>Create Quotation
</button>

     </div>
         
         @endif
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Quotations</h3>
                        
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
                        </div>
                        <table  class="table table-bordered table-hover" id="quotations-datatable" data-table="quotations">
                            <thead>
                                <tr>
                                    <th>Originator</th>
                                    <th>RFQ no</th>
                                    <th>Notes</th>
                                    <th>Delivery Date</th>
                                    <th>Delivery Address</th>
                                    <th class="project-actions no-sort">Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            var t = $('#quotations-datatable').DataTable({
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
                    url: "{{ route('ajax.quotations.list.all') }}",
                    type: 'GET',
                    data: function(d) {
                        d.project_filter_id = $('#project_filter').val();
                    }
                },
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'rfq', name: 'rfq'},
                    {data: 'notes', name: 'notes'},
                    {data: 'delivery_date', name: 'delivery_date'},
                    {data: 'delivery_address', name: 'delivery_address'},
                    {data: 'action', name: 'action'}
                ],
                "deferRender": true,
                'columnDefs': [{
                    "targets": 'no-sort',
                    "orderable": false,
                }]
            });
            $('#project_filter').on('change', function() {
                $('#quotations-datatable').DataTable().draw(true);
            });
        });
    </script>
@endpush
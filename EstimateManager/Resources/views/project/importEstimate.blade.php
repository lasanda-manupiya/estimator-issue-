@extends('user::layouts.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Import Estimates</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">

          <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
          <li class="breadcrumb-item active">Import Estimate</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
  <!-- Default box -->
  <div class="card">
    <div class="card-header">
        <h3 class="card-title">Import Estimate</h3><hr>
        <div class="card-tools">
            <a class="btn btn-success btn-sm" href="{{ asset('uploads/samples/estimate_import.xls') }}" title="Download Estimate Sample Excel">
                Download Excel Sample
            </a>
        </div>
    </div>
    <div class="card-body">
        @include('layouts.flash.alert')
        <form action="{{ route('estimates.projects.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-4 col-sm-12">
                    <div class="form-group @if($errors->has('project_id')) has-error @endif">
                        {{ Form::label('project_id', 'Project') }}<span class="asterisk">*</span>
                        {{ Form::select('project_id', $allProjects, old('project_id'), [
                            'class' => "form-control multiselect-dropdown",
                            'id' => "project_id",'required',
                            'data-live-search'=>'true',
                        ]) }}
                        @if($errors->has('project_id'))
                            <span style="color:red" class="error">{{ $errors->first('project_id') }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="form-group @if($errors->has('file')) has-error @endif">
                        {{ Form::label('file', 'File') }}<span class="asterisk">*</span>
                        {{ Form::file('file', [
                            'class' => "form-control",
                            'id' => "file",'required',
                        ]) }}
                        @if($errors->has('file'))
                            <span style="color:red" class="error">{{ $errors->first('file') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {{Form::submit('Submit', [ 'class' => "btn btn-primary btn-flat" ])}}
                    </div>
                </div>
            </div>
        </form>
    </div>
  </div>
</section>

@stop
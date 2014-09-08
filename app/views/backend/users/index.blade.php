@extends('backend._template')

@section('title')All Users @stop

@section('page-css')
    <style>
        table {
            font-size: 13px;
        }
        .more-options {
            /*margin-top: 5px;*/
        }
        .visibility {
            visibility: hidden;
        }
        a.red {
            color: #D54E21;
        }
        a:hover {
            color: #D54E21;
            text-decoration: none;
        }
        .page-options {
            margin: 10px 0;
        }
        .opacity {
            opacity: 0.3;
        }
    </style>
@stop

@section('page-title') <h3><i class="fa fa-group"></i> Users</h3> @stop

@section('page')
    <div class="col-lg-12">
        <div class="box info">
            <header>
                <div class="icons">
                    <i class="fa fa-flag-o"></i>
                </div>
                <h5>All Users</h5>
                <div class="toolbar">
                    <a class="btn btn-default btn-sm btn-flat" href="{{URL::to('dashboard/users/create')}}"><span class="fa fa-pencil"></span> New User</a>
                </div>
            </header>
        </div><!-- /.box -->
    </div> 

    <div class="col-md-4 optionsDiv opacity">
        {{ Form::open(['url' => '#', 'id' => 'bulk-options-form']) }}
        <div class="row">
            <div class="col-sm-6 col-md-5">
                <div class="form-group">
                    <select name="bulk-options" id="bulk-options" class="form-control" disabled="disabled">
                        <option value=''>Select Option</option>
                        <option value='1'>Delete Permanently</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="hidden" id="bulkDeleteUrl" value = "{{ URL::to('dashboard/users/bulk-destroy') }}" />
                    <div class="appendTarget"></div>
                </div>                
            </div>
            <div class="col-sm-6 col-md-4">                     
                <div class="form-group">
                    <button type="submit" class="btn btn-default btn-rect" id="bulk-submit" disabled="disabled">Submit</button> 
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
    <div class="col-md-4">
        {{ Form::open(['url' => URL::to('dashboard/users/set-records-per-page'), 'id' => 'set-records-form', 'class' => 'form-inline']) }}
            <div class="form-group">
                {{ Form::label('number', 'Records Per Page') }} 
                <select name="number" id="number" class="form-control">
                    <option value='10'>Select</option>
                    <option value='10' {{ $records == 10 ? 'selected' : '' }}>10</option>
                    <option value='20' {{ $records == 20 ? 'selected' : '' }}>20</option>
                    <option value='50' {{ $records == 50 ? 'selected' : '' }}>40</option>
                    <option value='100' {{ $records == 100 ? 'selected' : '' }}>100</option>
                </select>
            </div>               
        {{ Form::close() }}
    </div>
    <div class="col-md-4">
        {{ Form::open(['url' => URL::to('dashboard/users/set-order-by'), 'id' => 'set-order-by-form', 'class' => 'form-inline']) }}
            <div class="form-group">
                {{ Form::label('order-by', 'Sort') }} 
                <select name="order-by" id="order-by" class="form-control">
                    <option value='1'>Select</option>
                    <option value='1' {{ $orderBy == 1 ? 'selected' : '' }}>Oldest First</option>
                    <option value='2' {{ $orderBy == 2 ? 'selected' : '' }}>Latest First</option>
                    <option value='3' {{ $orderBy == 3 ? 'selected' : '' }}>A-Z (Name)</option>
                    <option value='4' {{ $orderBy == 4 ? 'selected' : '' }}>Z-A (Name)</option>
                </select>
            </div>               
        {{ Form::close() }}
    </div>    

    <div class="col-md-12"> 
        @if(Session::has('success'))
            <div class="alert alert-dismissable alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ Session::get('success') }}
            </div>
        @endif 
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th><input type='checkbox' id="checkAll" name='allposts'></th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Emails Sent</th>
                        <th>Created</th>
                        <th>Last Login</th>
                    </tr>
                </thead>
                <tbody>
                   {{ $usersHtml }}
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-md-12">
        {{ $links }}
    </div>
@stop

@section('page-js')
    <script>
        jQuery(document).ready(function($) {
            $('select#number').change(function(event) {
                $('form#set-records-form').submit();
            });

            //Order By
            $('select#order-by').change(function(event) {
                $('form#set-order-by-form').submit();
            });            
        });
    
        function unHideOptions() {
            $('.optionsDiv').removeClass('opacity');       
            $('#bulk-options').removeAttr('disabled');
            var html = '';
            $(':checkbox.acheckbox:checked').each(function() {
                html += "<input type='checkbox' name='users[]' value='" + $(this).val() + "' class='hidden' checked='checked'>";
            }); 
            $('#bulk-options-form .form-group .appendTarget').html(html);               
        }            

        function handleOption(option) {
            switch(option) {
                case "" :
                    $('#bulk-options-form').attr('action', '#');
                    $('#bulk-submit').attr('disabled', 'disabled').removeClass().addClass('btn btn-default btn-rect').text('Submit');
                    break;
                case "1" :
                    $('#bulk-options-form').attr( 'action', $('#bulkDeleteUrl').val() ); 
                    $('#bulk-submit').removeAttr('disabled').removeClass().addClass('btn btn-default btn-rect btn-metis-1').text('Permanently Delete ' + $(':checkbox.acheckbox:checked').size());
                    break;
            }                                
        }            
    </script>
    {{ HTML::script('assets/js/back.js') }}
@stop
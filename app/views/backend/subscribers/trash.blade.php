@extends('backend._template')

@section('title')Deleted Subscribers @stop

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

@section('page-title') <h3><i class="fa fa-rss"></i> Subscribers</h3> @stop

@section('page')
    <div class="col-lg-12">
        <div class="box info">
            <header>
                <div class="icons">
                    <i class="fa fa-flag-o"></i>
                </div>
                <h5>Deleted Subscribers</h5>
                <div class="toolbar">
                    <a class="btn btn-default btn-sm btn-flat" href="{{ URL::to('dashboard/subscribers/create') }}"><span class="fa fa-pencil"></span> New Subscriber</a>
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
                        <option value='1'>Restore</option>
                        <option value='2'>Delete Permanently</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="hidden" id="bulkRestoreUrl" value = "{{ URL::to('dashboard/subscribers/bulk-restore') }}" />
                    <input type="hidden" id="bulkDestroyUrl" value = "{{ URL::to('dashboard/subscribers/bulk-destroy') }}" />
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
    <div class="col-md-4 hidden-xs hidden-sm">
        {{ Form::open(['url' => URL::to('dashboard/subscribers/set-records-per-page'), 'id' => 'set-records-form', 'class' => 'form-inline']) }}
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
    <div class="col-md-4 hidden-xs hidden-sm">
        {{ Form::open(['url' => URL::to('dashboard/subscribers/set-order-by'), 'id' => 'set-order-by-form', 'class' => 'form-inline']) }}
            <div class="form-group">
                {{ Form::label('order-by', 'Sort') }} 
                <select name="order-by" id="order-by" class="form-control">
                    <option value='1'>Select</option>
                    <option value='1' {{ $orderBy == 1 ? 'selected' : '' }}>Oldest First</option>
                    <option value='2' {{ $orderBy == 2 ? 'selected' : '' }}>Latest First</option>
                    <option value='3' {{ $orderBy == 3 ? 'selected' : '' }}>A-Z (First Name)</option>
                    <option value='4' {{ $orderBy == 4 ? 'selected' : '' }}>Z-A (First Name)</option>
                    <option value='5' {{ $orderBy == 5 ? 'selected' : '' }}>A-Z (Last Name)</option>
                    <option value='6' {{ $orderBy == 6 ? 'selected' : '' }}>Z-A (Last Name)</option>
                    <option value='7' {{ $orderBy == 7 ? 'selected' : '' }}>A-Z (Email)</option>
                    <option value='8' {{ $orderBy == 8 ? 'selected' : '' }}>Z-A (Email)</option>                                        
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
        @if(Session::has('issues'))
            <div class="alert alert-dismissable alert-warning">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ Session::get('issues') }}
            </div>
        @endif         
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th><input type='checkbox' id="checkAll" name='allposts'></th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Active</th>
                        <th>Lists</th>
                        <th>Created</th>
                        <th>Updated</th>
                    </tr>
                </thead>
                <tbody>
                   {{ $subscribersHtml }}
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

            //Records per page
            $('select#number').change(function(event) {
                $('form#set-records-form').submit();
            });

            //Order By
            $('select#order-by').change(function(event) {
                $('form#set-order-by-form').submit();
            });

            //Chekboxes
            unCheckAll(); //reset
            $(':checkbox#checkAll').prop('checked', false); //reset

            $(':checkbox#checkAll').click(function(event) {
                if ( this.checked ) { checkAll(); }
                else { unCheckAll(); }
            });

            $(':checkbox.acheckbox').click(function(event) {
                toggleOptions();
            });

            $('#bulk-options').change(function(event) {
                handleOption( $(this).val() );
            });

            function checkAll() {
                $(':checkbox.acheckbox').each(function(event) {
                    this.checked = true;
                });

                toggleOptions();
            }

            function unCheckAll() {
                $(':checkbox.acheckbox').each(function(event) {
                    this.checked = false;
                });

                toggleOptions();
            }

            function toggleOptions() {
                if ( $(':checkbox.acheckbox:checked').size() > 0 ) { unHideOptions(); }
                else { hideOptions(); }
            }

            function hideOptions() {
                $('.optionsDiv').addClass('opacity'); 
                $('#bulk-options-form .form-group .appendTarget').html('');
                $('#bulk-options').attr('disabled', 'disabled').val('');
                $('#bulk-submit').attr('disabled', 'disabled').removeClass().addClass('btn btn-default btn-rect').text('Submit'); 
                $('#bulk-options-form').attr('action', '#');               
            }

            function unHideOptions() {
                $('.optionsDiv').removeClass('opacity');       
                $('#bulk-options').removeAttr('disabled');
                var html = '';
                $(':checkbox.acheckbox:checked').each(function() {
                    html += "<input type='checkbox' name='subscribers[]' value='" + $(this).val() + "' class='hidden' checked='checked'>";
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
                        $('#bulk-options-form').attr( 'action', $('#bulkRestoreUrl').val() ); 
                        $('#bulk-submit').removeAttr('disabled').removeClass().addClass('btn btn-default btn-rect btn-metis-5').text('Restore ' + $(':checkbox.acheckbox:checked').size());
                        break;
                    case "2" :
                        $('#bulk-options-form').attr( 'action', $('#bulkDestroyUrl').val() ); 
                        $('#bulk-submit').removeAttr('disabled').removeClass().addClass('btn btn-default btn-rect btn-metis-1').text('Permanently Delete ' + $(':checkbox.acheckbox:checked').size());
                        break;
                }                                
            }


        });
    </script>
@stop
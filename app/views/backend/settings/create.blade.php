<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="Configure new site" name="description">
        <meta content="James Ilaki" name="author">
        <title>Nifty Newsletter</title>
        <link href="{{asset('favicon.png')}}" rel="shortcut icon">
        <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
        <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css">
        {{ HTML::style('assets/template/css/main.css') }}
        {{ HTML::style('assets/template/lib/magic/magic.css') }}
        <!--[if lt IE 9]>
            {{ HTML::script('assets/bootstrap/js/html5shiv.js') }}
            {{ HTML::script('assets/bootstrap/js/respond.min.js') }}
        <![endif]-->
        <style>
            .login .form-signin #last_name, .login .form-signin #password {
              margin-bottom: -1px;
              border-radius: 0;
            }

            .login .form-signin #sitename {
              border-radius: 4px;
              margin-bottom: 10px;
            }

            .alert.alert-dismissable {
                display: none;
            }

        </style>        
    </head>
    <body class="login">
        <div class="container">
            <div class="text-center">
                <img src="{{asset('assets/template/img/logo.png')}}" alt="Nifty">
            </div>
            <div class="tab-content">
                <div id="login" class="tab-pane active">
                    {{ Form::open(['url' => 'setup', 'class' => 'form-signin', 'id' => 'setupForm']) }}
                        <p class="text-muted text-center">Please set up your newsletter application</p>
                        <div class="alert alert-dismissable">
                            <div class="alert-feedback"> </div>
                        </div>   
                        {{ Form::text('first_name', Input::old('first_name'), ['id' => 'first_name', 'class' => 'form-control', 'placeholder' => 'First Name', 'required' => 'required']) }}
                        {{ Form::text('last_name', Input::old('last_name'), ['id' => 'last_name', 'class' => 'form-control', 'placeholder' => 'Last Name', 'required' => 'required']) }}         
                        {{ Form::email('email', Input::old('email'), ['id' => 'email', 'class' => 'form-control', 'placeholder' => 'Email', 'required' => 'required']) }}
                        {{ Form::password('password', ['id' => 'password', 'class' => 'form-control', 'placeholder' => 'Password', 'required' => 'required']) }}
                        {{ Form::password('password_confirmation', ['id' => 'password_confirmation', 'class' => 'form-control', 'placeholder' => 'Password Confirmation', 'required' => 'required']) }}
                        {{ Form::text('sitename', Input::old('sitename'), ['id' => 'sitename', 'class' => 'form-control', 'placeholder' => 'Site Name', 'required' => 'required']) }}
                        <button class="btn btn-lg btn-block btn-metis-6 btn-rect btn-grad" type="submit">Submit</button>
                    {{ Form::close() }} 
                </div>
            </div>
        </div><!-- /container -->
        {{ HTML::script('assets/js/jQuery-1.10.2.min.js') }}
        {{ HTML::script('assets/bootstrap/js/bootstrap.min.js') }}
        <script>
            jQuery(document).ready(function($) {             
                $('form#setupForm').submit(function(e)
                {
                    e.preventDefault(); 
                    $this = $(this);
                    resetConsole();

                    var action = $this.attr('action');
                    var token = $('#setupForm input[name="_token"]').val(); 
                    var first_name = $.trim( $('#first_name').val() );
                    var last_name = $.trim( $('#last_name').val() );
                    var email = $.trim( $('#email').val() );
                    var password = $('#password').val();
                    var password_confirmation = $('#password_confirmation').val();
                    var sitename = $.trim( $('#sitename').val() );

                    if ( !password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/) || !password.match(/([!,%,&,@,#,$,^,*,?,_,~])/) )
                    {
                        showErrors('<i class="fa fa-times"></i> Password must contain an uppercase letter and a special character.');             
                    }  

                    else if ( password != password_confirmation )
                    {
                        showErrors('<i class="fa fa-times"></i> Passsword confirmation does not match.');
                    }
                    
                    else 
                    { 
                        $.post(action, {_token:token, first_name:first_name, last_name:last_name, email:email, password:password, password_confirmation:password_confirmation, sitename:sitename}, function(data)
                        {
                            if (data['success'] !=undefined)
                            {
                                showSuccess('<i class="fa fa-check"></i> ' + data['success']);

                                setTimeout(function()
                                {
                                    window.location.replace(data['url']);
                                }, 3000);
                            }   

                            var messages = '';

                            if(data['first_name'] != undefined)
                                messages += '<i class="fa fa-times"></i> ' + data['first_name'] + '<br />';
                            if(data['last_name'] != undefined)
                                messages +='<i class="fa fa-times"></i> ' +  data['last_name'] + '<br />';
                            if(data['email'] != undefined)
                                messages += '<i class="fa fa-times"></i> ' + data['email'] + '<br />';                            
                            if(data['password'] != undefined)
                                messages += '<i class="fa fa-times"></i> ' + data['password'] + '<br />';
                            if(data['sitename'] != undefined)
                                messages += '<i class="fa fa-times"></i> ' + data['sitename'];

                            if(messages.length > 0)
                            {
                                showErrors(messages);               
                            }

                        }, 'json'); 
                    }  

                    function resetConsole()
                    {
                        $('.login .alert').slideUp('slow');   
                        $('.login .alert').removeClass('alert-success').removeClass('alert-danger');
                        $('.alert-feedback').html('');                                           
                    }

                    function showErrors(html) 
                    {
                        $('.alert-feedback').html( html ); 
                        $('.login .alert').removeClass('alert-success').addClass('alert-danger');
                        $('.login .alert').slideDown('slow');               
                    }   

                    function showSuccess(html) 
                    {
                        $('.alert-feedback').html( html );
                        $('.login .alert').removeClass('alert-danger').addClass('alert-success'); 
                        $('.login .alert').slideDown('slow');
                    }              

                  
                });                          
            })            
        </script>    
    </body>
</html>
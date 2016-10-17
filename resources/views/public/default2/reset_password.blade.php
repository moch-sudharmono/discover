@extends('public/default2/_layouts._layout')
@section('content')
  <div class="container forget">
  	<div class="col-md-offset-2 col-md-8 col-xs-12">
		<div style="" id="show-forgot" class="col-md-12 col-sm-12 col-xs-12 forget-bg pad-s">
    @if ($errors->any())
        <div class="alert alert-error alert-danger">
            <button class="close" data-dismiss="alert">×</button>
            <strong>Error!</strong><br> {!! implode('<br>', $errors->all()) !!}
        </div>
    @endif

    @if ($errors->has('invalid_reset_code'))
        <!-- BEGIN FORGOT PASSWORD FORM -->
        {!! Form::open(array('url'=>'forgot_password', 'method'=>'POST', 'class'=>'form-vertical no-padding no-margin')) !!}
            <p class="center">Enter your e-mail address below to receive the reset code.</p>
            <div class="control-group">
                <div class="controls">
                    <div class="input-prepend">
                        <span class="add-on"><i class="icon-envelope"></i></span>
                        <input id="input-email" type="text" placeholder="Email" name="email" />
                    </div>
                </div>
                <div class="space10"></div>
            </div>
            <input type="submit" id="forget-btn" class="btn btn-block btn-inverse" value="Submit" />
        {!! Form::close() !!}
        <!-- END FORGOT PASSWORD FORM -->
    @else
        {!! Form::open(array('url'=>'reset_password', 'method'=>'POST', 'class'=>'form-vertical no-padding no-margin')) !!}
            {!! Form::hidden('id', $id) !!}
            {!! Form::hidden('token', $token) !!}
            {!! Form::hidden('target', $target) !!}

            <p><h4>Password Reset Form</h4></p>
<div class="form-group col-lg-12 col-sm-12 col-xs-12">
           <label class="col-lg-5 col-sm-5 col-xs-12 row">Enter your email address</label>
            <div class="col-lg-6 col-sm-7 col-xs-12 send">
 {!! Form::text('email', Input::old('email'), array('id'=>'input-username','class'=>'form-control','placeholder'=>'email address','required'=>'required')) !!}
            </div>
 </div>
       <!--     <p class="center">Security question</p>
            <div class="control-group">
                <div class="controls">
                    <div class="input-prepend">
                        <span class="add-on"><i class="icon-question-sign"></i></span>
                        {!! Form::text('security_question', $user->security_question, array('id'=>'input-security_question', 'placeholder' => 'Security Question', 'disabled')) !!}
                    </div>
                </div>
            </div>

            <p class="center">Enter your security answer</p>
            <div class="control-group">
                <div class="controls">
                    <div class="input-prepend">
                        <span class="add-on"><i class="icon-question-sign"></i></span>
                        {!! Form::text('security_answer', Input::old('security_answer'), array('id'=>'input-security_answer', 'placeholder' => 'Security Answer')) !!}
                    </div>
                </div>
            </div>-->
<div class="form-group col-lg-12 col-sm-12 col-xs-12">
     <label class="col-lg-5 col-sm-5 col-xs-12 row">Enter your new password</label>
   <div class="col-lg-6 col-sm7 col-xs-12 send">
                        {!! Form::password('password', array('id'=>'input-password','class'=>'form-control','placeholder'=>'Password','required'=>'required')) !!}                    
                </div>
            </div>
			<input type="hidden" name="_token" value="{{ csrf_token() }}">	
				<div>
            <input type="submit" id="login-btn" class="btn discover-btn" value="Reset Password" />
			</div>
        {!! Form::close() !!}
    @endif
	</div>
	</div>
  </div>
@stop
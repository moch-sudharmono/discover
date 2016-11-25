@extends('public/default2/_layouts._layout')
@section('styles')    
@stop
@section('content')
<section class="creat-page">
<div class="form-bg"> 
    <!-- tabs right -->
    <div class="tabbable tabs-right">
        <div class="tab-content left-side col-md-offset-2 col-md-8 col-md-offset-2 col-sm-12 col-xs-12" >
            <div class="tab-pane active" id="1">
                <div class="personal-form account-user">
                    <div class="per-1 active">
                        <div class="col-lg-12 col-sm-12 col-xs-12 ">
                            <h4> Personal Account Details </h4>
                            <p>You are the admin user to your new account. Add additional admin or event manager to your account.</p>
                        </div>
                           
                        <!---- error block start ------>                           
                        @if($errors->has() && (count($errors) > 0 || !empty(Session::get('share_event'))))
                        @foreach ($errors->all() as $key => $error)
                        <span class="col-md-12 full-error alert {{$key}}}">
                             <button data-dismiss="alert" aria-label="close" class="close">&times;</button>
                            {{$error}}
                        </span>	
                        @endforeach
                        @endif
                        <!---- error block end------>
                
                            
                        <div class="personal-form">
                            <form class="form-inline" method="post" enctype="multipart/form-data">
                                <ul class="nav nav-tabs" id="tabs">
                                    <li class="active"> 
                                        <div class=" per-1 active">
                                            <div class="form-group col-lg-6 col-sm-6 col-xs-12">
                                                <input type="text" placeholder="Address " value="{{ old('address') }}" name="address" class="form-control" required />
                                            </div>

                                            <div class="form-group col-lg-6 col-sm-6 col-xs-12">
                                                <div class="sel">
                                                    <select id="country" name="country" title="Country" required>
                                                        <option value="">Country</option> 
                                                        @foreach($cou_data as $cd)
                                                        @if($cd->id == old('country') )
                                                            <option value="{!! $cd->id !!}" selected="">{!! $cd->name !!}</option>
                                                        @else
                                                            <option value="{!! $cd->id !!}">{!! $cd->name !!}</option>
                                                        @endif
                                                        @endforeach
                                                            
                                                    </select>
                                                    {!! $errors->first('country', '<span class="help-block with-errors">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="form-group col-lg-6 col-sm-6 col-xs-12">
                                                    <div class="sel">					 
                                                        <select id="state" name="state" title="Province/State" required>
                                                            <option value="">Province</option>  
                                                        </select>
                                                        {!! $errors->first('state', '<span class="help-block with-errors">:message</span>') !!}
                                                    </div>
                                                </div>

                                                <div class="form-group col-lg-6 col-sm-6 col-xs-12">
                                                    <div class="sel cu-city">	
                                                       <select id="city" name="city" title="City" required>
                                                            <option value="">city</option>
                                                        </select>
                                                         {!! $errors->first('city', '<span class="help-block with-errors">:message</span>') !!}
                                                    </div>
                                                </div>

                                                <div class="form-group col-lg-6 col-sm-6 col-xs-12">
                                                    <input type="text" placeholder="Postal/Zip Code" value="{{ old('zip_code') }}" name="zip_code" class="form-control" required />
                                                    {!! $errors->first('zip_code', '<span class="help-block with-errors">:message</span>') !!}
                                                </div>

                                                <div class="form-group col-lg-6 col-sm-6 col-xs-12">
                                                    <input type="text" placeholder="Phone Number" value="{{ old('phone') }}" id="phone" name="phone" class="form-control" required />
                                                    {!! $errors->first('phone', '<span class="help-block with-errors">:message</span>') !!}	
                                                </div>
                                            </div>
                                        </li>            
                                    </ul>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">	
                                    <div class="form-group col-lg-12 col-sm-12 col-xs-12"> 
                                        <input type="submit" class="btn discover-btn" value="Next"/>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- /tabs -->
    </div>
</div>
</div>
</section>
@stop
@section('scripts')
<script>
    jQuery(function ($) {
        $("#phone").mask("(999) 999-9999");
    })
     jQuery(document).ready(function(){
        jQuery("#country").trigger("change");
    }); 
</script>
@stop
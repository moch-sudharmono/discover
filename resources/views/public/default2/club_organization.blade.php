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
                    <div class="personal-form">
                        <form class="form-inline" method="post" action="{{url('bmc-form')}}" enctype="multipart/form-data">
                            <div class=" per-1 active">
                                <div class="col-lg-12 col-sm-12 col-xs-12">
                                    <h4> Club/Organization </h4>
                                    <p>Business Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry.</p>
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
                                
                                <div class="frm-one">	
                                    <div class="form-group col-lg-6 col-sm-6 col-xs-12">
                                        <input type="text" placeholder="Club or Organization Name" value="{{ old('name') }}" name="name" class="form-control" required />	
                                        {!! $errors->first('name', '<span class="help-block with-errors">The Club/Organization Name has already been taken. please use different name</span>') !!}   						  
                                    </div>
                                    <div class="form-group col-lg-6 col-sm-6 col-xs-12">
                                        <input type="text" placeholder="Phone Number" id="phone" name="phone" value="{{ old('phone') }}" class="form-control" required />
                                        {!! $errors->first('phone', '<span class="help-block with-errors">:message</span>') !!}	
                                    </div>
                                </div>
                                <div class="frm-one">
                                    <div class="form-group col-lg-6 col-sm-6 col-xs-12">
                                        <input type="text" placeholder="Address " value="{{ old('address') }}" name="address" class="form-control" required />
                                        {!! $errors->first('address', '<span class="help-block with-errors">:message</span>') !!}
                                    </div>
                                    <div class="form-group col-lg-6 col-sm-6 col-xs-12">
                                        <input type="email" placeholder="Email" value="{{ old('email') }}" name="email" class="form-control" required />
                                        {!! $errors->first('email', '<span class="help-block with-errors">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="frm-one">
                                    <div class="form-group col-lg-6 col-sm-6 col-xs-12">
                                        <div class="sel">
                                            
                                            <select id="country" name="country" title="Country" required >
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
                                        <input type="text" name="website" placeholder="Website" value="{{ old('website') }}" class="form-control" required />
                                    </div>
                                </div>
                                <div class="frm-one">
                                    <div class="form-group col-lg-6 col-sm-6 col-xs-12">
                                        <div class="sel">
                                            <select id="state" name="state" title="State/Province" required >
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

                                </div>
                                <input type="hidden" name="actype" value="club">	
                                <div class="form-group col-lg-6 col-sm-6 col-xs-12">
                                    <input type="text" name="zip_code" value="{{ old('zip_code') }}" placeholder="Postal/Zip Code" class="form-control" required />
                                    {!! $errors->first('zip_code', '<span class="help-block with-errors">:message</span>') !!}
                                </div>
                                <div class="form-group col-lg-6 col-sm-6 col-xs-12">
                                    <input id="uploadFile" placeholder="Company Logo"  class="form-control" disabled="disabled" />
                                    <div class="fileUpload btn btn-primary"> <span>Upload</span>
                                        <input id="uploadBtn" name="upload_file" type="file" class="upload" required />
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">	
                            <div class="form-group col-lg-12 col-sm-12 col-xs-12"> 
                                <input type="submit" class="btn discover-btn" value="Next"/>
                            </div>
                        </form>
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
    document.getElementById("uploadBtn").onchange = function () {
        document.getElementById("uploadFile").value = this.value;
        
    };
    
jQuery(document).ready(function () {
        jQuery("#country").trigger("change");
        jQuery("#state").val("{{old('state') }}");
    });
</script>
@stop
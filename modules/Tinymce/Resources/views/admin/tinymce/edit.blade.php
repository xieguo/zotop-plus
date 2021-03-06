@extends('core::layouts.master')

@section('content')
<div class="main">
    <div class="main-header">
        <div class="main-back">
            <a href="{{request::referer()}}"><i class="fa fa-angle-left"></i><b>{{trans('core::master.back')}}</b></a>
        </div>
        <div class="main-title mr-auto">
            {{$title}}
        </div>
    </div>
    
    <div class="main-body scrollable">
        <div class="container-fluid">

            {form model="$tinymce" route="['tinymce.tinymce.store', $id]" id="tinymce-form" method="put" autocomplete="off"}

            <div class="form-title row">{{trans('tinymce::tinymce.form.base')}}</div>

            <div class="form-group row">
                <label for="title" class="col-2 col-form-label required">{{trans('tinymce::tinymce.title.label')}}</label>
                <div class="col-4">
                    {field type="text" name="title" required="required"}

                    @if ($errors->has('title'))
                    <span class="form-help text-error">{{ $errors->first('title') }}</span>
                    @else
                    <span class="form-help">{{trans('tinymce::tinymce.title.help')}}</span>                     
                    @endif                       
                </div>
            </div>

            {/form}

        </div>
    </div><!-- main-body -->
    <div class="main-footer">
        <div class="mr-auto">
            {field type="submit" form="tinymce-form" value="trans('core::master.save')" class="btn btn-primary"}
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">
    $(function(){
        $('form.form').validate({
            submitHandler:function(form){                
                var validator = this;
                $('.form-submit').prop('disabled',true);
                $.post($(form).attr('action'), $(form).serialize(), function(msg){
                    $.msg(msg);
                    if ( msg.state && msg.url ) {
                        location.href = msg.url;
                        return true;
                    }
                    $('.form-submit').prop('disabled',false);
                    return false;
                },'json').fail(function(jqXHR){
                    $('.form-submit').prop('disabled',false);
                    return validator.showErrors(jqXHR.responseJSON.errors);
                });
            }            
        });
    })
</script>
@endpush

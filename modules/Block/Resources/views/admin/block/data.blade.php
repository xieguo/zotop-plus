@extends('core::layouts.master')

@section('content')
<div class="main">
    <div class="main-header">
        @if ($block->data)        
        <div class="main-back">
            <a href="{{route('block.index',$block->category_id)}}"><i class="fa fa-angle-left"></i><b>{{trans('core::master.back')}}</b></a>
        </div>
        @endif
        <div class="main-title mr-auto">
            {{$block->name}}- {{$title}}
        </div>
        <div class="main-action">
            <a class="btn btn-primary" href="{{route('block.edit', $block->id)}}">
                <i class="fa fa-cog"></i> {{trans('block::block.setting')}}
            </a>            
        </div>
    </div>
    
    <div class="main-body scrollable">

            {form model="$block" route="['block.data', $id]" id="block-form" method="post" autocomplete="off"}
            
            @if ($block->type == 'code')
                {field type="code" name="data" height="400" required="required" placeholder="trans('block::block.data.placeholder.code')"}
            @elseif ($block->type == 'html')
                {field type="textarea" name="data" rows="18" class="rounded-0" required="required" placeholder="trans('block::block.data.placeholder.html')"}
            @elseif ($block->type == 'text')
                {field type="textarea" name="data" rows="18" class="rounded-0" required="required" placeholder="trans('block::block.data.placeholder.text')"}                
            @endif
            

            @if ($errors->has('data'))
            <span class="form-help text-error">{{ $errors->first('data') }}</span>
            @else
            <span class="form-help">{{$block->description}}</span>              
            @endif

            <input type="hidden" name="operation">
            {/form}

    </div><!-- main-body -->
    <div class="main-footer">
        <div class="ml-auto">
             {field type="button" form="block-form" value="trans('block::block.save.edit')" class="btn btn-primary btn-save-edit"}
            {field type="button" form="block-form" value="trans('block::block.save.back')" class="btn btn-success btn-save-back"}
        </div>
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">
    $(function(){

        // 保存并继续编辑
        $('.btn-save-edit').on('click',function(){
            $('[name=operation]').val('save-edit');
            $('form.form').submit();
        });

        // 保存并返回列表
         $('.btn-save-back').on('click',function(){
            $('[name=operation]').val('save-back');
            $('form.form').submit();
        });       

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
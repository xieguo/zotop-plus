@extends('core::layouts.master')

@section('content')
@include('media::media.side')
<div class="main">
    <div class="main-header">
        @if (empty($keywords))
        @if ($folder_id)
        <div class="main-back d-none">
            <a href="javascript:history.go(-1);"><i class="fa fa-angle-left"></i><b>{{trans('core::master.back')}}</b></a>
        </div>        
        <div class="main-title mr-auto">{{$folder->name}}</div>
        @else
        <div class="main-title mr-auto">{{trans('media::media.root')}}</div>
        @endif
        <div class="main-action">
            <a href="javascript:;" class="btn btn-primary file-upload" id="file-upload" data-url="{{route('core.file.upload')}}">
                <i class="fa fa-fw fa-upload"></i> {{trans('media::file.upload')}}
            </a>
            <a href="javascript:;" class="btn btn-outline-primary js-prompt" data-url="{{route('media.folder.create',[$folder_id])}}"  data-prompt="{{trans('media::folder.name')}}" data-name="name">
                <i class="fa fa-fw fa-folder-plus"></i> {{trans('media::folder.create')}}
            </a>
        </div>
        @else
        <div class="main-back">
            <a href="{{route('media.index')}}"><i class="fa fa-angle-left"></i><b>{{trans('core::master.back')}}</b></a>
        </div>        
        <div class="main-title mx-auto">
            {{trans('core::master.searching', [$keywords])}}
        </div>        
        @endif
        <div class="main-action">
            {form route="media.index" class="form-inline form-search" method="get"}
                <div class="input-group">
                    {{--{field type="select" name="type" options="Module::data('media::type.options')"}--}}
                    <input name="keywords" value="{{$keywords}}" class="form-control" type="search" placeholder="{{trans('media::media.search.placeholder')}}" required="required" aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit"> <i class="fa fa-fw fa-search"></i> </button>
                    </div>
                </div>
            {/form}
        </div>        
    </div>
    <div class="main-header progress p-0 rounded-0 d-none">
        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%"></div>        
    </div>
    @if (empty($keywords))    
    <div class="main-header breadcrumb text-xs p-2 m-0">
        @if ($folder_id)
        <a href="{{route('media.index',[$folder->parent_id])}}" class="breadcrumb-item breadcrumb-extra">
            <i class="fa fa-fw fa-arrow-up"></i>{{trans('media::folder.up')}}
        </a>
        @else
        <a href="javascript:;" class="breadcrumb-item breadcrumb-extra disabled"><i class="fa fa-arrow-up"></i>{{trans('media::folder.up')}}</a>
        @endif
        <a class="breadcrumb-item" href="{{route('media.index')}}">{{trans('media::media.root')}}</a>
        @foreach($parents as $p)
        <a class="breadcrumb-item" href="{{route('media.index', $p->id)}}">{{$p->name}}</a> 
        @endforeach      
    </div>
    @endif
    <div class="main-body scrollable" id="file-upload-dragdrop">

        {form route="media.operate" class="form-datalist" method="post"}
        <table class="table table-nowrap table-hover table-select">
            <thead>
            <tr>
                <th class="select">
                    <input type="checkbox" class="select-all text-muted">
                </th>
                <th colspan="3">{{trans('media::media.name')}}</th>
                <th width="10%">{{trans('media::media.type')}}</th>
                <th width="10%">{{trans('media::media.size')}}</th>
                <th width="10%">{{trans('media::media.created_at')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($media as $m)
                <tr class="js-media-open" data-type="{{$m->type}}" data-url="{{$m->url}}" data-title="{{$m->name}}">
                    <td class="select">
                        @if ($m->isFolder())
                        <input type="checkbox" name="ids[]" value="{{$m->id}}" data-type="folder" class="select text-muted">
                        @else
                        <input type="checkbox" name="ids[]" value="{{$m->id}}" data-type="file" class="select text-muted">
                        @endif
                    </td>                
                    <td width="1%" class="text-center pr-2">
                        @if ($m->isFolder())
                            <i class="fa fa-folder fa-md text-warning"></i>
                        @elseif ($m->isImage())
                            <div class="icon icon-md"><img src="{{$m->url}}"></div>
                        @else
                            <i class="fa {{$m->icon}} fa-md text-warning"></i>
                        @endif                        
                    </td>                
                    <td width="50%" class="pl-2">
                        <div class="title text-md">
                            @if ($m->isFolder())
                            <a href="{{$m->url}}">{{$m->name}}</a>
                            @else
                            {{$m->name}}
                            @endif
                        </div>
                        <div class="description">
                            @if ($m->isImage())
                            {{$m->width}}px × {{$m->height}}px
                            @endif
                        </div>
                    </td>
                    <td width="10%" class="manage manage-hover text-right">

                        @if ($m->isFolder())
                           <a class="manage-item js-prompt" href="javascript:;" data-url="{{route('media.folder.rename',[$m->id])}}"  data-prompt="{{trans('media::folder.name')}}" data-name="name" data-value="{{$m->name}}">
                                <i class="fa fa-fw fa-eraser"></i> {{trans('core::folder.rename')}}
                            </a>
                            <a href="javascript:;" class="manage-item js-move" data-url="{{route('media.folder.move', $m->id)}}" data-select="{{route('media.folder.select',[$m->parent_id])}}" data-title="{{$m->name}}">
                                <i class="fa fa-arrows-alt fa-fw"></i> {{trans('media::folder.move')}}
                            </a>                               
                            <a class="manage-item js-delete" href="javascript:;" data-url="{{route('media.folder.delete', $m->id)}}">
                                <i class="fa fa-fw fa-times"></i> {{trans('core::master.delete')}}
                            </a>                        
                        @else
                            @if ($m->isImage())
                            <a href="javascript:;" class="manage-item js-image" data-url="{{$m->url}}" data-title="{{$m->name}}">
                                <i class="fa fa-eye fa-fw"></i> {{trans('media::file.view')}}
                            </a>
                            @endif            
                            <a class="manage-item js-prompt" href="javascript:;" data-url="{{route('media.file.rename',[$m->id])}}"  data-prompt="{{trans('media::file.name')}}" data-name="name" data-value="{{$m->name}}">
                                <i class="fa fa-fw fa-eraser"></i> {{trans('media::file.rename')}}
                            </a>
                            <a href="javascript:;" class="manage-item js-move" data-url="{{route('media.file.move', $m->id)}}" data-select="{{route('media.folder.select',[$m->folder_id])}}" data-title="{{$m->name}}">
                                <i class="fa fa-arrows-alt fa-fw"></i> {{trans('media::file.move')}}
                            </a>                        
                            <a class="manage-item js-delete" href="javascript:;" data-url="{{route('media.file.delete', $m->id)}}">
                                <i class="fa fa-times fa-fw"></i> {{trans('media::file.delete')}}
                            </a>
                        @endif                      
                    </td>
                    <td>
                        @if ($m->isFolder())
                        {{trans('media::folder.type')}}
                        @else
                        {{trans('core::file.type.'.$m->type)}}
                        @endif
                    </td>
                    <td>{{$m->size_human}}</td>
                    <td>
                        <span class="font-weight-bold d-block username">{{$m->user->username}}</span>
                        <span class="font-weight-light text-sm created_at" title="{{$m->created_at}}" data-toggle="tooltip">{{$m->created_at_human}}</span>
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>
        {/form}

    </div><!-- main-body -->
    <div class="main-footer">
        <div class="main-action mr-auto">
            <div class="btn-group dropup">
                <button type="button" class="btn btn-light js-select-all">
                    {{trans('media::media.select.all')}}
                </button>
                <button type="button" class="btn btn-light dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-ellipsis-v"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-left">
                    <a href="javascript:;" class="dropdown-item js-select-none">{{trans('media::media.select.none')}}</a>
                    <a href="javascript:;" class="dropdown-item js-select" data-type="folder">{{trans('media::media.select.folder')}}</a>
                    <a href="javascript:;" class="dropdown-item js-select" data-type="file">{{trans('media::media.select.file')}}</a>
                </div>
            </div>

            <button type="button" class="btn btn-success js-select-operate disabled" disabled="disabled" data-operate="move" data-select="{{route('media.folder.select',[$folder_id])}}">
                <i class="fa fa-arrows-alt fa-fw"></i> {{trans('media::file.move')}}
            </button>
            <button type="button" class="btn btn-danger js-select-operate disabled" disabled="disabled" data-operate="delete" data-confirm="{{trans('core::master.delete.confirm')}}">
                <i class="fa fa-times fa-fw"></i> {{trans('media::file.delete')}}
            </button>
        </div>    

        {{ $media->appends($_GET)->links('core::pagination.default') }}
    </div>
</div>
@endsection

@push('js')
<script type="text/javascript" src="{{Module::asset('core:plupload/plupload.full.min.js')}}"></script>
<script type="text/javascript" src="{{Module::asset('core:plupload/i18n/'.App::getLocale().'.js')}}"></script>
<script type="text/javascript" src="{{Module::asset('core:plupload/jquery.plupload.js')}}"></script>
<script type="text/javascript">
    // upload
    $('.file-upload').each(function(){
        var self = $(this);
        var url = self.data('url');
        var success = 0;
        var options = {
                url : url,
                autostart : true, //自动开始
                multi_selection : true, //是否可以选择多个文件
                multipart_params: {
                    'folder_id'  : '{{$folder_id ?? 0}}',
                    'module'     : '{{app('current.module')}}',
                    'controller' : '{{app('current.controller')}}',
                    'action'     : '{{app('current.action')}}',
                    'user_id'    : '{{Auth::user()->id}}',
                    'token'      : '{{Auth::user()->token}}'
                },
                filters: {
                    //max_file_size:'20mb',
                    mime_types : [
                        { title : "select files", extensions : "*"},
                    ],
                    prevent_duplicates:false //阻止多次上传同一个文件
                },
                started : function(up){
                    self.data('progress', $.progress());
                },
                progress : function(up,file){
                    self.data('progress').percent(up.total.percent);
                },
                uploaded : function(up, file, response){
                    // 单个文件上传完成 返回信息在 response 中
                    if (response.result.state) {
                        $.success(response.result.content);
                        success ++;
                    } else {
                        $.error(response.result.content);
                    }
                },                
                complete : function(up, files){
                    // 全部上传完成
                    self.data('progress').close().remove();

                    if (success > 0) {
                        location.reload();
                    }
                },
                error : function(error, detail){
                    $.error(detail);
                }
        };

        self.plupload(options);
    });
</script>
<script type="text/javascript">  
    // move dialog
    function movedata(title, select, callback) {
        return $.dialog({
            title        : title,
            url          : select,
            width        : 500,
            height       : 400,
            padding      : '1rem',
            ok           : function() {
                callback(this);
                return false;
            },
            cancel       : $.noop,
            oniframeload : function() {
                this.loading(false);
            },
            opener       : window
        }, true).loading(true);        
    }

    // post data
    function postdata(url, data, callback) {
        $.post(url, data, function(msg) {
            $.msg(msg);
            // 操作成功
            if (msg.state) callback(); 
            //if (msg.url)  location.href = msg.url;
        });
    }

    $(function(){

        // 双击
        $(document).on('dblclick', '.js-media-open', function(event) {
            event.preventDefault();

            var type  = $(this).data('type');
            var url   = $(this).data('url');
            var title = $(this).data('title');
            var info  = $(this).data('info');

            if (type == 'folder') {
                location.href = url;
            }

            if (type == 'image') {
                $.image(url, title).statusbar(info);
            }

            event.stopPropagation();
        });

        // 单个文件夹和文件移动
        $(document).on('click', 'a.js-move',function(event){
            var title  = $(this).text();
            var move   = $(this).data('url');
            var select = $(this).data('select');

            movedata(title, select, function(dialog) {
                postdata(move, {folder_id:dialog.selected_folder_id}, function(){
                    dialog.close().remove();
                });
            });

            event.stopPropagation();
        });

        // 选择
        $(function(){
            var selectTable = $('table.table-select').data('selectTable');

            $('.js-select-all').on('click', function() {
                selectTable.selectAll(true);
            });

            $('.js-select-none').on('click', function() {
                selectTable.selectAll(false);
            });

            $('.js-select').on('click', function() {
                selectTable.selectAll(false);
                selectTable.select("[data-type="+ $(this).data('type') +"]",true);
            });          

            $('.js-select-operate').on('click', function() {   
                if ($(this).hasClass('disabled')) {
                    return false;
                }

                var title   = $(this).text();
                var operate = $(this).data('operate');
                var form    = $('form.form-datalist');
                var action  = form.attr('action');
                var data    = form.serializeArray();
                    data.push({name:"operate", value:operate});
                
                if (operate == 'move') {
                    movedata(title, $(this).data('select'), function(dialog) {
                        data.push({name:"move_folder_id", value:dialog.selected_folder_id});
                        postdata(action, $.param(data), function() {
                            dialog.close().remove();
                        });
                    })
                } else if(operate == 'delete') {
                    $.confirm($(this).data('confirm'), function(){
                        postdata(action, $.param(data), $.noop);
                    })
                } else {
                    postdata(action, $.param(data), $.noop);
                }
            });                 
        });      
    });

</script>
@endpush

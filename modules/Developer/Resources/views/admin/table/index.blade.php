@extends('core::layouts.master')

@section('content')
@include('developer::module.side')
<div class="main">
    <div class="main-header">
        <div class="main-title mr-auto">
            {{$title}}
        </div>
        <div class="main-action">
            <a href="{{route('developer.table.create',[$module])}}" class="btn btn-primary">
                <i class="fa fa-plus"></i> {{trans('developer::table.create')}}
            </a>
        </div>        
    </div>
    <div class="main-body scrollable">
        @if(count($tables) == 0)
            <div class="nodata">{{trans('core::master.nodata')}}</div>
        @else
            <table class="table table-nowrap table-hover">
                <thead>
                <tr>
                    <th colspan="2">{{trans('developer::table.name')}}</th>
                    <td width="1%"></td>
                </tr>
                </thead>
                <tbody>
                @foreach($tables as $table)
                    <tr>
                        <td width="1%" class="pr-2"><div class="fa fa-table fa-2x text-primary"></div></td>
                        <td class="pl-2">
                            <div class="title">
                                {{$table}}
                            </div>

                        </td>
                        <td class="manage">
                            <a class="manage-item" href="{{route('developer.table.manage', [$module, $table])}}">
                                <i class="fa fa-edit"></i> {{trans('developer::table.manage')}}
                            </a>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        @endif                       
    </div><!-- main-body -->
    <div class="main-footer">
        <div class="footer-text mr-auto">
            {{trans('developer::table.description')}}
        </div>
    </div>
</div>
@endsection

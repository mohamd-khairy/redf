@extends('layouts.dashboard.master')

@push('css')
    <link href="{{asset('dashboard_assets/plugins/custom/jstree/jstree.bundle.css')}}" rel="stylesheet"/>
@endpush

@section('page_header')
    <h5 class="text-dark font-weight-bold my-1 mr-5">
        <a href="{{url('dashboard/report/builder')}}">@lang('dashboard.report')</a>
    </h5>
    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
        <li class="breadcrumb-item text-muted">
            <a href="{{ url('/') }}" class="text-muted">@lang('dashboard.dashboard')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">@lang('dashboard.reports')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{url('dashboard/report/builder')}}" class="text-muted">@lang('dashboard.builder')</a>
        </li>
        <li class="breadcrumb-item text-muted">
            <a href="javascript:;" class="text-muted">{{handleTrans($title)}}</a>
        </li>
    </ul>
@endsection

@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-custom gutter-b ">
                        <div class="card-header">
                            <h3 class="card-title">@lang('dashboard.show_report_for'){{handleTrans($title)}}</h3>
                            <div class="card-toolbar">
                                <a href="{{url('dashboard/report/builder')}}"
                                   class="btn btn-primary ">
                                    <i class="flaticon2-reply-1"></i>
                                    @lang('dashboard.back')
                                </a>
                            </div>
                        </div>

                        <!--begin::Form-->
                        <form id="form" novalidate="novalidate" class="form form-label-right" method="get" action="{{url('dashboard/report/show')}}">
                            <input type="hidden" name="model_type" value="{{$type}}">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group validated">
                                            <label>@lang('dashboard.report_type') </label>
                                            <span class="text-danger"> *</span>
                                            <select required name="report_list" id="report_list"
                                                    class="form-control nice-select {{ inputError('report_list')}}">
                                                <option value="">@lang('dashboard.select_type')</option>
                                                @foreach(array_keys(config("$type.report")) as $report_type)
                                                    <option value="{{$report_type}}"
                                                        {{old('report_list') == $report_type ? 'selected' : ''}}>
                                                        {{__("dashboard.$report_type")}}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">
                                                <strong>{{ msgError('report_list')}}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group validated">
                                            <label>@lang('dashboard.show_type') </label>
                                            <span class="text-danger"> *</span>
                                            <select id="select_filter" required name="type"
                                                    class="form-control nice-select {{ inputError('type')}}">
                                                <option value="">@lang('dashboard.select_type')</option>
                                                <option
                                                    value="specific" {{old('type') == 'specific' ? 'selected' : ''}}>
                                                    {{__('dashboard.specific')}}
                                                </option>
                                                <option
                                                    value="comparison" {{old('type') == 'comparison' ? 'selected' : ''}}>
                                                    {{__('dashboard.comparison')}}
                                                </option>
                                            </select>
                                            <div class="invalid-feedback">
                                                <strong>{{ msgError('type')}}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4" id="select_container" style="display: none">
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group validated">
                                            <label>@lang('dashboard.time_type') </label>
                                            <span class="text-danger"> *</span>
                                            <select id="time_type"
                                                    class="form-control nice-select {{ inputError('time_type')}}"
                                                    name="time_type" required>
                                                <option value="">@lang('dashboard.select_time_type')</option>
                                                <option
                                                    value="dynamic" {{old('time_type') == 'dynamic' ? 'selected' : ''}}>
                                                    {{__('dashboard.dynamic')}}
                                                </option>
                                                <option
                                                    value="specific" {{old('time_type') == 'specific' ? 'selected' : ''}}>
                                                    {{__('dashboard.specific')}}
                                                </option>
                                            </select>
                                            <div class="invalid-feedback">
                                                <strong>{{ msgError('time_type')}}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="time_range" class="col-md-4"
                                         style="display: {{$errors->has('time_range') ? 'block' :'none'}}">
                                        <div class="form-group input-group input-daterange ">
                                            <label>@lang('dashboard.select_date') </label>
                                            <span class="text-danger"> *</span>
                                            <select name="time_range" class="form-control nice-select">
                                                <option
                                                    @if(old('time_range') == 'today'|| old('time_range') == null)
                                                    selected @endif value="today">
                                                    @lang('dashboard.today')
                                                </option>
                                                <option value="14" @if(old('time_range') ==14) selected @endif>
                                                    {{trans('dashboard.this_week')}}
                                                </option>
                                                <option value="13" @if(old('time_range') ==13) selected @endif>
                                                    {{trans('dashboard.last_week')}}
                                                </option>
                                                <option value="16" @if(old('time_range') ==16) selected @endif>
                                                    {{trans('dashboard.this_month')}}
                                                </option>
                                                <option value="15" @if(old('time_range') ==15) selected @endif>
                                                    {{trans('dashboard.last_month')}}
                                                </option>
                                                @for($i = 0; $i < date('m'); $i++)
                                                    <option value="{{date('m', strtotime("-$i month"))}}"
                                                            @if(old('time_range') == date('m', strtotime("-$i month")))
                                                            selected @endif >
                                                        <?php echo date('Y-m', strtotime("-$i month")); ?>
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>

                                    <div id="specific_time" class="col-md-8" style=" display: {{$errors->hasAny(['start','end']) ? 'block' :'none'}}">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group validated">
                                                    <label>@lang('dashboard.from_date')</label>
                                                    <span class="text-danger"> *</span>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="la la-exclamation-triangle flaticon-event-calendar-symbol"></i>
                                                        </span>
                                                        </div>
                                                        <input type="date" required name="start"
                                                               max="{{carbon()->now()->toDateString()}}"
                                                               value="{{old('start')}}"
                                                               class="form-control {{ inputError('start')}}"
                                                               placeholder="@lang('dashboard.enter') @lang('dashboard.from_date')"
                                                               aria-describedby="basic-addon1"
                                                        />
                                                        <div class="invalid-feedback">
                                                            <strong>{{ msgError('start')}}</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group validated">
                                                    <label>@lang('dashboard.end_date')</label>
                                                    <span class="text-danger"> *</span>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="la la-exclamation-triangle flaticon-event-calendar-symbol"></i>
                                                            </span>
                                                        </div>
                                                        <input type="date" name="end" value="{{old('end')}}" required
                                                               max="{{carbon()->now()->toDateString()}}"
                                                               class="form-control {{ inputError('end')}}"
                                                               placeholder="@lang('dashboard.enter') @lang('dashboard.end_date')">
                                                        <div class="invalid-feedback">
                                                            <strong>{{ msgError('end')}}</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <div class="form-actions">
                                    <button type="button" class="btn btn-primary close-setting add_loading">
                                        @lang('dashboard.show_report')
                                    </button>
                                    <input type="reset" class="btn btn-secondary" value="@lang('dashboard.cancel')"
                                    />
                                </div>
                            </div>
                        </form>
                        <!--end::Form-->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(function () {
            $("#select_filter").on('change', function (e) {
                let type = $(this).val();
                if (type === 'specific' || type === 'comparison') {
                    $.get("{{route('dashboard.report.get_sites')}}", {type: type}, function (data, textStatus, jqXHR) {
                        $("#select_container").html('').append(data);
                        $('.nice-select').select2({
                            minimumResultsForSearch: -1
                        });
                    });
                    $("#select_container").slideDown();
                } else {
                    $("#select_container").html('').slideUp();
                }
            });

            $("#time_type").on('change', function (e) {
                let type = $(this).val();
                if (type === 'dynamic') {
                    $("#specific_time").fadeOut('fast');
                    $("#time_range").fadeIn('fast');
                    $("#specific_time input").val('');
                } else if (type === 'specific') {
                    $("#specific_time").fadeIn('fast');
                    $("#time_range select").val('');
                    $("#time_range").fadeOut('fast');
                } else {
                    $("#specific_time").fadeOut('fast');
                    $("#time_range").fadeOut('fast')
                    $("#specific_time input").val('');
                    $("#time_range select").val('');
                }
            });

            if ("{{$errors->first()}}" !== "") {
                toastr.error("{{$errors->first()}}");
            }
        });
    </script>
@endpush

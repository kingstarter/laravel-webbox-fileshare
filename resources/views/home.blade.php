@extends('layouts.app')

@section('scripts')
  <script type="text/javascript">
    window.config = {
      storageTimes: [
        { value: '1 day',    label: '1 @lang('messages.time.day')' },
        { value: '2 days',   label: '2 @lang('messages.time.days')' },
        { value: '3 days',   label: '3 @lang('messages.time.days')' },
        { value: '1 week',   label: '1 @lang('messages.time.week')' },
        { value: '2 weeks',  label: '2 @lang('messages.time.weeks')' },
        { value: '1 month',  label: '1 @lang('messages.time.month')' },
        { value: '2 months', label: '2 @lang('messages.time.months')' },
        { value: '3 months', label: '3 @lang('messages.time.months')' },
        { value: '1 year',   label: '1 @lang('messages.time.year')' },
      ],
      defaultStorageTime: '1 month',
      defaultLocale: '{{ config('app.locale') }}',
      maxFilesize: {{ config('app.max_filesize') }}
    }
  </script>
  <script src="{{asset('js/app.js')}}"></script>
@endsection

@section('nav')
  <span class="float-right">
    <a class="mt-1 px-4 py-3 hover:bg-gray-200 rounded text-teal-800" href="/logout" title="@lang('messages.logout')">
      <span class="fa fa-sign-out-alt"></span>
    </a>
  </span>
@endsection

@section('content')
  <div id="app">
    <uploader
      :sessiontime={{ $time }}
      :sessionttl={{ $ttl }}>
    </uploader>
  </div>
@endsection

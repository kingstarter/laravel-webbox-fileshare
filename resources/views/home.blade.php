@extends('layouts.app')

@section('scripts')
  <script type="text/javascript">
    window.config = {
      storageTimes: {!! json_encode(config('webbox.storage_lifetime')) !!},
      defaultStorageTime: '{{ config('webbox.default_lifetime') }}',
      defaultLocale: '{{ config('app.locale') }}',
      maxFilesize: {{ intval(config('webbox.max_filesize')) }},
      mailEnabled: {{ json_encode(boolval(config('mail.default'))) }}
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

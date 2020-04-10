@extends('layouts.app')

@section('scripts')
  <script src="{{asset('js/app.js')}}"></script>
@endsection

@section('nav')
  <span class="float-right">
    <a class="mt-1 px-4 py-3 hover:bg-gray-200 rounded" href="/logout" title="logout">
      <span class="fa fa-sign-out-alt"></span>
    </a>
  </span>
@endsection

@section('content')
  <div id="app">
    <uploader
      sessionname="{{ $session }}"
      :sessiontime={{ $time }}
      :sessionttl={{ $ttl }}>
    </uploader>
  </div>
@endsection

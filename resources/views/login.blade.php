@extends('layouts.app')

@section('content')
  <div class="max-w-xs mx-auto">

    @isset($status)
    <div class="px-8">
      @if($status == 'error')
      {{-- ERROR MESSAGE --}}
      <div class="w-full text-center bg-red-400 border border-red-400 rounded p-1">
        {{ $message }}
      </div>
      @endif
    </div>
    @endisset

    <form class="px-8 pt-6 pb-8 mb-4" method="POST">
      @csrf
      @if(config('webbox.honeypot_enabled'))
      <div class="hny/pt">
        <label for="{{ config('webbox.honeypot_field') }}">@lang('messages.honeypot.email')</label>
        <input type="text" class="form-control" name="{{ config('webbox.honeypot_field') }}" value="" />
      </div>
      @endif
      <div class="mb-4">
        <input name="session_pin" type="password" placeholder="@lang('messages.token.required')"
          class="form-control" autocomplete="off" />
      </div>
      <div class="flex items-center justify-around">
        <input type="submit" value="@lang('messages.login')"
          class="btn btn-teal"/>
      </div>
    </form>

  </div>
@endsection

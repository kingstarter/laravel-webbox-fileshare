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
      <div class="hny/pt">
        <label for="email_456">Fake-email Feld für Schutz vor Bots. Bitte nicht ausfüllen.</label>
        <input type="text" class="form-control" name="email_456" value="" />
      </div>
      <div class="mb-4">
        <input name="session_pin" type="text" placeholder="Sicherheitscode erforderlich"
          class="form-control" />
      </div>
      <div class="flex items-center justify-around">
        <input type="submit" value="Anmelden"
          class="btn bg-blue-500 hover:bg-blue-700 text-white font-bold"/>
      </div>
    </form>

  </div>
@endsection

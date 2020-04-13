@extends('layouts.app')

@section('title', __('messages.public.title'))

@section('nav')
  <span class="pl-8 text-gray-500 cursor-default">
    <span>@lang('messages.link.valid.date'):</span>
    <span>{{ $endingdate }}</span>
  </span>
  <span class="float-right">
    <a class="mt-1 px-4 py-3 hover:bg-gray-200 rounded text-teal-800" href="/archive/{{$directory}}" title="@lang('messages.download.zip')">
      <span class="fa fa-long-arrow-alt-down"></span>
      <span class="fa fa-file-archive"></span>
    </a>
  </span>
@endsection

@section('content')
  <div class="w-full grid grid-cols-5 gap-5">
    @foreach ($files as $fobj)
      <div class="mb-4">

        <div class="flex flex-col border-2 border-gray-500 bg-gray-200 p-1 shadow-lg rounded relative">

          {{-- Download overlay link --}}
          <a href="{{ $fobj['url'] }}" download="{{ $fobj['basename'] }}"
            class="absolute inset-0 hover:bg-gray-700 opacity-25 z-10">
          </a>

          <div class="h-40 my-3 text-center">
            @if($fobj['image'] != null)
              <img class="object-contain h-40 mb-4 p-4 mx-auto" title="{{ $fobj['basename'] }}" src="{{ $fobj['image'] }}" alt="{{ $fobj['basename'] }}" />
            @else
              <span class="fa-stack fa-4x pt-4 text-teal-800">
                <i class="fas fa-circle fa-stack-2x"></i>
                <i class="fas fa-{{ $fobj['icon'] }} fa-stack-1x fa-inverse"></i>
              </span>
            @endif
          </div>

          <div class="text-sm p-2 text-center truncate">{{ $fobj['basename'] }}</div>
        </div>

      </div>
    @endforeach
  </div>
@endsection

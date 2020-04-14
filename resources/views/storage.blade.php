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
  <div class="w-full grid gap-5 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
    @foreach ($files as $fobj)
      {{-- Card style content --}}
      <div class="flex flex-col border-2 border-gray-500 bg-gray-200 p-1 shadow-lg rounded relative">

        @if($fobj['ispicture'])
          {{-- Modal overlay link for image files --}}
          <span onclick="previewImage('{{ $fobj['url'] }}')"
            class="absolute inset-0 hover:bg-gray-700 opacity-25 z-10 cursor-pointer">
          </span>
        @else
          {{-- Download overlay link for normal files --}}
          <a href="{{ $fobj['url'] }}" download="{{ $fobj['basename'] }}"
            class="absolute inset-0 hover:bg-gray-700 opacity-25 z-10">
          </a>
        @endif

        <div class="h-40 my-3 text-center">
          @if($fobj['image'] != null)
            <img class="object-contain h-40 mb-4 p-4 mx-auto" title="{{ $fobj['basename'] }}" src="{{ $fobj['image'] }}" alt="{{ $fobj['basename'] }}" />
          @else
            <span class="fa-stack fa-4x pt-4 text-teal-800">
              <i class="fa fa-circle fa-stack-2x"></i>
              <i class="fa fa-{{ $fobj['icon'] }} fa-stack-1x fa-inverse"></i>
            </span>
          @endif
        </div>

        <div class="text-sm p-2 text-center truncate">{{ $fobj['basename'] }}</div>
      </div>
    @endforeach
  </div>

  <div id="lightbox" class="modal hidden opacity-0 transition-opacity ease-in-out duration-500 fixed z-20 top-0 left-0 w-screen h-screen backdrop" onclick="modalClicked(event)">
    <div class="modal-content relative bg-white mx-auto my-8 md:my-12 lg:my-16 xl:my-20 h-7/8 lg:h-5/6 w-11/12 md:w-4/5 lg:w-3/4">

      <span class="close absolute top-0 right-0 p-4 text-gray-600 hover:text-gray-900 cursor-pointer" onclick="hidePreview()">
        <i class="fa fa-2x fa-times"></i>
      </span>

      <figure class="w-full h-full p-3 border-2">
        <img id="preview" class="w-full h-full object-scale-down" />
      </figure>

      <span class="absolute bottom-0 inset-x-0 w-full h-20 flex justify-center">
        <a href="javascript:" target="_self" download id="download_preview" class="btn btn-teal opacity-50 hover:opacity-100 w-16 h-16 rounded-full text-center pt-4">
          <i class="fa fa-2x fa-file-download"></i>
        </a>
      </span>
    </div>
  </div>
@endsection

@section('scripts')
<script type="text/javascript">
function previewImage(src) {
  var modal = document.querySelector('#lightbox')
  modal.querySelector('img#preview').setAttribute('src', src)
  modal.classList.remove('hidden')
  modal.querySelector('a#download_preview').setAttribute('href', src)
  setTimeout(function() {
    document.querySelector('#lightbox').classList.remove('opacity-0')
  }, 25)
}
function modalClicked(event) {
  // event.preventDefault()
  console.log(event)
  if (event.target.classList.contains('modal')) {
    hidePreview()
  }
}
function hidePreview() {
  document.querySelector('#lightbox').classList.add('opacity-0')
  setTimeout(function () {
    document.querySelector('#lightbox').classList.add('hidden')
  }, 500)
}
</script>
@endsection

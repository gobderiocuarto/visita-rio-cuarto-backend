@extends('web.layouts.app')
@section('content')
<div class="container home">
    <h1 class="display-4 text-center">Próximos eventos</h1>
    <div class="row">
      @forelse  ($events as $event)
      <div class="col-md-6 col-lg-4 col-xl-3">
      	@include('web.layouts.partials.event-card')
      </div>
      @empty
      	@include('web.layouts.partials.no_registros')
      @endforelse
    </div>
    {{ $events->links() }}
</div>
@endsection
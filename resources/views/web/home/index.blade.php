@extends('web.layouts.app')
@section('content')
@include('web.home.partials.hero')
	<div class="container home">
    <h1 class="display-4 text-center">Próximos eventos</h1>
    <div class="row">
    	@foreach ($events as $event)
		<div class="col-md-6 col-lg-4 col-xl-3">
			@include('web.layouts.partials.event-card')
		</div>
      	@endforeach
    </div>
	</div>
@include('web.ui.index')
@endsection
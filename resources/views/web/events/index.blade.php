@extends('web.layouts.app')
@section('content')
<div class="container home">
    <h1 class="display-4 text-center">Próximos eventos</h1>
    <div class="row">
      @for ($i = 0; $i < 6; $i++)
      <div class="col-md-6 col-lg-4 col-xl-3">
      	@include('web.layouts.partials.event-card')
      </div>
      @endfor
    </div>
  </div>
@endsection
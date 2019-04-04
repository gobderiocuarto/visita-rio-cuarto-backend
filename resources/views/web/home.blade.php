@extends('layouts.web')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            <h1>Listado de Servicios</h1>

            @foreach($organizations as $organization)

            <div class="card mb-3">
              <div class="row no-gutters">
                <div class="col-md-12 card-header p-3">{{ $organization->category->category->name }} :: {{ $organization->category->name }}</div>
                <div class="col-md-4">
                    @if($organization->file)
                    <img src="{{ $organization->file }}" class="img-responsive">
                    @endif
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h4 class="card-title">{{ $organization->name }}</h4>
                        @if($organization->description)
                        <p class="card-text">{{ $organization->description }}</p>
                        @endif
                        @if($organization->phone)
                        <address>
                            <strong>Teléfono/s:</strong><br>
                            {{ $organization->phone }}
                        </address>
                        @endif
                        <address>
                            <strong>Email:</strong><br>
                            {{ $organization->email }}
                        </address>
                        @if($organization->web)
                        <address>
                            <strong>Página Web:</strong><br>
                            {{ $organization->web }}
                        </address>
                        @endif                        
                    </div>
                </div>
              </div>
            </div>
            @endforeach

            {{ $organizations->render() }}
        </div>
    </div>
</div>
@endsection
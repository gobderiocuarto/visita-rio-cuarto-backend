@extends('web.layouts.app')
@section('container')
<main class="py-4">        
	<div class="container">
		<div class="row">
			<div class="col-12 col-md-10">
				<h1>Organizaciones</h1>
			    <h2>Listar organizaciones</h2>
			    </br>
				@foreach ($organizations as $organization)
			    <div class="card mb-3">
			    	<div class="col-md-12 card-header p-3">
			    		{{ $organization->category->category->name }} :: {{ $organization->category->name }}
			    	</div>
			    	<div class="card-body">
	              		<div class="row no-gutters">		                
		                	<div class="col-md-8">
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
		                    <div class="col-md-4">
			                    @if($organization->file)
			                    <img src="{{ $organization->file }}" class="img-responsive">
			                    @endif
			                </div>
		                </div>
	              	</div>
	              	<div class="card-footer">
			        	@forelse ($organization->tags as $tag)
			        	<span class="badge badge-info">{{$tag->name}}</span>
			        	@empty
			        	<em>Sin etiquetas</em>
			        	@endforelse
			        </div>
	            </div>
			   	</br>
			    @endforeach
			</div>
		</div>
	</div>
</main>
@endsection
@extends('admin.layouts.app')
@section('meta_title') {{ config('app.name'). " - Admin :: Home" }} @endsection
@section('content')
<div class="container">
  <div class="row justify-content-center">
    <nav class="col-12 col-md-10 mb-2" aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item active">Admin</li>
      </ol>
    </nav>
    <section class="col-12 col-md-10">
      <div class="card">
        <div class="card-body row">
          <div class="col-12 col-md-4">
            <a href="{{ url('/admin/events/') }}" class="btn btn-lg btn-primary mb-3 p-5 btn-block">
              <i class="fas fa-calendar-alt fa-2x d-block mb-3"></i>
              <h5 class="">Eventos</h5>
            </a>
          </div>
          <div class="col-12 col-md-4">
            <a href="{{ url('/admin/organizations/') }}" class="btn btn-lg btn-primary mb-3 p-5 btn-block">
              <i class="fas fa-calendar-alt fa-2x d-block mb-3"></i>
              <h5 class="">Organizaciones</h5>
            </a>
          </div>
          <div class="col-12 col-md-4">
            <a href="{{ url('/admin/services/') }}" class="btn btn-lg btn-primary mb-3 p-5 btn-block">
              <i class="fas fa-calendar-alt fa-2x d-block mb-3"></i>
              <h5 class="">Etiquetas</h5>
            </a>
          </div>
          <div class="col-12 col-md-4 offset-md-2">
            <a href="{{ url('/admin/categories/') }}" class="btn btn-lg btn-primary mb-3 p-5 btn-block">
              <i class="fas fa-calendar-alt fa-2x d-block mb-3"></i>
              <h5 class="">Categorías</h5>
            </a>
          </div>
          <div class="col-12 col-md-4">
            <a href="{{ url('/admin/spaces/') }}" class="btn btn-lg btn-primary mb-3 p-5 btn-block">
              <i class="fas fa-calendar-alt fa-2x d-block mb-3"></i>
              <h5 class="">Espacios</h5>
            </a>
          </div>
        </div>
      </div>
    </section>
  </div>
@endsection
@section('scripts')
@endsection
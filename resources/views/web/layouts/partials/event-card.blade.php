
<div class="card event">
  <div class="card-header">
    <div class="row">
        <div class="col-6 date">{{ $event->start_date }}, {{ $event->start_time }}</div>
    </div>
  </div>
  @if ($event->file)
  <div class="card-image" style="background-image: url('{{ Storage::url("events/{$event->id}/{$event->file->file_path}") }}')"></div>
  @else
  <div class="card-image"></div>
  @endif
  <div class="card-body">
    <h5 class="card-title">{{ $event->title }}</h5>
    <div class="mt-3">
      <a href="{{ url('eventos/'.$event->id.'/'.$event->slug) }}" class="btn btn-outline-branding">Me interesa</a>
    </div>
    <div class="mt-3">
    @if ($event->event)
      <a href="{{ $event->id }}" class="text-branding text-uppercase text-frame">{{ $event->event->title }}</a>
    @else
      <a href="" class="text-branding text-uppercase text-frame" disabled>&nbsp</a>
    @endif
    </div>
  </div>
  <div class="card-footer">
      faltan 2 días
  </div>
</div>
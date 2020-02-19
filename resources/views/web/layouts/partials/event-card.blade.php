
<div class="card event">
  <div class="card-header">
    <div class="row">
        <div class="col-6 date">
        {{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y')}} 
        </div>
        <div class="col-6 text-right">
        {{ \Carbon\Carbon::parse($event->start_time)->format('H.i')}} hs.
        </div>
    </div>
  </div>
  @if ($event->file)
  <div class="card-image" style="background-image: url('{{ Storage::url("events/medium/{$event->file->file_path}") }}')"></div>
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
      <a href="{{ url('eventos/marco/'.$event->event->id ) }}" class="text-branding text-uppercase text-frame">{{ $event->event->title }}</a>
    @else
      <a href="" class="text-branding text-uppercase text-frame" disabled>&nbsp</a>
    @endif
    </div>
  </div>
</div>
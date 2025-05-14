@extends('layouts.app')

@section('content')
<div class="pagetitle">
  <h1>Concurso</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.html">Home</a></li>
      <li class="breadcrumb-item">Talonarios</li>
    </ol>
    <a href="{{ url('talonarios/nuevo-talonario') }}"
        class="btn waves-effect waves-light btn-sm btn-warning">
        Nuevo Concurso</i>
    </a>
  </nav>
</div><!-- End Page Title -->

<section class="section">
  <div class="row">
    <div class="col-lg-12">
    @include('layouts.alerts')
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Concurso</h5>
          <div class="container mt-4">
            <div class="row g-4">
              @foreach($data as $rifa)
      
              <div class="col-12 col-md-6">
                <div class="card shadow-sm border-top-4 border-primary h-100">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                      <div class="flex-grow-1 me-3">
                        <small class="text-muted mb-1 d-block">{{$rifa->created_at}}</small>
                        <h3 class="card-title mb-3">{{$rifa->title}}</h3>
                        <div class="progress" style="height: 8px;">
                          <div class="progress-bar bg-primary" role="progressbar" style="width: {{($rifa->tickets_sell / $rifa->numbers) * 100}}%"></div>
                        </div>
                      </div>
                      <div class="d-flex flex-column gap-2" style="min-width: 60px;">
                        <a class="btn btn-outline-primary" href="{{ url('talonarios/' . $rifa->id . '/ver-talonario') }}"><i class="bi bi-grid-3x3-gap-fill"></i></a>
                        <!-- <button class="btn btn-outline-success get-winner" id_tal="{{$rifa->id}}"><i class="bi bi-trophy-fill"></i></i> -->
                        <div class="btn-group">
                          <button type="button" class="btn btn-outline-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                          <i class="bi bi-trophy-fill"></i>
                          </button>
                          <ul class="dropdown-menu">
                            
                              <li><button class="btn get-winner" id_tal="{{$rifa->id}}">Intantaneros</button></li>
                              <li><a class="dropdown-item" href="{{ url('talonarios/' . $rifa->id . '/selecionar-ganadores') }}">Gran Ganador</a></li>
                              <!-- <li><button class="btn get-great-winner" id_tal="{{$rifa->id}}">Gran Ganador</button></li>-->
                          </ul>
                        </div>
                        <a class="btn btn-outline-warning" href="{{ url('talonarios/' . $rifa->id . '/editar-talonario') }}"><i class="bi bi-pencil-fill"></i></a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
<script src="{{asset('libs/jquery/dist/jquery.min.js')}}"></script>

<script>

$(document).ready(function() {
  $('.get-winner').on('click', async function() {
    let baseUrl = '{{ url("talonarios/get-winner") }}';
      if(confirm("esta seguro de esta accion?") == true){
          await $.ajax({
              type: "POST",
              url: baseUrl,
              data: {
              "_token": "{{ csrf_token() }}",
              'id': $(this).attr('id_tal'),
              },
              success: function(response)
              {
                  alert(response);
                 
              },
              error: function(XMLHttpRequest, textStatus, errorThrown) { 
                alert(XMLHttpRequest.responseJSON)
              }
          });
      } 
  })

  $('.get-great-winner').on('click', async function() {
    let baseUrl = '{{ url("talonarios/get-great-winner") }}';
      if(confirm("esta seguro de esta accion?") == true){
          await $.ajax({
              type: "POST",
              url: baseUrl,
              data: {
              "_token": "{{ csrf_token() }}",
              'id': $(this).attr('id_tal'),
              },
              success: function(response)
              {
                  alert("el ganador es: " + response);
                 
              },
              error: function(XMLHttpRequest, textStatus, errorThrown) { 
                alert(XMLHttpRequest.responseJSON)
              }
          });
      } 
  })

});
</script>
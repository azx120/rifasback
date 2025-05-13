@extends('layouts.app')

@section('content')
<div class="pagetitle">
  <h1>Nuevo Plan</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.html">Home</a></li>
      <li class="breadcrumb-item">Planes</li>
      <li class="breadcrumb-item active">Nuevo</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section">
  <div class="row">
    <div class="col-lg-12">

      <div class="card">
        <div class="card-body">
        <h5 class="card-title">crear nuevo plan</h5>
           <form class="row g-3" id="submitForm" method="POST"  action="{{ url('planes/guardar-plan') }}" enctype="multipart/form-data">
                @csrf
                <div class="col-md-12">
                  
                  <input type="text" class="form-control @error('name') is-invalid @enderror" placeholder="nombre" name="name">
                  @if ($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                  @endif
                 
                </div>

                <div class="col-md-6">
                  <input type="text" class="form-control @error('precio') is-invalid @enderror" placeholder="precio" name="precio">
                  @if ($errors->has('precio'))
                      <div class="invalid-feedback">
                          {{ $errors->first('precio') }}
                      </div>
                  @endif
                </div>

                <div class="col-md-6">
                  <input type="text" class="form-control @error('saldo') is-invalid @enderror" placeholder="saldo" name="saldo">
                  @if ($errors->has('saldo'))
                      <div class="invalid-feedback">
                          {{ $errors->first('saldo') }}
                      </div>
                  @endif
                </div>

                <div class="quill-editor-full">
                </div>

                <div class="row mb-3 mt-5">

                  <div class="">
                    <button class="btn btn-primary">Submit Form</button>
                  </div>
                </div>

              </form><!-- End General Form Elements -->

          

        </div>
      </div>

    </div>
  </div>
</section>
@endsection
<script src="{{asset('libs/jquery/dist/jquery.min.js')}}"></script>
<script>
$(document).ready(function(){

  $("#submitForm").on("submit", function () {
    var hvalue = $('.ql-editor').html();
    alert(hvalue)
    $(this).append("<textarea name='descripcion' style='display:none'>"+hvalue+"</textarea>");
   });
});
</script>

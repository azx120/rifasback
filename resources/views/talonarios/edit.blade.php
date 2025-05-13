@extends('layouts.app')

@section('content')
<div class="pagetitle">
  <h1>Nuevo Talonarios</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.html">Home</a></li>
      <li class="breadcrumb-item">Talonarios</li>
      <li class="breadcrumb-item active">Editar</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section">
  <div class="row">
    <div class="col-lg-12">

      <div class="card">
        <div class="card-body">
        <h5 class="card-title">Editar Talonarios</h5>
           <form class="row g-3" id="submitForm" method="POST"  action="{{ url('talonarios/'. $data->id .'/actualizar-talonario') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <!-- Columna Izquierda -->
                    <div class="col-md-6 mb-3">
                        <label for="title" class="form-label">Título del Producto</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{$data->title}}" required>
                    </div>
                    <!-- Columna Derecha -->
                    <div class="col-md-6 mb-3">
                        <label for="imageUrl" class="form-label">URL de la Imagen</label>
                        <input type="file" class="form-control" id="imageUrl" name="imageUrl" placeholder="https://ejemplo.com/imagen.jpg">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label">Precio</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" value="{{$data->price}}" required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label">Cantidad de Numeros</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="numbers" name="numbers" step="0.01" min="0" value="{{$data->numbers}}" readonly>
                        </div>
                    </div>
                </div>

                <div class="row">
                  <div class="col-md-6 mb-3">
                      <label for="price" class="form-label">Finalización</label>
                      <div class="input-group">
                          <input type="date" class="form-control" id="endDate" name="endDate" value="{{$data->endDate}}"  required>
                      </div>
                  </div>
                </div>
                <div class="row">
                <div class="row" style="margin-bottom:150px;">
                  <div class="col-md-12 mb-3" >
                      <label for="price" class="form-label">descripcion</label>
                      <div class="quill-editor-full">
                        @php echo str_replace("<p></p>", "",$data->description) @endphp
                      </div> 
                  </div>
                </div>
                  
                </div>
                <p style="color: #717171; font-weigh:bold; text-align:center;">Agregar Nueva Galeria</p>

                  <hr>
                <div id="allGallery">
                  
                </div>
                <div class="col-md-6">
                  
                  <button class="btn waves-effect waves-light btn-sm btn-info" id="addGallery"><i class="bi bi-plus h3" style="color: #ffff;" ></i></button>
                  <input type="hidden" id="counterGallery" name="counterGallery" value="0"/>
                </div>

                <!-- Botón de Envío -->
                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-save"></i> Guardar Rifa
                    </button>
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
   function borrar (e){
     alert(e)
     $("#img_"+e).remove();
     var count = $("#counterGallery").val();
     count = parseInt(count)
     count = count - 1;
     if(count <= 0){count = 0}
     $("#counterGallery").val(count);
   }
$(document).ready(function(){

  $("#submitForm").on("submit", function () {
    var hvalue = $('.ql-editor').html();
    //alert(hvalue)
    $(this).append("<textarea name='description' style='visibility: hidden;'>"+hvalue+"</textarea>");
   });

   $("#addGallery").on("click", function (e) {
      e.preventDefault();
      var count = $("#counterGallery").val();
      var countDiv = "";
      count = parseInt(count)
      countDiv = count + 1; 

      $("#allGallery").append('<div class="row mt-5" id="img_'+countDiv+'"><div class="col-10"><input type="file" class="form-control" placeholder="imagen"  name="imgGallery_'+countDiv+'"></div><div class="col-2"><p class="btn waves-effect waves-light btn-sm btn-info" onclick="borrar('+countDiv+')" value="img_2" ><i class="bi bi-dash h3" style="color: #ffff;"></i></p></div></div>');
      
      $("#counterGallery").val( countDiv);

   });
});
</script>

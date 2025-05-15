@extends('layouts.app')

@section('content')
<div class="pagetitle">
  <h1>control de  Banner</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.html">Home</a></li>
      <li class="breadcrumb-item">Banner</li>
      <li class="breadcrumb-item active">Nuevo</li>
    </ol>
  </nav>
  <style>
        #imagePreview {
            display: none;
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
        }
        .invalid-feedback {
            display: none;
        }
    </style>
</div><!-- End Page Title -->

<section class="section">
  <div class="row">
    <div class="col-lg-12">
    @include('layouts.alerts')
      <div class="card">
        <div class="card-body">
            <h5 class="card-title">Cambiar Banner</h5>
            <div class="">
                <p class="">Banner Actual:</p>
                <img src="{{url('/')}}/storage/banner/banner.jpg" width="300px" />
            </div>
            <form class="mt-5" id="imageUploadForm" method="POST"  action="{{ url('banner/guardar-banner') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="imageInput" class="form-label">Seleccione una imagen (JPEG, JPG, PNG)</label>
                    <input class="form-control" type="file" id="imageInput" name="imageInput" accept=".jpg" required>
                    <div class="invalid-feedback" id="fileError">
                        Por favor seleccione una imagen válida (JPG).
                    </div>
                </div>
                
                <div id="previewContainer">
                    <p class="text-muted">Vista previa:</p>
                    <img id="imagePreview" alt="Vista previa de la imagen" class="img-thumbnail">
                    <div class="alert alert-info mt-2" id="noPreviewText">
                        No se ha seleccionado ninguna imagen.
                    </div>
                </div>
                
                <div class="d-grid gap-2 mt-3">
                    <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                        Subir Imagen
                    </button>
                </div>
            </form>
        </div>

    </div>
  </div>
</section>

@endsection
<script src="{{asset('libs/jquery/dist/jquery.min.js')}}"></script>
<script>
$(document).ready(function() {
    // Elementos del DOM
    const imageInput = $('#imageInput');
    const imagePreview = $('#imagePreview');
    const noPreviewText = $('#noPreviewText');
    const submitBtn = $('#submitBtn');
    const fileError = $('#fileError');
    
    // Validar y mostrar vista previa cuando se selecciona un archivo
    imageInput.on('change', function() {
        const file = this.files[0];
        
        // Ocultar mensajes de error previos
        imageInput.removeClass('is-invalid');
        fileError.hide();
        
        // Verificar si se seleccionó un archivo
        if (file) {
            // Validar el tipo de archivo
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!validTypes.includes(file.type)) {
                imageInput.addClass('is-invalid');
                fileError.show();
                resetPreview();
                return;
            }
            
            // Validar el tamaño del archivo (opcional, aquí 5MB)
            const maxSize = 5 * 1024 * 1024; // 5MB
            if (file.size > maxSize) {
                fileError.text('El archivo es demasiado grande (máximo 5MB).').show();
                imageInput.addClass('is-invalid');
                resetPreview();
                return;
            }
            
            // Crear vista previa
            const reader = new FileReader();
            
            reader.onload = function(e) {
                imagePreview.attr('src', e.target.result);
                imagePreview.show();
                noPreviewText.hide();
                submitBtn.prop('disabled', false);
            }
            
            reader.readAsDataURL(file);
        } else {
            resetPreview();
        }
    });
    

    // Función para resetear la vista previa
    function resetPreview() {
        imagePreview.hide();
        noPreviewText.show();
        submitBtn.prop('disabled', true);
        imageInput.val('');
    }
});
</script>

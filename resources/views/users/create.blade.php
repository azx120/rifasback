@extends('layouts.app')

@section('content')
<div class="pagetitle">
  <h1>Nuevo Administrador</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.html">Home</a></li>
      <li class="breadcrumb-item">Administrador </li>
      <li class="breadcrumb-item active">Nuevo</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section">
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">crear nuevo Administrador </h5>
          <form class="row g-3" id="submitForm" method="POST"  action="{{ url('users/guardar-usuario') }}" enctype="multipart/form-data">
            @csrf
            <div class="col-md-6">
              <input type="text" class="form-control @error('nombre') is-invalid @enderror" placeholder="nombre" name="nombre" required>
              @if ($errors->has('nombre'))
                <div class="text-danger">
                    {{ $errors->first('nombre') }}
                </div>
              @endif
            </div>

            <div class="col-md-6">
              <input type="text" class="form-control @error('apellido') is-invalid @enderror" placeholder="apellidos" name="apellido" required>
              @if ($errors->has('apellido'))
                <div class="text-danger">
                    {{ $errors->first('apellido') }}
                </div>
              @endif
            </div>

            <div class="col-md-6">
              <input type="text" class="form-control @error('dni') is-invalid @enderror" placeholder="Cedula" name="dni" id="dni" maxlength="10" required>
              <div class="text-danger">
                <p id="textDni"></p>
              </div>
              @if ($errors->has('dni'))
                  <div class="text-danger">
                      {{ $errors->first('dni') }}
                  </div>
              @endif
            </div>

            <div class="col-md-6">
              <input type="email" class="form-control @error('email') is-invalid @enderror" placeholder="correo" name="email" required>
              @if ($errors->has('email'))
                  <div class="text-danger">
                      {{ $errors->first('email') }}
                  </div>
              @endif
            </div>

            <div class="col-md-6">
              <input type="text" class="form-control @error('password') is-invalid @enderror" placeholder="contraseña" name="password" required>
              @if ($errors->has('password'))
                  <div class="text-danger">
                      {{ $errors->first('password') }}
                  </div>
              @endif
            </div>

            <div class="col-md-6">
              <input type="number" class="form-control @error('telefono') is-invalid @enderror" placeholder="telefono" name="telefono" required>
              @if ($errors->has('telefono'))
                  <div class="text-danger">
                      {{ $errors->first('telefono') }}
                  </div>
              @endif
            </div>

            <div class="col-md-6">
              <select class="form-control @error('rol') is-invalid @enderror" name="rol" id="rol" required>
                <option value="" hidden>Seleccione un rol</option>
                <option value="ADMIN">Administrador</option>
    
              </select>
              @if ($errors->has('rol'))
                  <div class="text-danger">
                      {{ $errors->first('rol') }}
                  </div>
              @endif
            </div>

            <!--<div class="quill-editor-full">
            </div>-->

            <div class="row mb-3 mt-5">
              @if ($errors->any())
                @foreach ($errors->all() as $error)
                  <div><p class="fs-5 text-danger">{{$error}}</p></div>
                @endforeach
              @endif
              <div class="">
                  <button id="guardarForm" class="btn btn-primary">Guardar</button>
              </div>
            </div>
          </form><!-- End General Form Elements -->
        </div>
      </div>
    </div>
  </div>
</section>

<script src="{{asset('libs/jquery/dist/jquery.min.js')}}"></script>
<script>
$(document).ready(function(){

  $("#submitForm").on("submit", function () {
    //var hvalue = $('.ql-editor').html();
    //alert(hvalue)
    //$(this).append("<textarea name='descripcion' style='display:none'>"+hvalue+"</textarea>");
   });
});
</script>

<script>

$("#dni").on("focusout", function(e){
  var cedula = $(this).val();
  validateDni(cedula);
});

$("#guardarForm").on("click", function(e){
  var cedula = $("#dni").val();
  var dniValidate = validateDni(cedula);
  if(dniValidate == "invalidate"){
    e.preventDefault()
  }

});


 
function validateDni (cedula){
     //Preguntamos si la cedula consta de 10 digitos 0701396830
     if(cedula.length == 10){
        
        //Obtenemos el digito de la region que sonlos dos primeros digitos
        var digito_region = cedula.substring(0,2);
        
        //Pregunto si la region existe ecuador se divide en 24 regiones
        if( digito_region >= 1 && digito_region <=24 ){
          
          // Extraigo el ultimo digito
          var ultimo_digito   = cedula.substring(9,10);

          //Agrupo todos los pares y los sumo
          var pares = parseInt(cedula.substring(1,2)) + parseInt(cedula.substring(3,4)) + parseInt(cedula.substring(5,6)) + parseInt(cedula.substring(7,8));

          //Agrupo los impares, los multiplico por un factor de 2, si la resultante es > que 9 le restamos el 9 a la resultante
          var numero1 = cedula.substring(0,1);
          var numero1 = (numero1 * 2);
          if( numero1 > 9 ){ var numero1 = (numero1 - 9); }

          var numero3 = cedula.substring(2,3);
          var numero3 = (numero3 * 2);
          if( numero3 > 9 ){ var numero3 = (numero3 - 9); }

          var numero5 = cedula.substring(4,5);
          var numero5 = (numero5 * 2);
          if( numero5 > 9 ){ var numero5 = (numero5 - 9); }

          var numero7 = cedula.substring(6,7);
          var numero7 = (numero7 * 2);
          if( numero7 > 9 ){ var numero7 = (numero7 - 9); }

          var numero9 = cedula.substring(8,9);
          var numero9 = (numero9 * 2);
          if( numero9 > 9 ){ var numero9 = (numero9 - 9); }

          var impares = numero1 + numero3 + numero5 + numero7 + numero9;

          //Suma total
          var suma_total = (pares + impares);

          //extraemos el primero digito
          var primer_digito_suma = String(suma_total).substring(0,1);

          //Obtenemos la decena inmediata
          var decena = (parseInt(primer_digito_suma) + 1)  * 10;

          //Obtenemos la resta de la decena inmediata - la suma_total esto nos da el digito validador
          var digito_validador = decena - suma_total;

          //Si el digito validador es = a 10 toma el valor de 0
          if(digito_validador == 10)
            var digito_validador = 0;

          //Validamos que el digito validador sea igual al de la cedula
          if(digito_validador == ultimo_digito){

            $("#textDni").removeClass('text-danger');
            $("#textDni").addClass('text-success');
            $("#textDni").text('la cedula: es correcta');
            return "validate"
          }else{
            $("#textDni").removeClass('text-success');
            $("#textDni").addClass('text-danger');
            $("#textDni").text('la cedula:es incorrecta');
            return "invalidate"
          }
          
        }else{
          // imprimimos en consola si la region no pertenece
          $("#textDni").removeClass('text-success');
          $("#textDni").addClass('text-danger');
          $("#textDni").text('Esta cedula no pertenece a ninguna region');
          return "invalidate"
        }
     }else{
        //imprimimos en consola si la cedula tiene mas o menos de 10 digitos
        $("#textDni").removeClass('text-success');
        $("#textDni").addClass('text-danger');
        $("#textDni").text('Esta cedula tiene menos de 10 Digitos');
        return "invalidate"
     }    
  
};
</script>
@endsection
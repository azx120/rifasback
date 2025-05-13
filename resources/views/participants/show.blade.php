@extends('layouts.app')

@section('content')
<div class="pagetitle">
  <h1>Numeros Talonario</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.html">Home</a></li>
      <li class="breadcrumb-item">Talonarios</li>
      <li class="breadcrumb-item active">Nuevo</li>
    </ol>
  </nav>
</div><!-- End Page Title -->
<style>
        .selectable-cell {
            cursor: pointer;
            transition: background-color 0.3s;
            text-align: center;
            font-weight: bold;
            padding: 10px;
            border: 1px solid #dee2e6;
            margin: 3px;

        }
        
        .selected {
            background-color: #6c757d !important; /* Gris */
            color: white !important;
        }
        
        .unselected {
            background-color: #1db16c !important; /* Verde Bootstrap */
            color: white !important;
        }
        
        #selectedNumbers {
            font-weight: bold;
        }
    </style>
<section class="section">
  <div class="row">
    <div class="col-lg-12">

      <div class="card">
        <div class="card-body">
        <h5 class="card-title">Datos de Participante</h5>        
        <div class="row mb-3 mt5">
            <input type="text" class="form-control" id="selectedNumbers" name='numbers' readonly disabled>
            <input type="hidden" class="form-control" value='{{$data->id}}' name='id' disabled>
            <div class="col-md-6 mt-3 mb-3">
                <label for="selectedNumbers" class="form-label" >Nombre:</label>
                <input type="text" class="form-control" id="name" name='name' value="{{$data->name}}" disabled>
            </div>
            <div class="col-md-6 mt-3 mb-3">
                <label for="selectedNumbers" class="form-label" >Apellido:</label>
                <input type="text" class="form-control" id="lastname" name='lastname' value="{{$data->lastname}}" disabled>
            </div>
            <div class="col-md-6 mt-3 mb-3">
                <label for="selectedNumbers" class="form-label" >Cedula:</label>
                <input type="text" class="form-control" id="ci" name='ci' value="{{$data->ci}}" disabled>
            </div>
            <div class="col-md-6 mt-3 mb-3">
                    <label for="selectedNumbers" class="form-label form-label-sm" >Cedula:</label>
                    <input type="email" class="form-control form-control-sm" id="email" name='email' value="{{$data->email}}" required>
                </div>
            <div class="col-md-6 mt-3 mb-3">
                <label for="selectedNumbers" class="form-label" >Número Telefono:</label>
                <input type="text" class="form-control" id="phone" name='phone' value="{{$data->phone}}" disabled>
            </div>
            <div class="col-md-6 mb-3">
                <label for="provincia" class="form-label">Provincia:</label>
                <select id="provincia" class="form-select" name="provincia">

                    <option value="{{$provincias->id}}">{{$provincias->name}}</option>
                    
                </select> 
            </div>
            <div class="col-md-6 mb-3">
                <label for="ciudad" class="form-label">Ciudad:</label>
                <select id="ciudad" class="form-select" name="ciudad">
                    
                    <option value="{{$ciudades->id}}" selected>{{$ciudades->name}}</option>

                </select> 
            </div>
        </div>
        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Rifa</th>
                                        <th>Finaliza</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($talonarios as $talonario)
                                    <tr>
                                        <td>{{$talonario->title}}</td>                                        
                                        <td>{{$talonario->endDate}}</td>
                                        </td>
                                        <td><span class="badge badge-active">@if ($talonario->status == 1) Activo @else Caducado @endif</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
        // Array to store selected numbers
        let selectedNumbers = [];
        
        // Handle cell click
        $('.selectable-cell').click(function() {
            const cellId = $(this).attr('id');
            
            // Check if number is already selected
            const index = selectedNumbers.indexOf(cellId);
            
            if (index === -1) {
                // Add to selection
                selectedNumbers.push(cellId);
                $(this).removeClass('unselected').addClass('selected');
            } else {
                // Remove from selection
                selectedNumbers.splice(index, 1);
                $(this).removeClass('selected').addClass('unselected');
            }
            
            // Update the input field
            $('#selectedNumbers').val(selectedNumbers.join(','));
        });

        $('#provincia').on('change', function() {

        let provinciaId = this.value;
        let baseUrl = '{{ url('ciudades') }}/' + provinciaId + '/ciudades-por-provincia';
        $.ajax({
            type: "GET",
            url: baseUrl,
            success: function(response)
            {
                console.log(response.ciudades);
                var data = JSON.parse(response.ciudades);
                
                $('#ciudad').children('option:not(:first)').remove();
                
                
                $('#ciudad').append('<option selected hidden value="">Escoger...</option>');
            

                $.each(data, function(index,dato){
                $("#ciudad").append('<option value="'+dato.id+'">'+dato.name+'</option>');
                });
            }
        });
    });
    });
</script>

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
       .number-grid {
            display: grid;
            grid-template-columns: repeat(10, 1fr);
            gap: 8px;
            margin: 20px auto;
            max-width: 800px;
            
        }
        
        .number {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            user-select: none;
            border-radius: 4px;
            position: relative;
            background-color: #4CAF50;
        }
        
        .number:hover:not(.reserved) {
            background-color: #f0f0f0;
        }
        
        .selected {
            background-color: #6c757d !important; /* Gris */
            color: white !important;
        }
        
        .reserved {
            background-color: #f44336;
            color: white;
            cursor: not-allowed;
            opacity: 0.7;
        }

        .sold {
            background-color: #544f4e;
            color: white;
            cursor: not-allowed;
            opacity: 0.7;
        }

        .winner {
            background-color: #544f4e;
            color: white;
            cursor: not-allowed;
            opacity: 0.7;
        }
        
        .winner-plus {
            background-color: #ffb623;
            color: white;
            cursor: not-allowed;
            opacity: 0.7;
        }
        
        .hidden-input {
            display: none;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            margin: 20px 0;
            gap: 5px;
        }
        
        .page-btn {
            padding: 8px 12px;
            border: 1px solid #ddd;
            background-color: #f8f8f8;
            cursor: pointer;
            border-radius: 4px;
        }
        
        .page-btn.active {
            background-color: #4CAF50;
            color: white;
            border-color: #388E3C;
        }
        
        .page-btn:hover:not(.active) {
            background-color: #ddd;
        }
        
        .selection-info {
            text-align: center;
            margin: 10px 0;
            font-weight: bold;
        }
        
        .status-badge {
            position: absolute;
            top: -8px;
            right: -4px;
            background: #c37400;
            color: white;
            border-radius: 10%;
            padding: 2px;
            height: 20px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .status-badge-sold {
            position: absolute;
            top: -8px;
            right: -4px;
            background: #000000;
            color: white;
            border-radius: 10%;
            padding: 2px;
            height: 20px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .status-badge-winner {
            position: absolute;
            top: -8px;
            right: -4px;
            background: #b97c00;
            color: white;
            border-radius: 10%;
            padding: 2px;
            height: 20px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
<section class="section">
  <div class="row">
    <div class="col-lg-12">
    @include('layouts.alerts')
      <div class="card">
        <div class="card-body">
        <h5 class="card-title">Numeros de Talonario</h5>
        <div class="table-responsive">
            <div class="pagination" style="visibility:hidden;" id="pagination"></div>
            
            <div class="number-grid" id="numberGrid"></div>
            
            <div class="pagination" id="paginationBottom"></div>
        </div>
        
        <div class="mb-3 mt5">
            <form class="row g-3" method="POST"  action="{{ url('talonarios/tomar-numero') }}" enctype="multipart/form-data">
                @csrf
                <input type="text" class="form-control form-control-sm" id="selectedNumbers" name='numbers' readonly required>
                <input type="hidden" class="form-control form-control-sm" value='{{$data->id}}' name='id' required>
                <input type="hidden" class="form-control form-control-sm" value='' name='id_tra' id="id_tra">
                <div class="col-md-6 mt-3 mb-3">
                    <label for="selectedNumbers" class="form-label form-label-sm" >Nombre:</label>
                    <input type="text" class="form-control form-control-sm" id="name" name='name' required>
                </div>
                <div class="col-md-6 mt-3 mb-3">
                    <label for="selectedNumbers" class="form-label form-label-sm" >Apellido:</label>
                    <input type="text" class="form-control form-control-sm" id="lastname" name='lastname' required>
                </div>
                <div class="col-md-6 mt-3 mb-3">
                    <label for="selectedNumbers" class="form-label form-label-sm" >Cedula:</label>
                    <input type="text" class="form-control form-control-sm" id="ci" name='ci' required>
                </div>
               <div class="col-md-6 mt-3 mb-3">
                    <label for="selectedNumbers" class="form-label form-label-sm" >Correo:</label>
                    <input type="email" class="form-control form-control-sm" id="email" name='email'>
                </div>
                <div class="col-md-6 mt-3 mb-3">
                    <label for="selectedNumbers" class="form-label form-label-sm" >Número Telefono:</label>
                    <input type="text" class="form-control form-control-sm" id="phone" name='phone' required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="provincia" class="form-label form-label-sm">Provincia:</label>
                    <select id="provincia" class="form-select form-select-sm" name="provincia">
                        <option selected hidden value="">Escoger...</option>
                        @foreach ($provincias as $provincia)
                        <option value="{{$provincia->id}}">{{$provincia->name}}</option>
                        @endforeach
                    </select> 
                </div>
                <div class="col-md-6 mb-3">
                    <label for="ciudad" class="form-label form-label-sm">Ciudad:</label>
                    <select id="ciudad" class="form-select form-select-sm" name="ciudad">
                        <option selected hidden value="">Escoger...</option>
                    </select> 
                </div>
                <div class="">
                    <button class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
        <div class="">
            <p>Numeros Ganadores:</p>
        </div>  
        <div class="d-flex flex-wrap gap-2">
            @foreach($data->winners as $winner)
                @if($winner->status == "winner plus")
                    <a type="button" class="btn btn-warning" @if($winner->participant != '') href="{{ url('participantes/' . $winner->participant . '/ver-participante-cedula') }}" @endif>{{$winner->id}} 👑</a>
                @else 
                    <a type="button" class="btn btn-secondary" @if($winner->participant != '') href="{{ url('participantes/' . $winner->participant . '/ver-participante-cedula') }}" @endif>{{$winner->id}} 👑</a>
                @endif
            @endforeach
        </div>
        <table class="table datatable">
            <thead>
              <tr>
                <th>#</th>
                <th>Nro</th>
                <th>Cedula</th>
                <th>
                  <b>N</b>ame
                </th>
                <th>Apellido</th>
                <th>Telefono</th>
                <th>Cantidad</th>
                <th>Numeros</th>
                <th>Comprobante</th>
                <th>acciones</th>
              </tr>
            </thead>
            <tbody id="pagosdiv">
                @foreach ($pagos as $item)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $item->code }}</td>
                        <td>{{ $item->ci }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->lastname }}</td>
                        <td>{{ $item->phone }}</td>
                        <td>{{ $item->numbers }}</td>
                        <td style="width: 30%; max-width: 250px;">{{ $item->selected_numbers }}</td>
                        <td><a href="{{ $item->comprobante }}" target="_blank"><i class="bi bi-eye-fill"></i>Ver</a></td>
                        @if($item->status == 'process')
                        <td>
                            <!-- Editar Categoria -->
                            <a class="btn waves-effect waves-light btn-sm btn-success" onClick="accion_numbers('{{$item->id}}', '{{$item->ci}}', '{{$item->numbers}}', 'validar')">
                                Elegir numeros
                            </a>
                            <!-- <a class="btn waves-effect waves-light btn-sm btn-danger accion-numbers" id_tal="{{$item->id}}" numbers="{{$item->numbers}}" type="rechazar">
                                Liberar
                            </a>       -->                           
                        </td>
                        @elseif($item->status == 'complete')
                        <td>Completado</td>
                        @elseif($item->status == 'refused')
                        <td>Rechazado</td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
</section>

@endsection
<script src="{{asset('libs/jquery/dist/jquery.min.js')}}"></script>

<script>
    $(document).ready(function() {
        const arrayNumbers = {!! $data->array_numbers !!};

        // Configuración
        const numbersPerPage = 50;
        
        // Variables de estado
        const selectedNumbers = [];
        let currentPage = 1;
        const totalPages = Math.ceil(arrayNumbers.length / numbersPerPage);
        
        // Inicializar
        createPagination();
        createNumberGrid();
        
        // Generar la cuadrícula de números para la página actual
        function createNumberGrid() {
            $('#numberGrid').empty();
            
            const startIndex = (currentPage - 1) * numbersPerPage;
            const endIndex = Math.min(currentPage * numbersPerPage, arrayNumbers.length);
            
            for(let i = startIndex; i < endIndex; i++) {
                const numberData = arrayNumbers[i];
                const numberElement = $('<div>').addClass('number').text(numberData.id);
                
                // Marcar como seleccionado si está en el array
                if(selectedNumbers.includes(numberData.id)) {
                    numberElement.addClass('selected');
                }
                
                // Marcar como reservado si no está libre
                if(numberData.status == 'reserved') {
                    let texto = "";
                    let class_t = "";
                    if(numberData.winner == true){
                        texto = "Ganador 👑"
                        class_t = "winner"
                        class_w = 'status-badge-winner'
                    }else{
                        texto = "Rerservado"
                        class_t = "reserved"
                        class_w = 'status-badge'

                    }
                    numberElement.addClass(class_t);
                    
                    // Mostrar badge si está reservado
                    if(numberData.participant) {
                        $('<div>')
                            .addClass(class_w)
                            .text(texto)
                            .attr('title', `Reservado por: ${numberData.participant}`)
                            .appendTo(numberElement);
                    }
                }else if (numberData.status == 'winner plus') {
                    
                    let texto = "Ganador 👑"
                    let  class_t = "winner-plus"
                    let  class_w = 'status-badge-winner'
                   
                    numberElement.addClass(class_t);
                    // Mostrar badge si está reservado

                        $('<div>')
                        .addClass(class_w)
                        .text(texto)
                        .attr('title', `Comprado por: ${numberData.participant}`)
                        .appendTo(numberElement);

                }else if (numberData.status == 'sold') {
                    
                    let texto = "";
                    let class_t = "";
                    if(numberData.winner == true){
                        texto = "Ganador 👑"
                        class_t = "winner"
                        class_w = 'status-badge-winner'
                    }else{
                        texto = "vendido"
                        class_t = "sold"
                        class_w = 'status-badge-sold'
                    }
                    numberElement.addClass(class_t);
                    // Mostrar badge si está reservado
                    if(numberData.participant) {
                        $('<div>')
                        .addClass(class_w)
                        .text(texto)
                        .attr('title', `Comprado por: ${numberData.participant}`)
                        .appendTo(numberElement);
                    }
                }else {
                    let texto = "";
                    let class_t = "";
                    if(numberData.winner == true){
                        texto = "Ganador 👑"
                        class_t = "winner"
                        numberElement.addClass(class_t);
                        // Mostrar badge si está reservado

                        $('<div>')
                        .addClass('status-badge-winner')
                        .text(texto)
                        .attr('title', `Comprado por: `)
                        .appendTo(numberElement);
                        
                    }else{
                        numberElement.on('click', function() {
                        //toggleNumber(numberData.id);
                    });
                    }
                   
                }
                
                $('#numberGrid').append(numberElement);
            }
        }
        
        // Función para manejar la selección
        function toggleNumber(number) {
            const index = selectedNumbers.indexOf(number);
            
            if(index === -1) {
                selectedNumbers.push(number);
            } else {
                selectedNumbers.splice(index, 1);
            }
            
            // Actualizar la vista
            updateSelectionView();
            // Volver a renderizar para actualizar los estilos
            createNumberGrid();
        }
        
        // Actualizar la información de selección
        function updateSelectionView() {
            $('#selectedCount').text(selectedNumbers.length);
            $('#selectedNumbers').val(JSON.stringify(selectedNumbers));
        }
        
        // Crear controles de paginación
        function createPagination() {
            $('#pagination, #paginationBottom').empty();
            
            // Botón Anterior
            createPageButton('«', currentPage > 1 ? currentPage - 1 : 1);
            
            // Páginas numeradas
            const maxVisiblePages = 5; // Máximo de páginas visibles en la paginación
            let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
            let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
            
            // Ajustar si estamos cerca del final
            if(endPage - startPage + 1 < maxVisiblePages) {
                startPage = Math.max(1, endPage - maxVisiblePages + 1);
            }
            
            // Mostrar primera página si no está visible
            if(startPage > 1) {
                createPageButton(1, 1);
                if(startPage > 2) {
                    $('<span>').text('...').appendTo('#pagination').clone().appendTo('#paginationBottom');
                }
            }
            
            // Páginas visibles
            for(let i = startPage; i <= endPage; i++) {
                createPageButton(i, i);
            }
            
            // Mostrar última página si no está visible
            if(endPage < totalPages) {
                if(endPage < totalPages - 1) {
                    $('<span>').text('...').appendTo('#pagination').clone().appendTo('#paginationBottom');
                }
                createPageButton(totalPages, totalPages);
            }
            
            // Botón Siguiente
            createPageButton('»', currentPage < totalPages ? currentPage + 1 : totalPages);
        }
        
        // Crear botón de página
        function createPageButton(text, page) {
            const pageBtn = $('<button>').addClass('page-btn').text(text);
            
            if(page === currentPage) {
                pageBtn.addClass('active');
            }
            
            pageBtn.on('click', function() {
                if(page !== currentPage) {
                    currentPage = page;
                    createNumberGrid();
                    createPagination();
                }
            });
            
            pageBtn.clone().appendTo('#pagination');
            pageBtn.appendTo('#paginationBottom');
        }
    });
    async function accion_numbers(id, ci,qty,type) {
    if(type == "validar"){
        const arrayNumbers = {!! $data->array_numbers !!};

        $("#id_tra").val(id)

        let baseUrl = '{{ url('participantes') }}/' + ci + '/ver-participante-id';
        $.ajax({
            type: "GET",
            url: baseUrl,
            success: function(response)
            {
                console.log(response.participante);
                var data = JSON.parse(response.participante);
                $('#name').val(data.name)
                $('#lastname').val(data.lastname)
                $('#phone').val(data.phone)
                $('#ci').val(data.ci)
                $('#email').val(data.email)
                $('#provincia').val(data.province_id)
                
                $("#ciudad").append('<option value="'+data.city_id+'" selected>'+data.city_name+'</option>');

            }
        });
        const cantidad = qty;


        //1. Filtrar elementos con status "free" y winner false
        const freeElements = arrayNumbers.filter(item => 
            item.participant === ""
        );

        // 2. Mezclar y seleccionar la cantidad deseada
            const shuffled = await [...freeElements].sort(() => 0.5 - Math.random());
        const selected = shuffled.slice(0, cantidad);

        // 3. Actualizar los seleccionados
        let select_numbers = '';
        selected.forEach(item => {
            if(select_numbers == ''){
                select_numbers = '"'+item.id+'",';
            }else{
                select_numbers = select_numbers+'"'+item.id+'",';
            }
                
            // Opcional: Cambiar status a "reserved"
            // item.status = "reserved";
        });
        select_numbers = select_numbers.slice(0, -1)
        // Mostrar resultados
        //const winners = arrayNumbers.filter(item => item.participant === ci);
        $('#selectedNumbers').val('['+select_numbers+']')
        
        /*$("#resultado").html(`
            <h2>Ganadores (${winners.length}):</h2>
            <pre>${JSON.stringify(winners, null, 2)}</pre>
        `);*/
    }else{
        let baseUrl = '{{ url("talonarios/accion-numbers") }}';
        if(confirm("esta seguro de esta accion?") == true){
            await $.ajax({
                type: "POST",
                url: baseUrl,
                data: {
                "_token": "{{ csrf_token() }}",
                'id': $(this).attr('id_tal'),
                'numbers': $(this).attr('numbers'),
                'type': $(this).attr('type'),
                },
                success: function(response)
                {
                    alert(response);
                    location.reload();
                }
            });
        } 

    }
}
</script>

<script>

$(document).ready(function() {

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

    function getPagos() {

        let baseUrl = '{{ url('pagos') }}/{{$data->id}}/pagos-talonario';
        $.ajax({
            type: "GET",
            url: baseUrl,
            success: function(response)
            {
                console.log(response.pagos);
                var data = JSON.parse(response.pagos);
                $('table.datatable-table tbody').empty();
                
                $.each(data, function(index,dato){
                    $('table.datatable-table tbody').append(`
                        <tr data-index="${index}">
                            <td scope="row">${index+1}</td>
                            <td>${dato.code}</td>
                            <td>${dato.ci}</td>
                            <td>${dato.name}</td>
                            <td>${dato.lastname}</td>
                            <td class="green">${dato.phone}</td>
                            <td>${dato.numbers}</td>
                            <td style="width: 30%; max-width: 250px;">${dato.selected_numbers}</td>
                            <td><a href="${dato.comprobante}" target="_blank"><i class="bi bi-eye-fill"></i>Ver</a></td>
                            ${(() => {
                                if (dato.status == 'process') {
                                return `
                                    <td>
                                        <button class="btn waves-effect waves-light btn-sm btn-success" onClick="accion_numbers('${dato.id}', '${dato.ci}', '${dato.numbers}', 'validar')" id_tal="" qty="" numbers="" type="validar">
                                            Elegir numeros
                                        </button>                          
                                    </td>

                                `                       
                      
                                }else if(dato.status == 'complete') {
                                    return "<td>Completado</td>"; 
                                }else{
                                    return  "<td>Rechazado</td>";
                                }
                            })()}
                        </tr>
                    `);
                });
            }
        });
    }
    //setInterval(getPagos, 60000);
  setInterval(getPagos, 120000);

});

</script>

<!-- 

if($item->status == 'process')
                        <td>
                         Editar Categoria 
                            <a class="btn waves-effect waves-light btn-sm btn-success accion-numbers" id_tal="$item->id}}" qty="$item->numbers }}" numbers="$item->ci}}" type="validar">
                                Elegir numeros
                            </a>                          
                        </td>
                        elseif($item->status == '')
                        
                        elseif($item->status == 'refused')
                        
                        endif-->

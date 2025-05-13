@extends('layouts.app')

@section('content')
<div class="container-fluid">
        <!-- Encabezado del Dashboard -->
        <div class="dashboard-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h3><i class="fas fa-ticket-alt me-2"></i>Escritorio</h3>
                    <p class="mb-0">Resumen general del sistema</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="d-inline-block me-3">
                        <span class="badge bg-light text-dark"><i class="fas fa-calendar-alt me-1"></i> <span id="current-date"></span></span>
                    </div>
                    <div class="d-inline-block">
                        <span class="badge bg-light text-dark"><i class="fas fa-clock me-1"></i> <span id="current-time"></span></span>
                    </div>
                </div>
            </div> 
        </div>
        
        <!-- Widgets de Estadísticas -->
        <div class="row">
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="icon text-primary">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="number" id="total-participants">{{count($participants)}}</div>
                    <div class="label">Participantes Totales</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="icon text-success">
                        <i class="bi bi-ticket-detailed-fill"></i>
                    </div>
                    <div class="number" id="tickets-sold">{{$tickets_sell}}</div>
                    <div class="label">Boletos Vendidos</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="icon text-warning">
                        <i class="bi bi-calendar3"></i>
                    </div>
                    <div class="number" id="expiring-raffles">{{count($talonarios_expire)}}</div>
                    <div class="label">Rifas Finalizadas</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="icon text-danger">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <div class="number" id="active-raffles">{{count($talonarios_actives)}}</div>
                    <div class="label">Rifas Activas</div>
                </div>
            </div>
        </div>
        
        <!-- Sección Principal -->
        <div class="row">
            <!-- Columna Izquierda -->
            <div class="col-lg-8">
                <!-- Rifas con más participación -->
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-trophy-fill"></i> Rifas con Más Participación
                    </div>
                    <div class="card-body mt-3">
                        <div class="table-responsive">
                            <table class="table table-hover datatable">
                                <thead>
                                    <tr>
                                        <th>Rifa</th>
                                        <th>Participantes</th>
                                        <th>Vendidos</th>
                                        <th>Progreso</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($talonarios as $talonario)
                                    <tr>
                                        <td>{{$talonario->title}}</td>
                                        <td>{{count($talonario->participants)}}</td>
                                        <td>{{$talonario->tickets_sell}}/{{$talonario->numbers}}</td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" style="width: {{($talonario->tickets_sell / $talonario->numbers) * 100}}%" aria-valuenow="{{($talonario->tickets_sell / $talonario->numbers) * 100}}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </td>
                                        <td><span class="badge badge-active">@if ($talonario->status == 1) Activo @else Caducado @endif</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Bitácora de Sesión -->
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-clock-history"></i> Bitácora de Actividad Reciente
                    </div>
                    <div class="card-body mt-3">
                        <div class="table-responsive">
                            <table class="table table-hover datatable"> 
                                <thead>
                                    <tr>
                                        <th>Fecha/Hora</th>
                                        <th>IP</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($bitacoras as $bitacora)
                                
                                    <tr>
                                        <td>{{$bitacora->created_at}}</td>
                                        <td>{{$bitacora->ip}}</td>
                                        <td>{{$bitacora->comment}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Columna Derecha -->
            <div class="col-lg-4">
                <!-- Rifas por Caducar -->
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-exclamation-triangle-fill"></i> Rifas por Caducar
                    </div>
                    <div class="card-body mt-3">
                        <div class="list-group">
                            @foreach($talonarios_expire as $talonario)
                            <a href="#" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{$talonario->title}}</h6>
                        
                                </div>
                                <!-- <p class="mb-1">{{$talonario->tickets_sell}}/{{$talonario->numbers}} boletos vendidos</p>-->
                                <small>Finaliza: {{$talonario->endDate}}</small>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <!-- Clientes que más han comprado -->
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-star-fill"></i> Clientes Destacados
                    </div>
                    <div class="card-body mt-3">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Cliente</th>
                                        <th>Boletos</th>
                                        <th>Participaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($participants as $participant)
                                    <tr>
                                        <td>
                                            {{$participant->name}} {{$participant->lastname}}
                                            ci: {{$participant->ci}}
                                        </td>
                                        <td>{{$participant->tickets_all}}</td>
                                        <td>1</td>
                                    </tr>
                                    @endforeach
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Actualizar fecha y hora en tiempo real
            function updateDateTime() {
                const now = new Date();
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                $('#current-date').text(now.toLocaleDateString('es-ES', options));
                $('#current-time').text(now.toLocaleTimeString('es-ES'));
            }
            
            updateDateTime();
            setInterval(updateDateTime, 1000);
            
            // Simular datos dinámicos (en un sistema real, estos vendrían de una API)
            setInterval(function() {
                // Actualizar números aleatorios para demostración
                //const randomParticipants = Math.floor(1248 + Math.random() * 50);
                //const randomTickets = Math.floor(5732 + Math.random() * 200);
                //const randomExpiring = Math.floor(7 + Math.random() * 2);
                //const randomActive = Math.floor(12 + Math.random() * 3);
                
                $('#total-participants').text(randomParticipants.toLocaleString());
                $('#tickets-sold').text(randomTickets.toLocaleString());
                $('#expiring-raffles').text(randomExpiring);
                $('#active-raffles').text(randomActive);
            }, 5000);
            
            // Aquí iría el código para cargar datos reales desde una API
            /*
            $.ajax({
                url: '/api/dashboard-stats',
                method: 'GET',
                success: function(data) {
                    $('#total-participants').text(data.totalParticipants);
                    $('#tickets-sold').text(data.ticketsSold);
                    // ... etc
                }
            });
            */
        });
    </script>
@endsection

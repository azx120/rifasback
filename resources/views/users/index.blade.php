@extends('layouts.app')

@section('content')
<div class="pagetitle">
  <h1>Personal Accumedical</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
      <li class="breadcrumb-item">coordinadores</li>
      <li class="breadcrumb-item active">Data</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section">
  <div class="row">
    <div class="col-lg-12">

      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Administradores</h5>
          @include('layouts.alerts')
          <div class="col-2">
              <a href="{{ route('users.create')}}" class="btn waves-effect waves-light btn-info">Nuevo Usuario</a>
          </div>

                  <!-- Table with stripped rows -->
          <table class="table datatable">
            <thead> 
              <tr>
                <th>
                  #
                </th>
                <th>Administrador</th>
                <th>correo</th>
                <th>Labor</th>
                <th>Estado</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $item->name }} {{ $item->lastname }}</td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->rol }}</td>
                        <td>{{ $item->status }}</td>
                        <td>
                            <!-- Editar Categoria -->
                            <a href="{{ url('users/' . $item->id . '/editar-usuario') }}"
                                class="btn waves-effect waves-light btn-sm btn-warning">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            @if(Auth::user()->rol == "ADMIN") 
                            <!-- Eliminar Categoria 
                            <button type="button" class="btn waves-effect waves-light btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal-{{ $item->id }}">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                            @endif
                            <div class="modal" tabindex="-1" id="exampleModal-{{ $item->id }}">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <form method="POST"
                                                action="{{ url('users/' . $item->id . '/deshabilitar-usuario') }}">
                                                @method('POST')
                                                @csrf
                                                <div class="modal-header modal-colored-header bg-warning">
                                                    <h4 class="modal-title"
                                                        id="warning-header-modalLabel">
                                                        Alerta
                                                    </h4>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <h1 class=""><i
                                                            class="fas fa-exclamation-triangle text-warning"></i>
                                                    </h1>
                                                    <h3 class="mt-1 text-danger">¿Está Seguro de deshabilitar el
                                                        Registro?</h5>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                    <button type="submit" class="btn btn-warning">Si, Estoy
                                                        Seguro</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>-->
                                    
                        </td>
                    </tr>
                @endforeach
            </tbody>
          </table>
          <!-- End Table with stripped rows -->

        </div>
      </div>

    </div>
  </div>
</section>

@endsection

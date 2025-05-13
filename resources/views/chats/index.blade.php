@extends('layouts.app')

@section('content')
<div class="pagetitle">
  <h1>Chats</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.html">Home</a></li>
      <li class="breadcrumb-item">Chats</li>
      
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section">
  <div class="row">
    <div class="col-lg-12">

      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Lista De usarios Con chat</h5>

          <!-- Table with stripped rows -->
          <table class="table datatable">
            <thead>
              <tr>
                <th>
                  #
                </th>
                <th><b>N</b>ame</th>
                <th>Telefono</th>
                <th data-type="date" data-format="YYYY/DD/MM">Start Date</th>
                <th>Completion</th>
              </tr>
            </thead>
            
            <tbody>
                @foreach ($data as $item)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->telefono }}</td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->rol }}</td>
                        <td>
                            <!-- Editar Categoria -->
                            <a href="{{ url('chatsUser/' . $item->telefono . '/ver') }}"
                                class="btn waves-effect waves-light btn-sm btn-info">
                                <i class="bi bi-chat"></i>
                            </a>
                            <!-- Eliminar Categoria 
                            <button type="button" class="btn waves-effect waves-light btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal-{{ $item->id }}">
                                <i class="bi bi-trash-fill"></i>
                            </button>-->

                            <div class="modal" tabindex="-1" id="exampleModal-{{ $item->id }}">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <form method="POST"
                                                action="">
                                                @method('DELETE')
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
                                                    <h3 class="mt-1 text-danger">¿Está Seguro de Eliminar el
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
                            </div>
                                    
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

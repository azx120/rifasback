@extends('layouts.app')

@section('content')
<div class="pagetitle">
  <h1>Data Tables Planes</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.html">Home</a></li>
      <li class="breadcrumb-item">Planes</li>
 
    </ol>

  </nav>
</div><!-- End Page Title -->

<section class="section">
  <div class="row">
    <div class="col-lg-12">

      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Datatables</h5>
          <p>Add lightweight datatables to your project with using the <a href="https://github.com/fiduswriter/Simple-DataTables" target="_blank">Simple DataTables</a> library. Just add <code>.datatable</code> class name to any table you wish to conver to a datatable. Check for <a href="https://fiduswriter.github.io/simple-datatables/demos/" target="_blank">more examples</a>.</p>

          <!-- Table with stripped rows -->
          <table class="table datatable">
            <thead>
              <tr>
                <th width="5px">#</th>
                <th width="500px">Plan</th>
                <th width="500px">usuario</th>
                <th width="500px">email</th>
                <th width="500px">status</th>
                <th width="500px">expired</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->precio }}</td>
                        <td>{{ $item->status }}</td>
                        <td>
                            <!-- Editar Categoria -->
                            <a href="{{ url('planes/' . $item->id . '/editar-plan') }}"
                                class="btn waves-effect waves-light btn-sm btn-warning">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <!-- Eliminar Categoria -->
                            <button type="button" class="btn waves-effect waves-light btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal-{{ $item->id }}">
                                <i class="bi bi-trash-fill"></i>
                            </button>

                            <div class="modal" tabindex="-1" id="exampleModal-{{ $item->id }}">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <form method="POST"
                                                action="{{ url('planes/' . $item->id . '/borrar-plan') }}">
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

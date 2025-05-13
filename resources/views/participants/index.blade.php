@extends('layouts.app')

@section('content')
<div class="pagetitle">
  <h1>Data Tables</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.html">Home</a></li>
      <li class="breadcrumb-item">Tables</li>
      <li class="breadcrumb-item active">Data</li>
    </ol>
  </nav>
</div><!-- End Page Title -->

<section class="section">
  <div class="row">
    <div class="col-lg-12">

      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Participantes</h5>

          <!-- Table with stripped rows -->
          <table class="table datatable">
            <thead>
              <tr>
                <th>#</th>
                <th>Cedula</th>
                <th>
                  <b>N</b>ame
                </th>
                <th>Apellido</th>
                <th>Telefono</th>
                <th>acciones</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $item->ci }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->lastname }}</td>
                        <td>{{ $item->phone }}</td>
                        <td>
                            <!-- Editar Categoria -->
                            <a href="{{ url('participantes/' . $item->id . '/ver-participante') }}"
                                class="btn waves-effect waves-light btn-sm btn-warning">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                                    
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

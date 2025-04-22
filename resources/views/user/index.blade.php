@extends('layout')

@section('title')
<title>Lista de profesores</title>
@endsection

@section('content')
<div class="container">
    <div class="col-lg-12">

        @if (Session::has('changed'))
        <div class="alert alert-secondary alert-dismissible fade show" role="alert">
            {!! session('changed') !!}
        </div>
        @endif

      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h5 class="card-title text-primary">Lista de Docentes</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover" id="datat">
                <thead>
                    <tr class="table-light">
                        <th>Apellidos</th>
                        <th>Nombres</th>
                        <th>Correo</th>
                        <th>Telefono</th>
                        <th>Area</th>
                        <th>Nro. de registros de asistencia</th>
                        <th>Usuario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                	@foreach($users as $user)
                    <tr>
                        <td>{{ $user->lastname }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>{{ $user->area }}</td>
                        <td>{{ $user->is_admin ? 'Administrador' : $user->assistances->count() }}</td>
                        <td>{{ $user->username }}</td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                @if(!$user->is_admin)
                                <a href="{{ route('user.show', $user->id) }}" class="btn btn-primary btn-sm" title="Ver registros de asistencia"><i class="bi-eye"></i></a>
                                <a href="{{ route('user.edit', $user->id) }}" class="btn btn-info btn-sm" title="Editar"><i class="bi-pencil"></i></a>
                                <a href="{{ route('user.create_assistance', $user->id) }}" class="btn btn-secondary btn-sm" title="Crear Asistencia"><i class="bi-card-checklist"></i></a>
                                <a href="{{ route('user.export', $user->id) }}" class="btn btn-success btn-sm" title="Descargar Excel"><i class="bi-download"></i></a>
                                <a href="{{ route('user.reset_password', $user->id) }}" class="btn btn-warning btn-sm" title="Regenerar contraseña"><i class="bi-lock"></i></a>
                                @else
                                <a href="{{ route('user.edit', $user->id) }}" class="btn btn-info btn-sm" title="Editar"><i class="bi-pencil"></i></a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection

@section('javascript')
<script>
$( document ).ready(function() {
    
    $('#datat').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
        }
    });

    @if(Session::has('success'))
    toastr.success('<strong>¡Exito!</strong><br>'+'{{ session("success") }}');
    @endif

});
</script>
@endsection
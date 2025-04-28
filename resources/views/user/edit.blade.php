@extends('layout')

@section('title')
<title>Editar Docente</title>
@endsection

@section('content')
<div class="container">
  <div class="col-lg-12">
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h5 class="card-title text-primary">Editar Docente</h5>
      </div>
      <form action="{{ route('user.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">	
            <div class="mb-3">
              <label for="exampleFormControlInput1" class="form-label"><b>Usuario</b><font color="red">*</font></label>
              <input type="text" class="form-control" id="exampleFirstName" name="username" value="{{ $user->username }}" required>
            </div>

            <div class="mb-3">
              <label for="exampleFormControlInput1" class="form-label"><b>Nombre(s)</b><font color="red">*</font></label>
              <input type="text" class="form-control" id="exampleFirstName" name="name" value="{{ $user->name }}" required>
            </div>

            <div class="mb-3">
              <label for="exampleFormControlInput1" class="form-label"><b>Apellido(s)</b><font color="red">*</font></label>
              <input type="text" class="form-control" id="exampleLastName" name="lastname" value="{{ $user->lastname }}" required>
            </div>

            <div class="mb-3">
              <label for="exampleFormControlInput1" class="form-label"><b>Correo</b><font color="red">*</font></label>
              <input type="email" class="form-control" id="exampleLastName" name="email" value="{{ $user->email }}" required>
            </div>

            <div class="mb-3">
              <label for="exampleFormControlInput1" class="form-label"><b>Telefono</font></label>
              <input type="number" class="form-control" id="exampleLastName" name="phone" value="{{ $user->phone }}">
            </div>

            <div class="mb-3">
              <label for="exampleFormControlInput1" class="form-label"><b>Area</font></label>
              <input type="text" class="form-control" id="exampleLastName" name="area" value="{{ $user->area }}">
            </div>
        </div>
        <div class="card-footer py-3">
          <button type="submit" class="btn btn-primary">Guardar</button>
          <a href="{{ route('user') }}" class="btn btn-danger">Cancelar</a>
        </div>
      </form>
    </div>
  </div>

  @if($user->is_admin)
  <div class="col-lg-12">
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h5 class="card-title text-primary">Cambiar Contraseña</h5>
      </div>
      <form action="{{ route('user.update_password', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body"> 
           
            <div class="mb-3">
              <label for="exampleFormControlInput1" class="form-label"><b>Contraseña</b><font color="red">*</font></label>
              <input type="password" class="form-control" id="exampleEmail" name="password" required>
            </div>
            <div class="mb-3">
              <label for="exampleFormControlInput1" class="form-label"><b>Repite Contraseña</b><font color="red">*</font></label>
              <input type="password" class="form-control" id="exampleEmail" name="repeat_password" required>
            </div>            

        </div>
        <div class="card-footer py-3">
          <button type="submit" class="btn btn-primary">Guardar</button>
          <a href="{{ route('user') }}" class="btn btn-danger">Cancelar</a>
        </div>
      </form>
    </div>
  </div>
  @endif


</div>
@endsection
@extends('layout')

@section('title')
<title>Crear Profesor</title>
@endsection

@section('content')
<div class="container">
  <div class="col-lg-12">
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h5 class="card-title text-primary">Crear Docente</h5>
      </div>
      <form action="{{ route('user.store') }}" method="POST">
        @csrf
        <div class="card-body">

            <div class="mb-3">
              <label for="exampleFormControlInput1" class="form-label"><b>Nombre(s)</b><font color="red">*</font></label>
              <input type="text" class="form-control" id="exampleFirstName" name="name" required>
              @error('name')
                <div class="invalid-feedback">Muy largo.</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="exampleFormControlInput1" class="form-label"><b>Apellido(s)</b><font color="red">*</font></label>
              <input type="text" class="form-control" id="exampleLastName" name="lastname" required>
              @error('lastname')
                <div class="invalid-feedback">Muy largo.</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="exampleFormControlInput1" class="form-label"><b>Correo</b><font color="red">*</font></label>
              <input type="email" class="form-control" id="exampleLastName" name="email" required>
              @error('email')
                <div class="invalid-feedback">Muy largo.</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="exampleFormControlInput1" class="form-label"><b>Telefono</label>
              <input type="number" class="form-control" id="exampleLastName" name="phone">
              @error('phone')
                <div class="invalid-feedback">Muy largo.</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="exampleFormControlInput1" class="form-label"><b>Area</font></label>
              <input type="text" class="form-control" id="exampleLastName" name="area">
            </div>
        </div>
        <div class="card-footer py-3">
          <button type="submit" class="btn btn-primary">Guardar</button>
          <a href="{{ route('user') }}" class="btn btn-danger">Cancelar</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('javascript')

<script>
$( document ).ready(function() {
   
  @if ($errors->any()) 
    @error('name')
        toastr.error('<strong>¡Error!</strong><br> Nombre incorrecto');
    @enderror
    @error('lastname')
        toastr.error('<strong>¡Error!</strong><br> Apellido incorrecto');
    @enderror
    @error('email')
        toastr.error('<strong>¡Error!</strong><br> Correo incorrecto');
    @enderror
    @error('phone')
        toastr.error('<strong>¡Error!</strong><br> Telefono incorrecto');
    @enderror
  @endif

});


</script>

@endsection
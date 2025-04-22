@extends('layout')

@section('title')
<title>Importar</title>
@endsection

@section('content')
<div class="container">
  <div class="col-lg-12">
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h5 class="card-title text-primary">Importar</h5>
      </div>
      <form action="{{ route('user.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="input-group mb-3">
              <input type="file" name="file-teachers" class="form-control" required>
              @error('file-teachers')
              <div id="validationServerUsernameFeedback" class="invalid-feedback">
                Incorrecto
              </div>
              @enderror
              <button type="submit" class="btn btn-primary">Subir</button>
              <a href="{{ route('user') }}" class="btn btn-danger">Cancelar</a>
            </div>

        </div>
      </form>
    </div>
  </div>
</div>
@endsection
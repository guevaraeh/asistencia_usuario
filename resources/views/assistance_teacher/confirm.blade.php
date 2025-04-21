@extends('layout')

@section('title')
<title>Confirmar Asistencia</title>
@endsection

@section('content')
<div class="container">
  <div class="col-lg-12">
      @include('includes.alert')
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h5 class="card-title text-primary">Confirmar asistencia</h5>
      </div>
      <form action="{{ route('assistance_teacher.store') }}" method="POST">
        @csrf
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover">
              <tbody>
                <tr>
                  <th class="col-4">Apellidos y Nombres</th>
                  <td>
                    {{ $user->lastname . ' ' . $user->name }}
                    <input type="hidden" name="user-id" value="{{ $assistance->input('user-id') }}">
                  </td>
                </tr>
                <tr>
                  <th>Módulo Formativo</th>
                  <td>
                    {{ $assistance->input('training-module') }}
                    <input type="hidden" name="training-module" value="{{ $assistance->input('training-module') }}">
                  </td>
                </tr>
                <tr>
                  <th>Período Académico</th>
                  <td>
                    {{ $assistance->input('period') }}
                    <input type="hidden" name="period" value="{{ $assistance->input('period') }}">
                  </td>
                </tr>
                <tr>
                  <th>Turno/Sección</th>
                  <td>
                    {{ $assistance->input('turn') }}
                    <input type="hidden" name="turn" value="{{ $assistance->input('turn') }}">
                  </td>
                </tr>
                <tr>
                  <th>Unidad Didáctica</th>
                  <td>
                    {{ $assistance->input('didactic-unit') }}
                    <input type="hidden" name="didactic-unit" value="{{ $assistance->input('didactic-unit') }}">
                  </td>
                </tr>
                <tr>
                  <th>Hora de ingreso</th>
                  <td>
                    {{ date('Y-m-d h:i A', strtotime($assistance->input('checkin-time'))) }}
                    <input type="hidden" name="checkin-time" value="{{ $assistance->input('checkin-time') }}">
                  </td>
                </tr>
                <tr>
                  <th>Hora de salida</th>
                  <td>
                    {{ date('Y-m-d h:i A', strtotime($assistance->input('departure-time'))) }}
                    <input type="hidden" name="departure-time" value="{{ $assistance->input('departure-time') }}">
                  </td>
                </tr>
                <tr>
                  <th>Tema de actividad de aprendizaje</th>
                  <td>
                    {{ $assistance->input('theme') }}
                    <input type="hidden" name="theme" value="{{ $assistance->input('theme') }}">
                  </td>
                </tr>
                <tr>
                  <th>Lugar de realización de actividad</th>
                  <td>
                    {{ $assistance->input('place') }}
                    <input type="hidden" name="place" value="{{ $assistance->input('place') }}">
                  </td>
                </tr>
                <tr>
                  <th>Plataformas educativas de apoyo</th>
                  <td>
                    {{ $assistance->input('educational-platforms') ? implode(', ', $assistance->input('educational-platforms')) : '' }}
                    @foreach($assistance->input('educational-platforms') as $edplat)
                    <input type="hidden" name="educational-platforms[]" value="{{ $edplat }}">
                    @endforeach
                  </td>
                </tr>
                <tr>
                  <th>Observaciones</th>
                  <td>
                    {{ $assistance->input('remarks') }}
                    <input type="hidden" name="remarks" value="{{ $assistance->input('remarks') }}">
                  </td>
                </tr>
              </tbody>
          </table>
          </div>
      </div>
      <div class="card-footer py-3">
          <button type="submit" class="btn btn-primary">Confirmar</button>
          <button type="button" onclick="history.back();" class="btn btn-danger">Atras</button>
      </div>
      </form>
    </div>
  </div>
</div>
@endsection
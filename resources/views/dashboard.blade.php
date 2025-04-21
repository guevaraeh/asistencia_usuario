@extends('layout')

@section('title')
<title>Inicio</title>
@endsection

@section('content')
<div class="container">

        <div class="card text-center">
            <div class="card-header">
                Completado
            </div>
        <div class="card-body">
            <h5 class="card-title">Asistencia Registrada</h5>
            <p class="card-text">Cierre la sesión y proceda con la clase.</p>
            <form id="h1-logout" action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success">Cerrar Sesión</button>
            </form>
        </div>
        <div class="card-footer text-muted">
            Fecha y hora de subida: {{ date('Y-m-d h:i A', time()) }}
        </div>
    </div>

</div>
@endsection
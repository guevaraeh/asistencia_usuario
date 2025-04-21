@extends('layout')

@section('title')
<title>Asistencia Registrada</title>
@endsection

@section('content')
<div class="container">

    <div class="card text-center border-success mb-3">
        <div class="card-header text-success border-success">
            Prof. {{ $user->name . ' ' . $user->lastname }}
        </div>
        <div class="card-body text-success ">
            <h2 class="card-title">Asistencia Registrada</h2>
            <p class="card-text">Cierre la sesión y proceda con la clase.</p>
            <form id="h1-logout" action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success">Cerrar Sesión</button>
            </form>
        </div>
        <div class="card-footer text-success border-success">
            Fecha y hora de subida: {{ date('Y-m-d h:i A', time()) }}
        </div>
    </div>

    <div class="card">
        <div class="card-body" id="block-comment">
            <form>
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label"><b>Comentario o sugerencia (opcional)</b></label>
                    <textarea class="form-control" id="comment" name="comment"></textarea>
                </div>
                <button type="button" id="send-comment" class="btn btn-primary">Enviar</button>
            </form>
        </div>
    </div>

</div>
@endsection

@section('javascript')
<script>
$( document ).ready(function() {

    $("#send-comment").click(function(){
        $.ajax({
            method: "POST",
            url: "{{ route('assistance_teacher.assistance_comment_ajax') }}", 
            data: {
                id: "{{ $user->id }}",
                comment: $('#comment').val(),
                _token: "{{ csrf_token() }}"
            },
            success: function(result){
                //$("#block-comment").html('<p class="card-text text-center">Enviado.</p>');
                $("#block-comment").html('<div class="alert alert-success alert-dismissible fade show text-center" role="alert"><strong>Enviado</strong></div>');
                //console.log(result);
            }
        });
    });

});
</script>
@endsection
@extends('layout')

@section('title')
<title>Editar Asistencia de {{ $assistance_teacher->user->lastname . ' ' . $assistance_teacher->user->name }}</title>
@endsection

@section('content')
<div class="container">
    <div class="col-lg-12">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h5 class="card-title text-primary">Editar Asistencia de {{ $assistance_teacher->user->lastname . ' ' . $assistance_teacher->user->name }}</h5>
        </div>
        <form action="{{ route('assistance_teacher.update', $assistance_teacher->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
        <div class="card-body">
          
            <div class="form-group row">
                <div class="col-sm-6 mb-3">
                  <label for="exampleFormControlInput1" class="form-label"><b>Apellidos y Nombres</b><font color="red">*</font></label>
                  <input type="hidden" name="user-id" value="{{ $assistance_teacher->user->id }}"><br>{{ $assistance_teacher->user->lastname . ' ' . $assistance_teacher->user->name }}
                </div>
                <div class="col-sm-6 mb-3">
                  <label for="exampleFormControlInput1" class="form-label"><b>Módulo Formativo</b><font color="red">*</font></label>
                  <select class="form-select" aria-label="Default select example" name="training-module" id="training-module" required>
                    <option value="Profesional/Especialidad" {{ $assistance_teacher->training_module == 'Profesional/Especialidad' ? 'selected' : '' }}>Profesional/Especialidad</option>
                    <option value="Transversal/Empleabilidad"{{ $assistance_teacher->training_module == 'Transversal/Empleabilidad' ? 'selected' : '' }}>Transversal/Empleabilidad</option>
                  </select>
              </div>
            </div>

                <div class="form-group row">
                <div class="col-sm-6 mb-3">
                  <label for="exampleFormControlInput1" class="form-label"><b>Periodo Académico</b><font color="red">*</font></label>
                  <select class="form-select" aria-label="Default select example" name="period" id="period" required>
                    {{--
                    <option value="Segundo" {{ $assistance_teacher->period == 'Segundo' ? 'selected' : '' }}>Segundo</option>
                    <option value="Cuarto" {{ $assistance_teacher->period == 'Cuarto' ? 'selected' : '' }}>Cuarto</option>
                    <option value="Sexto" {{ $assistance_teacher->period == 'Sexto' ? 'selected' : '' }}>Sexto</option>
                    --}}
                    @foreach($periods as $period)
                    <option value="{{ $period->name }}" {{ $assistance_teacher->period == $period->name ? 'selected' : '' }}>{{ $period->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-sm-6 mb-3">
                  <label for="exampleFormControlInput1" class="form-label"><b>Turno/Sección</b><font color="red">*</font></label>
                  <select class="form-select" aria-label="Default select example" name="turn" id="turn" required>
                    <option value="Diurno" {{ $assistance_teacher->turn == 'Diurno' ? 'selected' : '' }}>Diurno</option>
                    <option value="Nocturno" {{ $assistance_teacher->turn == 'Nocturno' ? 'selected' : '' }}>Nocturno</option>
                  </select>
              </div>
              </div>

            <div class="mb-3">
              <label for="exampleFormControlInput1" class="form-label"><b>Unidad Didáctica</b><font color="red">*</font></label>
              <textarea class="form-control" id="validationCustom01" name="didactic-unit" id="didactic-unit" required>{{ $assistance_teacher->didactic_unit }}</textarea>
            </div>

                <div class="form-group row">
                <div class="col-sm-6 mb-3">
                  <label for="exampleFormControlInput1" class="form-label"><b>Hora de ingreso a clase</b><font color="red">*</font></label>
                  <input type="text" class="form-control timepicker1" name="checkin-time" id="checkin-time" 
                    value="{{ date('Y-m-d h:i A', strtotime($assistance_teacher->checkin_time) ) }}"
                  readonly required>
                    @error('checkin-time')
                    <div class="invalid-feedback">Fecha y hora inválidos.</div>
                    @enderror
                </div>
                <div class="col-sm-6 mb-3">
                  <label for="exampleFormControlInput1" class="form-label"><b>Hora de salida de clase</b><font color="red">*</font></label>
                  <input type="text" class="form-control timepicker2" name="departure-time" id="departure-time" 
                    value="{{ date('Y-m-d h:i A', strtotime($assistance_teacher->departure_time) ) }}" 
                  readonly required>
                    @error('departure-time')
                    <div class="invalid-feedback">Fecha y hora inválidos.</div>
                    @enderror
                </div>
                </div>

            <div class="mb-3">
              <label for="exampleFormControlInput1" class="form-label"><b>Tema de actividad de aprendizaje</b><font color="red">*</font></label>
              <input type="text" class="form-control" name="theme" id="theme" value="{{ $assistance_teacher->theme }}" required>
                @error('theme')
                <div class="invalid-feedback">Incorrecto.</div>
                @enderror
            </div>

            <div class="form-group row">
            <div class="col-sm-6 mb-3">
            <label for="exampleFormControlInput1" class="form-label"><b>Lugar de realización de actividad</b><font color="red">*</font></label>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="place" value="Aula" {{ $assistance_teacher->place == 'Aula' ? 'checked' : '' }}>
                <label class="form-check-label" for="flexRadioDefault1">Aula</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="place" value="Laboratorio" {{ $assistance_teacher->place == 'Laboratorio' ? 'checked' : '' }}>
                <label class="form-check-label" for="flexRadioDefault2">Laboratorio</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="place" value="Taller" {{ $assistance_teacher->place == 'Taller' ? 'checked' : '' }}>
                <label class="form-check-label" for="flexRadioDefault2">Taller</label>
              </div>
              <div class="form-check">
                @if($assistance_teacher->place && !($assistance_teacher->place == 'Aula') && !($assistance_teacher->place == 'Laboratorio') && !($assistance_teacher->place == 'Taller'))
                <input class="form-check-input" type="radio" name="place" value="{{ $assistance_teacher->place }}" id="another-place" checked>
                <label class="form-check-label" for="flexRadioDefault2">Otros</label>
                <input type="text" class="form-control" id="ap" value="{{ $assistance_teacher->place }}" required>
                @else
                <input class="form-check-input" type="radio" name="place" value="" id="another-place">
                <label class="form-check-label" for="flexRadioDefault2">Otros</label>
                <input type="text" class="form-control" id="ap" disabled>
                @endif
              </div>
            </div>

            <div class="col-sm-6 mb-3">
            <label for="exampleFormControlInput1" class="form-label"><b>Plataformas educativas de apoyo</b></label>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="educational-platforms[]" value="Moodle Institucional" {{ in_array("Moodle Institucional", $edplat) ? 'checked' : '' }}>
                <label class="form-check-label" for="flexCheckDefault">Moodle Institucional</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="educational-platforms[]" value="Google Meet" {{ in_array("Google Meet", $edplat) ? 'checked' : '' }}>
                <label class="form-check-label" for="flexCheckChecked">Google Meet</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="educational-platforms[]" value="Skipe" {{ in_array("Skipe", $edplat) ? 'checked' : '' }}>
                <label class="form-check-label" for="flexCheckChecked">Skipe</label>
              </div>
              <div class="form-check">
                @if(array_diff($edplat, ['Moodle Institucional','Google Meet','Skipe']) != [] )
                <input class="form-check-input" type="checkbox" name="educational-platforms[]" id="another-platform" value="{{ end($edplat) }}" checked>
                <label class="form-check-label" for="flexCheckChecked">Otros</label>
                <input type="text" class="form-control" id="apf" value="{{ end($edplat) }}" required>
                @else
                <input class="form-check-input" type="checkbox" name="educational-platforms[]" id="another-platform" value="">
                <label class="form-check-label" for="flexCheckChecked">Otros</label>
                <input type="text" class="form-control" id="apf" disabled>
                @endif
              </div>
            </div>
            </div>

            <div class="mb-3">
              <label for="exampleFormControlInput1" class="form-label"><b>Observaciones</b></label>
              <textarea class="form-control" id="remarks" name="remarks" >{{ $assistance_teacher->remarks }}</textarea>
            </div>

        </div>
        <div class="card-footer py-3">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="{{ route('assistance_teacher') }}" class="btn btn-danger">Cancelar</a>
        </div>
        </form>
      </div>
    </div>
</div>
@endsection

@section('javascript')

<script>
$( document ).ready(function() {
    
    $('input[name="place"]').change(function(){
        //alert( "otro" );
        if($('#another-place').is(':checked'))
        {
            $('#ap').prop('disabled', false);
            $('#ap').prop('required', true);
        }
        else
        {
            $('#ap').prop('required', false);
            $('#ap').prop('disabled', true);
        }
    });

    $('#ap').change(function() {
        $("#another-place").val($(this).val());
    });


    $('input[name="educational-platforms[]"]').change(function(){
        //alert( "otro" );
        if($('#another-platform').is(':checked'))
        {
            $('#apf').prop('disabled', false);
            $('#apf').prop('required', true);
        }
        else
        {
            $('#apf').prop('required', false);
            $('#apf').prop('disabled', true);
        }
    });

    $('#apf').change(function() {
        $("#another-platform").val($(this).val());
    });

    /*$('.timepicker1').timepicker({
        timeFormat: 'h:mm p',
        interval: 15,
        minTime: '8',
        maxTime: '6:00 pm',
        //defaultTime: '11',
        //startTime: '10:00',
        dynamic: false,
        dropdown: true,
        scrollbar: true
    });*/

    /*$('.timepicker1').datetimepicker({
        language:'es',
        format: "dd/mm/yyyy hh:ii A",
        autoclose: true,
        todayBtn: true,
        startDate: "2013-02-14 10:00",
        minuteStep: 10
    });*/

    /*$('.timepicker2').timepicker({
        timeFormat: 'h:mm p',
        interval: 15,
        minTime: '10',
        maxTime: '8:00 pm',
        //defaultTime: '11',
        //startTime: '10:00',
        dynamic: false,
        dropdown: true,
        scrollbar: true
    });*/



    /*function generateAllowTimes() {
        var hours = [];
        var start = 5; 
        var end = 23; 

        for (var i = start; i <= end; i++) {
            hours.push(i.toString().padStart(2, '0') + ':00');
            hours.push(i.toString().padStart(2, '0') + ':15');
            hours.push(i.toString().padStart(2, '0') + ':30');
            hours.push(i.toString().padStart(2, '0') + ':45');
        }
        return hours;
    }

    $.datetimepicker.setLocale('es');

    $('.timepicker1').datetimepicker({
        //datepicker:false,
        mask:true,
        allowTimes: generateAllowTimes(),
        timepicker: true,
        format: 'Y-m-d H:i',
        //format: 'Y-m-d h:i A',
        utc: true,
        onShow:function( ct )
        {
         this.setOptions({
            maxDate: $('.timepicker2').val() ? $('.timepicker2').val() : false,
            maxTime: $('.timepicker2').val() ? ( moment( $('.timepicker1').val(),'YYYY-MM-DD HH:mm').format('YYYY-MM-DD') == moment( $('.timepicker2').val(),'YYYY-MM-DD HH:mm').format('YYYY-MM-DD') ? moment($('.timepicker2').val(),'YYYY-MM-DD HH:mm').format('HH:mm') : false ) : false,
         });
          
        },
    });

    $('.timepicker2').datetimepicker({
        //datepicker:false,
        mask:true,
        allowTimes: generateAllowTimes(),
        timepicker: true,
        format: 'Y-m-d H:i',
        //format: 'Y-m-d h:i A',
        utc: true,
        onShow:function( ct )
        {
         this.setOptions({
            minDate: $('.timepicker1').val() ? $('.timepicker1').val() : false,
            minTime: $('.timepicker1').val() ? ( moment($('.timepicker1').val(), 'YYYY-MM-DD HH:mm').format('YYYY-MM-DD') == moment($('.timepicker2').val(), 'YYYY-MM-DD HH:mm').format('YYYY-MM-DD') ? moment($('.timepicker1').val(), 'YYYY-MM-DD HH:mm').format('HH:mm') : false ) : false,
         });

        },
    });*/


    //https://preview.keenthemes.com/html/start-html-pro/docs/forms/tempus-dominus-datepicker
    const linkedPicker1Element = document.getElementById("checkin-time");
    const linked1 = new tempusDominus.TempusDominus(linkedPicker1Element, {
      stepping: 5,
      display: {
            icons: {
              time: 'bi bi-clock',
              date: 'bi bi-calendar',
              up: 'bi bi-arrow-up',
              down: 'bi bi-arrow-down',
              previous: 'bi bi-chevron-left',
              next: 'bi bi-chevron-right',
              today: 'bi bi-calendar-check',
              clear: 'bi bi-trash',
              close: 'bi bi-x',
            },
            sideBySide: true,
        },
      localization: {
            locale: 'en',
            hourCycle: 'h12',
            format: "yyyy-MM-dd hh:mm T"
        },
      restrictions: {
            maxDate: document.getElementById("departure-time").value,
        }
    });
    const linked2 = new tempusDominus.TempusDominus(document.getElementById("departure-time"), {
        useCurrent: false,
        stepping: 5,
        display: {
            icons: {
              time: 'bi bi-clock',
              date: 'bi bi-calendar',
              up: 'bi bi-arrow-up',
              down: 'bi bi-arrow-down',
              previous: 'bi bi-chevron-left',
              next: 'bi bi-chevron-right',
              today: 'bi bi-calendar-check',
              clear: 'bi bi-trash',
              close: 'bi bi-x',
            },
            sideBySide: true,
        },
        localization: {
            locale: 'en',
            hourCycle: 'h12',
            format: "yyyy-MM-dd hh:mm T"
        },
        restrictions: {
            minDate: document.getElementById("checkin-time").value,
        }
    });

    //using event listeners
    linkedPicker1Element.addEventListener(tempusDominus.Namespace.events.change, (e) => {
        linked2.updateOptions({
            restrictions: {
            minDate: e.detail.date,
            },
        });
    });

    //using subscribe method
    const subscription = linked2.subscribe(tempusDominus.Namespace.events.change, (e) => {
        linked1.updateOptions({
            restrictions: {
            maxDate: e.date,
            },
        });
    });



});


</script>

@endsection
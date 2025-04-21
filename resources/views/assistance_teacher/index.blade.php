@extends('layout')

@section('title')
<title>Lista de Asistencias</title>
@endsection

@section('content')

<form method="POST" id="deleteall">
    @csrf
    @method('DELETE')
</form>

<div class="container-fluid">
    <div class="col-lg-12">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h5 class="card-title text-primary">Lista de Asistencias</h5>
        </div>

        <div class="card-body">

            <nav>
                <div class="nav nav-tabs mb-1" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Busqueda</button>
                    <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Exportar a Excel</button>
                </div>
            </nav>

            <div class="tab-content p-3 bg-dark" id="nav-tabContent">

                <div class="tab-pane fade active show" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                    
                    <div class="row justify-content-center align-items-center">
                        <div class="col-sm-12 col-xl-8">
                            <div class="form-group row">
                                <div class="col-sm-3 mb-3">
                                    <label class="form-label"><b>Apellidos y Nombres</b></label>
                                    <input type="text" class="form-control" id="name-filter">
                                </div>
                                <div class="col-sm-3 mb-3">
                                    <label class="form-label"><b>Módulo Formativo</b></label>
                                    <select class="form-select" id="module-filter">
                                        <option hidden>Módulo Formativo</option>
                                        <option></option>
                                        <option>Profesional/Especialidad</option>
                                        <option>Transversal/Empleabilidad</option>
                                    </select>
                                </div>
                                <div class="col-sm-3 mb-3">
                                    <label class="form-label"><b>Periodo Académico</b></label>
                                    <select class="form-select" id="period-filter">
                                        <option hidden>Periodo Académico</option>
                                        <option></option>
                                        @foreach($periods as $period)
                                        <option>{{ $period->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 mb-3">
                                    <label class="form-label"><b>Turno/Sección</b></label>
                                    <select class="form-select" id="turn-filter">
                                        <option hidden>Turno/Sección</option>
                                        <option></option>
                                        <option>Diurno</option>
                                        <option>Nocturno</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-4 mb-3">
                                    <label class="form-label"><b>Fecha de subida</b></label>
                                    <input type="text" class="form-control date-filter" id="uploaded-filter">
                                </div>
                                <div class="col-sm-4 mb-3">
                                    <label class="form-label"><b>Hora de ingreso</b></label>
                                    <input type="text" class="form-control date-filter" id="checkin-filter">
                                </div>
                                <div class="col-sm-4 mb-3">
                                    <label class="form-label"><b>Hora de salida</b></label>
                                    <input type="text" class="form-control date-filter" id="departure-filter">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


                <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                    
                    <div class="row justify-content-center align-items-center">

                        <div class="col-sm-12 col-xl-4">
                            <div class="form-group row mb-3">
                                <div class="col-sm-3 col-form-label">
                                    <input class="form-check-input" type="radio" name="export-option" id="by-rank" checked>
                                    <label class="form-check-label"><b>Por rango</b></label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <!--<a href="#" id="ranks" class="btn btn-primary">.xlsx</a>-->
                                        <input type="text" class="form-control" id="init-date" value="{{ date('Y-m-d', strtotime('-1 days')) }}" readonly>
                                        <input type="text" class="form-control" id="end-date" value="{{ date('Y-m-d', time()) }}" readonly>
                                        <!--<button type="button" id="export" class="btn btn-primary">Generar</button>-->
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <div class="col-sm-3 col-form-label">
                                    <input class="form-check-input" type="radio" name="export-option" id="by-day" >
                                    <label class="form-check-label"><b>Por día</b></label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <!--<a href="#" id="days" class="btn btn-primary disabled">.xlsx</a>-->
                                        <input type="text" class="form-control" id="set-day" value="{{ date('Y-m-d', time()) }}" readonly disabled>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-12 col-xl-4">

                            <div class="form-group row mb-3">
                                <div class="col-sm-3 col-form-label">
                                    <input class="form-check-input" type="radio" name="export-option" id="by-month" >
                                    <label class="form-check-label"><b>Por mes</b></label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <!--<a href="#" id="months" class="btn btn-primary disabled">.xlsx</a>-->
                                        <input type="text" class="form-control" id="set-month" value="{{ date('Y-m', time()) }}" readonly disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <div class="col-sm-3 col-form-label">
                                    <input class="form-check-input" type="radio" name="export-option" id="by-year" >
                                    <label class="form-check-label"><b>Por año</b></label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <!--<a href="#" id="years" class="btn btn-primary disabled">.xlsx</a>-->
                                        <input type="text" class="form-control" id="set-year" value="{{ date('Y', time()) }}" readonly disabled>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-12 col-xl-8">

                            <div class="form-group mb-3">
                                <a href="#" id="export-excel" class="btn btn-primary">Exportar</a>
                                <a href="{{ route('assistance_teacher.export') }}" class="btn btn-primary">Exportar Todo</a>
                            </div>

                        </div>
                    </div>

                </div>

            </div>





          <div class="table-responsive">
            <table class="table table-hover" id="datat">
                <thead class="table-light">
                    <tr>
                        <!--<th></th>-->
                        <th class="input-filter" id="uploaded-col">Fecha de subida</th>
                        <th class="input-filter" id="name-col">Apellidos y Nombres</th>
                        <th id="select-module">Módulo Formativo</th>
                        <th id="select-period">Periodo Académico</th>
                        <th id="select-turn">Turno/Sección</th>
                        {{--<th>Unidad Didáctica</th>--}}
                        <th class="input-filter" id="checkin-col">Hora de ingreso</th>
                        <th class="input-filter" id="departure-col">Hora de salida</th>
                        {{--<th>Tema de actividad de aprendizaje</th>--}}
                        {{--<th>Lugar</th>--}}
                        {{--<th>Plataformas de apoyo</th>--}}
                        {{--<th>Observaciones</th>--}}
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tfoot class="table-light">
                    <tr>
                    	<th>Fecha de subida</th>
                        <th>Apellidos y Nombres</th>
                        <th>Módulo Formativo</th>
                        <th>Periodo Académico</th>
                        <th>Turno/Sección</th>
                        {{--<th>Unidad Didáctica</th>--}}
                        <th>Hora de ingreso</th>
                        <th>Hora de salida</th>
                        {{--<th>Tema de actividad de aprendizaje</th>--}}
                        {{--<th>Lugar</th>--}}
                        {{--<th>Plataformas de apoyo</th>--}}
                        {{--<th>Observaciones</th>--}}
                        <th>Acciones</th>
                    </tr>
                </tfoot>
                <tbody>

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

    var iconsDate = {
                  time: 'bi bi-clock',
                  date: 'bi bi-calendar',
                  up: 'bi bi-arrow-up',
                  down: 'bi bi-arrow-down',
                  previous: 'bi bi-chevron-left',
                  next: 'bi bi-chevron-right',
                  today: 'bi bi-calendar-check',
                  clear: 'bi bi-trash',
                  close: 'bi bi-x',
                };

/****************************************************************************************************************/

    var dt = $('#datat').DataTable({
        //searching : false,
        lengthChange: false,
        pageLength: 20,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
        },
        processing: true,
        serverSide: true,
        ajax:"{{ route('assistance_teacher') }}",
        columns: [
            //{data:'checks', name:'checks'},
            {data:'created_at', name:'created_at'},
            {data:'teacher_name', name:'teacher_name'},
            {data:'training_module', name:'training_module'},
            {data:'period', name:'period'},
            {data:'turn', name:'turn'},
            //{data:'didactic_unit', name:'didactic_unit'},
            {data:'checkin_time', name:'checkin_time'},
            {data:'departure_time', name:'departure_time'},
            //{data:'theme', name:'theme'},
            //{data:'place', name:'place'},
            //{data:'educational_platforms', name:'educational_platforms'},
            //{data:'remarks', name:'remarks'},
            {data:'action', name:'action'},
        ],
        initComplete: function () {
            $(".dt-search").html('');

            this.api()
                .columns('#uploaded-col')
                .every(function (index) {
                    let column = this;
                    let title = column.header().textContent;
     
                    let input = document.getElementById('uploaded-filter');
                    input.placeholder = title;
                    input.setAttribute('data-dt-column', index);

                    input.addEventListener('change', () => {
                        if (column.search() !== this.value) {
                            column.search(input.value).draw();
                        }
                    });
                    input.addEventListener('keyup', () => {
                        if (column.search() !== this.value) {
                            column.search(input.value).draw();
                        }
                    });
                });

            this.api()
                .columns('#name-col')
                .every(function (index) {
                    let column = this;
                    let title = column.header().textContent;
     
                    let input = document.getElementById('name-filter');
                    input.placeholder = title;
                    input.setAttribute('data-dt-column', index);

                    input.addEventListener('keyup', () => {
                        if (column.search() !== this.value) {
                            column.search(input.value).draw();
                        }
                    });
                });

            this.api()
                .columns('#select-module')
                .every(function (index) {
                    let column = this;
                    let title = column.header().textContent;
     
                    let input = document.getElementById('module-filter');
                    input.placeholder = title;
                    input.setAttribute('data-dt-column', index);

                    input.addEventListener('change', () => {
                        if (column.search() !== this.value) {
                            column.search(input.value).draw();
                        }
                        console.log('Modulo:',input.value);
                    });
                });

            this.api()
                .columns('#select-period')
                .every(function (index) {
                    let column = this;
                    let title = column.header().textContent;
     
                    let input = document.getElementById('period-filter');
                    input.placeholder = title;
                    input.setAttribute('data-dt-column', index);

                    input.addEventListener('change', () => {
                        if (column.search() !== this.value) {
                            column.search(input.value).draw();
                        }
                        console.log('Periodo:',input.value);
                    });
                });

            this.api()
                .columns('#select-turn')
                .every(function (index) {
                    let column = this;
                    let title = column.header().textContent;
     
                    let input = document.getElementById('turn-filter');
                    input.placeholder = title;
                    input.setAttribute('data-dt-column', index);

                    input.addEventListener('change', () => {
                        if (column.search() !== this.value) {
                            column.search(input.value).draw();
                        }
                        console.log('Turno:',input.value);
                    });
                });

            this.api()
                .columns('#checkin-col')
                .every(function (index) {
                    let column = this;
                    let title = column.header().textContent;
     
                    let input = document.getElementById('checkin-filter');
                    input.placeholder = title;
                    input.setAttribute('data-dt-column', index);

                    input.addEventListener('change', () => {
                        if (column.search() !== this.value) {
                            column.search(input.value).draw();
                        }
                        console.log('Hora de entrada:',input.value);
                    });
                    /*input.addEventListener('keyup', () => {
                        if (column.search() !== this.value) {
                            column.search(input.value).draw();
                        }
                    });*/
                });

            this.api()
                .columns('#departure-col')
                .every(function (index) {
                    let column = this;
                    let title = column.header().textContent;
     
                    let input = document.getElementById('departure-filter');
                    input.placeholder = title;
                    input.setAttribute('data-dt-column', index);

                    input.addEventListener('change', () => {
                        if (column.search() !== this.value) {
                            column.search(input.value).draw();
                        }
                        console.log('Hora de salida:',input.value);
                    });
                    input.addEventListener('keyup', () => {
                        if (column.search() !== this.value) {
                            column.search(input.value).draw();
                        }
                    });
                });

        }

    });


    dt.on('draw', function() {
        $('.swalDefaultSuccess').click(function(){
            Swal.fire({
                title: '¿Esta seguro que desea eliminarlo?',
                text: 'Registro de asistencia del '+$(this).val(),
                showDenyButton: true,
                confirmButtonText: "Si, eliminar",
                denyButtonText: "No, cancelar",
                icon: "warning",
                customClass: {
                    confirmButton: 'btn btn-primary',
                    denyButton: 'btn btn-danger'
                }
            }).then((result) => {
                if(result.isConfirmed){
                    $('#deleteall').attr('action', $(this).attr('formaction'));
                    $('#deleteall').submit();
                }
            })
        });
    });

    
    new tempusDominus.TempusDominus(document.getElementById("uploaded-filter"), {
            useCurrent: false,
            display: {
                icons: iconsDate,
                viewMode: 'calendar',
                components: {
                  clock: false,
                  decades: false,
                  year: true,
                  month: true,
                  date: true,
                },
            },
            localization: {
                locale: 'en',
                format: "yyyy-MM-dd"
            },
        });



    const linkedCheckinElement = document.getElementById("checkin-filter");
    const checkin_filter = new tempusDominus.TempusDominus(linkedCheckinElement, {
            useCurrent: false,
            stepping: 5,
            display: {
                icons: iconsDate,
                viewMode: 'calendar',
                components: {
                  decades: false,
                  year: true,
                  month: true,
                  date: true,
                  clock: true,
                },
                sideBySide: true,
            },
            localization: {
                locale: 'en',
                format: "yyyy-MM-dd hh:mm T"
            },
        });

    const departure_filter = new tempusDominus.TempusDominus(document.getElementById("departure-filter"), {
            useCurrent: false,
            stepping: 5,
            display: {
                icons: iconsDate,
                viewMode: 'calendar',
                components: {
                  decades: false,
                  year: true,
                  month: true,
                  date: true,
                  clock: true,
                },
                sideBySide: true,
            },
            localization: {
                locale: 'en',
                format: "yyyy-MM-dd hh:mm T"
            },
            //promptTimeOnDateChange: true,
        });

    linkedCheckinElement.addEventListener(tempusDominus.Namespace.events.change, (e) => {
        departure_filter.updateOptions({
            restrictions: {
            minDate: e.detail.date,
            },
        });
        if($("#checkin-filter").val().length > 0 && $("#departure-filter").val().length == 0)
        {
            var dep_date = moment(e.detail.date).add(6, 'hours').format('YYYY-MM-DD hh:mm A');
            $("#departure-filter").val(dep_date);
            dt.columns('#departure-col').search($("#departure-filter").val()).draw();
            
            checkin_filter.updateOptions({
                restrictions: {
                maxDate: $("#departure-filter").val(),
                },
            });
        }
    });

    const subscript_departure = departure_filter.subscribe(tempusDominus.Namespace.events.change, (e) => {
        checkin_filter.updateOptions({
            restrictions: {
            maxDate: e.date,
            },
        });
        if($("#departure-filter").val().length > 0 && $("#checkin-filter").val().length == 0)
        {
            var check_date = moment(e.date).subtract(6, 'hours').format('YYYY-MM-DD hh:mm A');
            $("#checkin-filter").val(check_date);
            dt.columns('#checkin-col').search($("#checkin-filter").val()).draw();

            departure_filter.updateOptions({
                restrictions: {
                minDate: $("#checkin-filter").val(),
                },
            });
        }
    });



/******************************************************************************************************************************/
    

    const linkedPicker1Element = document.getElementById("init-date");
    const linked1 = new tempusDominus.TempusDominus(linkedPicker1Element, {
      display: {
            icons: iconsDate,
            components: {
                clock: false,
                hours: false,
                minutes: false,
                seconds: false,
            },
        },
      localization: {
            locale: 'en',
            format: "yyyy-MM-dd"
        },
      restrictions: {
            maxDate: document.getElementById("end-date").value,
        }
    });
    const linked2 = new tempusDominus.TempusDominus(document.getElementById("end-date"), {
        useCurrent: false,
        display: {
            icons: iconsDate,
            components: {
                clock: false,
                hours: false,
                minutes: false,
                seconds: false,
            },
        },
        localization: {
            locale: 'en',
            format: "yyyy-MM-dd"
        },
        restrictions: {
            minDate: document.getElementById("init-date").value,
        }
    });

    linkedPicker1Element.addEventListener(tempusDominus.Namespace.events.change, (e) => {
        linked2.updateOptions({
            restrictions: {
            minDate: e.detail.date,
            },
        });
        $("#export-excel").attr("href", route+$('#init-date').val()+"/"+$('#end-date').val());
    });

    const subscription = linked2.subscribe(tempusDominus.Namespace.events.change, (e) => {
        linked1.updateOptions({
            restrictions: {
            maxDate: e.date,
            },
        });
        $("#export-excel").attr("href", route+$('#init-date').val()+"/"+$('#end-date').val());
    });


    const day = new tempusDominus.TempusDominus(document.getElementById("set-day"), {
        useCurrent: false,
        display: {
            icons: iconsDate,
            components: {
                //date: false,
                //month: false,
                clock: false,
                hours: false,
                minutes: false,
                seconds: false,
            }
        },
        localization: {
            locale: 'en',
            format: "yyyy-MM-dd"
        },
    });

    const month = new tempusDominus.TempusDominus(document.getElementById("set-month"), {
        useCurrent: false,
        display: {
            icons: iconsDate,
            viewMode: 'months',
            components: {
                date: false,
                //month: false,
                clock: false,
                hours: false,
                minutes: false,
                seconds: false,
            }
        },
        localization: {
            locale: 'en',
            format: "yyyy-MM"
        },
    });

    const year = new tempusDominus.TempusDominus(document.getElementById("set-year"), {
        useCurrent: false,
        display: {
            icons: iconsDate,
            viewMode: 'years',
            components: {
                date: false,
                month: false,
                clock: false,
                hours: false,
                minutes: false,
                seconds: false,
            }
        },
        localization: {
            locale: 'en',
            format: "yyyy"
        },
    });


    var route = "{{ route('assistance_teacher.export_by_range') }}"+"/";
    $("#export-excel").attr("href", route+$('#init-date').val()+"/"+$('#end-date').val());

    var route_date = "{{ route('assistance_teacher.export_by_date') }}"+"/";

    const subday = day.subscribe(tempusDominus.Namespace.events.change, (e) => {
        $("#export-excel").attr("href", route_date+$('#set-day').val());
    });
    const submonth = month.subscribe(tempusDominus.Namespace.events.change, (e) => {
        $("#export-excel").attr("href", route_date+$('#set-month').val());
    });
    const subyear = year.subscribe(tempusDominus.Namespace.events.change, (e) => {
        $("#export-excel").attr("href", route_date+$('#set-year').val());
    });

    $('input[name="export-option"]').change(function(){
        //alert( "otro" );
        if($('#by-rank').is(':checked'))
        {
            $('#init-date').prop('disabled', false);
            $('#end-date').prop('disabled', false);
            $('#set-day').prop('disabled', true);
            $('#set-month').prop('disabled', true);
            $('#set-year').prop('disabled', true);

            $("#export-excel").attr("href", route+$('#init-date').val()+"/"+$('#end-date').val());
        }
        if($('#by-day').is(':checked'))
        {
            $('#init-date').prop('disabled', true);
            $('#end-date').prop('disabled', true);
            $('#set-day').prop('disabled', false);
            $('#set-month').prop('disabled', true);
            $('#set-year').prop('disabled', true);

            $("#export-excel").attr("href", route_date+$('#set-day').val());
        }
        if($('#by-month').is(':checked'))
        {
            $('#init-date').prop('disabled', true);
            $('#end-date').prop('disabled', true);
            $('#set-day').prop('disabled', true);
            $('#set-month').prop('disabled', false);
            $('#set-year').prop('disabled', true);

            $("#export-excel").attr("href", route_date+$('#set-month').val());
        }
        if($('#by-year').is(':checked'))
        {
            $('#init-date').prop('disabled', true);
            $('#end-date').prop('disabled', true);
            $('#set-day').prop('disabled', true);
            $('#set-month').prop('disabled', true);
            $('#set-year').prop('disabled', false);

            $("#export-excel").attr("href", route_date+$('#set-year').val());
        }
    });


/*****************************************************************************************************************************************/

    @if(Session::has('success'))
    toastr.success('<strong>¡Exito!</strong><br>'+'{{ session("success") }}');
    @endif

});
</script>
@endsection
<?php

namespace App\Http\Controllers;

use App\Models\AssistanceTeacher;
use App\Models\User;
use App\Models\Period;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\StoreAssistanceTeacherRequest;
use App\Http\Requests\UpdateAssistanceTeacherRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AssistanceFilterExport;
use App\Exports\AssistanceTeacherExport;
use DataTables;
use Illuminate\Support\Facades\Gate;

class AssistanceTeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!Gate::allows('manage-assistance')) {
            abort(403);
        }

        //dd(explode('-',"2025"));
        //SELECT DATE_FORMAT(departure_time, '%Y-%m-%d %h:%i %p') FROM assistance_teachers WHERE departure_time <= STR_TO_DATE( '2025-04-08 12:15 PM' , '%Y-%m-%d %h:%i %p') ORDER BY id DESC

        if($request->ajax())
        {
            $assistance_teachers = AssistanceTeacher::query()
                ->select([DB::raw("CONCAT(users.lastname,' ',users.name) as teacher_name"),'assistance_teachers.*']) //periods.name
                ->join('users', 'assistance_teachers.user_id', '=', 'users.id')
                //->join('periods', 'assistance_teachers.period_id', '=', 'period.id')
                ->where('users.is_admin',0)
                ->orderBy('assistance_teachers.id', 'desc');
            return DataTables::eloquent($assistance_teachers)
                                ->filterColumn('teacher_name', function($query, $keyword) {
                                    $sql = "CONCAT(users.lastname,' ',users.name) like ?";
                                    $query->whereRaw($sql, ["%{$keyword}%"]);
                                })
                                /*->filterColumn('period', function($query, $keyword) { //en caso que se use periodo como foreign key
                                    $sql = "periods.name like ?";
                                    $query->whereRaw($sql, ["%{$keyword}%"]);
                                })*/
                                ->editColumn('created_at', function(AssistanceTeacher $data) {
                                    return date('Y-m-d h:i A', strtotime($data->created_at));
                                })
                                ->editColumn('checkin_time', function(AssistanceTeacher $data) {
                                    return date('Y-m-d h:i A', strtotime($data->checkin_time));
                                })
                                ->editColumn('departure_time', function(AssistanceTeacher $data) {
                                    return date('Y-m-d h:i A', strtotime($data->departure_time));
                                })
                                ->filterColumn('assistance_teachers.created_at', function($query, $keyword) {
                                    $sql = "DATE_FORMAT(assistance_teachers.created_at, '%Y-%m-%d') like ?";
                                    //$sql = "assistance_teachers.created_at like STR_TO_DATE( ? , '%Y-%m-%d') ";
                                    $query->whereRaw($sql, ["%{$keyword}%"]);
                                })
                                ->filterColumn('checkin_time', function($query, $keyword) {
                                    //$sql = "DATE_FORMAT(checkin_time, '%Y-%m-%d %h:%i %p') >= ? ";
                                    $sql = "checkin_time >= STR_TO_DATE( ? , '%Y-%m-%d %h:%i %p') ";
                                    $query->whereRaw($sql, [$keyword]);
                                })
                                ->filterColumn('departure_time', function($query, $keyword) {
                                    //$sql = "DATE_FORMAT(departure_time, '%Y-%m-%d %h:%i %p') <= ? ";
                                    $sql = "departure_time <= STR_TO_DATE( ? , '%Y-%m-%d %h:%i %p') ";
                                    $query->whereRaw($sql, [$keyword]);
                                })
                                ->addColumn('action',function (AssistanceTeacher $data){
                                    $updated = '';
                                    if(strtotime($data->created_at) < strtotime($data->updated_at)){
                                        $updated = 
                                        '<tr>
                                            <th><small>Editado</small></th>
                                            <th><small>'.date('Y-m-d h:i A', strtotime($data->updated_at)).'</small></th>
                                        </tr>';
                                    }
                                    $links = 
                                    '<div class="btn-group" role="group" aria-label="Basic mixed styles example">'.
                                      '<button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal'.$data->id.'" title="Ver registro completo">
                                          <i class="bi-eye"></i>
                                        </button>

                                        <div class="modal fade" id="modal'.$data->id.'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                          <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content">
                                              <div class="modal-header bg-primary">
                                                <h5 class="modal-title" id="exampleModalLabel"> Registro de asistencia del '.date('Y-m-d h:i A', strtotime($data->created_at)).'</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                              </div>
                                              <div class="modal-body">
                                                <div class="table-responsive">
                                                    <table class="table table-hover">
                                                        <tbody>
                                                            <tr>
                                                                <th class="col-4"><small>Apellidos y Nombres</small></th>
                                                                <td><small>'.$data->user->lastname.' '.$data->user->name.'</small></td>
                                                            </tr>
                                                            <tr>
                                                                <th><small>Módulo Formativo</small></th>
                                                                <td><small>'.$data->training_module.'</small></td>
                                                            </tr>
                                                            <tr>
                                                                <th><small>Período Académico</small></th>
                                                                <td><small>'.$data->period.'</small></td>
                                                            </tr>
                                                            <tr>
                                                                <th><small>Turno/Sección</small></th>
                                                                <td><small>'.$data->turn.'</small></td>
                                                            </tr>
                                                            <tr>
                                                                <th><small>Unidad Didáctica</small></th>
                                                                <td><small>'.$data->didactic_unit.'</small></td>
                                                            </tr>
                                                            <tr>
                                                                <th><small>Hora de ingreso</small></td>
                                                                <td><small>'.date('Y-m-d h:i A', strtotime($data->checkin_time)).'</small></th>
                                                            </tr>
                                                            <tr>
                                                                <th><small>Hora de salida</small></td>
                                                                <td><small>'.date('Y-m-d h:i A', strtotime($data->departure_time)).'</small></th>
                                                            </tr>
                                                            <tr>
                                                                <th><small>Tema de actividad de aprendizaje</small></th>
                                                                <td><small>'.$data->theme.'</small></td>
                                                            </tr>
                                                            <tr>
                                                                <th><small>Lugar de realización de actividad</small></th>
                                                                <td><small>'.$data->place.'</small></td>
                                                            </tr>
                                                            <tr>
                                                                <th><small>Plataformas educativas de apoyo</small></th>
                                                                <td><small>'.$data->educational_platforms.'</small></td>
                                                            </tr>
                                                            <tr>
                                                                <th><small>Observaciones</small></th>
                                                                <td><small>'.$data->remarks.'</small></td>
                                                            </tr>
                                                            '.$updated.'
                                                        </tbody>
                                                    </table>
                                                </div>
                                              </div>
                                              <div class="modal-footer">
                                                    <a type="button" href="'.route('assistance_teacher.edit',$data->id).'" class="btn btn-info"><i class="bi-pencil"></i> Editar</a>
                                                    <button type="button" class="btn btn-danger swalDefaultSuccess" form="deleteall" formaction="'.route('assistance_teacher.destroy',$data->id).'" value="'.date('Y-m-d h:i A', strtotime($data->created_at)).' de '.$data->teacher_name.'"><i class="bi-trash"></i> Eliminar</button>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                    '.
                                    '<a type="button" href="'.route('assistance_teacher.edit',$data->id).'" class="btn btn-info btn-sm" title="Editar"><i class="bi-pencil"></i></a>'
                                    .
                                    '<button type="button" class="btn btn-danger btn-sm swalDefaultSuccess" form="deleteall" formaction="'.route('assistance_teacher.destroy',$data->id).'" value="'.date('Y-m-d h:i A', strtotime($data->created_at)).' de '.$data->teacher_name.'" title="Eliminar"><i class="bi-trash"></i></button>'
                                    .'</div>'
                                    ;
                                    return $links;
                                })
                                /*->addColumn('checks',function (AssistanceTeacher $data){
                                    $check = '
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="checked-assistance[]" value="'.$data->id.'">
                                    </div>
                                    ';
                                    return $check;
                                })*/
                                ->rawColumns(['action','checks'])
                                ->make(true);
        }
        return view('assistance_teacher.index', ['periods' => Period::get()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //dd(date('Y-m-d h:i A', strtotime('')));
        $users = DB::table('users')->where('is_admin',0)->orderBy('lastname','asc')->get();
        return view('assistance_teacher.create',['users' => $users, 'periods' => Period::get()]);
    }

    public function select_teacher_ajax(Request $request)
    {
        if (Gate::allows('manage-assistance'))
            abort(403);

        if($request->ajax())
        {
            $tmp_assistance_teacher = null;
            if( DB::table('tmp_assistance_teachers')->where('user_id', $request->input('id'))->exists() )
            {
                $tmp_assistance_teacher = DB::table('tmp_assistance_teachers')->where('user_id', $request->input('id'))->first();
                $tmp_assistance_teacher->checkin_time = date('Y-m-d h:i A', strtotime($tmp_assistance_teacher->checkin_time));
                $tmp_assistance_teacher->departure_time = date('Y-m-d h:i A', strtotime($tmp_assistance_teacher->departure_time));
                $tmp_assistance_teacher->educational_platforms = $tmp_assistance_teacher->educational_platforms ? explode(', ', $tmp_assistance_teacher->educational_platforms) : [];

                return response()->json($tmp_assistance_teacher);
            }
            else{
                $data = [
                    'user_id' => $request->input('id'),
                    'training_module' => $request->input('training_module'),
                    'period' => $request->input('period'),
                    'turn' => $request->input('turn'),
                    'didactic_unit' => $request->input('didactic_unit'),
                    'checkin_time' => date('Y-m-d H:i:s', strtotime($request->input('checkin_time'))),
                    'departure_time' => date('Y-m-d H:i:s', strtotime($request->input('departure_time'))),
                    'theme' => $request->input('theme'),
                    'place' => $request->input('place'),
                    'educational_platforms' => $request->input('educational_platforms') ? implode(', ', $request->input('educational_platforms')) : null,
                    'remarks' => $request->input('remarks')
                ];
                DB::table('tmp_assistance_teachers')->insert($data);
            }
        }
    }

    public function temp_store_ajax(Request $request)
    {
        if (Gate::allows('manage-assistance'))
            abort(403);

        if($request->ajax())
        {
            if($request->input('id') != '' && DB::table('tmp_assistance_teachers')->where('user_id', $request->input('id'))->exists())
            {
                $data = [
                    'user_id' => $request->input('id'),
                    'training_module' => $request->input('training_module'),
                    'period' => $request->input('period'),
                    'turn' => $request->input('turn'),
                    'didactic_unit' => $request->input('didactic_unit'),
                    'checkin_time' => date('Y-m-d H:i:s', strtotime($request->input('checkin_time'))),
                    'departure_time' => date('Y-m-d H:i:s', strtotime($request->input('departure_time'))),
                    'theme' => $request->input('theme'),
                    'place' => $request->input('place'),
                    'educational_platforms' => $request->input('educational_platforms') ? implode(', ', $request->input('educational_platforms')) : null,
                    'remarks' => $request->input('remarks')
                ];
                DB::table('tmp_assistance_teachers')->where('user_id', $request->input('id') )->update($data);
            }
        }
    }

    public function confirm(StoreAssistanceTeacherRequest $request)
    {
        $validated = $request->validate([
            'user-id' => 'required',
            'training-module' => 'required',
            'period' => 'required',
            'turn' => 'required',
            'didactic-unit' => 'required',
            'checkin-time' => 'required|date',
            'departure-time' => 'required|date',
            'theme' => 'required|max:250',
            'place' => 'required',
        ]);

        return view('assistance_teacher.confirm',['assistance' => $request, 'user' => User::find($request->input('user-id'))]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAssistanceTeacherRequest $request)
    {
        $validated = $request->validate([
            'user-id' => 'required',
            'training-module' => 'required',
            'period' => 'required',
            'turn' => 'required',
            'didactic-unit' => 'required',
            'checkin-time' => 'required|date',
            'departure-time' => 'required|date',
            'theme' => 'required|max:250',
            'place' => 'required',
        ]);

        $assistanceTeacher = new AssistanceTeacher;

        $assistanceTeacher->user_id = $request->input('user-id');
        $assistanceTeacher->training_module = $request->input('training-module');
        $assistanceTeacher->period = $request->input('period');
        $assistanceTeacher->turn = $request->input('turn');
        $assistanceTeacher->didactic_unit = $request->input('didactic-unit');
        $assistanceTeacher->checkin_time = date('Y-m-d H:i:s', strtotime($request->input('checkin-time')));
        $assistanceTeacher->departure_time = date('Y-m-d H:i:s', strtotime($request->input('departure-time')));
        $assistanceTeacher->theme = $request->input('theme');
        $assistanceTeacher->place = $request->input('place');
        $assistanceTeacher->educational_platforms = $request->input('educational-platforms') ? implode(', ', $request->input('educational-platforms')) : null;
        $assistanceTeacher->remarks = $request->input('remarks');
        $assistanceTeacher->remember_token = Str::random(50);
        
        $assistanceTeacher->save();

        DB::table('tmp_assistance_teachers')->where('user_id', $request->input('user-id'))->delete();

        if (!Gate::allows('manage-assistance')) {
            return redirect(route('user.submitted', $assistanceTeacher->user_id));
        }

        return redirect(route('assistance_teacher'))->with('success', 'Asistencia registrada');
    }

    /**
     * Display the specified resource.
     */
    public function show(AssistanceTeacher $assistanceTeacher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AssistanceTeacher $assistanceTeacher)
    {
        if (!Gate::allows('manage-assistance')) {
            abort(403);
        }

        $edplat = $assistanceTeacher->educational_platforms ? explode(', ',$assistanceTeacher->educational_platforms) : [];
        $periods = Period::get();

        return view('assistance_teacher.edit',['assistance_teacher' => $assistanceTeacher, 'edplat' => $edplat, 'periods' => $periods]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAssistanceTeacherRequest $request, AssistanceTeacher $assistanceTeacher)
    {
        if (!Gate::allows('manage-assistance')) {
            abort(403);
        }

        /*$validated = $request->validate([
            'user-id' => 'required',
            'training-module' => 'required',
            'period' => 'required',
            'turn' => 'required',
            'didactic-unit' => 'required',
            'checkin-time' => 'required|date',
            'departure-time' => 'required|date',
            'theme' => 'required|max:250',
            'place' => 'required',
        ]);*/

        $assistanceTeacher->training_module = $request->input('training-module');
        $assistanceTeacher->period = $request->input('period');
        $assistanceTeacher->turn = $request->input('turn');
        $assistanceTeacher->didactic_unit = $request->input('didactic-unit');
        $assistanceTeacher->checkin_time = date('Y-m-d H:i:s', strtotime($request->input('checkin-time')));
        $assistanceTeacher->departure_time = date('Y-m-d H:i:s', strtotime($request->input('departure-time')));
        $assistanceTeacher->theme = $request->input('theme');
        $assistanceTeacher->place = $request->input('place');
        $assistanceTeacher->educational_platforms = $request->input('educational-platforms') ? implode(', ', $request->input('educational-platforms')) : null;
        $assistanceTeacher->remarks = $request->input('remarks');

        $assistanceTeacher->save();

        return redirect(route('assistance_teacher'))->with('success', 'Registro cambiado');
    }

    public function export() 
    {
        if (! Gate::allows('manage-assistance'))
            abort(403);

        return Excel::download(new AssistanceTeacherExport, 'Asistencias_'.date('YmdHi', time()).'.xlsx');
    }

    public function export_by_range($ini, $end)
    {
        if (!Gate::allows('manage-assistance'))
            abort(403);

        if(strtotime($ini) && strtotime($end))
            return Excel::download(new AssistanceTeacherExport($ini, $end), 'Asistencias_'.date('YmdHi', time()).'.xlsx');
    }

    public function export_by_date($date)
    {
        if (!Gate::allows('manage-assistance'))
            abort(403);

        if(strtotime($date))
            return Excel::download(new AssistanceFilterExport($date), 'Asistencias_'.date('YmdHi', time()).'.xlsx');
    }


    public function assistance_comment_ajax(Request $request) 
    {
        if($request->ajax())
        {
            $comment = new Comment;
            $comment->user_id = $request->input('id');
            $comment->text_comment = $request->input('comment');
            $comment->remember_token = Str::random(50);
            $comment->save();

            //return response()->json("Comentario enviado");
            //dd($request);
            return response()->json($request->input('id') . '/' . $request->input('comment'));
            //return response()->json(route('assistance_teacher'));
            //return response()->json(route('assistance_teacher.export_by_range',[$request->input('ini'), $request->input('end')]));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AssistanceTeacher $assistanceTeacher)
    {
        if (!Gate::allows('manage-assistance')) {
            abort(403);
        }

        //dd($assistanceTeacher);
        $assistanceTeacher->delete();
        return redirect(route('assistance_teacher'))->with('success', 'Registro eliminado');
    }

    public function destroy_many(Request $request)
    {
        if (!Gate::allows('manage-assistance')) {
            abort(403);
        }

        //AssistanceTeacher::destroy($request);
        //return redirect(route('assistance_teacher'))->with('success', 'Registros eliminados');
    }
}

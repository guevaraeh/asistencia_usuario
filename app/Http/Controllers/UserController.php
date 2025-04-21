<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AssistanceTeacher;
use App\Models\Period;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DataTables;

class UserController extends Controller
{
    public function index()
    {
        if (!Gate::allows('manage-assistance'))
            abort(403);

        return view('user.index',['users' => User::get()]);
    }

    public function create()
    {
        if (!Gate::allows('manage-assistance'))
            abort(403);
        
        return view('user.create');
    }

    public function store(Request $request)
    {
        if (!Gate::allows('manage-assistance')) 
            abort(403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'username' => 'required|string|lowercase|max:255|unique:'.User::class,
        ]);

        $user = new User;
        $user->username = $request->input('username');
        $user->name = $request->input('name');
        $user->lastname = $request->input('lastname');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->remember_token = Str::random(20);

        $password = Str::random(10);
        $user->password = Hash::make($password);
        $user->save();

        $message = 
        '<b>Docente creado.</b>
        <br><b>Nombre:     </b>'.$user->lastname.' '.$user->name.'
        <br><b>Usuario:     </b>'.$user->username.'
        <br><b>Contraseña:  </b>'.$password;

        return redirect(route('user'))->with('changed', $message);
    }

    public function show(User $user, Request $request)
    {
        if (!Gate::allows('manage-assistance')) {
            abort(403);
        }

        if($request->ajax())
        {
            $assistance_teachers = AssistanceTeacher::query()
                ->where('user_id', $user->id)
                ->orderBy('id', 'desc');
            return DataTables::eloquent($assistance_teachers)
                                ->editColumn('created_at', function(AssistanceTeacher $data) {
                                    return date('Y-m-d h:i A', strtotime($data->created_at));
                                })
                                ->editColumn('checkin_time', function(AssistanceTeacher $data) {
                                    return date('Y-m-d h:i A', strtotime($data->checkin_time));
                                })
                                ->editColumn('departure_time', function(AssistanceTeacher $data) {
                                    return date('Y-m-d h:i A', strtotime($data->departure_time));
                                })
                                ->filterColumn('created_at', function($query, $keyword) {
                                    $sql = "DATE_FORMAT(created_at, '%Y-%m-%d %r') like ?";
                                    //$sql = "created_at like STR_TO_DATE( ? , '%Y-%m-%d') ";
                                    $query->whereRaw($sql, ["%{$keyword}%"]);
                                })
                                ->filterColumn('checkin_time', function($query, $keyword) {
                                    //$sql = "DATE_FORMAT(checkin_time, '%Y-%m-%d %r') like ?";
                                    $sql = "checkin_time >= STR_TO_DATE( ? , '%Y-%m-%d %h:%i %p') ";
                                    $query->whereRaw($sql, [$keyword]);
                                })
                                ->filterColumn('departure_time', function($query, $keyword) {
                                    //$sql = "DATE_FORMAT(departure_time, '%Y-%m-%d %r') like ?";
                                    $sql = "departure_time <= STR_TO_DATE( ? , '%Y-%m-%d %h:%i %p') ";
                                    $query->whereRaw($sql, [$keyword]);
                                })
                                ->addColumn('action',function (AssistanceTeacher $data){
                                    $updated = '';
                                    if(strtotime($data->created_at) < strtotime($data->updated_at)){
                                        $updated = 
                                        '<tr>
                                            <th><small>Editado</small></th>
                                            <td><small>'.date('Y-m-d h:i A', strtotime($data->updated_at)).'</small></td>
                                        </tr>';
                                    }
                                    $links = 
                                    '<div class="btn-group" role="group" aria-label="Basic mixed styles example">'.
                                      '<button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal'.$data->id.'" title="Ver">
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
                                                                <th class="col-4"><small>Módulo Formativo</small></th>
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
                                                                <th><small>Hora de ingreso</small></th>
                                                                <td><small>'.date('Y-m-d h:i A', strtotime($data->checkin_time)).'</small></td>
                                                            </tr>
                                                            <tr>
                                                                <th><small>Hora de salida</small></th>
                                                                <td><small>'.date('Y-m-d h:i A', strtotime($data->departure_time)).'</small></td>
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
                                                    <button type="button" class="btn btn-danger swalDefaultSuccess" form="deleteall" formaction="'.route('assistance_teacher.destroy',$data->id).'" value="'.date('Y-m-d h:i A', strtotime($data->created_at)).'"><i class="bi-trash"></i> Eliminar</button>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                              </div>
                                            </div>
                                          </div>
                                        </div>'
                                    .
                                    '<a type="button" href="'.route('assistance_teacher.edit',$data->id).'" class="btn btn-info btn-sm" title="Editar"><i class="bi-pencil"></i></a>'
                                    .
                                    '<button type="button" class="btn btn-danger btn-sm swalDefaultSuccess" form="deleteall" formaction="'.route('assistance_teacher.destroy',$data->id).'" value="'.date('Y-m-d h:i A', strtotime($data->created_at)).'" title="Eliminar"><i class="bi-trash"></i></button>'
                                    .'</div>'
                                    ;
                                    return $links;
                                })
                                ->rawColumns(['action'])
                                ->make(true);
        }

        return view('user.show',['user' => $user, 'periods' => Period::get()]);
    }

    public function edit(User $user)
    {
        if (!Gate::allows('manage-assistance'))
            abort(403);

        return view('user.edit',['user' => $user]);
    }

    public function update(Request $request, User $user)
    {
        if (!Gate::allows('manage-assistance'))
            abort(403);

        $user->username = $request->input('username');
        $user->name = $request->input('name');
        $user->lastname = $request->input('lastname');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->save();

        return redirect(route('user'))->with('success', 'Usuario editado');
    }

    public function create_assistance(User $user)
    {
        if (Gate::allows('manage-assistance') || Gate::allows('manage-owner', $user))
            return view('assistance_teacher.create',['periods' => Period::get(), 'usr' => $user]);
        else abort(403);
    }

    public function reset_password(User $user)
    {
        if (!Gate::allows('manage-assistance'))
            abort(403);

        $password = Str::random(10);
        $user->password = Hash::make($password);
        $user->save();

        $message = 
        '<b>Contraseña cambiada.</b>
        <br><b>Docente:     </b>'.$user->lastname.' '.$user->name.'
        <br><b>Usuario:     </b>'.$user->username.'
        <br><b>Contraseña:  </b>'.$password;

        return redirect(route('user'))->with('changed', $message);
    }

    public function submitted(User $user)
    {
        if (Gate::allows('manage-assistance'))
            abort(403);

        return view('user.submitted',['user' => $user]);
    }

    public function export(User $user) 
    {
        if (!Gate::allows('manage-assistance'))
            abort(403);
        
        return Excel::download(new UserExport($user->id), 'Asistencias_'.$user->lastname.'_'.$user->name.'_'.date('YmdHi', time()).'.xlsx');
    }

    public function export_by_range(User $user, $ini, $end)
    {
        if (!Gate::allows('manage-assistance'))
            abort(403);

        if(strtotime($ini) && strtotime($end))
            return Excel::download(new UserExport($user->id, $ini, $end), 'Asistencias_'.$user->lastname.'_'.$user->name.'_'.date('YmdHi', time()).'.xlsx');
    }

    public function export_by_date(User $user, $date)
    {
        if (!Gate::allows('manage-assistance'))
            abort(403);

        if(strtotime($date)) 
            return Excel::download(new UserExport($user->id, $date), 'Asistencias_'.$user->lastname.'_'.$user->name.'_'.date('YmdHi', time()).'.xlsx');
    }
}

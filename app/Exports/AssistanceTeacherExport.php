<?php

namespace App\Exports;

use App\Models\AssistanceTeacher;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AssistanceTeacherExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $ini;
    protected $end;

    public function __construct($ini = null, $end = null) 
    {
        $this->ini = $ini;
        $this->end = $end;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }

    public function collection()
    {
        $assistance_teachers = AssistanceTeacher::select([
                DB::raw("DATE_FORMAT(assistance_teachers.created_at, '%Y-%m-%d %r')"),
                DB::raw("CONCAT(users.lastname,' ',users.name) as teacher_name"),
                'assistance_teachers.training_module',
                'assistance_teachers.period',
                'assistance_teachers.turn',
                'assistance_teachers.didactic_unit',
                DB::raw("DATE_FORMAT(assistance_teachers.checkin_time, '%Y-%m-%d %r')"),
                DB::raw("DATE_FORMAT(assistance_teachers.departure_time, '%Y-%m-%d %r')"),
                'assistance_teachers.theme',
                'assistance_teachers.place',
                'assistance_teachers.educational_platforms',
                'assistance_teachers.remarks',
                ]) //periods.name
            ->join('users', 'assistance_teachers.user_id', '=', 'users.id')
            //->join('periods', 'assistance_teachers.period_id', '=', 'period.id')
            ->orderBy('assistance_teachers.id', 'desc');

        if($this->ini != null && $this->end != null)
        {
            $assistance_teachers->whereRaw("date(assistance_teachers.created_at) >= ? ", [$this->ini])
                                ->whereRaw("date(assistance_teachers.created_at) <= ? ", [$this->end]);
        }
        return $assistance_teachers->get();
    }

    public function headings(): array
    {
        return [
            "Fecha de creación",
            "Apellidos y Nombres", 
            "Módulo Formativo", 
            "Período Académico",
            "Turno/Sección", 
            "Unidad Didáctica",
            "Hora de ingreso a clase",
            "Hora de salida de clase", 
            "Tema de actividad de aprendizaje",
            "Lugar de realización de actividad", 
            "Plataformas educativas de apoyo",
            "Observaciones", 
        ];
    }
}

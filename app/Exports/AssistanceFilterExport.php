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

class AssistanceFilterExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $date;

    public function __construct($date) 
    {
        $this->date = $date;
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

        $dt = explode('-', $this->date);

        if(count($dt) > 0)
            $assistance_teachers->whereRaw("year(assistance_teachers.created_at) = ? ", [$dt[0]]);
        if(count($dt) > 1)
            $assistance_teachers->whereRaw("month(assistance_teachers.created_at) = ? ", [$dt[1]]);
        if(count($dt) > 2)
            $assistance_teachers->whereRaw("day(assistance_teachers.created_at) = ? ", [$dt[2]]);

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

<?php

namespace App\Exports;

use App\Models\AssistanceTeacher;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UserExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $id;
    protected $ini;
    protected $end;

    public function __construct($id, $ini = null, $end = null) 
    {
        $this->id = $id;
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
        $assistance_teacher = AssistanceTeacher::select([
                DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d %r')"),
                'training_module',
                'period',
                'turn',
                'didactic_unit',
                DB::raw("DATE_FORMAT(checkin_time, '%Y-%m-%d %r')"),
                DB::raw("DATE_FORMAT(departure_time, '%Y-%m-%d %r')"),
                'theme',
                'place',
                'educational_platforms',
                'remarks',
                ]) //periods.name
            ->where('user_id', $this->id)
            ->orderBy('id', 'desc');

        if($this->ini != null && $this->end != null) //para exportar por rango de fechas
        {
            $assistance_teacher->whereRaw("date(created_at) >= ? ", [$this->ini])
                                ->whereRaw("date(created_at) <= ? ", [$this->end]);
        }
        elseif($this->ini != null && $this->end == null) //para exportar por año, mes o dia
        {
            $dt = explode('-', $this->ini);
            if(count($dt) > 0)
                $assistance_teacher->whereRaw("year(created_at) = ? ", [$dt[0]]);
            if(count($dt) > 1)
                $assistance_teacher->whereRaw("month(created_at) = ? ", [$dt[1]]);
            if(count($dt) > 2)
                $assistance_teacher->whereRaw("day(created_at) = ? ", [$dt[2]]);
        }

        return $assistance_teacher->get();
    }

    public function headings(): array
    {
        return [
            "Fecha de creación",
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

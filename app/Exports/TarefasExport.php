<?php

namespace App\Exports;

use App\Models\Tarefa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TarefasExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // return Tarefa::all();
        return auth()->user()->tarefas()->get();
    }

    public function Headings():array {
        return [
           'ID da tarefas',
           'Tarefa',
           'Data limite conclusão',

        ];
    }

    public function map($line):array {
        return [
            $line->id,
            $line->tarefa,
            date('d/m/Y', strtotime($line->data_limite_conclusao)),
        ];
    }
}

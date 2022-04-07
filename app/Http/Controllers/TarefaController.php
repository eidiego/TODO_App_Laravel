<?php

namespace App\Http\Controllers;

use Mail;
use App\Mail\NovaTarefaMail;
use App\Models\Tarefa;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TarefasExport;
use Barryvdh\DomPDF\Facade\Pdf;


class TarefaController extends Controller
{

    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = auth()->user()->id;
        $tarefas =  Tarefa::where('user_id', $user_id)->paginate(1);
        return view('tarefa.index', ['tarefas' => $tarefas]);
    }
    // if(auth()->check()) {
    //     return ('Você está logado aqui');
    // } else {
    //     return ('Precisa se logar mano');
    // }
    /**
     * Show the form for creating a new resource.
     *s
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('tarefa.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dados = $request->all('tarefa', 'data_limite_conclusao');
        $dados['user_id'] = auth()->user()->id;

        $tarefa = Tarefa::create($dados);

        $destinatario = auth()->user()->email;
        Mail::to($destinatario)->send(new NovaTarefaMail($tarefa));

        return redirect()->route('tarefa.show', ['tarefa' => $tarefa->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tarefa  $tarefa
     * @return \Illuminate\Http\Response
     */
    public function show(Tarefa $tarefa)
    {
       return view('tarefa.show', ['tarefa' => $tarefa]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tarefa  $tarefa
     * @return \Illuminate\Http\Response
     */
    public function edit(Tarefa $tarefa)
    {
        $user_id = auth()->user()->id;
        if($tarefa->user_id == $user_id) {
            return view ('tarefa.edit', ['tarefa' => $tarefa]);
        }
      return view('acesso-negado');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tarefa  $tarefa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tarefa $tarefa)
    {

        $user_id = auth()->user()->id;

        if($tarefa->user_id == $user_id) {

            $tarefa->update($request->all());
            return redirect()->route('tarefa.show', ['tarefa' => $tarefa->id]);

        }
         return view('acesso-negado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tarefa  $tarefa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tarefa $tarefa)
    {
        if(!$tarefa->user_id == auth()->user()->id) {
            return view('acesso.negado');
        }

            $tarefa->delete();
            return redirect()->route('tarefa.index');

    }

    public function exportacao($extensao) {
        $nome_arquivo = 'lista_tarefas';

        if(in_array($extensao, ['xlsx', 'csv', 'pdf'])) {
            return Excel::download(new TarefasExport, 'lista_tarefas.'.$extensao);
        }
        return redirect()->route('tarefa.index');
    }

    public function exportar(){
        $tarefas = auth()->user()->tarefas()->get();
        $pdf = PDF::loadView('tarefa.pdf', ['tarefas' => $tarefas]);
        return $pdf->stream('lista_de_tarefas.pdf');
    }

}

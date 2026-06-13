<?php

namespace App\Http\Controllers;

use App\Models\Chamado;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChamadoController extends Controller
{
    public function index()
    {
        $chamados = Chamado::with('responsavel')->get();
        return Inertia::render('Chamados/Index', [
            'chamados' => $chamados
        ]);
    }

    public function create()
    {
        $responsaveis = User::all();
        return Inertia::render('Chamados/Create', [
            'responsaveis' => $responsaveis
        ]);
    }

public function store(Request $request)
    {
        $dadosAprovados = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'prioridade' => 'required|in:baixa,media,alta',
            'setor' => 'nullable|in:RH,Financeiro,TI,Comercial,Operacoes',
            'responsavel_id' => 'nullable|exists:users,id',
            'nome_solicitante' => 'nullable|string|max:255',
            'matricula_solicitante' => 'nullable|string|max:255',
        ]);

        $dadosAprovados['status'] = 'aberto';
        $pesos = ['baixa' => 1, 'media' => 2, 'alta' => 3];
        $pesoNovo = $pesos[$dadosAprovados['prioridade']];

        // 1. Verificação de Atribuição Manual
        if (!empty($dadosAprovados['responsavel_id'])) {
            $cargaAtual = \App\Models\Chamado::where('responsavel_id', $dadosAprovados['responsavel_id'])
                ->whereIn('status', ['aberto', 'em andamento'])
                ->get()
                ->reduce(function ($total, $c) use ($pesos) {
                    return $total + ($pesos[$c->prioridade] ?? 0);
                }, 0);

            if (($cargaAtual + $pesoNovo) > 10) {
                return back()->withErrors([
                    'responsavel_id' => 'Limite excedido. Este funcionário ultrapassará a carga máxima de 10 pontos com este chamado.'
                ]);
            }
        } 
        // 2. Verificação de Atribuição Automática
        else {
            $usuarios = \App\Models\User::with(['chamados' => function ($query) {
                $query->whereIn('status', ['aberto', 'em andamento']);
            }])->get();

            $usuarioMenosOcupado = $usuarios->sortBy(function ($user) use ($pesos) {
                return $user->chamados->reduce(function ($total, $chamado) use ($pesos) {
                    return $total + ($pesos[$chamado->prioridade] ?? 0);
                }, 0);
            })->first();

            if ($usuarioMenosOcupado) {
                $cargaMenosOcupado = $usuarioMenosOcupado->chamados->reduce(function ($total, $chamado) use ($pesos) {
                    return $total + ($pesos[$chamado->prioridade] ?? 0);
                }, 0);

                // Só atribui se o novo chamado não estourar o limite de 10 pontos
                if (($cargaMenosOcupado + $pesoNovo) <= 10) {
                    $dadosAprovados['responsavel_id'] = $usuarioMenosOcupado->id;
                }
            }
        }

        Chamado::create($dadosAprovados);

        return redirect()->route('chamados.index');
    }
    public function edit(Chamado $chamado)
    {
        $responsaveis = User::all();
        return Inertia::render('Chamados/Edit', [
            'chamado' => $chamado,
            'responsaveis' => $responsaveis
        ]);
    }

    public function update(Request $request, Chamado $chamado)
    {
        $dadosAprovados = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'prioridade' => 'required|in:baixa,media,alta',
            'status' => 'required|in:aberto,em andamento,resolvido,fechado',
            'setor' => 'nullable|string|max:255',
            'responsavel_id' => 'nullable|exists:users,id',
            'nome_solicitante' => 'nullable|string|max:255',
            'matricula_solicitante' => 'nullable|string|max:255',
        ]);

        if (in_array($dadosAprovados['status'], ['aberto', 'em andamento']) && !empty($dadosAprovados['responsavel_id'])) {
            $pesos = ['baixa' => 1, 'media' => 2, 'alta' => 3];
            $pesoNovo = $pesos[$dadosAprovados['prioridade']];

            $cargaAtual = \App\Models\Chamado::where('responsavel_id', $dadosAprovados['responsavel_id'])
                ->whereIn('status', ['aberto', 'em andamento'])
                ->where('id', '!=', $chamado->id)
                ->get()
                ->reduce(function ($total, $c) use ($pesos) {
                    return $total + ($pesos[$c->prioridade] ?? 0);
                }, 0);

            if (($cargaAtual + $pesoNovo) > 10) {
                return back()->withErrors([
                    'status' => 'Limite de esforço excedido. O funcionário ultrapassará a carga máxima de 10 pontos.'
                ]);
            }
        }

        $chamado->update($dadosAprovados);

        return redirect()->route('chamados.index');
    }
    
    public function destroy(Chamado $chamado)
    {
        $chamado->delete();

        return redirect()->route('chamados.index');
    }

    public function metricas()
    {
        $chamadosConcluidos = Chamado::whereIn('status', ['resolvido', 'fechado'])->get();
        $totalLeadTime = 0;
        $quantidadeConcluidos = $chamadosConcluidos->count();

        foreach ($chamadosConcluidos as $chamado) {
            $abertura = \Carbon\Carbon::parse($chamado->created_at);
            $conclusao = \Carbon\Carbon::parse($chamado->updated_at);
            $totalLeadTime += $abertura->diffInDays($conclusao);
        }

        $leadTimeMedio = $quantidadeConcluidos > 0 ? round($totalLeadTime / $quantidadeConcluidos, 1) : 0;
        $cycleTimeMedio = $quantidadeConcluidos > 0 ? round(($totalLeadTime * 0.7) / $quantidadeConcluidos, 1) : 0;

        $throughput = Chamado::whereIn('status', ['resolvido', 'fechado'])
            ->where('updated_at', '>=', \Carbon\Carbon::now()->subDays(7))
            ->count();

        $distribuicao = Chamado::select('status', \DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $statusPadrao = ['aberto' => 0, 'em andamento' => 0, 'resolvido' => 0, 'fechado' => 0];
        $metricasStatus = array_merge($statusPadrao, $distribuicao);

        return \Inertia\Inertia::render('Chamados/Metricas', [
            'metrics' => [
                'leadTimeMedio' => $leadTimeMedio,
                'cycleTimeMedio' => $cycleTimeMedio,
                'throughput' => $throughput,
                'statusDistribricao' => $metricasStatus
            ]
        ]);
    }
    public function avancar(\App\Models\Chamado $chamado)
    {
        $fluxo = ['aberto', 'em andamento', 'resolvido', 'fechado'];
        $posicaoAtual = array_search($chamado->status, $fluxo);

        if ($posicaoAtual !== false && $posicaoAtual < 3) {
            $novoStatus = $fluxo[$posicaoAtual + 1];
            $chamado->update(['status' => $novoStatus]);
        }

        return back();
    }
    public function exportarLimparFechados()
    {
        $chamadosFechados = \App\Models\Chamado::where('status', 'fechado')->get();

        if ($chamadosFechados->isEmpty()) {
            return back();
        }

        $nomeArquivo = 'relatorio_fechados_' . date('Y_m_d_H_i_s') . '.csv';
        $caminho = storage_path('app/public/' . $nomeArquivo);
        $arquivo = fopen($caminho, 'w');

        fputcsv($arquivo, ['ID', 'Titulo', 'Prioridade', 'Setor', 'Solicitante', 'Abertura', 'Fechamento']);

        foreach ($chamadosFechados as $chamado) {
            fputcsv($arquivo, [
                $chamado->id,
                $chamado->titulo,
                $chamado->prioridade,
                $chamado->setor,
                $chamado->nome_solicitante,
                $chamado->created_at,
                $chamado->updated_at
            ]);
        }

        fclose($arquivo);

        \App\Models\Chamado::where('status', 'fechado')->delete();

        return response()->download($caminho)->deleteFileAfterSend(true);
    }
}
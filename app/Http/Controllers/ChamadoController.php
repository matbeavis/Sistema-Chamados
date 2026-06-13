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
            'setor' => 'nullable|string|max:255',
            'responsavel_id' => 'nullable|exists:users,id',
        ]);

        $dadosAprovados['status'] = 'aberto';

        if (empty($dadosAprovados['responsavel_id'])) {
            $usuarioMenosOcupado = User::withCount(['chamados' => function ($query) {
                $query->whereIn('status', ['aberto', 'em andamento']);
            }])->orderBy('chamados_count', 'asc')->first();

            if ($usuarioMenosOcupado) {
                $dadosAprovados['responsavel_id'] = $usuarioMenosOcupado->id;
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
        ]);

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
}
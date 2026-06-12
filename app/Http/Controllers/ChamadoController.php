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
}
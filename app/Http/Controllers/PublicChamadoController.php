<?php

namespace App\Http\Controllers;

use App\Models\Chamado;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PublicChamadoController extends Controller
{
    public function create()
    {
        return Inertia::render('Public/Create');
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'setor' => 'required|string|max:255',
            'nome_solicitante' => 'required|string|max:255',
            'matricula_solicitante' => 'required|string|max:255',
        ]);

        $dados['status'] = 'aberto';
        $dados['prioridade'] = 'media';

        $pesos = ['baixa' => 1, 'media' => 2, 'alta' => 3];
        $pesoNovo = $pesos['media'];

        $usuarios = User::with(['chamados' => function ($query) {
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

            if (($cargaMenosOcupado + $pesoNovo) <= 10) {
                $dados['responsavel_id'] = $usuarioMenosOcupado->id;
            }
        }

        Chamado::create($dados);

        return redirect()->route('chamado.sucesso');
    }

    public function sucesso()
    {
        return Inertia::render('Public/Sucesso');
    }
}
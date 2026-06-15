<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Chamado;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RotasChamadoTest extends TestCase
{
    use RefreshDatabase;

    public function test_movimentacao_bloqueada_quando_coluna_cheia()
    {
        $usuario = User::factory()->create();
        
        Chamado::factory()->count(5)->create([
            'status' => 'em andamento'
        ]);

        $chamado = Chamado::factory()->create([
            'status' => 'aberto'
        ]);

        $resposta = $this->actingAs($usuario)->patch(route('chamados.avancar', $chamado->id));

        $resposta->assertSessionHasErrors('status');
        $this->assertEquals('aberto', $chamado->fresh()->status);
    }

    public function test_deslocamento_cascata_quando_coluna_abertos_cheia()
    {
        Chamado::factory()->count(10)->create([
            'status' => 'aberto',
            'created_at' => now()->subMinutes(10)
        ]);

        $chamadoMaisAntigo = Chamado::where('status', 'aberto')->orderBy('created_at', 'asc')->first();

        $dadosNovoChamado = [
            'titulo' => 'Ticket Publico',
            'descricao' => 'Teste cascata automatica',
            'prioridade' => 'baixa',
            'setor' => 'TI',
            'nome_solicitante' => 'Visitante Externo',
            'matricula_solicitante' => '12345'
        ];

        $this->post(route('chamado.publico.store'), $dadosNovoChamado);

        $this->assertEquals('em andamento', $chamadoMaisAntigo->fresh()->status);
        $this->assertEquals(10, Chamado::where('status', 'aberto')->count());
    }

    public function test_exportacao_e_limpeza_de_chamados_fechados()
    {
        $usuario = User::factory()->create();

        Chamado::factory()->count(3)->create([
            'status' => 'fechado'
        ]);

        Chamado::factory()->count(2)->create([
            'status' => 'aberto'
        ]);

        $this->actingAs($usuario)->get(route('chamados.exportar.limpar'));

        $this->assertEquals(0, Chamado::where('status', 'fechado')->count());
        $this->assertEquals(2, Chamado::count());
    }
}
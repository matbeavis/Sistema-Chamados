<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Chamado;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegrasChamadoTest extends TestCase
{
    use RefreshDatabase;

    public function test_distribuicao_automatica_atribui_ao_usuario_menos_ocupado()
    {
        $usuarioOcupado = User::factory()->create();
        $usuarioLivre = User::factory()->create();

        Chamado::factory()->count(3)->create([
            'responsavel_id' => $usuarioOcupado->id,
            'status' => 'aberto',
            'prioridade' => 'alta' 
        ]);

        Chamado::factory()->create([
            'responsavel_id' => $usuarioLivre->id,
            'status' => 'aberto',
            'prioridade' => 'baixa'
        ]);

        $dadosNovoChamado = [
            'titulo' => 'Problema na rede',
            'descricao' => 'Sem internet',
            'prioridade' => 'media',
            'setor' => 'TI'
        ];

        $this->actingAs($usuarioLivre)->post(route('chamados.store'), $dadosNovoChamado);

        $chamadoCriado = Chamado::where('titulo', 'Problema na rede')->first();

        $this->assertEquals($usuarioLivre->id, $chamadoCriado->responsavel_id);
    }

    public function test_bloqueio_por_carga_maxima_de_trabalho()
    {
        $usuario = User::factory()->create();

        Chamado::factory()->count(3)->create([
            'responsavel_id' => $usuario->id,
            'status' => 'aberto',
            'prioridade' => 'alta' 
        ]);

        $dadosAtribuicaoInvalida = [
            'titulo' => 'Erro no sistema',
            'descricao' => 'Tela branca',
            'prioridade' => 'media', 
            'setor' => 'TI',
            'responsavel_id' => $usuario->id
        ];

        $resposta = $this->actingAs($usuario)->post(route('chamados.store'), $dadosAtribuicaoInvalida);

        $resposta->assertSessionHasErrors('responsavel_id');
        $this->assertEquals(3, Chamado::count());
    }
}
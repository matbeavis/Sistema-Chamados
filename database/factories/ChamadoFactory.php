<?php

namespace Database\Factories;

use App\Models\Chamado;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChamadoFactory extends Factory
{
    protected $model = Chamado::class;

    public function definition()
    {
        return [
            'titulo' => $this->faker->sentence,
            'descricao' => $this->faker->paragraph,
            'prioridade' => $this->faker->randomElement(['baixa', 'media', 'alta']),
            'status' => 'aberto',
            'setor' => $this->faker->randomElement(['RH', 'Financeiro', 'TI', 'Comercial', 'Operacoes']),
            'responsavel_id' => null,
            'nome_solicitante' => $this->faker->name,
            'matricula_solicitante' => $this->faker->numerify('#####'),
        ];
    }
}
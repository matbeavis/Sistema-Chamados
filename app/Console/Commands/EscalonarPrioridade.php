<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Chamado;
use Carbon\Carbon;

class EscalonarPrioridade extends Command
{
    protected $signature = 'chamados:escalonar';
    protected $description = 'Aumenta a prioridade de chamados antigos';

    public function handle()
    {
        $limiteMedia = Carbon::now()->subDays(2);
        $limiteAlta = Carbon::now()->subDays(4);

        Chamado::whereIn('status', ['aberto', 'em andamento'])
            ->where('prioridade', 'baixa')
            ->where('created_at', '<', $limiteMedia)
            ->update(['prioridade' => 'media', 'escalonado' => true]);

        Chamado::whereIn('status', ['aberto', 'em andamento'])
            ->where('prioridade', 'media')
            ->where('created_at', '<', $limiteAlta)
            ->update(['prioridade' => 'alta', 'escalonado' => true]);
    }
}
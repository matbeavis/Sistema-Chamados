<?php

use App\Http\Controllers\ChamadoController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    $usuario = auth()->user();
    
    $chamadosAtivos = \App\Models\Chamado::where('responsavel_id', $usuario->id)
        ->whereIn('status', ['aberto', 'em andamento'])
        ->orderBy('created_at', 'desc')
        ->get();

    $pesos = ['baixa' => 1, 'media' => 2, 'alta' => 3];
    $cargaTrabalho = $chamadosAtivos->reduce(function ($total, $chamado) use ($pesos) {
        return $total + ($pesos[$chamado->prioridade] ?? 0);
    }, 0);

    $estatisticas = [
        'pendentes' => $chamadosAtivos->count(),
        'cargaTrabalho' => $cargaTrabalho,
        'resolvidosHoje' => \App\Models\Chamado::where('responsavel_id', $usuario->id)
            ->whereIn('status', ['resolvido', 'fechado'])
            ->whereDate('updated_at', \Carbon\Carbon::today())
            ->count(),
    ];

    return \Inertia\Inertia::render('Dashboard', [
        'meusChamados' => $chamadosAtivos->take(5)->values(),
        'estatisticas' => $estatisticas
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/chamados/metricas', [ChamadoController::class, 'metricas'])->name('chamados.metricas');
    Route::resource('chamados', ChamadoController::class);
});

require __DIR__.'/auth.php';
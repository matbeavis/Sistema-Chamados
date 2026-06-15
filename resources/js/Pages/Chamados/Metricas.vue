<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    metrics: Object,
});

const formatarTempo = (tempoDecimal) => {
    if (!tempoDecimal) return '0h';

    const horasInteiras = Math.floor(tempoDecimal);
    const minutosDecimais = (tempoDecimal - horasInteiras) * 60;
    const minutosArredondados = Math.round(minutosDecimais);

    if (horasInteiras === 0) {
        return `${minutosArredondados}m`;
    }

    if (minutosArredondados === 0) {
        return `${horasInteiras}h`;
    }

    return `${horasInteiras}h ${minutosArredondados}m`;
};
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-center text-xl text-gray-200 leading-tight">Métricas do Sistema</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

                    <div class="bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-700">
                        <div class="text-sm font-medium text-gray-400 uppercase">Lead Time Médio</div>
                        <div class="mt-2 flex items-baseline">
                            <span class="text-3xl font-semibold text-gray-100">{{ formatarTempo(metrics.leadTimeMedio) }}</span>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Tempo total desde a criação até o encerramento do chamado.</p>
                    </div>

                    <div class="bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-700">
                        <div class="text-sm font-medium text-gray-400 uppercase">Cycle Time Médio</div>
                        <div class="mt-2 flex items-baseline">
                            <span class="text-3xl font-semibold text-gray-100">{{ formatarTempo(metrics.cycleTimeMedio) }}</span>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Tempo médio de atuação em progresso ativo na equipe.</p>
                    </div>

                    <div class="bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-700">
                        <div class="text-sm font-medium text-gray-400 uppercase">Throughput (7 dias)</div>
                        <div class="mt-2 flex items-baseline">
                            <span class="text-3xl font-semibold text-gray-100">{{ metrics.throughput }}</span>
                            <span class="ml-2 text-sm text-gray-400">chamados</span>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Quantidade de demandas finalizadas na última semana útil.</p>
                    </div>

                    <div class="bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-700">
                        <div class="text-sm font-medium text-gray-400 uppercase">WIP Atual</div>
                        <div class="mt-2 flex items-baseline">
                            <span class="text-3xl font-semibold text-gray-100">{{ metrics.statusDistribricao['em andamento'] }}</span>
                            <span class="ml-2 text-sm text-gray-400">chamados</span>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Total de tarefas simultâneas na coluna em andamento.</p>
                    </div>

                </div>

                <div class="bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-700">
                    <h3 class="text-lg font-bold text-gray-200 mb-6">Distribuição Real do Fluxo</h3>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-sm text-gray-400 mb-1">
                                <span>Abertos</span>
                                <span class="font-semibold text-gray-200">{{ metrics.statusDistribricao['aberto'] }}</span>
                            </div>
                            <div class="w-full bg-gray-900 rounded-full h-2">
                                <div class="bg-red-500 h-2 rounded-full"
                                    :style="{ width: Math.min(metrics.statusDistribricao['aberto'] * 10, 100) + '%' }">
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm text-gray-400 mb-1">
                                <span>Em Andamento</span>
                                <span class="font-semibold text-gray-200">{{ metrics.statusDistribricao['em andamento'] }}</span>
                            </div>
                            <div class="w-full bg-gray-900 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full"
                                    :style="{ width: Math.min(metrics.statusDistribricao['em andamento'] * 10, 100) + '%' }">
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm text-gray-400 mb-1">
                                <span>Resolvidos</span>
                                <span class="font-semibold text-gray-200">{{ metrics.statusDistribricao['resolvido'] }}</span>
                            </div>
                            <div class="w-full bg-gray-900 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full"
                                    :style="{ width: Math.min(metrics.statusDistribricao['resolvido'] * 10, 100) + '%' }">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
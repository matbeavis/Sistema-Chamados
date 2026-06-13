<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    chamados: Array,
});

const colunas = [
    { titulo: 'Abertos', status: 'aberto', limite: 10, corFundo: 'bg-red-50', corBorda: 'border-red-200' },
    { titulo: 'Em Andamento', status: 'em andamento', limite: 3, corFundo: 'bg-blue-50', corBorda: 'border-blue-200' },
    { titulo: 'Resolvidos', status: 'resolvido', limite: 10, corFundo: 'bg-green-50', corBorda: 'border-green-200' },
    { titulo: 'Fechados', status: 'fechado', limite: 50, corFundo: 'bg-gray-50', corBorda: 'border-gray-200' }
];

const filtrarChamados = (status) => {
    return props.chamados.filter(chamado => chamado.status === status);
};

const calcularEsforco = (chamadosFiltrados) => {
    const pesos = { baixa: 1, media: 2, alta: 3 };
    return chamadosFiltrados.reduce((total, chamado) => total + (pesos[chamado.prioridade] || 0), 0);
};

const obterPeso = (prioridade) => {
    const pesos = { baixa: 1, media: 2, alta: 3 };
    return pesos[prioridade] || 0;
};

const formatarData = (dataString) => {
    if (!dataString) return '';
    const data = new Date(dataString);
    return new Intl.DateTimeFormat('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    }).format(data);
};

const avancarStatus = (chamadoId) => {
    router.patch(route('chamados.avancar', chamadoId));
};

</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Quadro de Chamados</h2>
                <div class="flex gap-4">
                    <a :href="route('chamados.exportar.limpar')"
                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm flex items-center shadow transition">
                        Gerar Relatório Fechados
                    </a>
                    <Link :href="route('chamados.create')"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded text-sm flex items-center shadow transition">
                        Novo Chamado
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div v-for="coluna in colunas" :key="coluna.status" class="flex flex-col h-full">
                        <div class="bg-white rounded-t-lg shadow border-b-4 p-4 text-center font-bold text-gray-700"
                            :class="coluna.corBorda">
                            <div>
                                {{ coluna.titulo }}
                                <span class="text-xs ml-2 px-2 py-0.5 rounded transition-colors"
                                    :class="filtrarChamados(coluna.status).length > coluna.limite ? 'bg-red-500 text-white' : 'bg-gray-200 text-gray-700'">
                                    {{ filtrarChamados(coluna.status).length }} / {{ coluna.limite }}
                                </span>
                            </div>
                            <div class="text-[10px] text-gray-400 mt-1 uppercase tracking-wide">
                                Carga Total {{ calcularEsforco(filtrarChamados(coluna.status)) }} pontos
                            </div>
                        </div>

                        <div class="flex-1 p-2 rounded-b-lg shadow-inner min-h-[500px]" :class="coluna.corFundo">

                            <div v-for="chamado in filtrarChamados(coluna.status)" :key="chamado.id"
                                class="bg-white p-4 mb-3 rounded shadow border border-gray-200 hover:bg-gray-50 transition">

                                <Link :href="route('chamados.edit', chamado.id)" class="block cursor-pointer">
                                    <h3 class="font-bold text-gray-800 text-sm mb-1">{{ chamado.titulo }}</h3>
                                    <div class="text-xs text-gray-500 mb-2">Setor {{ chamado.setor || 'Não informado' }}
                                    </div>

                                    <div v-if="chamado.nome_solicitante"
                                        class="mt-2 text-[11px] text-gray-600 bg-gray-100 p-1.5 rounded border border-gray-200">
                                        <span class="font-bold text-gray-700">Solicitante</span> {{
                                        chamado.nome_solicitante }} ({{
                                        chamado.matricula_solicitante }})
                                    </div>

                                    <div class="flex justify-between items-center text-xs mt-3">
                                        <span
                                            class="px-2 py-1 bg-gray-100 rounded-full text-gray-600 font-semibold truncate max-w-[120px]">
                                            {{ chamado.responsavel ? chamado.responsavel.name : 'Sem responsável' }}
                                        </span>
                                        <div class="flex gap-2 items-center">
                                            <span
                                                class="text-[10px] bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded border border-gray-200">
                                                Peso {{ obterPeso(chamado.prioridade) }}
                                            </span>
                                            <span class="font-bold uppercase" :class="{
                                                'text-red-500': chamado.prioridade === 'alta',
                                                'text-yellow-500': chamado.prioridade === 'media',
                                                'text-green-500': chamado.prioridade === 'baixa'
                                            }">
                                                {{ chamado.prioridade }}
                                            </span>
                                        </div>
                                    </div>

                                    <div v-if="chamado.escalonado"
                                        class="mt-3 bg-orange-100 text-orange-800 text-[10px] font-bold px-2 py-1 rounded flex items-center gap-1 w-fit border border-orange-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        ESCALONADO POR ATRASO
                                    </div>
                                </Link>

                                <div class="mt-4 pt-3 border-t border-gray-100 flex justify-between items-end">
                                    <div class="flex flex-col gap-1 text-[11px] text-gray-500">
                                        <div>Abertura {{ formatarData(chamado.created_at) }}</div>
                                        <div>Última alteração {{ formatarData(chamado.updated_at) }}</div>
                                    </div>

                                    <button v-if="chamado.status !== 'fechado'" @click.stop="avancarStatus(chamado.id)"
                                        class="text-gray-400 hover:text-indigo-600 transition p-1.5 bg-gray-50 hover:bg-indigo-50 rounded-full border border-gray-200 hover:border-indigo-200"
                                        title="Avançar para a próxima etapa">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
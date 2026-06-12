<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    chamados: Array,
});

const colunas = [
    { titulo: 'Abertos', status: 'aberto', corFundo: 'bg-red-50', corBorda: 'border-red-200' },
    { titulo: 'Em Andamento', status: 'em andamento', corFundo: 'bg-blue-50', corBorda: 'border-blue-200' },
    { titulo: 'Resolvidos', status: 'resolvido', corFundo: 'bg-green-50', corBorda: 'border-green-200' },
    { titulo: 'Fechados', status: 'fechado', corFundo: 'bg-gray-50', corBorda: 'border-gray-200' }
];

const filtrarChamados = (status) => {
    return props.chamados.filter(chamado => chamado.status === status);
};
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Quadro de Chamados</h2>
                <Link :href="route('chamados.create')" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    Novo Chamado
                </Link>
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div v-for="coluna in colunas" :key="coluna.status" class="flex flex-col h-full">
                        <div class="bg-white rounded-t-lg shadow border-b-4 p-4 text-center font-bold text-gray-700" :class="coluna.corBorda">
                            {{ coluna.titulo }}
                        </div>
                        
                        <div class="flex-1 p-2 rounded-b-lg shadow-inner min-h-[500px]" :class="coluna.corFundo">
                            <Link v-for="chamado in filtrarChamados(coluna.status)" :key="chamado.id" :href="route('chamados.edit', chamado.id)" class="block bg-white p-4 mb-3 rounded shadow border border-gray-200 cursor-pointer hover:bg-gray-50 transition">
                                <h3 class="font-bold text-gray-800 text-sm mb-1">{{ chamado.titulo }}</h3>
                                <div class="text-xs text-gray-500 mb-2">Setor {{ chamado.setor || 'Não informado' }}</div>
                                
                                <div class="flex justify-between items-center text-xs mt-3">
                                    <span class="px-2 py-1 bg-gray-100 rounded-full text-gray-600 font-semibold truncate max-w-[120px]">
                                        {{ chamado.responsavel ? chamado.responsavel.name : 'Sem responsável' }}
                                    </span>
                                    <span class="font-bold uppercase" :class="{
                                        'text-red-500': chamado.prioridade === 'alta',
                                        'text-yellow-500': chamado.prioridade === 'media',
                                        'text-green-500': chamado.prioridade === 'baixa'
                                    }">
                                        {{ chamado.prioridade }}
                                    </span>
                                </div>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    meusChamados: Array,
    estatisticas: Object,
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-center text-xl text-gray-200 leading-tight">Painel Pessoal</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-700">
                        <div class="text-sm font-medium text-gray-400 uppercase mb-2">Minhas Pendências</div>
                        <div class="text-4xl font-bold text-gray-100">{{ estatisticas.pendentes }}</div>
                        <div class="text-sm text-gray-500 mt-1">Chamados aguardando sua ação</div>
                    </div>

                    <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-700">
                        <div class="text-sm font-medium text-gray-400 uppercase mb-2">Concluídos Hoje</div>
                        <div class="text-4xl font-bold text-green-500">{{ estatisticas.resolvidosHoje }}</div>
                        <div class="text-sm text-gray-500 mt-1">Seu progresso de entregas diárias</div>
                    </div>
                </div>

                <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-700">
                    <div class="p-6 text-gray-100 border-b border-gray-700 font-bold text-lg">
                        Meus Chamados Recentes
                    </div>
                    
                    <div class="p-6">
                        <div v-if="meusChamados.length === 0" class="text-gray-400 text-center py-4">
                            Você não possui demandas ativas no momento
                        </div>
                        
                        <div v-else class="space-y-4">
                            <Link v-for="chamado in meusChamados" :key="chamado.id" :href="route('chamados.edit', chamado.id)" class="block bg-gray-900 p-4 rounded border border-gray-700 hover:bg-gray-700 transition">
                                <div class="flex justify-between items-center">
                                    <h3 class="font-bold text-gray-200">{{ chamado.titulo }}</h3>
                                    <span class="font-bold uppercase text-xs" :class="{
                                        'text-red-500': chamado.prioridade === 'alta',
                                        'text-yellow-500': chamado.prioridade === 'media',
                                        'text-green-500': chamado.prioridade === 'baixa'
                                    }">
                                        {{ chamado.prioridade }}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-400 mt-2">
                                    Setor {{ chamado.setor || 'Não informado' }}
                                </div>
                            </Link>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
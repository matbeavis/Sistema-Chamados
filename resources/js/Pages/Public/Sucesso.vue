<script setup>
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Chamado Enviado" />

    <div class="min-h-screen bg-gray-900 flex flex-col items-center justify-center p-4">
        <div class="w-full max-w-md p-8 bg-gray-800 rounded-lg border border-gray-700 text-center shadow-lg">
            <div class="w-16 h-16 bg-green-900/30 text-green-500 rounded-full flex items-center justify-center mx-auto mb-4 border border-green-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            
            <h2 class="text-2xl font-bold text-gray-200 mb-2">Solicitação Registrada</h2>
            <p class="text-gray-400 mb-8">A equipe de suporte técnico recebeu os dados e fará a análise do problema o mais rápido possível.</p>
            
            <Link :href="route('chamado.publico.create')" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded transition">
                Abrir Novo Chamado
            </Link>
        </div>
    </div>
</template>
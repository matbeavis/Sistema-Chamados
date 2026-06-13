<script setup>
import { useForm, Head } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const form = useForm({
    nome_solicitante: '',
    matricula_solicitante: '',
    titulo: '',
    setor: '',
    descricao: '',
});

const submit = () => {
    form.post(route('chamado.publico.store'));
};
</script>

<template>
    <Head title="Abrir Chamado" />

    <div class="min-h-screen bg-gray-900 flex flex-col items-center pt-12 sm:pt-20">
        <div class="w-full sm:max-w-2xl mt-6 px-6 py-8 bg-gray-800 shadow-md overflow-hidden sm:rounded-lg border border-gray-700">
            
            <div class="mb-8 text-center">
                <h2 class="text-2xl font-bold text-gray-200">Portal de Suporte Técnico</h2>
                <p class="text-gray-400 text-sm mt-2">Preencha os dados abaixo para registrar uma solicitação.</p>
            </div>

            <form @submit.prevent="submit" class="space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <InputLabel for="nome_solicitante" value="Seu Nome Completo" class="text-gray-300" />
                        <TextInput id="nome_solicitante" v-model="form.nome_solicitante" type="text" class="mt-1 block w-full bg-gray-900 border-gray-700 text-gray-300" required />
                    </div>

                    <div>
                        <InputLabel for="matricula_solicitante" value="Sua Matrícula" class="text-gray-300" />
                        <TextInput id="matricula_solicitante" v-model="form.matricula_solicitante" type="text" class="mt-1 block w-full bg-gray-900 border-gray-700 text-gray-300" required />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div class="sm:col-span-2">
                        <InputLabel for="titulo" value="Título do Problema" class="text-gray-300" />
                        <TextInput id="titulo" v-model="form.titulo" type="text" class="mt-1 block w-full bg-gray-900 border-gray-700 text-gray-300" required />
                    </div>

                    <div>
                        <InputLabel for="setor" value="Seu Setor" class="text-gray-300" />
                        <TextInput id="setor" v-model="form.setor" type="text" class="mt-1 block w-full bg-gray-900 border-gray-700 text-gray-300" required />
                    </div>
                </div>

                <div>
                    <InputLabel for="descricao" value="Descrição Detalhada" class="text-gray-300" />
                    <textarea id="descricao" v-model="form.descricao" class="mt-1 block w-full bg-gray-900 border-gray-700 text-gray-300 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm" rows="5" required></textarea>
                </div>

                <div class="flex items-center justify-end mt-6 pt-6 border-t border-gray-700">
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="w-full sm:w-auto justify-center">
                        Enviar Solicitação
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </div>
</template>
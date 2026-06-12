<script setup>
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    chamado: Object,
    responsaveis: Array,
});

const form = useForm({
    titulo: props.chamado.titulo,
    descricao: props.chamado.descricao,
    prioridade: props.chamado.prioridade,
    status: props.chamado.status,
    setor: props.chamado.setor || '',
    responsavel_id: props.chamado.responsavel_id || '',
});

const submit = () => {
    form.put(route('chamados.update', props.chamado.id));
};
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 text-center leading-tight">Editar Chamado #{{ chamado.id }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <form @submit.prevent="submit" class="space-y-4">
                        <div>
                            <InputLabel for="titulo" value="Título" />
                            <TextInput id="titulo" v-model="form.titulo" type="text" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" required />
                        </div>

                        <div>
                            <InputLabel for="descricao" value="Descrição" />
                            <textarea id="descricao" v-model="form.descricao" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4" required></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <InputLabel for="prioridade" value="Prioridade" />
                                <select id="prioridade" v-model="form.prioridade" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="baixa">Baixa</option>
                                    <option value="media">Média</option>
                                    <option value="alta">Alta</option>
                                </select>
                            </div>

                            <div>
                                <InputLabel for="status" value="Status" />
                                <select id="status" v-model="form.status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="aberto">Aberto</option>
                                    <option value="em andamento">Em Andamento</option>
                                    <option value="resolvido">Resolvido</option>
                                    <option value="fechado">Fechado</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <InputLabel for="setor" value="Setor" />
                                <TextInput id="setor" v-model="form.setor" type="text" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" />
                            </div>

                            <div>
                                <InputLabel for="responsavel_id" value="Responsável" />
                                <select id="responsavel_id" v-model="form.responsavel_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Atribuição Automática</option>
                                    <option v-for="responsavel in responsaveis" :key="responsavel.id" :value="responsavel.id">
                                        {{ responsavel.name }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Atualizar Chamado
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    responsaveis: Array,
});

const form = useForm({
    titulo: '',
    descricao: '',
    prioridade: 'media',
    setor: '',
    responsavel_id: '',
});

const submit = () => {
    form.post(route('chamados.store'));
};
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-center text-xl text-gray-200 leading-tight">Novo Chamado</h2>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <form @submit.prevent="submit" class="space-y-4">
                        <div>
                            <InputLabel for="titulo" value="Título" class="text-gray-300" />
                            <TextInput id="titulo" v-model="form.titulo" type="text"
                                class="mt-1 block w-full bg-gray-900 border-gray-700 text-gray-300 focus:border-indigo-600 focus:ring-indigo-600 shadow-sm"
                                required />
                        </div>

                        <div>
                            <InputLabel for="descricao" value="Descrição" class="text-gray-300" />
                            <textarea id="descricao" v-model="form.descricao"
                                class="mt-1 block w-full bg-gray-900 border-gray-700 text-gray-300 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm"
                                rows="4" required></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <InputLabel for="prioridade" value="Prioridade" class="text-gray-300" />
                                <select id="prioridade" v-model="form.prioridade"
                                    class="mt-1 block w-full bg-gray-900 border-gray-700 text-gray-300 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="baixa">Baixa</option>
                                    <option value="media">Média</option>
                                    <option value="alta">Alta</option>
                                </select>
                            </div>

                            <div>
                                <InputLabel for="setor" value="Setor" class="text-gray-300" />
                                <TextInput id="setor" v-model="form.setor" type="text"
                                    class="mt-1 block w-full bg-gray-900 border-gray-700 text-gray-300 focus:border-indigo-600 focus:ring-indigo-600 shadow-sm" />
                            </div>
                        </div>

                        <div>
                            <InputLabel for="responsavel_id"
                                value="Responsável (Deixe em branco para atribuição automática)"
                                class="text-gray-300" />
                            <select id="responsavel_id" v-model="form.responsavel_id"
                                class="mt-1 block w-full bg-gray-900 border-gray-700 text-gray-300 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Atribuição Automática</option>
                                <option v-for="responsavel in responsaveis" :key="responsavel.id"
                                    :value="responsavel.id">
                                    {{ responsavel.name }}
                                </option>
                            </select>

                            <div v-if="form.errors.responsavel_id"
                                class="text-red-500 text-[11px] font-bold mt-2 bg-red-900/30 p-2 rounded border border-red-800">
                                {{ form.errors.responsavel_id }}
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Salvar Chamado
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
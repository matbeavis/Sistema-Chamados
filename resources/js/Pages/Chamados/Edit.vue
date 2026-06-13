<script setup>
import { useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
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

const confirmandoExclusao = ref(false);

const confirmarExclusao = () => {
    confirmandoExclusao.value = true;
};

const fecharModal = () => {
    confirmandoExclusao.value = false;
};

const excluirChamado = () => {
    router.delete(route('chamados.destroy', props.chamado.id));
};
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-center text-xl text-gray-200 leading-tight">Editar Chamado #{{ chamado.id }}
            </h2>
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
                                <InputLabel for="status" value="Status" class="text-gray-300" />
                                <select id="status" v-model="form.status"
                                    class="mt-1 block w-full bg-gray-900 border-gray-700 text-gray-300 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="aberto">Aberto</option>
                                    <option value="em andamento">Em Andamento</option>
                                    <option value="resolvido">Resolvido</option>
                                    <option value="fechado">Fechado</option>
                                </select>
                                <div v-if="form.errors.status"
                                    class="text-red-500 text-[11px] font-bold mt-2 bg-red-900/30 p-2 rounded border border-red-800">
                                    {{ form.errors.status }}
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <InputLabel for="setor" value="Setor" class="text-gray-300" />
                                <TextInput id="setor" v-model="form.setor" type="text"
                                    class="mt-1 block w-full bg-gray-900 border-gray-700 text-gray-300 focus:border-indigo-600 focus:ring-indigo-600 shadow-sm" />
                            </div>

                            <div>
                                <InputLabel for="responsavel_id" value="Responsável" class="text-gray-300" />
                                <select id="responsavel_id" v-model="form.responsavel_id"
                                    class="mt-1 block w-full bg-gray-900 border-gray-700 text-gray-300 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="">Atribuição Automática</option>
                                    <option v-for="responsavel in responsaveis" :key="responsavel.id"
                                        :value="responsavel.id">
                                        {{ responsavel.name }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4 space-x-3">
                            <button @click="confirmarExclusao" type="button"
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Excluir Chamado
                            </button>

                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Atualizar Chamado
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div v-if="confirmandoExclusao"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-70">
            <div class="bg-gray-800 p-6 rounded-lg shadow-xl max-w-md w-full mx-4 border border-gray-700">
                <h3 class="text-lg font-bold text-gray-200 mb-6 text-center">Você realmente deseja excluir o chamado?
                </h3>
                <div class="flex justify-center space-x-4">
                    <button @click="fecharModal" type="button"
                        class="px-4 py-2 bg-gray-600 text-gray-200 rounded-md hover:bg-gray-500 font-semibold transition">
                        Não
                    </button>
                    <button @click="excluirChamado" type="button"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 font-semibold transition">
                        Sim
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
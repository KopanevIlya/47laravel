<template>

    <Head title="Чат" />
    
    <AuthenticatedLayout>
        <template #header>
        <h2
            class="text-xl font-semibold leading-tight red-text text-gray-800 "
        >
            Чат
        </h2>
    </template>

    <div class="max-w-xl mx-auto mt-10 p-4 bg-white rounded shadow">
        <h1 class="text-2xl font-bold mb-4">Чат</h1>
        <div class="space-y-4 mb-6">
            <div v-for="msg in messages" :key="msg.id" class="p-3 rounded border">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="font-semibold">{{ msg.user.name }}</span>
                        <span class="text-xs text-gray-400 ml-2">{{ new Date(msg.created_at).toLocaleString() }}</span>
                    </div>
                    <div v-if="msg.user.id === $page.props.auth.user.id">
                        <button @click="startEdit(msg)" class="text-blue-500 text-xs hover:underline">Редактировать</button>
                        <button @click="deleteMessage(msg.id)" class="text-red-500 text-xs hover:underline">Удалить</button>
                    </div>
                </div>
                <div v-if="editingId === msg.id">
                    <input v-model="editForm.content" class="border rounded px-2 py-1 mt-2 w-full" />
                    <div class="mt-2 flex gap-2">
                        <button @click="submitEdit(msg.id)" class="bg-blue-500 text-white px-3 py-1 rounded">Сохранить</button>
                        <button @click="cancelEdit" class="bg-gray-300 px-3 py-1 rounded">Отмена</button>
                    </div>
                    <div v-if="editForm.errors.content" class="text-red-500 text-xs mt-1">{{ editForm.errors.content }}</div>
                </div>
                <div v-else>
                    <p class="mt-2">{{ msg.content }}</p>
                </div>
            </div>
        </div>
 
        <form @submit.prevent="form.post(route('chat.store'), { preserveScroll: true })" class="flex gap-2">
            <input v-model="form.content" class="border rounded px-2 py-1 flex-1" placeholder="Введите сообщение..." />
            <button class="bg-blue-500 text-white px-4 py-1 rounded" :disabled="form.processing">Отправить</button>
        </form>
        <div v-if="form.errors.content" class="text-red-500 text-xs mt-1">{{ form.errors.content }}</div>
    </div>


</AuthenticatedLayout>
</template>
 
<script setup>
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
 
const props = defineProps({ messages: Array })
 
const form = useForm({ content: '' })
 
const editingId = ref(null)
const editForm = useForm({ content: '' })
 
function startEdit(msg) {
    editingId.value = msg.id
    editForm.content = msg.content
}
 
function cancelEdit() {
    editingId.value = null
    editForm.content = ''
}

function deleteMessage(id){
    editForm.delete(route('chat.destroy',id),{
        preserveScroll: true,
    })
}
 
function submitEdit(id) {
    editForm.put(route('chat.update', id), {
        preserveScroll: true,
        onSuccess: () => {
            editingId.value = null
            editForm.content = ''
        }
    })
}
</script>
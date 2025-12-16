<script setup>
import { ref, onMounted } from 'vue'
import Sidebar from '@/components/Sidebar.vue'
import CustomerForm from '@/components/CustomerForm.vue'
import Modal from '@/components/Modal.vue'
import { customerService } from '@/services/customer.js'
import { getErrorMessage } from '@/utils/errorHandler.js'

const customers = ref([])
const loading = ref(false)
const searchTerm = ref('')
const showCustomerForm = ref(false)
const showDeleteModal = ref(false)
const customerToDelete = ref(null)
const deleteError = ref('')
const deleting = ref(false)

const fetchCustomers = async () => {
  loading.value = true
  try {
    customers.value = await customerService.list(searchTerm.value)
  } catch (error) {
    console.error('Erro ao buscar clientes:', error)
  } finally {
    loading.value = false
  }
}

const handleCustomerSaved = () => {
  fetchCustomers()
}

const openDeleteModal = (customer) => {
  customerToDelete.value = customer
  deleteError.value = ''
  showDeleteModal.value = true
}

const closeDeleteModal = () => {
  showDeleteModal.value = false
  customerToDelete.value = null
  deleteError.value = ''
}

const confirmDelete = async () => {
  if (!customerToDelete.value) return
  
  deleteError.value = ''
  deleting.value = true
  
  try {
    await customerService.delete(customerToDelete.value.id)
    closeDeleteModal()
    fetchCustomers()
  } catch (error) {
    deleteError.value = getErrorMessage(error, 'Erro ao excluir cliente')
  } finally {
    deleting.value = false
  }
}

onMounted(() => {
  fetchCustomers()
})
</script>

<template>
  <div class="page">
    <Sidebar />
    <main class="page__content">
      <div class="customers">
        <div class="customers__header">
          <h1 class="customers__title">Clientes</h1>
          <button class="btn btn--primary" @click="showCustomerForm = true">Novo Cliente</button>
        </div>

        <div class="customers__filters">
          <input 
            type="text" 
            placeholder="Pesquisar..." 
            class="customers__search" 
            v-model="searchTerm"
            @keyup.enter="fetchCustomers"
          />
          <button class="btn btn--secondary" @click="fetchCustomers">Pesquisar</button>
        </div>

        <div class="customers__body">
          <div v-if="loading" class="customers__loading">Carregando...</div>
          <div v-else-if="customers.length === 0" class="customers__empty">Nenhum cliente encontrado.</div>
          <table v-else class="customers__table">
            <thead>
              <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Ações</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="customer in customers" :key="customer.id">
                <td>{{ customer.name }}</td>
                <td>{{ customer.email }}</td>
                <td>
                  <button class="btn btn--icon">Editar</button>
                  <button class="btn btn--icon btn--danger" @click="openDeleteModal(customer)">Excluir</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </main>

    <CustomerForm 
      :show="showCustomerForm" 
      @close="showCustomerForm = false"
      @saved="handleCustomerSaved"
    />

    <Modal
      :isOpen="showDeleteModal"
      title="Confirmar Exclusão"
      type="danger"
      :confirmText="deleting ? 'Excluindo...' : 'Excluir'"
      cancelText="Cancelar"
      @update:isOpen="showDeleteModal = $event"
      @confirm="confirmDelete"
      @cancel="closeDeleteModal"
    >
      <p class="delete-modal__message">
        Tem certeza que deseja excluir o cliente <strong>{{ customerToDelete?.name }}</strong>?
      </p>
      <p class="delete-modal__warning">
        Esta ação não pode ser desfeita.
      </p>
      <div v-if="deleteError" class="delete-modal__error">
        {{ deleteError }}
      </div>
    </Modal>
  </div>
</template>

<style scoped lang="scss">
.page {
  display: flex;
  min-height: 100vh;

  &__content {
    flex: 1;
    padding: 24px;
    background-color: #f5f5f5;
  }
}

.customers {
  max-width: 100%;

  &__header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
  }

  &__title {
    margin: 0;
    font-size: 28px;
    font-weight: 600;
    color: var(--color-text);
  }

  &__filters {
    display: flex;
    gap: 16px;
    margin-bottom: 32px;
  }

  &__search {
    padding: 8px;
    border: 1px solid var(--color-border);
    border-radius: 4px;
    min-width: 300px;
    font-size: 16px;

    &:focus {
      outline: none;
      border-color: var(--color-primary);
    }
  }

  &__body {
    background: var(--color-bg);
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    overflow: hidden;
  }

  &__loading,
  &__empty {
    text-align: center;
    padding: 48px;
    color: var(--color-text-muted);
    font-size: 16px;
  }

  &__table {
    width: 100%;
    border-collapse: collapse;

    th,
    td {
      padding: 16px;
      text-align: left;
      border-bottom: 1px solid #eee;
    }

    th {
      background-color: var(--color-bg-secondary);
      font-weight: 600;
      color: var(--color-text-muted);
      font-size: 14px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    tbody tr {
      transition: background-color 0.2s;

      &:hover {
        background-color: var(--color-bg-secondary);
      }

      &:last-child td {
        border-bottom: none;
      }
    }

    td {
      color: var(--color-text);
    }
  }
}

.delete-modal {
  &__message {
    margin: 0;
    color: var(--color-text);
  }

  &__warning {
    margin: 16px 0 0 0;
    color: var(--color-text-muted);
    font-size: 14px;
  }

  &__error {
    margin-top: 16px;
    padding: 12px;
    background-color: rgba(239, 68, 68, 0.1);
    border-radius: 4px;
    color: var(--color-danger);
    font-size: 14px;
  }
}
</style>

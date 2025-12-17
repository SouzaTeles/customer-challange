<script setup>
import { ref, computed, watch } from 'vue'
import { getErrorMessage } from '@/utils/errorHandler.js'
import { customerService } from '@/services/customer.js'

const props = defineProps({
  show: {
    type: Boolean,
    required: true
  },
  customer: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['close', 'saved'])

const name = ref('')
const cpf = ref('')
const birthDate = ref('')
const email = ref('')
const rg = ref('')
const phone = ref('')
const loading = ref(false)
const error = ref('')

const isEditMode = computed(() => !!props.customer)

watch(() => props.customer, (customer) => {
  if (customer) {
    name.value = customer.name || ''
    cpf.value = customer.cpf || ''
    birthDate.value = customer.birthDate || ''
    email.value = customer.email || ''
    rg.value = customer.rg || ''
    phone.value = customer.phone || ''
  }
}, { immediate: true })

const handleSubmit = async () => {
  error.value = ''
  loading.value = true
  
  try {
    const customerData = {
      name: name.value,
      cpf: cpf.value.replace(/\D/g, ''), // Remove formatação
      birthDate: birthDate.value,
      email: email.value,
      rg: rg.value || null,
      phone: phone.value || null
    }

    if (isEditMode.value && props.customer) {
      await customerService.update(props.customer.id, customerData)
    } else {
      await customerService.create(customerData)
    }
    
    clearForm()
    emit('saved')
    emit('close')
  } catch (e) {
    const action = isEditMode.value ? 'atualizar' : 'cadastrar'
    error.value = getErrorMessage(e, `Erro ao ${action} cliente`)
  } finally {
    loading.value = false
  }
}

const clearForm = () => {
  name.value = ''
  cpf.value = ''
  birthDate.value = ''
  email.value = ''
  rg.value = ''
  phone.value = ''
  error.value = ''
  isEditMode.value = false
}

const handleClose = () => {
  emit('close')
}
</script>

<template>
  <Transition name="slide">
    <div v-if="show" class="slide-in">
      <div class="slide-in__overlay" @click="handleClose"></div>
      <div class="slide-in__panel">
        <div class="slide-in__header">
          <h2 class="slide-in__title">{{ isEditMode ? 'Editar Cliente' : 'Novo Cliente' }}</h2>
          <button class="slide-in__close" @click="handleClose">&times;</button>
        </div>

        <form class="slide-in__form" @submit.prevent="handleSubmit">
          <div v-if="error" class="slide-in__error">{{ error }}</div>

          <div class="slide-in__field">
            <label class="slide-in__label">Nome Completo *</label>
            <input 
              type="text" 
              class="slide-in__input" 
              v-model="name" 
              required 
              :disabled="loading"
              maxlength="200"
            />
          </div>

          <div class="slide-in__field">
            <label class="slide-in__label">CPF *</label>
            <input 
              type="text" 
              class="slide-in__input" 
              v-model="cpf" 
              required 
              :disabled="loading"
              placeholder="000.000.000-00"
              maxlength="14"
            />
          </div>

          <div class="slide-in__field">
            <label class="slide-in__label">Data de Nascimento *</label>
            <input 
              type="date" 
              class="slide-in__input" 
              v-model="birthDate" 
              required 
              :disabled="loading"
            />
          </div>

          <div class="slide-in__field">
            <label class="slide-in__label">Email *</label>
            <input 
              type="email" 
              class="slide-in__input" 
              v-model="email" 
              required 
              :disabled="loading"
              maxlength="255"
            />
          </div>

          <div class="slide-in__field">
            <label class="slide-in__label">RG</label>
            <input 
              type="text" 
              class="slide-in__input" 
              v-model="rg" 
              :disabled="loading"
              maxlength="20"
            />
          </div>

          <div class="slide-in__field">
            <label class="slide-in__label">Telefone</label>
            <input 
              type="tel" 
              class="slide-in__input" 
              v-model="phone" 
              :disabled="loading"
              placeholder="(00) 00000-0000"
              maxlength="20"
            />
          </div>

          <div class="slide-in__actions">
            <button type="button" class="btn btn--secondary" @click="handleClose" :disabled="loading">
              Cancelar
            </button>
            <button type="submit" class="btn btn--primary" :disabled="loading">
              {{ loading ? 'Salvando...' : (isEditMode ? 'Atualizar' : 'Salvar') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </Transition>
</template>

<style scoped lang="scss">
.slide-in {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 1000;
  display: flex;
  justify-content: flex-end;

  &__overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(2px);
  }

  &__panel {
    position: relative;
    width: 100%;
    max-width: 500px;
    height: 100%;
    background: var(--color-bg);
    box-shadow: -2px 0 8px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    overflow-y: auto;
  }

  &__header {
    padding: 24px;
    border-bottom: 1px solid var(--color-border);
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  &__title {
    margin: 0;
    font-size: 20px;
    font-weight: 600;
    color: var(--color-text);
  }

  &__close {
    background: none;
    border: none;
    font-size: 32px;
    cursor: pointer;
    color: var(--color-muted);
    padding: 0;
    line-height: 1;
    transition: color 0.2s;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;

    &:hover {
      color: var(--color-text);
    }
  }

  &__form {
    padding: 24px;
    flex: 1;
    display: flex;
    flex-direction: column;
  }

  &__error {
    color: var(--color-danger);
    margin-bottom: 16px;
    padding: 12px;
    background-color: rgba(239, 68, 68, 0.1);
    border-radius: 4px;
    font-size: 14px;
  }

  &__field {
    margin-bottom: 20px;
  }

  &__label {
    display: block;
    margin-bottom: 8px;
    color: var(--color-text);
    font-weight: 500;
    font-size: 14px;
  }

  &__input {
    width: 100%;
    padding: 12px;
    border: 1px solid var(--color-border);
    border-radius: 4px;
    font-size: 16px;
    transition: border-color 0.2s;

    &:focus {
      outline: none;
      border-color: var(--color-primary);
    }

    &:disabled {
      background-color: var(--color-bg-secondary);
      cursor: not-allowed;
    }
  }

  &__actions {
    margin-top: auto;
    padding-top: 24px;
    display: flex;
    gap: 12px;
    justify-content: flex-end;
  }
}

.slide-enter-active,
.slide-leave-active {
  transition: opacity 0.3s ease;

  .slide-in__panel {
    transition: transform 0.3s ease;
  }
}

.slide-enter-from,
.slide-leave-to {
  opacity: 0;

  .slide-in__panel {
    transform: translateX(100%);
  }
}
</style>

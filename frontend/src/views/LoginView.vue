<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { authService } from '@/services/auth.js'
import { getErrorMessage } from '@/utils/errorHandler.js'

const email = ref('')
const password = ref('')
const error = ref('')
const router = useRouter()

const handleLogin = async () => {
  try {
    await authService.login(email.value, password.value)
    router.push('/')
  } catch (e) {
    error.value = getErrorMessage(e, 'Erro ao realizar login')
  }
}
</script>

<template>
  <div class="login">
    <div class="login__card">
      <h1 class="login__title">Login</h1>
      <form class="login__form" @submit.prevent="handleLogin">
        <div class="login__field">
          <label class="login__label">Email</label>
          <input class="login__input" type="email" v-model="email" required />
        </div>
        <div class="login__field">
          <label class="login__label">Senha</label>
          <input class="login__input" type="password" v-model="password" required />
        </div>
        <div v-if="error" class="login__error">{{ error }}</div>
        <button class="login__button" type="submit">Entrar</button>
      </form>
    </div>
  </div>
</template>

<style scoped lang="scss">
.login {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  background-color: #f0f2f5;

  &__card {
    background: var(--color-bg);
    padding: 32px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    width: 100%;
    max-width: 400px;
  }

  &__title {
    text-align: center;
    margin-bottom: 32px;
    color: var(--color-text);
  }

  &__form {
    width: 100%;
  }

  &__field {
    margin-bottom: 16px;
  }

  &__label {
    display: block;
    margin-bottom: 8px;
    color: var(--color-text-muted);
  }

  &__input {
    width: 100%;
    padding: 12px;
    border: 1px solid var(--color-border);
    border-radius: 4px;
    font-size: 16px;

    &:focus {
      outline: none;
      border-color: var(--color-primary);
    }
  }

  &__button {
    width: 100%;
    padding: 12px;
    background-color: var(--color-primary);
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    margin-top: 16px;

    &:hover {
      filter: brightness(110%);
    }
  }

  &__error {
    color: var(--color-danger);
    margin-bottom: 16px;
    text-align: center;
  }
}
</style>

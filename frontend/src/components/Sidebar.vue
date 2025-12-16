<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import iconLogOff from '../assets/icons/log-off.svg'
import Modal from './Modal.vue'
import { authService } from '@/services/auth.js'

const showLogoffModal = ref(false)
const router = useRouter()

const user = computed(() => authService.getUser())
const userName = computed(() => user.value?.name || 'Usuário')
const userInitials = computed(() => {
    const name = userName.value
    const parts = name.split(' ')
    if (parts.length >= 2) {
        return (parts[0][0] + parts[1][0]).toUpperCase()
    }
    return name.substring(0, 2).toUpperCase()
})

const handleLogoff = async () => {
    await authService.logout()
    showLogoffModal.value = false
    router.push('/login')
}
</script>

<template>
    <aside class="sidebar" aria-label="Sidebar">
        <div class="sidebar__brand">
            <div class="sidebar__title">Cadastro de Clientes</div>
        </div>

        <nav class="sidebar__nav">
            <a class="sidebar__link is-active" href="#">
                <span class="sidebar__linkLabel">Clientes</span>
            </a>
        </nav>

        <div class="sidebar__footer">
            <div class="sidebar__user">
                <div class="sidebar__userAvatar">{{ userInitials }}</div>
                <div class="sidebar__userInfo">
                    <span class="sidebar__userName">{{ userName }}</span>
                </div>
            </div>
            <button class="sidebar__logoff" title="Sair" @click="showLogoffModal = true">
                <img :src="iconLogOff" alt="Sair" />
            </button>
        </div>

        <Modal
            v-model:isOpen="showLogoffModal"
            title="Sair do Sistema"
            message="Tem certeza que deseja sair do sistema?"
            confirmText="Sair"
            type="danger"
            @confirm="handleLogoff"
        />
    </aside>
</template>

<style scoped lang="scss">
.sidebar {
    width: 280px;
    min-width: 280px;
    height: 100vh;
    background: var(--color-primary);
    color: white;
    padding: 18px 14px;
    display: flex;
    flex-direction: column;
    gap: 18px;

    &__brand {
        display: flex;
        align-items: center;
        padding: 8px 10px;
    }

    &__title {
        font-weight: 700;
        font-size: 14px;
    }

    &__nav {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    &__link {
        display: flex;
        align-items: center;
        height: 40px;
        padding: 0 10px;
        border-radius: 10px;
        color: white;
        text-decoration: none;

        &.is-active {
            background: white;
            color: var(--color-primary);
        }
    }

    &__linkLabel {
        font-size: 14px;
        font-weight: 600;
    }

    &__footer {
        margin-top: auto;
        padding-top: 18px;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    &__user {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    &__userAvatar {
        width: 36px;
        height: 36px;
        background: white;
        color: var(--color-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
    }

    &__userInfo {
        display: flex;
        flex-direction: column;
        line-height: 1.2;
    }

    &__userName {
        font-size: 13px;
        font-weight: 600;
    }

    &__logoff {
        background: transparent;
        border: none;
        color: white;
        cursor: pointer;
        padding: 8px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: .2s;
        opacity: 0.8;

        &:hover {
            background: #FFFFFF26;
        }
    }
}
</style>

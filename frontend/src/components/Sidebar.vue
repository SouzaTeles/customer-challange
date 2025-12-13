<script setup>
import iconLogOff from '../assets/icons/log-off.svg'

const title = 'Customer Challenge'
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
                <div class="sidebar__userAvatar">AD</div>
                <div class="sidebar__userInfo">
                    <span class="sidebar__userName">Admin User</span>
                </div>
            </div>
            <button class="sidebar__logoff" title="Sair">
                <img :src="iconLogOff" alt="Sair" />
            </button>
        </div>
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
}

.sidebar__brand {
    display: flex;
    align-items: center;
    padding: 8px 10px;
}

.sidebar__title {
    font-weight: 700;
    font-size: 14px;
}

.sidebar__nav {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.sidebar__link {
    display: flex;
    align-items: center;
    height: 40px;
    padding: 0 10px;
    border-radius: 10px;
    color: white;
    text-decoration: none;
}

.sidebar__link.is-active {
    background: white;
    color: var(--color-primary);
}

.sidebar__linkLabel {
    font-size: 14px;
    font-weight: 600;
}

.sidebar__footer {
    margin-top: auto;
    padding-top: 18px;
    border-top: 1px solid color-mix(in srgb, white 20%, transparent);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
}

.sidebar__user {
    display: flex;
    align-items: center;
    gap: 12px;
}

.sidebar__userAvatar {
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

.sidebar__userInfo {
    display: flex;
    flex-direction: column;
    line-height: 1.2;
}

.sidebar__userName {
    font-size: 13px;
    font-weight: 600;
}

.sidebar__logoff {
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
</style>

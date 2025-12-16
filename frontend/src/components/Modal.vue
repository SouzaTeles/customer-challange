<script setup>
defineProps({
    isOpen: {
        type: Boolean,
        required: true
    },
    title: {
        type: String,
        default: ''
    },
    message: {
        type: String,
        default: ''
    },
    confirmText: {
        type: String,
        default: 'Confirmar'
    },
    cancelText: {
        type: String,
        default: 'Cancelar'
    },
    type: {
        type: String,
        default: 'info',
        validator: (value) => ['info', 'danger', 'success'].includes(value)
    }
})

const emit = defineEmits(['update:isOpen', 'confirm', 'cancel'])

const close = () => {
    emit('update:isOpen', false)
    emit('cancel')
}

const confirm = () => {
    emit('confirm')
}
</script>

<template>
    <Transition name="modal">
        <div v-if="isOpen" class="modal__overlay" @click.self="close">
            <div class="modal__container" role="dialog" aria-modal="true">
                <div class="modal__header" v-if="title">
                    <h3 class="modal__title">{{ title }}</h3>
                    <button class="modal__close" @click="close" aria-label="Fechar">
                        &times;
                    </button>
                </div>

                <div class="modal__body">
                    <slot>
                        <p v-if="message">{{ message }}</p>
                    </slot>
                </div>

                <div class="modal__footer">
                    <button class="btn btn--secondary" @click="close">
                        {{ cancelText }}
                    </button>
                    <button class="btn btn--primary" :class="{ 'btn--danger': type === 'danger' }" @click="confirm">
                        {{ confirmText }}
                    </button>
                </div>
            </div>
        </div>
    </Transition>
</template>

<style scoped lang="scss">
.modal {
    &__overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        backdrop-filter: blur(2px);
    }

    &__container {
        background: var(--color-bg);
        border-radius: 12px;
        width: 90%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
    }

    &__header {
        padding: 20px 24px;
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
        font-size: 24px;
        cursor: pointer;
        color: var(--color-muted);
        padding: 0 5px;
        line-height: 1;
        transition: color 0.2s;

        &:hover {
            color: var(--color-text);
        }
    }

    &__body {
        padding: 24px;
        color: var(--color-text);
        line-height: 1.5;
    }

    &__footer {
        padding: 20px 24px;
        border-top: 1px solid var(--color-border);
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        background-color: var(--color-bg-secondary);
        border-bottom-left-radius: 12px;
        border-bottom-right-radius: 12px;
    }
}

/* Transições */
.modal-enter-active,
.modal-leave-active {
    transition: opacity .3s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}

.modal-enter-active .modal__container,
.modal-leave-active .modal__container {
    transition: transform 0.3s ease;
}

.modal-enter-from .modal__container,
.modal-leave-to .modal__container {
    transform: scale(0.95) translateY(10px);
}
</style>

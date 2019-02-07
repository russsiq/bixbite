<template>
<transition name="modal__fade">
    <div class="modal__overlay" @click="handleClick()">
        <div class="modal__dialog">
            <div class="modal__content">
                <div class="modal__header" v-if="hasHeaderSlot()">
                    <h5 class="modal__title">
                        <slot name="modal__header" />
                    </h5>
                    <button type="button" class="modal__close" @click="close()">&times;</button>
                </div>

                <div class="modal__body">
                    <slot name="modal__body" />
                </div>

                <div class="modal__footer">
                    <slot name="modal__footer">
                        <button type="button" class="btn btn-outline-dark" @click="close()">Close</button>
                    </slot>
                </div>
            </div>
        </div>
    </div>
</transition>
</template>

<script type="text/ecmascript-6">
export default {
    data() {
        return {

        }
    },

    created() {
        document.addEventListener('keydown', this.handleEscape)
        document.body.classList.add('overflow-hidden')
    },

    destroyed() {
        document.removeEventListener('keydown', this.handleEscape)
        document.body.classList.remove('overflow-hidden')
    },

    methods: {
        /**
         * Handle a click on the modal.
         */
        handleClick(e) {
            if (e.target.classList.contains('modal__overlay')) {
                this.close()
            }
        },

        /**
         * Handle a keydown `Esc` in the modal.
         */
        handleEscape(e) {
            e.stopPropagation()

            if (e.keyCode == 27) {
                this.close()
            }
        },

        /**
         * Close the modal.
         */
        close() {
            this.$emit('close')
        },

        /**
         * Only show header slot if it has title.
         */
        hasHeaderSlot() {
            return !!this.$slots.modal__header
        }
    }
}
</script>

<style scoped>
.modal__fade-enter {
    opacity: 0;
}

.modal__fade-leave-active {
    opacity: 0;
}

.modal__fade-enter .modal__dialog,
.modal__fade-leave-active .modal__dialog {
    transform: translate(0, -40px);
}

.modal__overlay {
    position: fixed;
    overflow-y: scroll;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 1080;
    background: rgba(0, 0, 0, .5);
    padding-right: 8px;
    transition: opacity .2s ease;
}

.modal__dialog {
    position: relative;
    width: auto;
    margin: .8rem;
    transition: all .2s ease;
}

@media (min-width: 576px) {
    .modal__dialog {
        max-width: 500px;
        margin: 1.8rem auto;
    }
}

.modal__content {
    position: relative;
    display: flex;
    flex-direction: column;
    width: 100%;
    background-color: #fff;
    /*border-radius: .2rem;*/
    box-shadow: 0 24px 40px 0 rgba(0, 0, 0, .4);
}

.modal__header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    padding: 1rem 1rem;
    border-bottom: 1px solid #eee;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
}

.modal__title {
    margin-bottom: 0;
    line-height: 1.6;
    font-family: inherit;
    font-size: 1.125rem;
}

.modal__close {
    float: right;
    font-size: 1.35rem;
    font-weight: 700;
    line-height: 1;
    opacity: 0.6;
    padding: 1rem 1rem;
    margin: -1rem -1rem -1rem auto;
    cursor: pointer;
    color: #888;
    background-color: transparent;
    border: none;
    appearance: none;
    outline: none;
    text-shadow: none;
}

.modal__body {
    position: relative;
    flex: 1 1 auto;
    padding: 1rem;
}

.modal__footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    padding: 1rem;
    border-top: 1px solid #eee;
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 0;
}
</style>

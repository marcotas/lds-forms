export default {
    mounted() {
        const events = ['hidden', 'hide', 'show', 'shown'];
        for (event of events) {
            this.$refs.modal.$on(event, this.emit.bind(this));
        }
    },

    methods: {
        open() {
            const beforeOpen = this.beforeOpen();
            if (beforeOpen !== false) {
                this.$refs.modal.open();
                this.afterOpen();
            }
            return this;
        },
        close() {
            const beforeClose = this.beforeClose();
            if (beforeClose !== false) {
                this.$refs.modal.close();
                this.afterClose();
            }
            return this;
        },
        emit(event) {
            this.$emit(event.type, event);
            switch (event.type) {
                case 'hide':
                    return this.beforeClose();
                case 'hidden':
                    return this.afterClose();
                case 'show':
                    return this.beforeOpen();
                case 'shown':
                    return this.afterOpen();
            }
        },
        afterOpen() {},
        afterClose() {},
        beforeOpen() {},
        beforeClose() {},
    },
};

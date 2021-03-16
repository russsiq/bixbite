import { Bus } from "@/store/bus.js";

export default new class {
    _show(params) {
        const id = Math.random().toString(36).slice(2);

        Bus.$emit('toast.show', {
            id: `toast-${id}`,
            time: new Date().toLocaleTimeString(),
            header: params.header || params.title || BixBite.app_name,
            body: params.detail || params.body || params.message || 'The description is unavailable.',
            animation: params.animation || true,
            autohide: params.autohide || true,
            delay: params.delay || 8000,
            ...params,
        });
    }

    info(params) {
        this._show({
            type: 'info',
            ...params,
        });
    }

    success(params) {
        this._show({
            type: 'success',
            ...params,
        });
    }

    warning(params) {
        this._show({
            type: 'warning',
            ...params,
        });
    }

    danger(params) {
        this._show({
            type: 'danger',
            ...params,
        });
    }

    error(params) {
        this.danger(params);
    }
}

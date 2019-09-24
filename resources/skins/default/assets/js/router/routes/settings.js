import Setting from '@/store/models/setting';

import Settings from '@/views/settings/index';
import SettingEdit from '@/views/settings/edit';
import System from '@/views/settings/system';

export default [{
    path: '/settings',
    name: 'settings',
    // name: 'settings.index',
    component: Settings,
    meta: {
        title: 'Список настроек'
    },
    props: route => ({
        model: Setting,
    }),
}, {
    path: '/settings/create',
    name: 'settings.create',
    component: SettingEdit,
    meta: {
        title: 'Создание настройки'
    },
    props: route => ({
        model: Setting,
        id: 0,
    }),
}, {
    path: '/settings/:id/edit',
    name: 'settings.edit',
    component: SettingEdit,
    meta: {
        title: 'Редактирование настройки'
    },
    props: route => ({
        model: Setting,
        id: parseInt(route.params.id, 10),
    }),
}, {
    path: '/system',
    name: 'system.settings',
    alias: '/system/settings',
    component: System,
    meta: {
        title: 'Настройки системы'
    }
}];

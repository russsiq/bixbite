<?php

use Illuminate\Database\Seeder;

/**
 *
 * Следующие привилегии доступны только владельцам сайта:
 * - privileges,
 * - settings,
 * - templates,
 * - themes,
 * - x_fields,
 */
class PrivilegesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Подготовка массива привилегий.
        $privileges = [
            // Просмотр заблокированного сайта
            // Не реализовано.
            'global.locked',

            // Доступ в административную панель.
            'global.panel',

            // Возможно там будет статистика.
            // Сейчас идет запрос последних заметок.
            // Разрешено всем.
            'dashboard',

            // Настройка: Разрешить регистрацию на сайте
            // 'register',

            // 'password.email',
            // 'password.request',
            // Сброс пароля.
            // 'password.reset',

            // 'articles.index',
            // // 'articles.tag',
            // // 'articles.category',
            // // 'articles.article',
            // 'articles.view',
            // 'articles.create',
            // 'articles.update',
            // 'articles.other_update',
            // 'articles.delete',

            // Список категорий всегда извлекается.
            // Разрешено всем.
            // 'categories.index',

            // 'categories.view',
            // 'categories.create',
            // 'categories.update',
            // 'categories.other_update',
            // 'categories.delete',

            // Настройка: Только для зарегистрированных.
            // 'comment.store',
            // 'comments.other_update',

            // 'files.index',
            // 'files.view',
            // 'files.create',
            // 'files.update',
            // 'files.other_update',
            // 'files.delete',

            // 'users.index',
            // 'users.view',
            // 'users.create',
            // 'users.update',
            // 'users.other_update',
            // // 'users.delete', only owner site or profile
        ];

        $inserted = [];

        foreach ($privileges as $privilege) {
            $inserted[] = [
                'privilege' => $privilege,
                'description' => trans('privileges.'.$privilege)
            ];
        }

        // Insert
        \DB::table('privileges')->insert($inserted);
    }
}

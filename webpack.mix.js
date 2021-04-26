/**
 * @cmd `npm run prod`
 * @cmd `npm run prod --dashboard`
 * @cmd `npm run watch`
 * @cmd `npm run watch --dashboard`
 */

const mix = require('laravel-mix');
const isDashboard = process.env.npm_config_dashboard;

mix.options({
    processCssUrls: false
});

if (isDashboard) {
    const DASHBOARD = process.env.APP_DASHBOARD;
    const DASHBOARD_PATH = `resources/dashboards/${DASHBOARD}`;

    mix.alias({
        '@': `${__dirname}/${DASHBOARD_PATH}/js`
    });

    mix.setPublicPath(`public/dashboards/${DASHBOARD}`)
        .vue()
        .js(DASHBOARD_PATH + '/js/app.js', 'js')
        .sass(DASHBOARD_PATH + '/sass/app.scss', 'css')
        .js(DASHBOARD_PATH + '/js/code-editor.js', 'js')
        .sass(DASHBOARD_PATH + '/sass/code-editor.scss', 'css')
        .sass(DASHBOARD_PATH + '/sass/login.scss', 'css')
        .copyDirectory(
            'node_modules/font-awesome/fonts', `public/dashboards/${DASHBOARD}/css/fonts/font-awesome`
        )
        .copyDirectory(
            `${DASHBOARD_PATH}/public`, `public/dashboards/${DASHBOARD}`
        )
        .extract([
            'axios',
            // 'baguettebox.js',
            // 'codemirror',
            // '@emmetio/codemirror-plugin',
            // // 'bxb-modal',
            // // 'bxb-notification',
            // // 'bxb-scroll-to-top',
            // // 'lodash',
            // 'quill',
            // 'vue',
        ]);
} else {
    const THEME = process.env.APP_THEME;
    const THEME_PATH = `resources/themes/${THEME}`;

    mix.setPublicPath(`public/themes/${THEME}`)
        .vue()
        .js(THEME_PATH + '/js/app.js', 'js')
        .sass(THEME_PATH + '/sass/app.scss', 'css')
        .copyDirectory(
            'node_modules/font-awesome/fonts', `public/themes/${THEME}/css/fonts/font-awesome`
        )
        .copyDirectory(
            `${THEME_PATH}/public`, `public/themes/${THEME}`
        )
        .extract([
            'axios',
        ]);
}

mix.inProduction() && mix.version();

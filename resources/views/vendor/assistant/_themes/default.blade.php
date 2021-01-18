<style media="screen">
    :root {
        --blue: #008cba;
        --indigo: #6610f2;
        --purple: #6f42c1;
        --pink: #c7254e;
        --red: #f04124;
        --orange: #fd7e14;
        --yellow: #e99002;
        --green: #43ac6a;
        --teal: #20c997;
        --cyan: #5bc0de;
        --dark: #222;
        --gray-dark: #333;
        --gray: #888;
        --light: #eee;
        --white: #fff;
        --primary: var(--blue);
        --secondary: var(--light);
        --success: var(--green);
        --info: var(--cyan);
        --warning: var(--yellow);
        --danger: var(--red);
        --font-family-sans-serif: 'Ubuntu', sans-serif;
        --font-family-monospace: 'Courier New', monospace;
        --spacer: 1.25rem;
    }

    *,
    *:after,
    *:before {
        box-sizing: border-box
    }

    body::-webkit-scrollbar {
        width: 12px;
    }

    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
    }

    ::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, .125);
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.5);
    }

    ::-webkit-scrollbar-thumb:window-inactive {
        background: rgba(0, 0, 0, .125);
    }

    html {
        font-family: sans-serif;
        min-height: 100%;
        -webkit-text-size-adjust: 100%;
        -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
    }

    body {
        margin: 0;
        font-family: var(--font-family-sans-serif);
        font-size: .9rem;
        font-weight: 100;
        line-height: 1.6;
        min-height: 100%;
        color: var(--dark);
        background-color: var(--light);
        background-image: linear-gradient(45deg, var(--pink), var(--blue));
    }

    article,
    aside,
    figcaption,
    figure,
    footer,
    header,
    hgroup,
    main,
    nav,
    section {
        display: block;
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        font-family: inherit;
        font-weight: 300;
        line-height: 1.2;
        margin-top: 0;
        margin-bottom: .5rem;
    }

    h2 {
        font-size: 1.5rem;
    }

    h5 {
        font-size: 1.125rem;
    }

    p {
        margin-bottom: 1rem;
        margin-top: 0;
    }

    dl,
    ol,
    ul {
        margin-bottom: 1rem;
        margin-top: 0;
    }

    ul {
        list-style: circle inside;
    }

    b,
    strong,
    .bold {
        font-weight: 800;
    }

    .small,
    small {
        font-size: 80%;
        font-weight: 400;
    }

    a {
        color: var(--primary);
        text-decoration: none;
        background-color: transparent;
    }

    a,
    a:hover,
    a:focus {
        transition: all .3s;
    }

    .table {
        border-collapse: collapse;
        /* font-size: .7875rem; */
        width: 100%;
        max-width: 100%;
        width: 100%;
        margin-bottom: 1rem;
        color: var(--dark);
        line-height: 1.42857143;
    }

    .table td,
    .table th {
        padding: .75rem;
        vertical-align: middle;
        text-align: inherit;
        border-top: 1px solid #dee2e6;
        position: relative;
    }

    .table thead th {
        vertical-align: bottom;
        border-bottom: 2px solid #dee2e6;
    }

    .table tfoot td,
    .table thead th {
        text-transform: uppercase;
        font-size: .7rem;
        font-weight: 700;
    }

    .table-sm td,
    .table-sm th {
        padding: .3rem;
    }

    .table>tbody:first-child>tr:first-child>td {
        border-top: 0;
    }

    table .table td,
    table .table th {
        vertical-align: top;
    }

    fieldset {
        /* min-width: 0;
        padding: 0;
        margin: 0;
        border: 0; */
        border: 1px solid rgba(0, 0, 0, .1);
        min-width: 0;
        margin: 1.25rem 0;
        padding: 1rem;
    }

    legend {
        display: block;
        max-width: 100%;
        margin: 0;
        padding: 0 0.5rem;
        font-size: 1.2rem;
        line-height: inherit;
        color: inherit;
        white-space: normal;
    }

    label {
        display: inline-block;
        margin-bottom: .5rem;
        line-height: 1.2;
        font-weight: 300;
        margin-bottom: 0;
    }

    .control-label {
        text-align: left !important;
        display: block;
        margin-bottom: 0;
    }

    .checkbox,
    .control-label,
    .dropdown-item,
    .form-control-feedback,
    .help-block,
    .radio,
    label {
        font-size: .9rem;
    }

    button,
    input,
    optgroup,
    select,
    textarea {
        margin: 0;
        font-family: inherit;
        font-size: inherit;
        line-height: inherit;
    }

    button,
    input {
        overflow: visible;
    }

    button,
    select {
        text-transform: none;
    }

    [type="button"],
    [type="reset"],
    [type="submit"],
    button {
        -webkit-appearance: button;
    }

    [type="button"]:not(:disabled),
    [type="reset"]:not(:disabled),
    [type="submit"]:not(:disabled),
    button:not(:disabled) {
        cursor: pointer;
    }

    img {
        max-width: 100%;
    }

    hr {
        box-sizing: content-box;
        height: 0;
        overflow: visible;
        margin-top: 1.25rem;
        margin-bottom: 1.25rem;
        border: 0;
        border-top: 1px solid rgba(0, 0, 0, .1);
    }

    code,
    kbd,
    pre,
    samp {
        font-family: var(--font-family-monospace);
        font-size: 90%;
    }

    code,
    kbd,
    mark {
        padding: 2px 4px;
        font-size: 90%;
        word-break: break-word;
        border-radius: 2px;
    }

    code {
        color: var(--pink);
        background-color: #f9f2f4;
    }

    small code {
        font-size: 100%;
    }

    kbd {
        color: var(--light);
        background-color: var(--dark);
        white-space: nowrap;
    }

    mark {
        color: var(--light);
        background-color: var(--yellow);
        white-space: nowrap;
    }

    ol.data {
        margin: 5px 0;
    }

    .status {
        color: rgb(169, 68, 66, 1)
    }

    .status.ok {
        color: rgba(30, 255, 20, 1)
    }

    input[type=radio],
    input[type=checkbox] {
        font-weight: 700;
        border: 1px solid #bbb;
        color: #555;
        background: var(--white);
        cursor: pointer;
        display: inline-block;
        line-height: 0;
        height: 15px;
        width: 15px;
        margin: 0;
        outline: 0 !important;
        padding: 0 !important;
        text-align: center;
        vertical-align: middle;
        -webkit-appearance: none;
    }

    input[type=radio] {
        border-radius: 50%;
        line-height: 15px;
    }

    input[type=radio]:checked:before,
    input[type=checkbox]:checked:before {
        float: left;
        display: inline-block;
        vertical-align: middle;
        width: 15px;
        font-size: 15px;
    }

    input[type=checkbox]:checked:before {
        content: '\2714';
        margin: 6px 0 0;
        color: var(--primary);
    }

    input[type=radio]:checked:before {
        content: '\2022';
        text-indent: -9999px;
        border-radius: 50px;
        font-size: 22px;
        width: 7px;
        height: 7px;
        margin: 3px;
        line-height: 15px;
        background-color: #7d0029;
    }

    label>input[type=radio],
    label>input[type=checkbox] {
        margin-right: 8px;
    }

    input[type="checkbox"]:disabled,
    input[type="radio"]:disabled {
        border: 1px dashed #ccc;
    }

    input[type="checkbox"]:disabled:before,
    input[type="radio"]:disabled:before {
        color: #ccc;
    }

    .assistant {
        background: var(--white);
        box-shadow: 0 16px 28px 0 rgba(0, 0, 0, .22), 0 25px 55px 0 rgba(0, 0, 0, .21);
        position: relative;
    }

    .assistant__header {
        padding: .75rem 1.25rem;
        margin-bottom: 0;
        background-color: rgba(0, 0, 0, .03);
        border-bottom: 1px solid rgba(0, 0, 0, .125);
        font-size: 1.125rem;
    }

    .assistant__body {
        display: flex;
        flex-wrap: wrap;
        margin-right: -15px;
        margin-left: -15px;
    }

    .assistant__aside {
        position: relative;
        width: 100%;
        padding-left: 15px;
        flex: 0 0 25%;
        max-width: 25%;
        border-right: 1px solid rgba(0, 0, 0, .125);
    }

    .assistant__main {
        position: relative;
        width: 100%;
        padding-right: 15px;
        flex: 0 0 75%;
        max-width: 75%;
    }

    .assistant__footer {
        background-color: rgba(0, 0, 0, 0.03);
        padding: 0.75rem 1.25rem;
        border-top: 1px solid rgba(0, 0, 0, 0.125);
    }

    .aside__list {
        display: flex;
        flex-direction: column;
        padding-left: 0;
        margin-bottom: 0;
        font-size: .7875rem;
        font-weight: 300;
    }

    .aside_list__item {
        position: relative;
        display: block;
        width: 100%;
        border-bottom: 1px solid rgba(0, 0, 0, .125);
    }

    .aside_list__action {
        display: block;
        width: 100%;
        color: #495057;
        text-align: inherit;
        padding: .75rem 1.25rem;
        background-color: var(--white);
    }

    .aside_list__action.active {
        background-color: var(--primary);
        border-color: var(--primary);
        color: var(--white);
    }

    .aside_list__action.disabled,
    .aside_list__action:disabled {
        color: rgb(136, 136, 136);
        pointer-events: none;
        background-color: rgb(238, 238, 238);
    }

    .form__header {
        padding: .75rem 0;
        border-bottom: 1px solid var(--primary);
        margin: .75rem 1.25rem;
    }

    .form__body {
        padding: 0.75rem 1.25rem 0;
    }

    .form__footer {
        padding: 1.25rem;
        padding-top: 0;
        border-top: 1px solid rgba(0, 0, 0, 0.125);
        border-top: none;
        display: flex;
        background: var(--white);
    }

    /**
     * THEMES
     */
    .theme-card {
        background-size: cover;
        box-shadow: 0 8px 12px 0 rgba(0, 0, 0, .25);
        font-size: .78rem;
        color: var(--white);
        height: 100%
    }

    .theme-card .color-overlay {
        height: 100%;
        background: linear-gradient(90deg, rgba(42, 159, 255, .2) 0, #212120 60%, #212120);
        background-blend-mode: multiply
    }

    .theme-card .theme-content {
        width: 40%;
        float: right;
        padding-right: 1.25rem;
        height: 100%;
        margin-bottom: 0
    }

    .theme-card .theme-content .theme-title {
        color: var(--white);
        text-transform: uppercase;
        line-height: 1;
        letter-spacing: 4px;
        margin: .75rem 0
    }

    .theme-card .theme-content .theme-info {
        letter-spacing: 1px;
        font-size: .8rem;
        line-height: 1;
        margin: 0;
        opacity: .6
    }

    .theme-card .theme-content .theme-header {
        margin-bottom: 3rem
    }

    .theme-card .theme-content .theme-desc {
        margin-bottom: 4.5rem
    }

    .theme-card .theme-content .theme-btn {
        bottom: 1.25rem;
        right: 2.25rem;
        position: absolute
    }

    @media(max-width:992px) {
        .theme-card .theme-content {
            width: 100%;
            float: none;
            padding: 1.25rem
        }

        .theme-card .color-overlay {
            background: linear-gradient(180deg, rgba(0, 140, 186, .2) 0, #212120 60%, #212120)
        }
    }

    /**
     * Bootstrap helpers class
     */
    .container {
        width: 100%;
        max-width: 1140px;
        padding-right: 15px;
        padding-left: 15px;
        margin-right: auto;
        margin-left: auto;
        min-height: calc(100vh + 54px);
        padding-top: calc(1.25rem + 54px);
        padding-bottom: 20rem;
    }

    .row {
        display: flex;
        flex-wrap: wrap;
        margin-right: -15px;
        margin-left: -15px;
    }

    .col,
    [class^="col-"] {
        position: relative;
        width: 100%;
        padding-right: 15px;
        padding-left: 15px;
    }

    .col-12 {
        flex: 0 0 100%;
        max-width: 100%;
    }

    @media(min-width: 576px) {
        .col-sm-3 {
            flex: 0 0 25%;
            max-width: 25%;
        }

        .col-sm-6 {
            flex: 0 0 50%;
            max-width: 50%;
        }

        .col-sm-9 {
            flex: 0 0 75%;
            max-width: 75%;
        }
    }

    @media(min-width: 992px) {
        .col-lg-6 {
            flex: 0 0 50%;
            max-width: 50%;
        }
    }

    @media(min-width: 576px) {
        .offset-sm-3 {
            margin-left: 25%;
        }
    }

    .card {
        position: relative;
        display: flex;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: var(--white);
        background-clip: border-box;
        border: 1px solid rgba(0, 0, 0, .125);
        margin-bottom: 1.25rem;
    }

    .card-header {
        padding: .75rem 1.25rem;
        margin-bottom: 0;
        background-color: rgba(0, 0, 0, .03);
        border-bottom: 1px solid rgba(0, 0, 0, .125);
        border-bottom: none;
    }

    .card-body {
        flex: 1 1 auto;
        padding: 1.25rem;
    }

    .card-footer {
        background-color: rgba(0, 0, 0, 0.03);
        padding: 0.75rem 1.25rem;
        border-top: 1px solid rgba(0, 0, 0, 0.125);
        border-top: none;
    }

    .d-flex {
        display: flex !important;
    }

    .d-block {
        display: block !important;
    }

    .justify-content-around {
        justify-content: space-around !important;
    }

    .ml-auto,
    .mx-auto {
        margin-left: auto !important;
    }

    .mb-4,
    .my-4 {
        margin-bottom: 1.5rem !important;
    }

    .p-0 {
        padding: 0 !important;
    }

    .pr-0,
    .px-0 {
        padding-right: 0 !important;
    }

    .pl-0,
    .px-0 {
        padding-left: 0 !important;
    }

    .text-success {
        color: var(--success) !important;
    }

    .text-danger {
        color: var(--danger) !important;
    }

    .text-muted {
        color: var(--gray) !important;
    }

    .alert {
        position: relative;
        padding: .75rem 1.25rem;
        margin-bottom: 1.25rem;
        color: var(--white);
    }

    .alert-list {
        margin: 0;
        padding: 0;
    }

    .alert-link {
        font-weight: normal;
        color: var(--white);
        text-decoration: underline;
    }

    .alert-dark {
        background-color: var(--dark);
    }

    .alert-light {
        background-color: var(--light);
    }

    .alert-primary {
        background-color: var(--primary);
    }

    .alert-secondary {
        background-color: var(--secondary);
    }

    .alert-success {
        background-color: var(--success);
    }

    .alert-info {
        background-color: var(--info);
    }

    .alert-danger {
        background-color: var(--danger);
    }

    .alert-warning {
        background-color: var(--warning);
    }

    .invalid-feedback {
        display: none;
        width: 100%;
        margin-top: .25rem;
        font-size: 80%;
        color: var(--danger);
    }

    .form-control.is-invalid,
    .was-validated .form-control:invalid {
        border-color: var(--danger);
        padding-right: calc(1.6em + .75rem);
        background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23F04124' viewBox='-2 -2 7 7'%3E%3Cpath stroke='%23F04124' d='M0 0l3 3m0-3L0 3'/%3E%3Ccircle r='.5'/%3E%3Ccircle cx='3' r='.5'/%3E%3Ccircle cy='3' r='.5'/%3E%3Ccircle cx='3' cy='3' r='.5'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: 100% calc(.4em + .1875rem);
        background-size: calc(.8em + .375rem) calc(.8em + .375rem)
    }

    .form-control.is-invalid:focus,
    .was-validated .form-control:invalid:focus {
        border-color: var(--danger);
        box-shadow: 0 0 0 .2rem rgba(240, 65, 36, .25)
    }

    .form-control.is-invalid~.invalid-feedback,
    .form-control.is-invalid~.invalid-tooltip,
    .was-validated .form-control:invalid~.invalid-feedback,
    .was-validated .form-control:invalid~.invalid-tooltip {
        display: block
    }

    .was-validated textarea.form-control:invalid,
    textarea.form-control.is-invalid {
        padding-right: calc(1.6em + .75rem);
        background-position: top calc(.4em + .1875rem) right calc(.4em + .1875rem)
    }

    .form-control {
        font-size: 1rem;
        color: #444;
        display: block;
        width: 100%;
        padding: 0.5rem 0.75rem;
        background-color: var(--white);
        background-clip: padding-box;
        border: 1px solid #ccc;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    select.form-control:not([size]):not([multiple]) {
        padding: .5rem .75rem;
        line-height: 1.5;
        height: 42px;
    }

    .form-control:focus {
        color: #000;
        background-color: var(--white);
        border-color: #3bceff;
        outline: 0;
        box-shadow: none;
    }

    .form-text {
        display: block;
        margin-top: .25rem;
    }

    .col-form-label {
        padding-top: calc(.375rem + 1px);
        padding-bottom: calc(.375rem + 1px);
        margin-bottom: 0;
        font-size: inherit;
        line-height: 1.6;
    }

    .is-invalid .form-control,
    .is-invalid input[type=radio],
    .is-invalid input[type=checkbox] {
        /* color: var(--danger); */
        border-color: var(--danger);
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-group:last-of-type {
        margin-bottom: 0;
    }

    .btn-group,
    .btn-group-vertical {
        position: relative;
        display: inline-flex;
        vertical-align: middle;
    }

    .btn:hover {
        color: rgb(34, 34, 34);
        text-decoration: none;
    }

    .btn-group-vertical>.btn,
    .btn-group>.btn {
        position: relative;
        flex: 1 1 auto;
    }

    .btn:hover,
    .btn:active,
    .btn:focus,
    .btn:active:focus,
    .btn.active:focus {
        opacity: 0.6;
        color: rgb(255, 255, 255);
        outline: 0px;
    }

    .btn-group-vertical>.btn.active,
    .btn-group-vertical>.btn:active,
    .btn-group-vertical>.btn:focus,
    .btn-group-vertical>.btn:hover,
    .btn-group>.btn.active,
    .btn-group>.btn:active,
    .btn-group>.btn:focus,
    .btn-group>.btn:hover {
        z-index: 1;
    }

    .btn {
        display: inline-block;
        font-weight: 300;
        color: rgb(34, 34, 34);
        text-align: center;
        vertical-align: middle;
        user-select: none;
        background-color: transparent;
        font-size: 0.9rem;
        line-height: 1.6;
        border-width: 1px;
        border-style: solid;
        border-color: transparent;
        border-image: initial;
        padding: 0.375rem 0.5rem;
        transition: color 0.15s ease-in-out 0s, background-color 0.15s ease-in-out 0s, border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s;
    }

    .btn {
        color: rgb(255, 255, 255);
        text-shadow: none;
        box-shadow: none;
        background: rgb(0, 140, 186);
        transition: all 0.3s ease 0s;
        padding: 0.5rem 0.75rem;
    }

    .btn.btn-previous,
    .btn.btn-previous:focus,
    .btn.btn-previous:active:focus,
    .btn.btn-previous.active:focus {
        background: #bbb;
    }
</style>

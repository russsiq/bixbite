// $(function() {
//     // Open/close the collapse panels based on history
//     $('.collapse').on('hidden.bs.collapse', function () {
//         localStorage.removeItem('open_' + this.id);
//     });
//
//     $('.collapse').on('shown.bs.collapse', function () {
//         localStorage.setItem('open_' + this.id, true);
//     });
//
//     $('.collapse').each(function () {
//         // Default close unless saved as open
//         if (localStorage.getItem('open_' + this.id)) {
//             $(this).collapse('show');
//         }
//     });
// });

const TOGGLE_MAP = {
    tab: tabHandler,
    dropdown: dropdownHandler,
    collapse: collapseHandler
}

document.addEventListener('click', clickHandler);

// Функция-обработчик щелчка.
function clickHandler(event) {
    // Элемент, по которому был выполнен щелчок.
    const target = event.target;

    // Первый ближайший родительский элемент (или сам элемент),
    // который содержит атрибут `data-toggle`.
    const element = target.closest('[data-toggle]');

    // Если таковой элемент был найден и
    // он имеет не пустой необходимый атрибут.
    if (element && element.dataset.toggle) {
        // Обработчик текущего щелчка.
        const handler = TOGGLE_MAP[element.dataset.toggle];

        if (handler) {
            // Отменяем стандартное поведение для клика по ссылке.
            event.preventDefault();

            // Отменяем всплытие клика.
            event.stopPropagation();

            // Передаем данные обработчику.
            handler.apply(element, event);
        }
    }

    // while (target !== this) {
    //     if (target.matches('a[data-toggle="dropdown"]')) {
    //         console.log(target);
    //         break;
    //     }
    //
    //     target = target.parentNode;
    // }
}

function tabHandler(event) {
    const selector = this.getAttribute('href') || this.dataset.target;
    const parent = this.closest('.nav-tabs').parentNode;

    const [...tabs] = parent.querySelectorAll('.nav-tabs a');
    const [...panes] = parent.querySelectorAll('.tab-content .tab-pane');

    tabs.forEach(function(item) {
        item.classList.toggle('active', item.matches(`[href="${selector}"]`));
    });

    panes.forEach(function(item) {
        item.classList.toggle('active', item.matches(selector));
    });
}

function dropdownHandler(event) {
    const dropdown = this.nextElementSibling;

    if (dropdown) {
        dropdown.classList.toggle('show');

        this.addEventListener('blur', function(event) {
            dropdown.classList.toggle('show', false);
            event.relatedTarget && event.relatedTarget.click();
        }, {
            once: true,
        });
    }
}

function collapseHandler(event) {
    const selector = this.getAttribute('href') || this.dataset.target;
    const collapseble = document.body.querySelector(selector);

    collapseble && collapseble.classList.toggle('collapse');
}

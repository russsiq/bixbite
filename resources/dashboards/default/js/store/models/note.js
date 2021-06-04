import Model from '@/store/model';

class Note extends Model {
    static entity = 'notes';

    /**
     * Получить CSS класс для кнопки,
     * переключающей атрибут `is_completed`.
     * @NB: в данный момент неизвестно насколько данный подход оправдан.
     * @return {Object}
     */
    get toggleButtonClass() {
        return {
            'fa fa-check text-success': this.is_completed,
            'fa fa-line-chart text-warning': !this.is_completed,
        }
    }
}

export default Note;

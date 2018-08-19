/**
 * Created by PhpStorm.
 * User: Vadim Epifanov
 * Date: 19.08.2018
 * Time: 13:25
 */

const CITY_NO_SELECT = 0;

/**
 * Скрипт выполняется после загрузки DOM
 */
$(function () {

    if ($('#city').prop('value') > CITY_NO_SELECT) { // Если город выбран

        // Убирает класс делающий цвет надписи серым
        $('#city').removeClass('select-no');
    }

    /**
     * Скрипт отслеживает изменение списка выбора города
     */
    $('#city').on('change', function (event) {

        if (event.target.value > CITY_NO_SELECT) { // Если город выбран

            // Убирает класс делающий цвет надписи серым
            $('#city').removeClass('select-no');

        } else { // Если город не выбран

            // Добавляет класс делающий цвет надписи серым
            $('#city').addClass('select-no');
        }
    });
});

<?php

declare(strict_types = 1);

return [
    'errors'    => [
        'get_settings_error'    => 'Ошибка при получении настроек: :message',
        'column_slug_exist'     => 'Свойство slug уже существует :slug',
        'product_not_found'     => 'Товар c id=:id не найден',
        'get_untappd_error'     => 'Ошибка при запросе к Untappd. Ошибка[:code]: :message.',
        'not_valid_url'         => 'Не корректный URL',
        'image_download_failed' => 'Ошибка загрузки изображения',
        'content_not_image'     => 'Содержимое не является изображением',
    ],
    'moonshine' => [
        'ui' => [
            'user_resource'             => 'Пользователи',
            'category_resource'         => 'Категории',
            'manufacturer_resource'     => 'Производители',
            'product_resource'          => 'Товары',
            'product_property_resource' => 'Параметры товара',
            'product_props'             => 'Свойства товара',
            'status_resource'           => 'Статус товара',
            'option_resource'           => 'Опции товара',
            'option_value_resource'     => 'Значения опций товара',
            'catalog'                   => 'Каталог',
            'product'                   => 'Товар',
            'content'                   => 'Контент',
            'content_block_resource'    => 'Страницы',
            'site_menu_resource'        => 'Меню',
            'project_settings'          => 'Настройки проекта',
            'project_group_settings'    => 'Группы настроек',
            'beer_style_resource'       => 'Стили',
            'labels'                    => [
                'price'     => 'Цена, оригинальная цена (без наценки).',
                'beerStyle' => 'Стиль',
                'level'     => 'Уровень',
                'message'   => 'Сообщение',
                'context'   => 'Контекст',
                'date'      => 'Дата',
            ],
            'log_resource'              => 'Журнал',
        ],
    ],
];

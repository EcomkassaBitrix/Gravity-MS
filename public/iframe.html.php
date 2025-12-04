 <!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">

    <title>Екомкасса</title>
    <meta name="description" content="Екомкасса">

    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

    <link rel="stylesheet" href="css/selectivity-full.min.css">
    <link rel="stylesheet" href="css/uikit.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php
if ($isAdmin) {
?>
    
    <?php
        if (is_array($document)) {
            $a = [];

            foreach ($document as $index => $item) {
                $a[$index]['document'] = $document[$index] ?? null;
                $a[$index]['action'] = $action[$index] ?? null;
                $a[$index]['type'] = $type[$index] ?? null;
                $a[$index]['method'] = $method[$index] ?? null;
                $a[$index]['new_status'] = $newStatus[$index] ?? null;
                $a[$index]['obj'] = $obj[$index] ?? null;
            }

            print '<script>';
            print 'window.DOCUMENTS = ' . json_encode($a) . ';';
            print '</script>';
        }
    ?>
    
    <form method="post" action="update-settings.php">  
        <div class="main-block">
            <div class="">
                <div class="tabs-border js-tabs">
                    <ul class="tabs-border__buttons">
                        <li class="tabs-border__button js-tabs-button b-active">Подключение к Екомкасса</li>
<!--
                        <li class="tabs-border__button js-tabs-button">Фискальные операции</li>
-->
                        <li class="tabs-border__button js-tabs-button">Автоматизация</li>
                    </ul>
                    <div class="tabs-border__items">
                        <div class="tabs-border__item js-tabs-item  b-active">


    <div class="buttons">
        <input type="button" class="button js-popup-open" data-role="open-window" data-name="cool-window" value="Загрузить демонстрационную конфигурацию..." />
        <div class="popup b-hide js-popup-window" data-name="cool-window">
            <div class="popup__overlay js-popup-close"></div>
            <dialog open="" class="popup__body">
                <div class="popup__title">Демонстрационная конфигурация</div>
                <div class="popup__content">
                    <p>Демонстрационная конфигурация предназначена для тестирования решения во время
                        интеграции и для демонстрации возможностей решения.</p>
                    <p>При загрузке демонстрационной конфигурации текущая конфигурация будет заменена на демонстрационную.
                        Рабочую конфигурацию потребуется указать вручную повторно.
                        После загрузки демонстрационной конфигурации нажмите кнопку "Сохранить".</p>
                    <p>Загрузить демонстрационную конфигурацию?</p>
                </div>
                <div class="buttons">
                    <input type="button" class="button button--success js-popup-close" onclick="loadDemonstationConfig();" data-role="popup" value="Загрузить конфигурацию" />
                    <input type="button" class="button js-popup-close" value="Закрыть" />
                </div>
            </dialog>
        </div>
    </div>


    <?php
    if (!empty($message)) {
        ?>
    <p class="message message-success"><?= $message; ?></p>
        <?php
    }
    ?>

        <div class="mb-3">
            <label class="form-label">Логин</label>
            <div class="input-group">
                <input type="text" class="ui-input" id="login" name="login" value="<?= htmlspecialchars($login); ?>" />
            </div>
            <div class="form-text">Логин от личного кабинета Екомкасса.</div>
        </div>

        <div class="mb-3">
            <label class="form-label" for="password">Пароль</label>
            <div class="input-group">
                <input type="password" class="ui-input" id="password" name="password" value="<?= htmlspecialchars($password); ?>" />
            </div>
            <div class="form-text">Пароль от личного кабинета Екомкасса.</div>
        </div>

        <div class="mb-3">
            <label class="form-label" for="shop_id">ID магазина</label>
            <div class="input-group">
                <input type="text" class="ui-input" id="shop_id" name="shop_id" placeholder="123..." value="<?= htmlspecialchars($shopId); ?>" />
            </div>
            <div class="form-text">Идентификатор магазина (см. в личном кабинете Екомкасса).</div>
        </div>

        <div class="mb-3">
            <label class="form-label" for="inn">ИНН</label>
            <div class="input-group">
                <input type="text" class="ui-input" id="inn" name="inn" placeholder="123..." value="<?= htmlspecialchars($inn); ?>" />
            </div>
            <div class="form-text">ИНН организации.</div>
        </div>

        <div class="mb-3">
            <label class="form-label" for="inn">Система налогообложения</label>
            <div class="input-group">
                <select type="text" class="ui-select-custom selectivity-input" id="sno" name="sno" style="width: 250px">
                    <?php
                    $a = [
                        'osn' => 'общая СН',
                        'usn_income' => 'упрощенная СН (доходы)',
                        'usn_income_outcome' => 'упрощенная СН (доходы минус расходы)',
                        'envd' => 'единый налог на вмененный доход',
                        'esn' => 'единый сельскохозяйственный налог',
                        'patent' => 'патентная СН',
                    ];

                    foreach ($a as $index => $item) {
                        print '<option ' . ($sno == $index ? 'selected' : '') . ' value="' . $index . '">' . $item  . '</option>';
                    }

                    ?>
                </select>
            </div>
            <div class="form-text">Система налогобложения организации.</div>
        </div>

        <div class="mb-3">
            <label class="form-label" for="email">E-mail</label>
            <div class="input-group">
                <input type="text" class="ui-input" id="email" name="email" placeholder="example@example.com..." value="<?= htmlspecialchars($email); ?>" />
            </div>
            <div class="form-text">E-mail организации.</div>
        </div>

        <div class="mb-3">
            <label class="form-label" for="address">Адрес получателя</label>
            <div class="input-group">
                <input type="text" class="ui-input" id="address" name="address" placeholder="https://www..." value="<?= htmlspecialchars($address); ?>" />
            </div>
            <div class="form-text">Адрес точки продаж. Для сайтов - адрес сайта.</div>
        </div>

                            
                            
                            
                        </div>
<!--
                        <div class="tabs-border__item js-tabs-item">
                            <p>
                                Если вы используете стандартный способ ведения продаж (пользуетесь кассой, оформляете продажи, отгрузки, входящие платежи), то МойСклад может создавать фискальные чеки через Екомкассу при появлении следующих событий:
                            </p>

                            <label class="check option">
                                <input class="check__input" type="checkbox" name="fiscal[]" <?= in_array('retaildemand', $fiscal) ? 'checked' : '' ?> value="retaildemand">
                                <span class="check__box"></span>
                                <span class="check__text">Создание продажи</span>
                            </label>
                            <label class="check option">
                                <input class="check__input" type="checkbox" name="fiscal[]" <?= in_array('retaisalesreturn', $fiscal) ? 'checked' : '' ?> value="retaisalesreturn">
                                <span class="check__box"></span>
                                <span class="check__text">Возврат продажи</span>
                            </label>

                        </div>
-->
                        <div class="tabs-border__item js-tabs-item">
                            <p>
                                Автоматизация предназначена для определения условий автоматического создания фискальных чеков
                                при изменении состояний некоторых документов в системе.
                            </p>
                            <p>
                                Автоматизация работает в обход работы стандартных фискальных операций и позволяет быстро
                                создать чек при изменении статусов документов.
                            </p>
                            <p>
                                <b>Если список статусов пустой, то вам потребуется
                                добавить нужные статусы вручную для каждого документа, обработка которого может быть автоматизирована</b>.
                            </p>

                            
<?php

    print '<script> if (window.STATES == undefined) { window.STATES = {}; } </script> ';
$jsonApi = JsonApi();

if ($jsonApi) {
    $meta = $jsonApi->getMetadata('customerorder');
    if ($meta) {
        $states = $meta->states;
        $a = [];
        if ($states) {
            foreach ($states as $state) {
                $a[$state->id] = $state->name;
            }
        }

        if ($a) {
            print '<script>';
            print 'window.STATES.customerorder = ' . json_encode($a) . ';';
            print '</script>';
        }
    }

    $meta = $jsonApi->getMetadata('salesreturn');

    if ($meta) {
        $states = $meta->states ?? null;
        $a = [];
        if ($states) {
            foreach ($states as $state) {
                $a[$state->id] = $state->name;
            }
        }

        if ($a) {
            print '<script>';
            print 'window.STATES.salesreturn = ' . json_encode($a) . ';';
            print '</script>';
        }
    }

    $meta = $jsonApi->getMetadata('demand');

    if ($meta) {
        $states = $meta->states ?? null;
        $a = [];
        if ($states) {
            foreach ($states as $state) {
                $a[$state->id] = $state->name;
            }
        }

        if ($a) {
            print '<script>';
            print 'window.STATES.demand = ' . json_encode($a) . ';';
            print '</script>';
        }
    }
}
?>
                            
                            <div id="ecom-documents">
                            </div>
                             
                            <div class="buttons" style="margin-top: 10px;">
                                <input type="button" class="button button--success" data-role="add-document-button" value="Добавить документ">
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
   
  
        <input type="hidden" name="contextKey" value="<?= $contextKey; ?>"/>
        <input type="hidden" name="appUid" value="<?= $appUid; ?>"/>
        <input type="hidden" name="appId" value="<?= $appId; ?>"/>
        <input type="hidden" name="accountId" value="<?=$accountId?>"/>
        
        <div class="buttons">
            <input type="submit" class="button button--success" value="Сохранить">
        </div>
    </form>

<?php
} else {
?>
Настройки доступны только администратору аккаунта
<?php
}
?>
    <script src="js/jquery.min.js"></script>
    <script src="js/selectivity-jquery.js"></script>
    <script src="js/uikit.min.js"></script>
    <script src="js/script.js?<?= filemtime('js/script.js'); ?>"></script>
</body>
</html>

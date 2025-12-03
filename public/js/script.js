function loadDemonstationConfig() {
    let config = {
        login: 'sales@ecomkassa.ru',
        password: 'ecomkassa1',
        shop_id: '990',
        email: 'sales@ecomkassa.ru',
        address: 'https://moysklad.ru',
        inn: '7724923302',
    };

    for (let key in config) {
        var o = document.getElementById(key);

        if (o) {
            o.value = config[key];
        }
    };
}

// 
;(function() {
    
    function init() {
        document.addEventListener('click', function(event) {

            var n = 'add-document-button';

            if (event.target.dataset.role === n) {
                add();
            }
        });

        if (window.DOCUMENTS) {
            for (var item in window.DOCUMENTS) {
                var doc = window.DOCUMENTS[item];

                add(doc);
            }
        } else {
            add();
        }

        var sno = document.getElementById('sno');

        if (sno) {
            $(sno).selectivity({showSearchInputInDropdown:!1}) 
        }
    }

    function param(d) {
        var o = document.createElement('div');

        o.append(d);

        return o;
    }

    function select(data, options) {
        var s = document.createElement('select');

        var defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = '(не выбрано)';
        s.append(defaultOption);
        
        if (options.class) {
            options.class.split(' ').forEach(function(className) {
                s.classList.add(className);
            });
        }

        if (options.name) {
            s.name = options.name;
        }

        if (data) {
            for (var i in data) {
                var o = document.createElement('option');

                o.value = data[i].value;
                o.text = data[i].text;
                s.append(o);
            }
        }

        return s;
    }

    function documentParam(o, doc) {
        var data = [
            {value: 'customerorder', text: 'Продажи. Заказы покупателей'},
            {value: 'demand', text: 'Продажи. Отгрузка (доступна маркировка товаров)'},
            {value: 'salesreturn', text: 'Продажи. Возвраты покупателей'},
        ];

        var s = select(data, {
            class: 'ui-select-custom selectivity-input',
            name: 'document[]',
        });

        var d = document.createElement('div');
        d.classList.add('ecom-document-column');
        d.append(label('Документ'));

        d.append(s);
        
        $(s).selectivity({
            showSearchInputInDropdown:!1,  
        });

        $(s).on('change', function(e) {
            var value = e.value;
            var states = window.STATES[value];

            if (states) {
                updateStateList(s, states);
            } else {
                updateStateList(s);
            }
        });

        if (doc) {
            var emptyValue = '-';
            var v = doc.document;

            if (v == null) {
                v = emptyValue;
            }
            $(s).selectivity('setValue', v);
        }
 
        o.append(d);

        return s;
    }

    function updateStateList(s, states)
    {
        var emptyValue = '-';
        //var oldStatuses = jQuery(s).closest('.ecom-document-row').find('[name="old_status[]"]');
        //oldStatuses.empty();
        var newStatuses = jQuery(s).closest('.ecom-document-row').find('[name="new_status[]"]');
        newStatuses.empty();
        var items = [
            {
                id: emptyValue,
                text: '(не выбрано)',
            }
        ];
        
        if (states) {
            for (var i in states) {
                items.push({
                    id: i,
                    text: states[i],
                });
            }
        }
        
        if (items) {
            for (var i in items) {
                var option = document.createElement('option');
                option.value = items[i].id;
                option.text = items[i].text;

                // oldStatuses.append(option);
                newStatuses.append(option);
            }
        }
        
        if (states) {
            //oldStatuses.selectivity('clear');
            //oldStatuses.selectivity('setOptions', {
            //    showSearchInputInDropdown:!1,
            //    items: items,
            //});

            newStatuses.selectivity('clear');
            newStatuses.selectivity('setOptions', {
                showSearchInputInDropdown:!1,
                items: items,
            });

        } else {

            //oldStatuses.selectivity('clear');
            //oldStatuses.selectivity('setOptions', {
            //    showSearchInputInDropdown:!1,
             //   items: [],
            //});

            newStatuses.selectivity('clear');
            newStatuses.selectivity('setOptions', {
                showSearchInputInDropdown:!1,
                items: [],
            });
        }

       // oldStatuses.selectivity('setValue', emptyValue);
        newStatuses.selectivity('setValue', emptyValue);
    }

    function documentStatusFrom(o, doc) {
        var data = [];

        var s = select(data, {
            'class': 'ui-select-custom selectivity-input',
            name: 'old_status[]',
        });

        var d = document.createElement('div');
        d.classList.add('ecom-document-column');
        d.append(label('В статусе'));

        d.append(s);
        $(s).selectivity({showSearchInputInDropdown:!1})

        o.append(d);

        return s;
    }

    function documentStatusTo(o, doc) {
        var data = [];

        var s = select(data, {
            'class': 'ui-select-custom selectivity-input',
            name: 'new_status[]',
        });

        var d = document.createElement('div');
        d.classList.add('ecom-document-column');
        d.append(label('Статус изменился на'));

        d.append(s);
        $(s).selectivity({showSearchInputInDropdown:!1})

        if (doc) {
            var emptyValue = '-';
            var v = doc.new_status;

            if (v == null) {
                v = emptyValue;
            }
            $(s).selectivity('setValue', v);
        }

        o.append(d);

        return s;
    }

    function documentAction(o, doc)
    {
        var data = [
            {value: 'print', text: 'Печать чека'},
        ];

        var s = select(data, {
            'class': 'ui-select-custom selectivity-input',
            name: 'action[]',
        });

        var d = document.createElement('div');
        d.classList.add('ecom-document-column');
        d.append(label('Выполняем'));

        d.append(s);
        $(s).selectivity({showSearchInputInDropdown:!1})

        if (doc) {
            var emptyValue = '-';
            var v = doc.action;

            if (v == null) {
                v = emptyValue;
            }
            $(s).selectivity('setValue', v);
        }

        o.append(d);

        return s;
    }

    function documentCheckType(o, doc) {
        var data = [
            {value: 'type_cash', text: 'Наличные'},
            {value: 'type_card', text: 'Безналичные'},
            {value: 'type_cashcard', text: 'Предварительная оплата (зачет аванса и (или) предыдущих платежей'},
            {value: 'type_credit', text: 'Постоплата (кредит)'},
            {value: 'type_another', text: 'Иная форма оплаты (встречное представление)'},
        ];

        var s = select(data, {
            'class': 'ui-select-custom selectivity-input',
            name: 'type[]',
        });

        var d = document.createElement('div');
        d.classList.add('ecom-document-column');
        d.append(label('С типом'));

        d.append(s);
        $(s).selectivity({showSearchInputInDropdown:!1})

        if (doc) {
            var emptyValue = '-';
            var v = doc.type;

            if (v == null) {
                v = emptyValue;
            }
            $(s).selectivity('setValue', v);
        }

        o.append(d);

        return s;
    }

    function documentCheckMethod(o, doc) {
        var data = [
            {value: 'full_prepayment', text: 'Предоплата 100%'},
            {value: 'prepayment', text: 'Предоплата (частичная)'},
            {value: 'advance', text: 'Аванс'},
            {value: 'full_payment', text: 'Полный расчет'},
            {value: 'partial_payment', text: 'Частичный расчет и кредит'},
            {value: 'credit', text: 'Кредит (передача предмета расчета без оплаты в момент передачи)'},
            {value: 'credit_payment', text: 'Оплата кредита (передача предмета расчета с оплатой в момент передачи)'},
        ];

        var s = select(data, {
            'class': 'ui-select-custom selectivity-input',
            name: 'method[]',
        });

        var d = document.createElement('div');
        d.classList.add('ecom-document-column');
        d.append(label('С методом'));

        d.append(s);
        $(s).selectivity({showSearchInputInDropdown:!1})

        if (doc) {
            var emptyValue = '-';
            var v = doc.method;

            if (v == null) {
                v = emptyValue;
            }
            $(s).selectivity('setValue', v);
        }

        o.append(d);

        return s;
    }


    function documentCheckObject(o, doc) {

        var data = [
            {"value": "1", "text": "Реализуемый товар, за исключением подакцизного товара и товара, подлежащего маркировке средствами идентификации (наименование и иные сведения, описывающие товар)"},
            {"value": "2", "text": "Реализуемый подакцизный товар, за исключением товара, подлежащего маркировке средствами идентификации (наименование и иные сведения, описывающие товар)"},
            {"value": "3", "text": "Выполняемая работа (наименование и иные сведения, описывающие работу)"},
            {"value": "4", "text": "Оказываемая услуга (наименование и иные сведения, описывающие услугу)"},
            {"value": "5", "text": "Прием ставок при осуществлении деятельности по проведению азартных игр"},
            {"value": "6", "text": "Выплата денежных средств в виде выигрыша при осуществлении деятельности по проведению азартных игр"},
            {"value": "7", "text": "Прием денежных средств при реализации лотерейных билетов, электронных лотерейных билетов, приеме лотерейных ставок при осуществлении деятельности по проведению лотереи"},
            {"value": "8", "text": "Выплата денежных средств в виде выигрыша при осуществлении деятельности по проведению лотереи"},
            {"value": "9", "text": "Предоставление прав на использование результатов интеллектуальной деятельности или средств индивидуализации"},
            {"value": "10", "text": "Авансы, задатки, предоплаты, кредиты"},
            {"value": "11", "text": "Вознаграждение пользователя, являющегося платежным агентом (субагентом), банковским платежным агентом (субагентом), комиссионером, поверенным или иным агентом"},
            {"value": "12", "text": "Взнос в счет оплаты, пени, штрафы, вознаграждения, бонусы и иные аналогичные предметы расчета"},
            {"value": "13", "text": "Предмет расчета, не относящийся к предметам расчета, которым может быть присвоено значение от «1» до «11» и от «14» до «26»"},
            {"value": "14", "text": "Передача имущественных прав"},
            {"value": "15", "text": "Внереализационный доход"},
            {"value": "16", "text": "Суммы расходов, платежей и взносов, указанных в подпунктах 2 и 3 пункта Налогового кодекса Российской Федерации, уменьшающих сумму налога"},
            {"value": "17", "text": "Суммы уплаченного торгового сбора"},
            {"value": "18", "text": "Туристический налог"},
            {"value": "19", "text": "Залог"},
            {"value": "20", "text": "Суммы произведенных расходов в соответствии со статьей 346.16 Налогового кодекса Российской Федерации, уменьшающих доход"},
            {"value": "21", "text": "Страховые взносы на обязательное пенсионное страхование, уплачиваемые ИП, не производящими выплаты и иные вознаграждения физическим лицам"},
            {"value": "22", "text": "Страховые взносы на обязательное пенсионное страхование, уплачиваемые организациями и ИП, производящими выплаты и иные вознаграждения физическим лицам"},
            {"value": "23", "text": "Страховые взносы на обязательное медицинское страхование, уплачиваемые ИП, не производящими выплаты и иные вознаграждения физическим лицам"},
            {"value": "24", "text": "Страховые взносы на обязательное медицинское страхование, уплачиваемые организациями и ИП, производящими выплаты и иные вознаграждения физическим лицам"},
            {"value": "25", "text": "Страховые взносы на обязательное социальное страхование на случаи временной нетрудоспособности и в связи с материнством, на обязательное социальное страхование от несчастных случаев на производстве и профессиональных заболеваний"},
            {"value": "26", "text": "Прием и выплата денежных средств при осуществлении казино и залами игровых автоматов расчетов с использованием обменных знаков игорного заведения"},
            {"value": "27", "text": "Выдача денежных средств банковским платежным агентом"},
            {"value": "30", "text": "Реализуемый подакцизный товар, подлежащий маркировке средством идентификации, не имеющий кода маркировки"},
            {"value": "31", "text": "Реализуемый подакцизный товар, подлежащий маркировке средством идентификации, имеющий код маркировки"},
            {"value": "32", "text": "Реализуемый товар, подлежащий маркировке средством идентификации, не имеющий кода маркировки, за исключением подакцизного товара"},
            {"value": "33", "text": "Реализуемый товар, подлежащий маркировке средством идентификации, имеющий код маркировки, за исключением подакцизного товара"}
        ];

        var s = select(data, {
            'class': 'ui-select-custom selectivity-input wide-select',
            name: 'obj[]',
        });

        var d = document.createElement('div');
        d.classList.add('ecom-document-column');
        d.append(label('Предмет расчета'));

        d.append(s);
        $(s).selectivity({showSearchInputInDropdown:!1});

        if (doc) {
            var emptyValue = '-';
            var v = doc.obj;

            if (v == null) {
                v = emptyValue;
            }
            $(s).selectivity('setValue', v);
        }

        o.append(d);

        return s;
    }

    function documentRemove(o) {
        var d = document.createElement('div');

        d.classList.add('buttons');
        d.classList.add('ecom-document-column');

        var b = document.createElement('input');

        b.type = 'button';
        b.classList.add('button');
        b.classList.add('ecom-document-delete-button')
        b.value = 'Удалить';

        b.addEventListener('click', function(event) {
            var t = event.target.closest('.ecom-document-row');

            if (t) {
                t.remove();
            }
        });

        d.append(b);

        o.append(d);
    }

    function add(doc) {
        var n = 'ecom-documents';
        var c = document.getElementById(n);

        if (c) {
            var d = document.createElement('div');
            d.classList.add('ecom-document-row');
            c.append(d);

            var sDocument = documentParam(d, doc);
            //documentStatusFrom(d, doc);
            var sStatusTo = documentStatusTo(d, doc);
            documentAction(d, doc);
            documentCheckType(d, doc);
            documentCheckMethod(d, doc);
            documentCheckObject(d, doc);

            documentRemove(d);

            var value = $(sDocument).val();
            var states = window.STATES[value];

            if (states) {
                updateStateList(sDocument, states);
            } else {
                updateStateList(sDocument);
            }

            if (doc) {
                $(sStatusTo).selectivity('setValue', doc.new_status);
            }
        }
    }

    function label(s) {
        var o = document.createElement('span');

        o.classList.add('ecom-document-param-label');
        o.textContent = s;

        return o;
    }

    window[addEventListener ? 'addEventListener' : 'attachEvent'](addEventListener ? 'load' : 'onload', init);
})();

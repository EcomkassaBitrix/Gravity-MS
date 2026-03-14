<?php

namespace Ecomkassa\Moysklad;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use PhpDevCommunity\DotEnv;
use Ecomkassa\Moysklad\Service\PopupService;

require_once __DIR__ . '/../vendor/autoload.php';

$absolutePathToEnvFile = __DIR__ . '/../.env';
(new DotEnv($absolutePathToEnvFile))->load();

$logger = new Logger('popup');

$logFilename = getenv('WEBHOOK_LOG');
$streamHandler = new StreamHandler($logFilename, Logger::INFO);
$logger->pushHandler($streamHandler);

$contextKey = $_REQUEST['contextKey'] ?? null;
$appUid = $_REQUEST['appUid'] ?? null;
$appId = $_REQUEST['appId'] ?? null;

$popupService = new PopupService($logger);
$popupService->setContextKey($contextKey)
    ->setAppUid($appUid)
    ->setAppId($appId);

//$entityId = null;
//$products = $popupService->getProductsByEntityId($entityId);

header('Cache-Control: no-cache');

?><!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/selectivity-full.min.css">
    <link rel="stylesheet" href="css/uikit.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/popup.css">

    <script src="https://cdn.jsdelivr.net/npm/@moysklad-official/js-widget-sdk/dist/widget.min.js"></script>

<script>

function messageId() {
    return Math.round(new Date().getTime() / 1000);
}

function closePopup() {
    window.parent.postMessage({
        "name":"ClosePopup",
        "messageId": messageId(),
    }, '*');
}

function searchProduct(term) {
    fetch('<?= $_ENV['APP_BASE_URL']; ?>/search_products.php?contextKey=<?= urlencode($contextKey); ?>&appUid=<?= urlencode($appUid); ?>&appId=<?= urlencode($appId); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            term: term,
        })
    })
    .then(response => response.json())
    .then(products => {
        showResult(products);
    })
    .catch(error => {
        console.error('Ошибка:', error);
    });
}

function showResult(products) {
    if (products.items) {
        const result = document.getElementById('result');

        if (result) {
            result.innerHTML = '';
            if (products.items.length > 0) {
                result.classList.add('show');
                result.classList.remove('empty');
                result.classList.remove('notfound');
            } else {
                result.classList.remove('show');
                result.classList.add('notfound');
            }

            for (let i in products.items) {
                const item = products.items[i];
                const r = document.createElement('div');

                r.setAttribute('data-id', item.id ?? '');
                r.setAttribute('data-name', item.name ?? '');
                r.setAttribute('data-mark', item.mark ?? '');
                r.setAttribute('data-mark-id', item.mark?.id ?? '');
                r.setAttribute('data-price', item.price ?? '');
                r.innerHTML = item.name;
                r.classList.add('product');
                r.onclick = function() {

                    addProduct({
                        id: this.getAttribute('data-id'),
                        product_id: this.getAttribute('data-id'),
                        name: this.getAttribute('data-name'),
//                        quantity: 1,
                        mark: this.getAttribute('data-mark'),
                        mark_id: this.getAttribute('data-mark-id'),
                        price: this.getAttribute('data-price'),
                    }, true);

                    hideResults();

                };

                result.append(r);
            }
        } else {
            console.error('Не найден контейнер result');
        }
    }
}

function addInputProduct(input) {
    if (input) {
        const product = {
            id: input.getAttribute('data-id'),
            name: input.getAttribute('data-name'),
//            quantity: 1,
            mark: input.getAttribute('data-mark'),
            mark_id: input.getAttribute('data-mark-id'),
            price: input.getAttribute('data-price'),
        };

        addProduct(product);
    }
}

function searchAndAdd(o) {
    fetch('<?= $_ENV['APP_BASE_URL']; ?>/search_products.php?contextKey=<?= urlencode($contextKey); ?>&appUid=<?= urlencode($appUid); ?>&appId=<?= urlencode($appId); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
        })
    })
    .then(response => response.json())
    .then(products => {
        showResult(products);
    })
    .catch(error => {
        console.error('Ошибка:', error);
    });
}

function getProductsId() {
    return '#products';
}

function getRowCount(products) {
    return products.querySelectorAll('tr').length;
}

function addBlur(event) {
    window.result_flag = false;
    setTimeout(function() {
        if (window.result_flag == false) {
            const result = document.getElementById('result');

            if (result) {
                 result.classList.remove('show');
            }
        }
    }, 250);
}

function clickResult(event) {
    window.result_flag = true;
}

function refreshProducts() {
    const productsId = getProductsId();
    const products = document.querySelector(productsId);
    const a = products.querySelectorAll('tr');

    if (products) {
        const rowCount = getRowCount(products);

        a.forEach(function(product, i) {
            const td = product.querySelector('td:first-child');
            td.innerHTML = (i + 1) + '.';
        });
    }
}

function removeProduct(id) {
    const productId = 'tr[data-id="' + id + '"]';
    const product = document.querySelector(productId);
    if (product) {
        product.remove();
        refreshProducts();
    }
}

function clearProduct(id) {
    const productId = 'tr[data-id="' + id + '"] TEXTAREA[name="mark"]';
    const textarea = document.querySelector(productId);

    if (textarea) {
        textarea.value = '';
    }
}

function clearProducts() {
    const products = document.getElementById('products');

    if (products) {
        products.innerHTML = '';
    }
}

function toHex(str) {
  const codes = [];
  for (let i = 0; i < str.length; ) {
    const codePoint = str.codePointAt(i);
    if (codePoint === undefined) break;
    
    // Преобразуем в hex и дополняем до 4 или 8 цифр
    const hex = codePoint.toString(16).toUpperCase().padStart(
      codePoint <= 0xFFFF ? 4 : 8, '0'
    );
    codes.push(hex);
    
    // Переходим к следующему code point (учитываем суррогатные пары)
    i += codePoint > 0xFFFF ? 2 : 1;
  }
  return codes.join(' ');
}

function addProduct(product, isNew) {
    const productsId = getProductsId();
    const products = document.querySelector(productsId);

    if (products) {
        const tr = document.createElement('tr');
        tr.setAttribute('data-id', product.id);
        tr.setAttribute('data-price', product.price);
        tr.setAttribute('data-product-id', product.product_id);

        if (isNew === true) {
            tr.setAttribute('data-new', 'true');
        } else {
            tr.setAttribute('data-new', 'false');
        }

        const rowCount = getRowCount(products);

        const td1 = document.createElement('td');
        td1.innerHTML = (rowCount + 1) + '.';
        td1.width = '10';
        td1.align = 'right';

        const td2 = document.createElement('td');
        td2.innerHTML = product.name;
        td2.width = '50%';
        td2.align = 'left';
        td2.classList.add('product-name');

        const tds = document.createElement('td');
        tds.classList.add('markcode-status');

        if (product.mark_status !== null) {
            if (product.mark_status === true) {
                tds.innerHTML = 'Проверен';
                tds.classList.add('markcode-status-success');
            } else {
                tds.innerHTML = 'Ошибочный';
                tds.classList.add('markcode-status-failure');
            }
        } else {
            tds.innerHTML = 'Не проверялся';
            tds.classList.add('markcode-status-unknown');
        }


        const td3 = document.createElement('td');
//        const quantityInput = document.createElement('input');
//        quantityInput.name = 'quantity';
//        quantityInput.type = 'number';
//        quantityInput.value = product.quantity;
        const td3s = document.createElement('div');
        td3s.innerHTML = product.quantity;

        const td3w = document.createElement('div');
        if (parseInt(product.quantity) > 1) {
            td3w.innerHTML = '(должен быть 1)';
            td3s.style.color = '#cf0000';
            td3w.style.color = '#cf0000';

            const errs = document.getElementById('errors');
            errs.innerHTML = 'У вас есть ошибочное количество в товарах. На каждую единицы товара должен быть только один код маркировки. Если нужно несколько единиц товара, то необходимо добавить каждую единицу товара отдельно и каждой единице товара добавить код маркировки.';
        }

        td3.append(td3s);
        td3.append(td3w);

        td3.width = '10%';
        td3.align = 'left';
        td3.classList.add('product-quantity');

        const td4 = document.createElement('td');

        const input = document.createElement('textarea');
        input.type = 'text';
        input.name = 'mark';
        input.style.width = '100%';
        input.style.height = '34px';
        input.style.marginTop = '5px';
        input.style.resize = 'none';
        input.lineHeight = '22px';
        input.value = product.mark ?? '';
        input.overflow = 'hidden';

        input.addEventListener('keypress', function(e) {
            if (e.keyCode == 13) {
                return false;
            }
        });

        input.addEventListener('keydown', function(e) {
           console.log('Ввод символа', e.keyCode, e.code);

            const gsReplace = '\\u001D';
            if (e.code == 'F8') {
                e.target.value += gsReplace;
            }

            if (e.code == 'F12') {
                e.target.value += gsReplace;
            }

            if (e.keyCode == 13) {
                const tr = e.target.closest('tr').nextElementSibling;

                if (tr) {
                    const input = tr.querySelector('textarea[name="mark"]');

                    if (input) {
                        input.focus();

                    }
                }

                return false;
            }
        });
//            value = value.replace(/\x1C/g, ':');
//            value = value.replace(/\xE8/g, ';');

        input.addEventListener('input', function() {

            let value = this.value;

//            value = value.replace(/\x1C/g, '^');
//            value = value.replace(/\xE8/g, '_');

            console.log('Код маркировки ', value, toHex(value));

            if (this.value !== value) {
                const cursorPosition = this.selectionStart;
                this.value = value;

                this.setSelectionRange(cursorPosition, cursorPosition);
            }

        });
        input.setAttribute('data-mark-id', product.mark?.id ?? '');

        td4.appendChild(input);

        const td5 = document.createElement('td');
        const a = document.createElement('a');
        a.href = '#';
        a.setAttribute('data-id', product.id);
        a.classList.add('delete');
        a.innerHTML = '&times;';
        a.onclick = function(e) {
//            return removeProduct(product.id);
            return clearProduct(product.id);
        };
        td5.appendChild(a);
        td5.width = '10';

        tr.append(td1);
        tr.append(td2);
        tr.append(td3);
        tr.append(tds);
        tr.append(td4);
        tr.append(td5);

        let exists = false;
        let b = products.querySelectorAll('tr');

        if (exists == false) {
            products.appendChild(tr);
        }
    }

}

function loadProductsFromEntity(parameters, callback) {
    fetch('<?= $_ENV['APP_BASE_URL']; ?>/products.php?contextKey=<?= urlencode($contextKey); ?>&appUid=<?= urlencode($appUid); ?>&appId=<?= urlencode($appId); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(parameters)
    })
    .then(response => response.json())
    .then(products => {
        callback(products);
    })
    .catch(error => {
        console.error('Ошибка:', error);
    });
}

/**
 * Получает сообщения от родительского окна "Мой Склад"
 * и сохраняет в глобальную переменную window.popupParameters
 */
window.addEventListener('message', function(event) {
    const data = event.data ?? null;

    console.log('Входящее сообщение для popup-окна', data);

    clearProducts();

    if ((data.name ?? null) == 'OpenPopup') {
        const popupParameters = data.popupParameters ?? null;
        if (popupParameters) {
            const objectId = popupParameters.objectId;
            const extensionPoint = popupParameters.extensionPoint;

            window.objectId = objectId;
            window.extensionPoint = extensionPoint;

            loadProductsFromEntity({
                objectId: objectId,
                extensionPoint: extensionPoint,
            }, function(products) {
                const items = products.items ?? null;

                const errs = document.getElementById('errors');
                errs.innerHTML = '';

                if (items !== null) {
                    items.forEach(function(item) {
                        addProduct(item, false);
                    });
                }
            });
        }
    }
});

function onSubmit() {
    let popupResponse = new Array();

    const products = document.querySelectorAll('#products tr');

    if (products) {
        products.forEach(function(item) {
            const product = {
                id: item.getAttribute('data-id'),
                product_id: item.getAttribute('data-product-id'),
                name: item.querySelector('.product-name').innerHTML,
//                quantity: parseFloat(item.querySelector('INPUT[name="quantity"]').value),
                mark: item.querySelector('TEXTAREA[name="mark"]').value,
                mark_id: item.querySelector('TEXTAREA[name="mark"]').getAttribute('data-mark-id'),
                price: parseFloat(item.getAttribute('data-price')),
                new: item.getAttribute('data-new') == 'true',
            };

            popupResponse.push(product);
        });
    }

    saveProducts(popupResponse, function() {
        window.parent.postMessage({
            name: "ClosePopup",
            messageId: Math.round(10000000 * Math.random()),
            popupResponse: popupResponse,
        }, '*');
    });
}

function saveProducts(products, callback) {
    const errs = document.getElementById('errors');
    errs.innerHTML = '';

    fetch('<?= $_ENV['APP_BASE_URL']; ?>/save_products.php?contextKey=<?= urlencode($contextKey); ?>&appUid=<?= urlencode($appUid); ?>&appId=<?= urlencode($appId); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            objectId: window.objectId,
            extensionPoint: window.extensionPoint,
            items: products,
        })
    })
    .then(response => response.json())
    .then(result => {
        const error = result.error ?? null;

        if (error) {
            errs.innerHTML = error;
        }

        const statuses = result.statuses ?? null;
        let hasError = false;

        if (statuses) {
            statuses.forEach(function(status) {
                console.log(status);
                const td = document.querySelector('tr[data-id="' + status.position_id + '"] td.markcode-status');
                if (td) {
                    td.classList.remove('markcode-status-success');
                    td.classList.remove('markcode-status-failure');
                    td.classList.remove('markcode-status-unknown');

                    if (status.checked === true) {
                        td.innerHTML = 'Проверен';
                        td.classList.add('markcode-status-success');
                    }

                    if (status.checked === false) {
                        td.innerHTML = 'Ошибочный';
                        td.classList.add('markcode-status-failure');
                        hasError = true;
                    }

                    if (status.checked === null) {
                        td.innerHTML = 'Не проверялся';
                        td.classList.add('markcode-status-unknown');
                    }
                }
            });

            if (hasError === true) {
                errs.innerHTML = 'Некоторые коды маркировки не прошли проверку. Исправьте коды или удалите их.';
            }
        }

        if (hasError !== true) {
            callback();
        }
    })
    .catch(error => {
        console.error('Ошибка:', error);
    });
}

function hideResults() {
    const result = document.getElementById('result');
    if (result) {
        result.classList.remove('show');
        result.classList.remove('notfound');
    }
}

document.addEventListener('click', function(event) {
    if (event.target.closest('.search-block') === null) {
        hideResults();
    }
});

document.addEventListener('keydown', function(event) {
    if (event.code === 'Escape') {
        hideResults();
        document.body.focus();
    }
});

</script>
</head>
    <body>
        <form>

            <div class="search-block" style="display: none;">
                <label class="form-label" for="add">Добавить товар из номенклатуры:</label><br/>
                <input id="add" class="add" type="text" placeholder="Введите название товара" onblur="addBlur(event)" onkeyup="if (event.code != 'Escape') searchProduct(this.value)" autocomplete="off">
                <div class="result" id="result" onclick="clickResult(event)"></div>
            </div>

            <table class="ui-table">
                <thead>
                    <tr>
                        <th>№</th>
                        <th>Товар</th>
                        <th>Количество</th>
                        <th>Статус кода</th>
                        <th colspan="2">Код маркировки</th>
                    </tr>
                </thead>
                <tbody id="products">
                </tbody>
            </table>

            <div id="errors" class="errors"></div>

            <div class="footer-buttons">
                <input type="button" class="button button--success" value="Сохранить" onclick="onSubmit()">
                <input type="button" class="button" value="Отмена" onclick="closePopup();">
            </div>

        </form>
    </body>
</html>
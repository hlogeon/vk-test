VK.com Тестовое задание
==================================

Разработка приложения велась в следующем окружении:
* PHP 5.5.9-1ubuntu4.9
* Apache/2.4.7
* memcached 1.4.14 (Ubuntu)
* mysql  Ver 14.14 Distrib 5.5.41, for debian-linux-gnu (x86_64)

Для корректной работы приложения так же необходимы модули apache:
* mod_rewrite
* mod_headers
* mod_expires

При выполнении задания главной целью было: максимально быстрая разработка с полным соответствием заданию.

```
Реализовать простую систему просмотра списка товаров.

Товар описывается несколькими полями: id, название, описание, цена, url картинки.
Требуется:
- интерфейс создания/редактирования/удаления товара;
- страница просмотра списка товаров.

Товары можно просмотривать отсортированные по цене или по id.

Поддерживать количество товаров в списке – до 1000000.
Устойчивость к нагрузке – 1000 запросов к списку товаров в минуту.
Время открытия страницы списка товаров < 500 мс.

Техника:
PHP (без ООП), mysql, memcached.
Фронтэнд - на ваше усмотрение.
Проект должен быть на гитхабе и отражать процесс разработки.
В результате — ссылка на гитхаб и развёрнутое демо.
```

* [Результаты нагрузочного тестирования](#Результаты-нагрузочного-тестирования)
* [Под капотом](#Под-капотом)


### Обо всем по порядку:

#### Товар описывается несколькими полями: id, название, описание, цена, url картинки

Соответствует такой схеме БД:
```
CREATE TABLE products( id INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY, title VARCHAR(30), description TEXT, image VARCHAR(255), price DECIMAL(12, 2));
```

#### пользовательские интерфейсы
Все интерфейсы сделаны максимально простыми, использовался bootstrap, для возможности повтороного
использования верстки, вся она реализована в PHP файлах. Некоторые страницы используют другие страницы для большего удобства.
Например, страница списка товаров использует header.php, footer.php и pager.php


#### Поддерживать количество товаров в списке – до 1000000

Тестирование проводилось с использованием БД, содержащей **1 191 572** записей

#### Устойчивость к нагрузке – 1000 запросов к списку товаров в минуту

Тестирование проводилось с помощью npm-пакета loader с настройкой **rps(request per second) = 20**, что в свою очередб
равно 1200 запросам в минуту. Результаты представлены ниже

##### Результаты нагрузочного тестирования

| Время                                      | Общее кол-во отправленных запросов | RPS      | Среднее время отклика|
| ------------------------------------------ | ---------------------------------- | -------- | -------------------- |
| [Fri Jul 03 2015 00:08:35 GMT+0700 (ICT)]  | 0                                  |0         |0ms                   |
| [Fri Jul 03 2015 00:08:40 GMT+0700 (ICT)]  | 95                                 |19        |10ms                  |
| [Fri Jul 03 2015 00:08:45 GMT+0700 (ICT)]  | 195                                |20        |10ms                  |
| [Fri Jul 03 2015 00:08:50 GMT+0700 (ICT)]  | 295                                |20        |10ms                  |
| [Fri Jul 03 2015 00:08:55 GMT+0700 (ICT)]  | 395                                |20        |10ms                  |
| [Fri Jul 03 2015 00:09:00 GMT+0700 (ICT)]  | 495                                |20        |0ms                   |
| [Fri Jul 03 2015 00:09:05 GMT+0700 (ICT)]  | 595                                |20        |0ms                   |
| [Fri Jul 03 2015 00:09:10 GMT+0700 (ICT)]  | 695                                |20        |10ms                  |
| [Fri Jul 03 2015 00:09:15 GMT+0700 (ICT)]  | 795                                |20        |10ms                  |
| [Fri Jul 03 2015 00:09:20 GMT+0700 (ICT)]  | 895                                |20        |0ms                   |
| [Fri Jul 03 2015 00:09:25 GMT+0700 (ICT)]  | 995                                |20        |0ms                   |
| [Fri Jul 03 2015 00:09:30 GMT+0700 (ICT)]  | 1095                               |20        |10ms                  |

Как видно из таблицы, никаких трудностей приложение при подобных нагрузках не испытало.


#### Время открытия страницы списка товаров < 500 мс

Здесь все не так просто, как может покахаться на первый взгляд. Дело в том, что, если я загружу статические ассеты на собственный сервер,
на результат будет влиять то, как долго до него ходят запросы от конкретного интернет-провайдера. Поэтому то, что можно загрузить через cdn
приложение загружает через него, из-за чего, время открытия страницы так же не может полностью зависеть от меня. При этом
я использовал и настроил кеширование изображений, *.js и *.css файлов, но, опять таки, если кеширование отключено в браузере это так же не поможет.
Во время тестирования на локальной машине(не могу провести тест открытия страницы на удаленном сервере из-за ужасного интернет-соединения)
время полной загрузки страницы составляло **400ms**, что укладывается в рамки, обозначенные в ТЗ.
На всякий случай я так же проверил время, за которое полностью генерируется ответ сервера - ** 60ms **


## Под капотом

Вообще, я уже немного отвык делать приложения без использования ООП и до начала выполнения задания провел около часа
в поисках "лучших практик". Однако, как оказалось, публичный мир PHP старается максимально отойти от процедурного подхода
и любые запросы с тегами "procedural" или "without OOP" на первых 3х страницах поисковой выдачи выдают в основном
трактаты о том, что procedural - плохо, а OOP - хорошо. Поэтому, пришлось больше полагаться на интуицию, а некоторые концепции просто
перенести из уже ставшего привычным MVC-подхода, но с огромным упрощением, а иногда и коверканьем идей.

### Конфигурация
Конфигурация приложения описывается в файле `config.php`
С помощью этого файла можно настроить соединение с БД, название приложения и число товаров выводимых на странице

### Роутинг
Роутер разбирает запрос пользователя и отправляет его нужному обработчику(`dispatcher`)
Сам разбор запроса сейчас очень сильно упрощен просто ввиду ненадобности более сложного, однако, несложно будет его переписать или дописать так, что бы он
соответствовал новым требованиям, если таковые появятся. В данный момент я ограничил возможные запросы на:
1. односегментные => `/`
2. двухсегментные => `/list`
3. трехсегментные => `/view/1`
4. четырехсегментные => `/list/price/1`
Конечно, можно было бы озадачится и сделать унифицированный роутер, который разбирал бы любые запросы, но данное ТЗ
никаких намеков на это не дает, а мне, в процессе реализыции понадобились только эти, так что я просто решил не терять времени зря.

Для работы роутера, ему необходима конфигурация существующих маршрутов, которая описана в файле `routes.php`
В этом файле определяется глобальный ассоциативный массив `$routes`, ключ которого - шаблон запроса, а значение - обработчик.
Примечательно, что в качестве обработчика можно указать как анонимную функцию, так и строку с названием функции.
Анонимные функции довольно удобны, если нужно быстро протестировать функционал.

```php
$routes = [
    '/view/{id}' => 'view_record', //Название функции - обработчика
    '/list/{page}' => 'product_list',
    '/' => function(){ //Анониманя функция-обработчик
        echo "Dummy home page";
    },
];
```
Как можно заметить из примера выше, некоторые из шаблонов запроса имеют сегменты с текстом, заключенном в фигурные скобки
Это параметры зарпоса. Если запрос совпадет с шаблоном, то параметры будут переданы в обработчик в качесве аргументов,
например, для того что бы посмотреть запись с идентификатором 1 пользователь перейдет по ссылке `/view/1`, что соотвтетствует
шаблону `/view/{id}`. В свою очередь, роутер вызовет обработчик - в данном случае это функция `view_record`.

```php
    function view_record($id)
    {
        //find product and render it's page here
    }
```

При этом параметр id будет передан автоматически. **ВАЖНО: поскольку роутинг не является целью этого ТЗ, я не делал
автоматический биндинг параметров по имени, как это обычно делают**


### Представления
Представления отвечают за отображение данных. Ничего сверхъестественного

### SQL и работа с ним

Файл database.php создает новое соединение с базой данных, а так же определяет основные функции для работы с ней.
Сначала была идея выделить работу с конкретными таблицами в отдельные файлы, но потом я подумал, что для данного ТЗ это будет overkill и оставил это
для возможного улучшения в будущем.


### Единая точка входа
Все запросы в приложении отправляются ТОЛЬКО в index.php, что позволяет с легкостью управлять приложением из одной точки



## Забавный факт:
На написание этого документа я потратил больше времени, чем на выполнение самого задания, если не учитывать предварительную подготовку в виде поиска лучших практик
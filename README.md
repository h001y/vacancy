# Тестовое задание для PHP-разработчика

##Технические требования
 - PHP 7.0+
 - MySQL
 
## Структура репозитория
 - `load-files` - примеры файлов, с которыми работает приложение
 - `schema` - `.sql` файлы с таблицами, которые использует приложение
 - `sources` - исходники приложения

## Описание приложения
Имеется абстрактное приложение, задачей которого является парсинг структурированных CSV файлов 
с загрузкой содержимого
в базу данных. 

Входная точка приложения - файл `sources/cli.php`.
Пример выполнения:

```bash
$ php sources/cli.php load-files/market.eu.20180227
```

В результате выполнения часть столбцов файла `load-files/market.eu.20180227` будут загружены в таблицу `market_data`
в следующем порядке:
>Note: Остальные подробности - в исходниках приложения

DB column | CSV column[index]
------------ | -------------
id_value | 0
price | 1
is_noon | 5
update_data | `current date`


## Описание задания
Задание разбито на две задачи:
1. Починить приложение
2. Дополнить приложение новым функционалом


## Задача 1
>Note: Это задание справедливо **только** для случая выполнения скрипта с файлом `market.eu.`

### Требования к окружению
 - База данных с таблицей `market_data` из файла `schema/market_data.sql`
 - Файл типа `market.eu.%date%`

### Описание задачи
Предположим, что произошли какие-либо невероятные события, после чего проект перестал функционировать
должным образом.  Скрипт отрабатывает, но в базе данных 
новые записи не появляются.

Наши наблюдения:
 - Скрипт отрабатывает без ошибок
 - В базе данных нет записей с исходного файла
 - Лог файл не содержит сообщений типа WARN/DEBUG, значит, все отработало верно
 - Информация в лог файле перезаписывается с каждым запуском скрипта, ранее всегда дописывалась
 
 
**Необходимо** починить скрипт:
 
 - Данные из файлов типа `market.eu.` в результате выполнения скрипта должны быть успешно загруженны в базу данных
 - Ошибки должны быть верно обработаны
 - Логгер должен записывать все типы ошибок, при этом дописывая в лог-файл, а не перезаписывая его

## Задача 2
>Note: Это задание справедливо для случаев выполнения скрипта и с `market.eu.` и с `market.us.` файлами


## Тестовое задание
Нужно написать парсер csv файлов с данными (в качестве разделителя использовать любой удобный символ)

1. Папка /import/ с вложенными подпапками /YYYY/MM/DD. В конечной папке - несколько csv файлов;
2. Поля в файлах такие "рег. номер, наименование, url, телефон, email";
3. Необходимо собрать все данные из файлов в mysql таблицу;
4. Повторная обработка файла(ов) в будущем не допускается;
5. Название конечных полей на усмотрение исполнителя;
6. Все события нужно логировать в отдельную таблицу.

### Реализация 

Для выполнения задания был выбран фреймворк Laravel v. 8.27.0 (В виду того, что исполнителя имеет достаточный опыт работы с данным фреймворком).
В качестве разделителя как ни странно выбрана запятая (Сomma-Separated Values как никак).

1. Изначально планировал выполнить данную часть задания полностью автоматическим формированием папки import.
Задействованные файлы:
- app/Http/Repositories/FolderRepository - Логика формирования папок YYYY/MM/DD;
- app/Http/v1/csv/FolderManager - Формирование папок без csv файлов. 
На данном этапе остановился и понял, что часть 1 занимает слишком много времени, хотя, по факту, это можно сделать ручным способом (И это скорее всего и предполагалось)
На данном этапе папки формируются полностью по пути (public/import/...). Формирование папок запускается при GET запросе на url: /init.
CSV-файлы в итоге сформированы вручную.

2. Поля в csv-файлах соответствуют следующим полям в БД:

- Регистрационный номер - registration_number
- Наименование - name
- url - url
- Телефон - phone
- Email - email

3. Данные собираются в базу данных csv_parser, в таблицу import - после GET запроса по url: /run

4. После проведения работы папка import удаляется, и её возможно пересобрать (п.1)

5. ОК

6. Таблица Logs

### Время реализации
Суммарно потрачено около 5 часов

### Недостатки реализации
В виду иерархической структуры папок, не сильно глубокой но всё же в реализации присутствуют вложенные циклы for (что явно не является хорошей практикой, но в данном случае считается оправданным)

### Пример csv файла (1 строки)
registration_number;name;url;phone;email
1;basil;https://stackoverflow.com;000-0000-0000;nozh@mail.ru


# Парсер

Парсер использует для работы с html встроенный в `php` класс `DOMXPath`. Результат выдает в формате `.xml`.  

Структура готового `xml` похожа на структуру `yml`:

```xml
<?xml version="1.0" encoding="utf-8"?>
<catalog>
    <categories>
        <category id="d855ddef997f7d7a8dec872ad7ef9e37" parentId="d71c83e2d7915a882bcf3350d08d7ac7">Раздел 1</category>
        <category id="2e0ca46f8297c2083e72f1ee7cbddafc" parentId="d71c83e2d7915a882bcf3350d08d7ac7">Раздел 2</category>
    <categories/>
    <offers>
        <offer>
            <name> IP-видеодомофон BAS-IP AT-07L SILVER</name>
            <category>Видеодомофоны/IP видеодомофоны</category>
            <categoryId>3118e2253560273158bd4ded1eed3d3c</categoryId>
            <code>001373</code>
            <price>32 762 </price>
            <brand>BAS-IP</brand>
            <images>
                <image>
                    https://cdn.shortpixel.ai/client/q_lqip,ret_wait/https://pipl24.ru/wp-content/uploads/2020/10/at-07l...jpg
                </image>
            </images>
            <props>
                <prop id="ae962fb2001486c74f8c5d599fcfc172">
                    <name>Дисплей</name>
                    <value>7” TFT LCD, сенсорный емкостный</value>
                </prop>
                <prop id="e99bf403082ecda1aab68c963c9cea07">
                    <name>Разрешение экрана</name>
                    <value>1024×600</value>
                </prop>
            </props>
        </offer>
    </offers>
</catalog>
```
Атрибуты `id` товаров/разделов/свойств генерируются путем `md5`-преобразования названия товара/раздела/свойства.
## Структура папок

- `classes` - основные классы парсера
- `utils` - вспомогательные функции, не объединенные в классы
- `parsers` - директория с **рабочими** парсерами
- `dev` - директория для прочего кода

##

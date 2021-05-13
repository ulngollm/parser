<?php
const ROOT = '/home/ully/Документы/parser/catalog_parser';
include_once(ROOT . '/utils/autoload.php');


// $exp_result = '<span><a>ccskrссылка</a>test</span>';
// $html = '<span class="mark" align="center" style="display:none;"><a href="html" class="hre">ccskrссылка</a>test</span>';
// $result = remove_attr($html);
// echo $result;
// echo $exp_result = $result;

// echo Utils::remove_symbols('<strong>&#xD;
// <p>&#xD;
//         ОБРАТИТЕ ВНИМАНИЕ! ТЕРМОРЕГУЛЯТОР HUMMEL SAFEDRIVE ПОДХОДИТ ДЛЯ БОЛЬШИНСТВА КЛАПАНОВ (OVENTROP, HEIMEIER, DANFOSS, HONEYWELL, HONEYWELL MNG И ДР.).&#xD;
// </p>&#xD;
// <p>&#xD;
// </p>&#xD;
// <p>&#xD;
// &ndsp;');

$html = '<h2></h2>&#xD;
<h2 style="text-align: justify;">Радиатор биметаллический Royal Thermo — Biliner Bianco Traffico белого цвета с боковым подключением</h2>&#xD;
<p style="text-align: justify;">&#xD;
         Порой кажется, что все, что можно было придумать с радиатором, уже придумано. Однако инженеры Royal Thermo доказывают: все самое интересное еще впереди. Полностью биметаллический дизайн-радиатор BILINER Bianco Traffico белого цвета полюбился покупателям благодаря аэродинамическому дизайну и высокой теплоотдаче. Нижние концы ребер расположены по дуге, поэтому холодный воздух эффективно забирается из непрогретых слоев помещения. Благодаря технологии POWERSHIFT <sup>®</sup> (дополнительные ребра на вертикальном коллекторе) воздух при движении вдоль секции нагревается максимально эффективно.&#xD;
</p>&#xD;
<div style="text-align: justify;">&#xD;
        <ul>&#xD;
                <li>Высота межосевая: 500 мм</li>&#xD;
                <li> Ширина секции: 80 мм</li>&#xD;
                <li> Глубина секции: 87 мм</li>&#xD;
                <li> Мощность секции: 171 Вт</li>&#xD;
                <li> Рабочее давление: 30 атм.</li>&#xD;
                <li> Радиаторы поставляются в сборе до 14 секций, возможно увеличение количества секций путем сборки двух радиаторов в один по месту.</li>&#xD;
        </ul>&#xD;
 <b>Полностью стальной коллектор нового поколения ABSOLUTBIMETALL <sup>®</sup></b><br></br>&#xD;
        <ul>&#xD;
                <li>Применение только полностью стальных коллекторов гарантирует надежную работу в системах подверженных гидроударам и с химически агрессивными теплоносителями (в том числе антифризами)</li>&#xD;
        </ul>&#xD;
        <p>&#xD;
 <b> Повышенная мощность, технология POWERSHIFT. Патент № 122469</b>&#xD;
        </p>&#xD;
        <ul>&#xD;
                <li>Дополнительное оребрение на вертикальном коллекторе секции увеличивает теплоотдачу радиатора на 5%.</li>&#xD;
        </ul>&#xD;
        <p>&#xD;
 <b> Сверхстойкая 7-ми этапная N</b><b>ANO</b><b> покраска T</b><b>ECNOFIRMA</b><b><sup>®</sup></b>&#xD;
        </p>&#xD;
        <ul>&#xD;
                <li>Нанесение экологически чистых нано-красок AkzoNobel (Нидерланды) и FreiLacke (Германия) в семь этапов, гарантирует стойкость к механическим повреждениям и обеспечивает долговечность покрытия радиатора в помещениях с повышенной влажностью</li>&#xD;
        </ul>&#xD;
</div>&#xD;
 <br></br>                                                                                                                                              <p class="product_shipping" id="images-notice">
                                                        <img alt="HomeHeat" class="img-responsive" src="/images/delivery-icon.svg"></img>
                                                        <a data-type="product" href="/include/shipping/product_shipping.php?ELEMENT_ID=156750">Сколько стоит доставка</a>
                                                </p>
                                                       ';
Utils::clear_html($html);
echo $html;

<?php
$arr = [1, 2, 3, 8, 9, 7, -8];
foreach ($arr as $key=>$item) {//фиксируется состав массива
    if ($item > 1) {
        array_push($arr, $item - 2);
    }
    print("$key: $item\n");
}
print_r($arr);


<?php
require_once 'Lunar.php';

echo ">> Excute php function.\n";
$start = microtime(true);

for ($i=0; $i<10000; $i++){
    datetolunar_php(2016, 2, 27);
}

$end = microtime(true);
echo $end - $start;
echo "\n";

echo ">> Excute php extension function.\n";

$start = microtime(true);

for ($i=0; $i<10000; $i++){
    datetolunar(2014, 10, 25);
}
$end = microtime(true);
echo $end - $start;
echo "\n";


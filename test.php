<?php 

$old_date = new DateTime("0000-00-00 00:00:00");
$old_date->modify('+1 year');

$current_date = new DateTime();
$date_to_insert = date('Y-m-d H:i:s');

if($old_date > $current_date) {
    $date_to_insert = $old_date->format('Y-m-d H:i:s');
}

$date_obj = new DateTime($date_to_insert);

var_dump($old_date->format('ymd'));

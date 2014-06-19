<?php

use Components\Entity\Napravl;
use Components\Entity\Discipline;
use Components\Classes\db;

require_once('includes/application_top.php');

$default_napravl = Napravl::findOneBy(array(
  'name' => 'Прочее',
));

if (!$default_napravl) {
  $default_napravl_id = Napravl::create(array(
    'name' => 'Прочее',
  ));
} else {
  $default_napravl_id = $default_napravl['id'];
}

$disciplines = Discipline::findAll();
foreach($disciplines as $discipline) {
  db::replace(TABLE_DISCIPLINE_TO_NAPRAVL, array(
    'napravl_id' => $default_napravl_id,
    'discipline_id' => $discipline['id'],
  ));
}
<?php

use Components\Classes\Napravls;
use Components\Classes\db;
use Components\Entity\Discipline;
use Components\Classes\Disciplines;

require_once('includes/application_top.php');

$disciplines = Discipline::findAll();
$default_napravl_id = Napravls::getDefaultID();

foreach($disciplines as $discipline) {
  $discipline_napravl = db::get_single_value("SELECT COUNT(*) FROM " . TABLE_DISCIPLINE_TO_NAPRAVL . " WHERE discipline_id = " . $discipline['id']);

  if ($discipline_napravl > 1) {
    db::delete(TABLE_DISCIPLINE_TO_NAPRAVL, 'napravl_id = ' . $default_napravl_id . ' AND discipline_id = ' . $discipline['id']);
  }
}
<?php

use Components\Classes\Napravls;
use Components\Classes\db;
use Components\Entity\Discipline;
use Components\Classes\Disciplines;
use Components\Entity\Order;

require_once('includes/application_top.php');

$default_napravl_id = Napravls::getDefaultID();

if (!$default_napravl_id) {
  die('no default napravl');
}

$orders = db::get_arrays("SELECT * FROM " . TABLE_ORDERS . " WHERE disc_id = 0 AND disc_user != ''");

foreach($orders as $order) {
  $discipline_id = Discipline::create(array(
    'name' => $order['disc_user'],
  ));

  Disciplines::addToDefaultNaprav($discipline_id);

  Order::update($order['id'], array(
    'disc_id' => $discipline_id,
    'disc_user' => '',
  ));
}
<?php

use Components\Classes\db;
use Components\Entity\Client;

require_once('includes/application_top.php');

foreach(Client::findAll() as $client) {
  if (empty($client['referrer_code'])) {
    Client::update($client['id'], array(
      'referrer_code' => uniqid(),
    ));
  }
}
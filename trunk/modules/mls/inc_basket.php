<?php

use Components\Classes\Roles;
use Components\Classes\db;

use Components\Entity\Message;

if (isset($_REQUEST["_add"])) {
  $message = Message::find(intval($_REQUEST["_add"]));
  if ($message && ($message["addr"] == "u" . $_SESSION["user"]["data"]["id"])) {
    mls_setbasket($message, 1);
    if ($message["basket"]) {
      $GUI->OK("Перемещено в корзину");
    } else {
      $GUI->ERR("Не удалось переместить в корзину");
    }
  } else {
    $GUI->ERR("Нельзя переместить в корзину");
  }
  page_reloadToSec("2");
}

$tbl = $GUI->Table("mls_in", array("cur_sort_up" => true));
$tbl->Width = "100%";
$tbl->DataMYSQL("messages");
$tbl->FilterMYSQL("addr='u" . $_SESSION["user"]["data"]["id"] . "' AND basket=1");
$tbl->Pager(CGUI_PAGER_FLAG_SEL | CGUI_PAGER_FLAG_RR | CGUI_PAGER_FLAG_R | CGUI_PAGER_FLAG_FF | CGUI_PAGER_FLAG_F, 10, array(
  10,
  20,
  50,
  100,
  0
));

global $n;

if (Roles::isActionAllowed($GUI->mmenu->selected->id, $GUI->mmenu->selected->selected->id, $_SESSION["user"]["data"]["group_id"], "Просмотр сообщения")) {
  $tbl->RowEvent2 = "document.location.href=\"?section=mls&subsection=2&type=b&read=%var%\"";
}

$tbl->OnRowStart = "_set_row_color";

$columns_resource = Roles::getColumns($GUI->mmenu->selected->id, $GUI->mmenu->selected->selected->id, $_SESSION["user"]["data"]["group_id"]);

if (!is_resource($columns_resource)) {
  $GUI->ERR($columns_resource);
  page_reload();
}

$new_columns= array();
$column_group_name = array();
while ($row = db::fetch_array($columns_resource)) {
  if ($row['group_internal_name'] != "") {
    $column_group_name[] = $row['group_internal_name'];
    $new_columns[$row['group_internal_name']]['custom'][] = $row;
  } else {
    $new_columns[] = $row;
  }
}

foreach ($new_columns as $column) {
  if (isset($column['internal_name']) && in_array($column['internal_name'], $column_group_name)) {
    continue;
  }

  $r = null;

  if (isset($column['custom']) && count($column['custom'])) {
    $r = $tbl->NewColumn();
    foreach ($column['custom'] as $custom_column) {
      $r1 = new CGUI_TableColumn();
      $r1->Caption = $custom_column['name'];
      $r1->DoSort = $custom_column['do_sort'];
      $r1->Key = $custom_column['internal_name'];
      $r1->Align = $custom_column['align'];
      $r1->Process = $custom_column['on_execute'];
      $r->Custom[] = $r1;
    }
  } else {
    $r = $tbl->NewColumn();
    $r->Caption = $column['name'];
    $r->DoSort = $column['do_sort'];
    $r->Key = $column['internal_name'];
    $r->Align = $column['align'];
    $r->Process = $column['on_execute'];
  }

  	if ($column['internal_name'] == "id")
	{
		$tbl->DefaultSortBy($r, true);
	}
}
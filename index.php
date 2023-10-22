<?php
require 'vendor/autoload.php';
include "lib/smarty.inc.php";

if (!isset($_GET["start"]) || !isset($_GET["end"]))
{
    $start = date("Y-m-d", strtotime("first day of this month"));
    $end = date("Y-m-d", strtotime("last day of this month"));
    $start_next_month = date("Y-m-01", strtotime("+1 month"));
    $end_next_month = date("Y-m-t", strtotime("+1 month"));
    $start_previous_month = date("Y-m-01", strtotime("-1 month"));
    $end_previous_month = date("Y-m-t", strtotime("-1 month"));     
}
else
{
    $start = $_GET["start"];
    $end = $_GET["end"];
    $start_next_month = date("Y-m-01", strtotime("{$end} +1 month"));
    $end_next_month = date("Y-m-t", strtotime("{$end} +1 month"));
    $start_previous_month = date("Y-m-01", strtotime("{$start} -1 month"));
    $end_previous_month = date("Y-m-t", strtotime("{$start} -1 month"));
}
$smarty->assign("start", $start);
$smarty->assign("end", $end);
$smarty->assign("start_next_month", $start_next_month);
$smarty->assign("end_next_month", $end_next_month);
$smarty->assign("start_previous_month", $start_previous_month);
$smarty->assign("end_previous_month", $end_previous_month);

// File upload
if (isset($_FILES["fileupload"]))
{
    move_uploaded_file($_FILES["fileupload"]["tmp_name"], "db.mmbak");
}

// Google drive upload
if (isset($_POST["gdriveurl"]))
{
    preg_match_all('/\/d\/(.*)\//m', $_POST["gdriveurl"], $matches, PREG_SET_ORDER, 0);
    file_put_contents("db.mmbak", file_get_contents("https://drive.google.com/uc?export=download&id=".$matches[0][1]));
}

// DB read
if (file_exists("db.mmbak"))
{
    $Db = new Ricci69\MmbakViewer\DbDriver();
    $Currencies = new Ricci69\MmbakViewer\Currencies($Db);
    $Inoutcome = new Ricci69\MmbakViewer\Inoutcome($Db);
    $Categories = new Ricci69\MmbakViewer\Categories($Db);
    $Wallet = new Ricci69\MmbakViewer\Wallet($Db); 

    $currencies = $Currencies->get();
    $smarty->assign("currencies", $currencies); 

    $transactions = $Inoutcome->getFull($start, $end);
    $smarty->assign("transactions", $transactions);

    $sumIn = $Inoutcome->getSumIn($start, $end);
    if (is_null($sumIn["sum"])) $sumIn = array("sum"=>0, "currency"=>key($currencies));
    $smarty->assign("sumIn", $sumIn);

    $sumOut = $Inoutcome->getSumOut($start, $end);
    if (is_null($sumOut["sum"])) $sumOut = array("sum"=>0, "currency"=>key($currencies));
    $smarty->assign("sumOut", $sumOut);

    $balance = $Wallet->getBalance();
    $smarty->assign("balance", $balance);
}
else
{
    $smarty->assign("fileNotFound", 1);
    $smarty->assign("currencies", array(""=>""));
    $smarty->assign("transactions", array());
    $smarty->assign("sumIn", array("sum"=>0, "currency"=>""));
    $smarty->assign("sumOut", array("sum"=>0, "currency"=>""));
    $smarty->assign("balance", array("balance"=>0, "currency"=>""));
}
    
$smarty->display('index.tpl.html');
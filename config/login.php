<?php
//really ugly script, feel free to release a better one :)
// be aware that we don't even test the password in this script
$host = "localhost"; 
$user = "root";
$passwd = "fortera2017";
$db = "MurrugaFeso2017@sql009";

$lnk = mysql_connect("$host", "$user", "$passwd") or die ('ERROR MySql: ' . mysql_error());
mysql_select_db("$db", $lnk) or die ('ERROR MySql: ' . mysql_error());    

$request_body = file_get_contents('php://input');
$result = json_decode($request_body, true);

$acc = $result["accountname"];
$password = $result["password"];

$dbResource = mysql_query("SELECT `id` FROM `accounts` WHERE `name` = '$acc' LIMIT 1;");

if(!$dbResource) {
	die("failed to get account.");
}

$dbRet = mysql_fetch_array($dbResource);

$accId = $dbRet[0];

$dbResource = mysql_query("SELECT `name` FROM `players` WHERE `account_id` = '$accId';");
if(!$dbResource) {
	die("failed to get charactters.");
}

$accArray = array();

while ($dbRet = mysql_fetch_array($dbResource, MYSQL_BOTH)) {
	$dict = array("worldid" => 0, "name" => $dbRet["name"]);
	$accArray[] = $dict;
}

$data = array();

$session = array(
	"sessionkey" => $acc . "\n" . $password,
	"lastlogintime" => 1461290246,
    "ispremium" => true,
    "premiumuntil" => 1463788913,
    "status" => "active"	
);

$data["session"] = $session;

$playerData = array();

$world = array(
	"id" => 0,
	"name" => "Relembra Global",
	"externaladdress" => "relembra-global.com",
	"externalport" => 7171,
	"previewstate" => 0
);

$worlds = array($world);
$playerData["worlds"] = $worlds;
$playerData["characters"] = $accArray;

 
$data["playdata"] = $playerData;

echo json_encode($data);
?>
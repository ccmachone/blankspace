<?php
include_once("../../classes.php");
echo "heree";
$user = new \User();
$user->setFirst_name("Colby");
$user->setLast_name(time());
$user->setEmail("test@none.com");
$user->save();
var_dump($user);

$follow = new \Follow();
$follow->setUser_id(1);
$follow->setFollowing_id(2);
$follow->save();
var_dump($follow);

$checkin = new \Checkin();
$checkin->setUser_id(1);
$checkin->setMap_url("https://www.google.com");
$checkin->setLatitude(123.456);
$checkin->setLongitude(234.567);
$checkin->save();
var_dump($checkin);



//$user->setId(3);
//$user->delete();

//$user_da = new \User_DA();
//$user_da->getById(2);
?>

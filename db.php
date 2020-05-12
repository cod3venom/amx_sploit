<?php 

if(!defined("host")){define("host","localhost");}
if(!defined("uname")){define("uname","root");}
if(!defined("pwd")){define("pwd","root");}
if(!defined("db")){define("db","Brute");}

$conn = mysqli_connect(host,uname,pwd,db);
if(!$conn)
{
    echo "CANT CONNECT";
    exit();
}
else
{
   echo "connected";
}

?>

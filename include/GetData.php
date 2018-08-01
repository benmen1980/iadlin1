<?php
include 'APIconfig.php';


$booknum = 'LY55972';
$cellphone = '0523782066';

$booknum= $_GET["booknum"];
$cellphone = $_GET["cellphone"];

$odatafilter = '?$filter=BOOKNUM eq \''.$booknum.'\' and (ELYD_CELL1 eq \''.$cellphone.'\' or ELYD_CELL2 eq \''.$cellphone.'\')';
//next example will recieve all messages for specific conversation
$service_url = 'https://yl.wee.co.il/odata/priority/tabula.ini/'.$companyname.'/'.$priform.$odatafilter;
//https://yl.wee.co.il/odata/Priority/tabula.ini/ar1301/DOCUMENTS_D?$filter=BOOKNUM eq 'LY55972' and (ELYD_PHONE eq '0523782066' or ELYD_CELL eq '0542466884')
$curl = curl_init($service_url);

curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($curl, CURLOPT_USERPWD, "$login:$password");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_URL, str_replace(" ", '%20',$service_url));

$curl_response = curl_exec($curl);
if ($curl_response === false) {
	throw new Exception(curl_error($curl), curl_errno($curl));
	$info = curl_getinfo($curl);
	curl_close($curl);
	die('error occured during curl exec. Additioanl info: ' . var_export($info));
}
curl_close($curl);
$decoded = json_decode($curl_response,true);
if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
	die('error occured: ' . $decoded->response->errormessage);
}
$parsed = $decoded['value'];
echo json_encode($parsed);
?>
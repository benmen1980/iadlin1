<?php

include 'APIconfig.php';

/* debug static data
$docno = "SH18028050";
$cdes = "dsdfgf";
$address = "rthrththrth";
$city = "Jerusalem";
$comment = "my comment";
*/

$docno = $_GET["docno"];
$cdes = $_GET["cdes"];
$address = $_GET["address"];
$city = $_GET["city"];
$comment = $_GET["comment"];


$odatafilter = '(DOCNO=\''.$docno.'\',TYPE=\'D\')';


$service_url = 'https://yl.wee.co.il/odata/priority/tabula.ini/'.$companyname.'/'.$priform.$odatafilter;
// https://yl.wee.co.il/odata/Priority/tabula.ini/ar1301/DOCUMENTS_D(DOCNO='SH18028050',TYPE='D')
//$curl = curl_init($service_url);

$data = array("CDES" => $cdes, "ELIT_FULLADDRESS" => $address,"ELIT_CITYNAME" => $city,"ELYD_SENDOFF" => $comment );
$data_string = json_encode($data);

$headers = array('Content-Type: application/json');
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $service_url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($curl, CURLOPT_USERPWD, "$login:$password");
curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

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
//$parsed = $decoded['value'];
echo json_encode($decoded);
?>

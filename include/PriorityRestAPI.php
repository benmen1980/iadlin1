<?php
$login = 'demo';
$password = '123456';

//next example will recieve all messages for specific conversation
$service_url = 'https://devpri.roi-holdings.com/odata/priority/tabula.ini/demo/DOCUMENTS_D';
$curl = curl_init($service_url);
//curl_escape($curl,$service_url);
curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($curl, CURLOPT_USERPWD, "$login:$password");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_URL, str_replace(" ", '%20',$service_url));
//curl_setopt($curl, CURLOPT_HTTPHEADER, array('application/json;odata.metadata=minimal','Authorization: Basic '. base64_encode("user:password")));
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
//parse_str(curl_exec($curl), $parsed);
echo json_encode($parsed);
//echo $decoded;
//var_export($decoded->response);

?>
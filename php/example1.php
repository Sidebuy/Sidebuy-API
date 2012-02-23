<?php
/*
This example is provided by Sidebuy API Team.
You need to change the API Key to the one you obtained from Sidebuy in Order to be able to use these examples
For any question or difficulties using this example, email reza@sidebuy.com
*/
$API_KEY	= 'abc62ee1fd09c296fb9f45702063a374';		// REPLACE THIS WITH YOR OWN API KEY
$format 	= 'json';


$query = array(
			'city'		=>		'new-york',	// City code, for all city codes please refer to http://sidebuy.com/api/cities
			'limit'		=>		'0-100',	// Return 100 deals. Starting from 0 to 100
			'sort'		=>		'expiryepoch|1'	// Sort by expiry, DESC
		);



$query['format'] = $format;
$API_END_POINT = 'http://v1.sidebuy.com/api/get/'.$API_KEY.'/?'.http_build_query($query);

$ch = curl_init($API_END_POINT);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, 0);
$data = curl_exec($ch); 	// Sidebuy response
curl_close($ch);	
$response =  json_decode($data, true);	// Decode JSON String

//print_r($response);	// Uncomment to see all data returned

foreach ($response as $deal){
	echo "Deal title: ".htmlspecialchars_decode($deal['title'])." - Price: ".$deal['price']."<br/>";
}
?>
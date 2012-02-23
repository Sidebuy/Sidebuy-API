<?php
/*
This example is provided by Sidebuy API Team.
You need to change the API Key to the one you obtained from Sidebuy in Order to be able to use these examples
For any question or difficulties using this example, email reza@sidebuy.com

This code is ready to "Copy & Paste" ot your website. But please make sure you change the $API_KEY
Location is found automatically based on users IP address. If location is unavailable then all online deals (Global deals) will be returned.
*/

// IMPORTANT, CHANGE THIS KEY TO THE ONE YOU OBTAINED FROM SIDEBUY
$API_KEY	= 'abc62ee1fd09c296fb9f45702063a374';		// REPLACE THIS WITH YOR OWN API KEY
$format 	= 'json';

$query = array(
			'range'		=>		'50',	// Range, 50 KM
			'mkm'		=>		'km',
			'sort'		=>		'expiryepoch|1',	//Sort by expiry
			'limit'		=>		'0-100',	// Only show 100 deals
			'epoch_to_time'	=>	'true'
		);

$city = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$_SERVER['REMOTE_ADDR'])); // Courtesy of geoplugin.net, automatically find users latitude and longitude 
if (isset($city)){
	$query['loc'] = "(".$city['geoplugin_latitude'].",".$city['geoplugin_longitude'].")"; // set the latitude and longitude
}	else {
	$query['online'] = 'true'; // If no latitude and longitude found, then show all online deals.
}

$query['format'] = $format;
$API_END_POINT = 'http://v1.sidebuy.com/api/get/'.$API_KEY.'/?'.http_build_query($query); // Create the query string

$ch = curl_init($API_END_POINT); // CURL to get data from sidebuy
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, 0);
$data = curl_exec($ch); 	// Sidebuy response
curl_close($ch);	
$response =  json_decode($data, true);	// Decode JSON String
?>
<div class="deals">
<?php foreach ($response as $deal){ // loop through all deals?>
	
	<?php
	if (isset($deal['merchant']['address'])){
		if (isset($deal['merchant']['name'])){
			$merchant_info = '<div class="deal_address_box"><ul><li class="deal_address"><b> '.htmlspecialchars_decode(strip_tags($deal['merchant']['name'])).'</b><br>'.strip_tags($deal['merchant']['address']).'</li></ul></div>';
		} else {
			$merchant_info = '<div class="deal_address_box"><ul><li class="deal_address">'.strip_tags($deal['merchant']['address']).'</li></ul></div>';
		}
	}
	?>
	<div class="deal clearfix">
		<div class="deal_image">
			<img alt="<?php echo htmlspecialchars_decode(strip_tags($deal['title'])); ?>" src="<?php echo $deal['image']; ?>">
		</div>
		<div class="deal_title">
			<a href="<?php echo $deal['link']; ?>"><?php echo htmlspecialchars_decode($deal['title']); ?></a>
			<div class="merchant"><?php echo $merchant_info; ?></div>
		</div>
	<div class="deal_col_3">
		<div class="deal_buy_info clearfix line_2">
			<span class="buy_label">$<?php echo str_replace('.00', '', $deal['price']); ?></span>
			<span class="buy_button"><a href="<?php echo $deal['link']; ?>" target="_blank" class="large blue awesome">see deal</a></span>
		</div>
		<div class="deal_buy_facts clearfix line_2">
			<div class="info_col">
				<span class="deal_info_caption">value</span>
				<span class="deal_info_value">$<?php echo str_replace('.00', '', $deal['original_price']); ?></span>
			</div>
		<div class="info_col">
			<span class="deal_info_caption">discount</span>
			<span class="deal_info_discount">$<?php echo str_replace('.00', '', $deal['saving']); ?></span>
		</div>
		<div class="info_col">
			<span class="deal_info_caption">savings</span>
			<span class="deal_info_saving"><?php echo $deal['discount']; ?>%</span>
		</div>
	</div>
	<div class="deal_expiry line_2"><?php echo $deal['expiryepoch'];?> left</div>
	</div>
	</div>	
<?php } ?>
</div>
<style type="text/css">
.deals{ padding:10px; margin:10px; font-family:arial; width:900px;}
.deal{ margin-bottom:10px; border-radius:3px;-webkit-border-radius:3px; -moz-border-radius:3px; border: 1px solid #f8fee4; box-shadow: 1px 1px 3px #BBBBBB; -moz-box-shadow: 1px 1px 3px #BBBBBB; -webkit-box-shadow: 1px 1px 3px #BBBBBB; padding:5px; min-height:80px; background-color:#fff;}
.deal_title a, .deal_title a:visited, .deal_title a:link, .deal_title a:hover{ font-size:16px; text-decoration:none; color:#4D6876;}
.deal img{ width:120px; height:80px;}
.awesome, .awesome:visited, .awesome:link {font-size: 16px; padding: 8px 14px 9px;margin:5px;background: #326177;display: inline-block; padding: 5px 10px 6px; color: #fff; text-decoration: none;-moz-border-radius: 5px; -webkit-border-radius: 5px;border-radius:5px;-moz-box-shadow: 0 1px 3px rgba(0,0,0,0.5);-webkit-box-shadow: 0 1px 3px rgba(0,0,0,0.5);text-shadow: 0 -1px 1px rgba(0,0,0,0.25);border-bottom: 1px solid rgba(0,0,0,0.25);position: relative;cursor: pointer;}
.awesome:hover	{ background-color: #111; color: #fff; }
.deal_image{width:120px;float:left;margin:10px;}
.deal_title{font-size:20px;width:500px;float:left;margin:10px;}
.deal_price{font-size:14px;margin:8px;}
.clearfix:after {content: ".";display: block;height: 0;clear: both;visibility: hidden;}
.deal_address_box{margin:3px;padding:5px;font-size:12px;font-family:arial;color:#6d6d6d;}
.deal_address_box li{margin:3px;padding:0px;float:left;}
.deal_address_box ul{list-style-type: none;margin:3px;padding:0px;}
.deal .deal_col_3{float:left;width: 210px;padding:5px;}
.deal .deal_buy_info{display: block;}
.deal .buy_label{float:left; font-size:22px; font-weight:bolder; color:#e45831; margin-top:1px;}
.deal .buy_button{float:right;}
.deal .deal_buy_facts{text-align:center;}
.deal .deal_expiry{text-align:center;}
.deal .views{text-align:center;}
.deal_buy_facts .info_col span{display:block;text-align:center;}
.deal_buy_facts .deal_info_caption{font-weight: bold;font-size:10px;font-family:arial;color:#6d6d6d;}
.deal_buy_facts .info_col{text-align:center;width:70px;float:left;font-family:arial;color:#6d6d6d;}
.deal_buy_facts .info_col .deal_info_value{margin:0px;padding:0px;font-size:18px;font-weight: bolder;color:#6d6d6d;}
.deal_buy_facts .info_col .deal_info_discount{margin:0px;padding:0px;font-size:18px;font-weight: bolder;color:#6d6d6d;}
.deal_buy_facts .info_col .deal_info_saving{margin:0px;padding:0px;font-size:18px;font-weight: bolder;color:#6d6d6d;}
.deal_expiry{font-weight: bold;font-size:12px;font-family:arial;color:#6d6d6d;}
.deal_col_3 div{margin-top:7px;}
</style>
<!--[if IE]>
<style type="text/css">
  .clearfix {
    zoom: 1;     /* triggers hasLayout */
    }  /* Only IE can see inside the conditional comment
    and read this CSS rule. Don't ever use a normal HTML
    comment inside the CC or it will close prematurely. */
</style>
<![endif]-->

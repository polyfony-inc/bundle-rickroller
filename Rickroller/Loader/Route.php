<?php 

// for each registered url
foreach(Models\Offenders::redirected_urls as $index => $url) {
	Polyfony\Router::map(
		$url,
		'Rickroller/Rickroller@Rickroller'
	);	
}

Polyfony\Router::get(
	Models\Offenders::offenders_ips_url,
	'Rickroller/Rickroller@Listing'
);


?>

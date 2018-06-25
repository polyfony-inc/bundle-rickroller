<?php

class RickrollerController extends Polyfony\Controller {

	public function RickrollerAction() {

		// log the event normally 
		Polyfony\Logger::warning('Rick Astley has been trigerred, add this IP to your blacklist.');
		
		// log to the bad ips list to provide blacklists for firewalls
		Models\Offenders::addMe('Rickrolled for trying to access a malicious URL');
		
		// redirect to Rick Astley
		Polyfony\Response::setRedirect('https://www.youtube.com/watch?v=dQw4w9WgXcQ',0);
		
		// render the page
		Polyfony\Response::render();

	}

	public function ListingAction() {

		Polyfony\Response::setType('text');
		Polyfony\Response::setContent(Models\Offenders::allAsPlainText());
		Polyfony\Response::render();

	}

}

?>

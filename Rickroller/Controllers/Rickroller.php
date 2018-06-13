<?php

class RickrollerController extends Polyfony\Controller {

	public function RickrollerAction() {

		// log
		Polyfony\Logger::warning('Rick Astley has been trigerred, add this IP to your blacklist.');
		
		// redirect
		Polyfony\Response::setRedirect('https://www.youtube.com/watch?v=dQw4w9WgXcQ',0);
		Polyfony\Response::render();

	}

}

?>

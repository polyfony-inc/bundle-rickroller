<?php

// CREATE TABLE "Offenders" (
// "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
// "ip_address" text(16) NOT NULL,
// "first_seen_date" integer(16) NOT NULL,
// "last_seen_date" integer(16) NOT NULL,
// "offenses" integer NOT NULL,
// "reason" text(256) NOT NULL )

namespace Models;
use Polyfony\Database as Db;
use Polyfony\Format as Format;
use Polyfony\Request as Request;
use Polyfony\Config as Config;

class Offenders extends \Polyfony\Record {


	public static function addMe(string $reason='None provided') {


		// must be in prod to log bad ips
		if(!Config::isProd()) {
			// go no further
			return;
		}

		// try seing if an offense was already registered for this IP
		$offender = self::_select()
			->where([
				'ip_address'=>Request::server('REMOTE_ADDR')
			])
			->first()
			->execute();
		
		// if no offense were registered with that ip
		if(!$offender) {
			// create a new offender record
			$offender = new self();
			// set the basics
			$offender->set([
				// source address, cleaned up
				'ip_address'		=>Format::truncate(
					Format::htmlSafe(
						Request::server('REMOTE_ADDR')
					),
					16
				),
				// first time we've seen that ip
				'first_seen_date'	=>time(),
				// offenses counter
				'offenses'			=>0
			]);
		}

		// update the offender's infos
		$offender
			->set([
				// last time we've seen that offender
				'last_seen_date'=>time(),
				// the latest reason
				'reason'		=>$reason,
				// counter of offenses is incremented
				'offenses'		=>$offender->get('offenses') + 1
			])
			// and save it
			->save();

	}

	public static function allAsPlainText() :array {

		// declare an array to store bad ips
		$ips = [];
		// for each currently logged bad ip
		foreach(self::_select(['ip_address'])->execute() as $offender) {
			// add it to the list
			$ips[] = $offender->get('ip_address');
		}
		// return a clean list of bad ips
		return implode("\n",$ips);

	}

}


?>

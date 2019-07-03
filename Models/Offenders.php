<?php

namespace Models;
use Polyfony\Database 	as Db;
use Polyfony\Format 	as Format;
use Polyfony\Request 	as Request;
use Polyfony\Config 	as Config;
use Polyfony\Logger 	as Logger;
use Polyfony\Cache 		as Cache;
use Polyfony\Exception 	as Exception;

class Offenders extends \Polyfony\Record {

	const redirected_urls 						= [
		'/FileZilla.xml',
		'/filezilla.xml',
		'/sitemanager.xml',
		'/WS_FTP.INI',
		'/ws_ftp.ini',
		'/deployment-config.json',
		'/config/database.yml',
		'/config/databases.yml',
		'/lfm.php',
		'/sqlite/main.php',
		'/sqlitemanager/main.php',
		'/SQLiteManager/main.php',
		'/SQlite/main.php',
		'/agSearch/SQlite/main.php',
		'/password',
		'/HNAP1/',
		'/getcfg.php',
		'/wp-content/',
		'/wp-config.php',
		'/wp-admins.php',
		'/jenkins/script',
		'/scripts/setup.php',
		'/mysqldumper/',
		'/mysql/',
		'/sql/',
		'/typo3/phpmyadmin/index.php',
		'/phpMyAdmin-4.2.1-english/',
		'/phpMyAdmin-4.2.1-all-languages/',
		'/hudson/script',
		'/Joomla/administrator/',
		'/joomla/administrator/',
		'/status?full=true',
		'/admin.php',
		'/admin/login.php',
		'/administrator/index.php',
		'/ajaxproxy/proxy.php',
		'/magmi/web/magmi.php',
		'/pma-old/index.php',
		'/phpMyAdmin/scripts/setup.php',
		'/wp-admin/admin-ajax.php',
		'/wp-admin/includes/themes.php',
		'/wp-admin/options-link.php',
		'/wp-admin/post-new.php',
		'/wp-login.php',
		'/blog/xmlrpc.php',
		'/xmlrpc.php',
		'/phpinfo.php',
		'/phpsysinfo/',
		'/phpmyadmin/',
		'/login.php',
		'/config.php',
		'/config/',
		'/data/',
		'/lib/',
		'/library/',
		'/cgi/',
		'/cgi.cgi/',
		'/bin/',
		'/phpMyAdmin/',
		'/admin/cgi',
		'/piwik/',
		'/magento/',
		'/cgi-bin/',
		'/adm/',
		'/administrator/',
		'/administrator/wp-login.php',
		'/administrator/xmlrpc.php',
		'/3rdparty/phpmyadmin/',
		'/muhstik-dpr.php',
		'/muhstiks.php',
		'/pma/',
		'/PMA/index.php',
		'/PMA2/index.php',
		'/_query.php',
		'/cmd.php',
		'/qq.php',
		'/shell.php',
		'/x.php',
		'/xx.php',
		'/test.php',
		'/ownCloud/',
		'/cms/',
		'/index.pl',
		'/index.cgi',
		'/index.py',
		'/test/',
		'/wordpress/',
		'/cms/',
		'/index.action',
		'/login.action',
		'/manager/',
		'/mantis/',
		'/mantisbt/',
		'/info.php',
		'/info_php.php',
		'/test.php',
		'/admin.cgi',
		'/login.pl',
		'/data/owncloud.log',
		'/data/owncloud.db',
		'/.htpasswd',
		'/.passwd',
		'/private/',
		'/phpBB/',
		'/postnuke/',
		'/mysql-admin/index.php',
		'/admin/login.php',
		'/admin/mysql/index.php',
		'/admin/phpMyAdmin/index.php',
		'/admin/phpmyadmin/index.php',
		'/admin/pma/index.php',
		'/~root/',
		'/~admin/',
		'/fckeditor/editor/filemanager/connectors/php/upload.php',
		'/.Trash/',
		'/.Trashes/',
		'/.adminer.php.swp',
		'/.bash_history',
		'/.cache/',
		'/app_dev.php',
		'/index_dev.php',
		'/.eclipse',
		'/.ftppass',
		'/.git/',
		'/.ssh/id_rsa',
		'/.ssh/id_rsa.pub',
		'/.ssh/id_rsa.key',
		'/007/',
		'/00.php',
		'/0manager/',
		'/0admin/',
		'/Access/',
		'/Adm.php',
		'/Admin-login.php',
		'/Admin.asp',
		'/Config.php',
		'/DB/',
		'/DefaultWebApp.aspx',
		'/Drupal.php',
		'/ESYSManager/',
		'/Dump.php',
		'/FCKeditor/',
		'/FCKeditor.php',
		'/FCKeditor/editor/filemanager/browser/default/connectors/php/connector.php',
		'/FTP.php',
		'/install/',
		'/install.php',
		'/Install/',
		'/Joomla.php',
		'/Logs/',
		'/logs/',
		'/Login.cgi',
		'/Login.jsp',
		'/Login.rb',
		'/Login.pl',
		'/MyAdmin/',
		'/PORTAL/',
		'/Password.dat',
		'/password.ini',
		'/passwords.txt',
		'/Secret/',
		'/Setup/',
		'/Setup.php',
		'/Signin.asp',
		'/SiteAdmin/login.php',
		'/SiteServer/Admin.aspx',
		'SnoopServlet.aspx',
		'/tmp/',
		'/tmp.php',
		'/tamp.php',
		'/wp/',
		'/wp.php',
		'/WordPress/',
		'/wordpress/',
		'/_adminer.php',
		'/__adminer.php',
		'/__backup/',
		'/__old/',
		'/_old/',
		'/_admin/',
		'/_admin.php',
		'/_actions/',
		'/_actions/_login.php',
		'/_admin_/',
		'/_ajax/',
		'/_cms/',
		'/access_db/',
		'/admin.config.php',
		'/archives.zip',
		'/backdoorbot/',
		'/backdoor/',
		'/bb-admin/',
		'/crypto/wallet.dat',
		'/core/wallet.dat',
		'/coin/wallet.dat',
		'/btc/wallet.dat',
		'/bitcoin/wallet/wallet.dat',
		'/bitcoin/wallet.dat',
		'/backup/wallet.dat',
		'/hidden/wallet.dat',
		'/.bitcoin/wallet.dat',
		'/.bitcoin/wallet/wallet.dat',
		'/node/wallet.dat',
		'/wallet.dat',
		'/wallet/wallet.dat'
	];

	const redirection_url 						= 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'; // rick astley's youtube URL

	const offenders_ips_url 					= '/badips.txt'; // the public url listing offenders

	const default_minimum_offenses 				= 2; // default is two

	const default_ignore_offenses_older_than 	= 604800; // default is two weeks

	const cache_for 							= 900; // default is 15 minutes

	const table_creation_query 					= 'CREATE TABLE IF NOT EXISTS "Offenders" ( "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT, "ip_address" text(16) UNIQUE NOT NULL, "first_seen_date" integer(16) NOT NULL, "last_seen_date" integer(16) NOT NULL, "offenses" integer NOT NULL, "reason" text(256) NOT NULL );';

	const VALIDATORS = [
		'ip_address'		=>FILTER_VALIDATE_IP
	];

	private static function createTableIfNeeded() :void {

		// create the offenders table if necessary
		Db::query()->query(self::table_creation_query)->execute();

	}

	public static function enforce(
		int $minimum_offenses 				= self::default_minimum_offenses, 
		int $ignore_offenses_older_than 	= self::default_ignore_offenses_older_than
	) :void {

		// if offenders are cached
		if(Cache::has('Offenders')) {
			// feth it from the cache
			$offenders = Cache::get('Offenders');
		}
		else {
			// create the table if needed
			self::createTableIfNeeded();
			// get a fresh list of offenders
			$offenders = [];
			// for each of those
			foreach(self::_select()->execute() as $offender) {
				// build an array
				$offenders[$offender->get('ip_address')] = [
					'offenses'		=>$offender->get('offenses'),
					'last_seen_date'=>$offender->get('last_seen_date', true)
				];
			}
			// cache it for some time
			Cache::put('Offenders', $offenders, true, self::cache_for);
		}
		// if the offender exists in our list
		if(array_key_exists(Request::server('REMOTE_ADDR'), $offenders)) {
			if(
				// if is has enough offenses
				$offenders[Request::server('REMOTE_ADDR')]['offenses'] >= $minimum_offenses && 
				// and if those are fresh enough
				$offenders[Request::server('REMOTE_ADDR')]['last_seen_date'] >= time() - $ignore_offenses_older_than
			) {
				// log it
				Logger::warning('A request is being blocked due to previous offenses');
				// stop it altogether
				Throw new Exception(
					'You are banned due to dubious activity', 
					403
				);
			}
		}
	}

	public static function addMe(string $reason='None provided') {

		// must be in prod to log bad ips
		if(!Config::isProd()) {
			// go no further
			return;
		}

		// create the table if needed
		self::createTableIfNeeded();
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

	public static function allAsPlainText() :string {

		// create the table if needed
		self::createTableIfNeeded();
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

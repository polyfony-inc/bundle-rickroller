### Rickroller (Bundle)

This bundle provides redirections from URL that botnets usually try, and sends them listen to Rick Astley. 
Inspired by [Nick Craver](https://github.com/NickCraver) @StackOverflow 
Appart from the obvious musical benefits, this bundle actually enhances security a tad by generating noise that the attackers will have to filter thru. 

It also pushes offender's IP into a local SQL table, and serves a public `/badips.txt` with all of the offender's IP, to provide realtime blacklisting for your firewalls (Suricata, Snort and what not). 
*Blacklisting occurs only in production environment.*

### Requirements

* Polyfony 2.2+
* a SQL backend with the table provided bellow

### Required Table

```sql
CREATE TABLE "Offenders" (
	"id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
	"ip_address" text(16) UNIQUE NOT NULL,
	"first_seen_date" integer(16) NOT NULL,
	"last_seen_date" integer(16) NOT NULL,
	"offenses" integer NOT NULL,
	"reason" text(256) NOT NULL 
)
```

### Installation

* Place the `Rickroller` folder in your `Bundles/` folder  
* Place the `Models/Offenders.php` in your `Models/` folder

You are good to go.

### Hard banning in addition to rickrolling 

In case you don't use a firewall accepting plaintext IP lists and still wish to prevent further access attemps, you can add the following code in your controllers
```php
Offenders::enforce($minimum_offenses = 2, $ignore_older_than = 604800);
``` 
This will check the client's IP against our current list of offenders and throw a 403 exception if the offender has more than `minimum_offenses` and if the offenders' `last_seen_date` is not older than `$ignore_older_than`.

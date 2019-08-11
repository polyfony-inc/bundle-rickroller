<?php

// protect against offenders, application-wide
// this should pretty much be a middleware actually
Models\Offenders::enforce();

?>

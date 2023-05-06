<?php
	$home = getenv('HOME');
	$dbpath = ($home ? $home : '/home/klm') . '/php/jptables/jptables.db';
?>
<style>
:root { --bg-color:white; color:black; --h1Color:DarkBlue; --thColor:LightBlue; --nthChild:LightGray; }

	h1 { font-size:3em; color:var(--h1Color) }
	h2 { font-size:2.7em; color:var(--h1Color) }
	h3 { font-size:2em; color:var(--h1Color) }
	p, label, input, option, select, td, textarea, form, pre, button { font-size:2rem }
</style>

<?php

global $gBitSystem, $gUpgradeFrom, $gUpgradeTo;

$upgrades = array(

	'BWR1' => array(
		'BWR2' => array(
// de-tikify tables
array( 'DATADICT' => array(
	array( 'RENAMETABLE' => array(
		'tiki_hotwords' => 'hotwords',
	)),
)),
		)
	),
);

if( isset( $upgrades[$gUpgradeFrom][$gUpgradeTo] ) ) {
	$gBitSystem->registerUpgrade( HOTWORDS_PKG_NAME, $upgrades[$gUpgradeFrom][$gUpgradeTo] );
}
?>

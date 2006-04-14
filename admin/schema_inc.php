<?php

$tables = array(

'hotwords' => "
	word C(40) PRIMARY,
	url C(255) NOTNULL
"

);

global $gBitInstaller;

foreach( array_keys( $tables ) AS $tableName ) {
	$gBitInstaller->registerSchemaTable( HOTWORDS_PKG_NAME, $tableName, $tables[$tableName] );
}

$gBitInstaller->registerPackageInfo( HOTWORDS_PKG_NAME, array(
	'description' => "Hotwords allow you to specify particular words that can be associated with a particular link. e.g. if someone writes 'google' in any text, you can associate it with 'http://www.google.com'.",
	'license' => '<a href="http://www.gnu.org/licenses/licenses.html#LGPL">LGPL</a>',
	'version' => '0.1',
	'state' => 'alpha',
	'dependencies' => '',
) );

// ### Default Preferences
$gBitInstaller->registerPreferences( HOTWORDS_PKG_NAME, array(
	array(HOTWORDS_PKG_NAME, 'hotwords_new_window','n'),
	//array(HOTWORDS_PKG_NAME, 'hotwords','y')
) );

?>

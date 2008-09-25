<?php
require_once( '../../bit_setup_inc.php' );
require_once( HOTWORDS_PKG_PATH.'BitHotwords.php' );

$gHotwords = new BitHotwords();

$gBitSystem->verifyPackage( 'hotwords' );
$gBitSystem->verifyPermission( 'p_admin' );

// Process form
if( !empty( $_REQUEST["word"] ) && !empty( $_REQUEST["url"] )) {
	$gHotwords->store( $_REQUEST["word"], $_REQUEST["url"] );
}

if( !empty( $_REQUEST["remove"] )) {
	$gHotwords->expunge( $_REQUEST["remove"] );
}

$words = $gHotwords->getList( $_REQUEST );
$gBitSmarty->assign_by_ref( 'words', $words );
$gBitSmarty->assign_by_ref( 'listInfo', $_REQUEST['listInfo'] );

// Display the template
$gBitSystem->display( 'bitpackage:hotwords/admin_hotwords.tpl', "Admin Hotwords", array( 'display_mode' => 'admin' ));
?>

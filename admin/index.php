<?php

// $Header: /cvsroot/bitweaver/_bit_hotwords/admin/index.php,v 1.6 2008/06/25 22:21:11 spiderr Exp $

// Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

// Initialization
require_once( '../../bit_setup_inc.php' );

include_once ( HOTWORDS_PKG_PATH.'hotword_lib.php' );

$gBitSystem->verifyPackage( 'hotwords' );
$gBitSystem->verifyPermission( 'p_admin' );

// Process the form to add a user here
if (isset($_REQUEST["add"])) {
	
	if(empty($_REQUEST["word"]) || empty($_REQUEST["url"])) {
	        $gBitSmarty->assign('msg', tra("You have to provide a hotword and a URL"));
		$gBitSystem->display( 'error.tpl' , NULL, array( 'display_mode' => 'admin' ));
		die;
	}
	$hotwordlib->add_hotword($_REQUEST["word"], $_REQUEST["url"]);
}

if (isset($_REQUEST["remove"]) && !empty($_REQUEST["remove"])) {
	
	$hotwordlib->remove_hotword($_REQUEST["remove"]);
}

if ( empty( $_REQUEST["sort_mode"] ) ) {
	$sort_mode = 'word_desc';
} else {
	$sort_mode = $_REQUEST["sort_mode"];
}

$gBitSmarty->assign_by_ref('sort_mode', $sort_mode);

// If offset is set use it if not then use offset =0
// use the max_records php variable to set the limit
// if sortMode is not set then use last_modified_desc
if (!isset($_REQUEST["offset"])) {
	$offset = 0;
} else {
	$offset = $_REQUEST["offset"];
}
if (isset($_REQUEST['page'])) {
	$page = &$_REQUEST['page'];
	$offset = ($page - 1) * $max_records;
}
$gBitSmarty->assign_by_ref('offset', $offset);

if (isset($_REQUEST["find"])) {
	$find = $_REQUEST["find"];
} else {
	$find = '';
}

$gBitSmarty->assign('find', $find);

$words = $hotwordlib->list_hotwords($offset, $max_records, $sort_mode, $find);
$cant_pages = ceil($words["cant"] / $max_records);
$gBitSmarty->assign_by_ref('cant_pages', $cant_pages);
$gBitSmarty->assign('actual_page', 1 + ($offset / $max_records));

if ($words["cant"] > ($offset + $max_records)) {
	$gBitSmarty->assign('next_offset', $offset + $max_records);
} else {
	$gBitSmarty->assign('next_offset', -1);
}

// If offset is > 0 then prev_offset
if ($offset > 0) {
	$gBitSmarty->assign('prev_offset', $offset - $max_records);
} else {
	$gBitSmarty->assign('prev_offset', -1);
}

// Get users (list of users)
$gBitSmarty->assign_by_ref('words', $words["data"]);



// Display the template
$gBitSystem->display( 'bitpackage:hotwords/admin_hotwords.tpl', NULL, array( 'display_mode' => 'admin' ));

?>

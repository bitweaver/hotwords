<?php
/**
 * @version  $Header$
 * @package  liberty
 * @subpackage plugins_filter
 */

/**
 * definitions ( guid character limit is 16 chars )
 */
define( 'PLUGIN_GUID_FILTERHOTWORDS', 'filterhotwords' );

global $gLibertySystem, $gBitSystem;

$pluginParams = array(
	'title'               => 'Hotwords',
	'description'         => 'If you are using the hotwords package, you need to enable this filter.',
	'auto_activate'       => TRUE,
	'plugin_type'         => FILTER_PLUGIN,

	// filter functions
	'postparse_function' => 'hotwords_postfilter',
);
$gLibertySystem->registerPlugin( PLUGIN_GUID_FILTERHOTWORDS, $pluginParams );

/**
 * hotwords_postfilter 
 * 
 * @param array $pData 
 * @param array $pFilterHash 
 * @access public
 * @return TRUE on success, FALSE on failure - mErrors will contain reason for failure
 */
function hotwords_postfilter( &$pData, &$pFilterHash ) {
	global $gBitSystem, $gBitSmarty;
	if( $gBitSystem->isPackageActive( 'hotwords' )) {
		require_once( HOTWORDS_PKG_PATH.'BitHotwords.php' );
		$hotwords = new BitHotwords();
		$listHash = array(
			'max_records' => -1
		);
		$words = $hotwords->getList( $listHash );

		// we need to protect text that is already in links
		preg_match_all( "#(<a.*?>.*?</a>)#i", $pData, $protected );
		if( !empty( $protected )) {
			foreach( $protected[0] as $i => $prot ) {
				$key = md5( mt_rand() );
				$replacements[$key] = $protected[0][$i];
				$pData = str_replace( $prot, $key, $pData );
			}
		}

		// now we can do the hotwords thing
		if( !empty( $words ) ) {
			$js = ( $gBitSystem->isFeatureActive( 'hotwords_new_window' )) ? "onkeypress='popUpWin(this.href,'fullScreen',0,0);' onclick='popUpWin(this.href,'fullScreen',0,0);return false;'" : '';

			// extract all links before we start this mahem

			// replace hotwords
			foreach( $words as $data ) {
				// this will preserve the case of the word used in the text.
				$pData = preg_replace( "#([\s\n])(".preg_quote( $data['word'], "#" ).")\b#i", "$1<a href=\"{$data['url']}\" $js>$2</a>", $pData );
			}
		}

		// re-insert the protected text
		if( !empty( $replacements )) {
			foreach( $replacements as $key => $replace ) {
				$pData = str_replace( $key, $replace, $pData );
			}
		}
	}
}
?>

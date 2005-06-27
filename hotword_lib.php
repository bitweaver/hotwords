<?php
/**
 * @version $Header: /cvsroot/bitweaver/_bit_hotwords/Attic/hotword_lib.php,v 1.1.1.1.2.1 2005/06/27 10:55:45 lsces Exp $
 * @package hotwords
 */

/**
 * @version $Header: /cvsroot/bitweaver/_bit_hotwords/Attic/hotword_lib.php,v 1.1.1.1.2.1 2005/06/27 10:55:45 lsces Exp $
 * @package HotwordsLib
 */
class HotwordsLib extends BitBase {
	function HotwordsLib() {				
	BitBase::BitBase();
	}

	function list_hotwords($offset = 0, $maxRecords = -1, $sort_mode = 'word_desc', $find = '') {

		if ($find) {
			$findesc = $this->qstr('%' . strtoupper( $find ) . '%');
			$mid = " where UPPER(`word`) like ?";
			$bindvars = array($findesc);
		} else {
			$mid = '';
			$bindvars = array();
		}

		$query = "select * from `".BIT_DB_PREFIX."tiki_hotwords` $mid order by ".$this->convert_sortmode($sort_mode);
		$query_cant = "select count(*) from `".BIT_DB_PREFIX."tiki_hotwords` $mid";
		$result = $this->query($query,$bindvars,$maxRecords,$offset);
		$cant = $this->getOne($query_cant,$bindvars);
		$ret = array();

		while ($res = $result->fetchRow()) {
			$ret[] = $res;
		}

		$retval = array();
		$retval["data"] = $ret;
		$retval["cant"] = $cant;
		return $retval;
	}

	function add_hotword($word, $url) {
		$word = addslashes($word);

		$url = addslashes($url);
		$query = "delete from `".BIT_DB_PREFIX."tiki_hotwords` where `word`=?";
		$result = $this->query($query,array($word));
		$query = "insert into `".BIT_DB_PREFIX."tiki_hotwords`(`word`,`url`) values(?,?)";
		$result = $this->query($query,array($word,$url));
		return true;
	}

	function remove_hotword($word) {
		$query = "delete from `".BIT_DB_PREFIX."tiki_hotwords` where `word`=?";
		$result = $this->query($query,array($word));
	}

	// Replace hotwords in given line
	function replace_hotwords($line, $words) {
		global $gBitSystem;
		$hotw_nw = ($gBitSystem->isFeatureActive( 'feature_hotwords_nw' )) ? "onkeypress='popUpWin(this.href,'fullScreen',0,0);' onclick='popUpWin(this.href,'fullScreen',0,0);return false;'" : '';

		// Replace Hotwords
		foreach ($words as $word => $url) {
			// \b is a word boundary, \s is a space char
			$line = preg_replace("/^$word(\b)/i","<a href=\"$url\" $hotw_nw>$word</a>$1",$line);
			$line = preg_replace("/\s$word(\b)/i"," <a href=\"$url\" $hotw_nw>$word</a>$1",$line);
		}

		return $line;
	}

	function get_hotwords() {
		static $retHotwords = NULL;
		if( !isset( $retHotwords ) ) {
			$query = "select * from `".BIT_DB_PREFIX."tiki_hotwords`";
			$result = $this->query($query, array());
			$retHotwords = array();
			while ($res = $result->fetchRow()) {
				$retHotwords[$res["word"]] = $res["url"];
			}
		}
		return $retHotwords;
	}

}

global $hotwordlib;
$hotwordlib = new HotwordsLib();

?>

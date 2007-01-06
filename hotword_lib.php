<?php
/**
 * @version $Header: /cvsroot/bitweaver/_bit_hotwords/Attic/hotword_lib.php,v 1.8 2007/01/06 09:46:15 squareing Exp $
 * @package hotwords
 */

/**
 * @version $Header: /cvsroot/bitweaver/_bit_hotwords/Attic/hotword_lib.php,v 1.8 2007/01/06 09:46:15 squareing Exp $
 * @package hotwords
 */
class HotwordsLib extends BitBase {
	function HotwordsLib() {				
	BitBase::BitBase();
	}
	/**
	 * List hotwords
	 * @return Data array of hotwords
	 * [data] is the actual array of words
	 * [cant] is a count of the number of entries in [data]
	 */
	function list_hotwords($offset = 0, $max_records = -1, $sort_mode = 'word_desc', $find = '') {

		if ($find) {
			$findesc = $this->mDb->qstr('%' . strtoupper( $find ) . '%');
			$mid = " where UPPER(`word`) like ?";
			$bindvars = array($findesc);
		} else {
			$mid = '';
			$bindvars = array();
		}

		$query = "select * from `".BIT_DB_PREFIX."hotwords` $mid order by ".$this->mDb->convertSortmode($sort_mode);
		$query_cant = "select count(*) from `".BIT_DB_PREFIX."hotwords` $mid";
		$result = $this->mDb->query($query,$bindvars,$max_records,$offset);
		$cant = $this->mDb->getOne($query_cant,$bindvars);
		$ret = array();

		while ($res = $result->fetchRow()) {
			$ret[] = $res;
		}

		$retval = array();
		$retval["data"] = $ret;
		$retval["cant"] = $cant;
		return $retval;
	}

	/**
	 * Add hotword
	 * 
	 * @param word		Word to be replaced by link
	 * @param url		Url to be used with that word 
	 */
	function add_hotword($word, $url) {
		$word = addslashes($word);

		$url = addslashes($url);
		$query = "delete from `".BIT_DB_PREFIX."hotwords` where `word`=?";
		$result = $this->mDb->query($query,array($word));
		$query = "insert into `".BIT_DB_PREFIX."hotwords`(`word`,`url`) values(?,?)";
		$result = $this->mDb->query($query,array($word,$url));
		return true;
	}

	/**
	 * Remove hotword
	 * 
	 * @param word		Word to be removed
	 */
	function remove_hotword($word) {
		$query = "delete from `".BIT_DB_PREFIX."hotwords` where `word`=?";
		$result = $this->mDb->query($query,array($word));
	}

	/**
	 * Replace hotword
	 *
	 * @param line		Text to be modified by adding links
	 * @param words		Words to be replaced by links 
	 * @return String with hotword link
	 */
	function replace_hotwords($line, $words) {
		global $gBitSystem;
		$hotw_nw = ($gBitSystem->isFeatureActive( 'hotwords_new_window' )) ? "onkeypress='popUpWin(this.href,'fullScreen',0,0);' onclick='popUpWin(this.href,'fullScreen',0,0);return false;'" : '';

		// Replace Hotwords
		foreach ($words as $word => $url) {
			// \b is a word boundary, \s is a space char
			$line = preg_replace("/^$word(\b)/i","<a href=\"$url\" $hotw_nw>$word</a>$1",$line);
			$line = preg_replace("/\s$word(\b)/i"," <a href=\"$url\" $hotw_nw>$word</a>$1",$line);
		}

		return $line;
	}

	/**
	 * Get hotwords
	 *
	 * @return Array of hotwords
	 */
	function get_hotwords() {
		static $retHotwords = NULL;
		if( !isset( $retHotwords ) ) {
			$query = "select * from `".BIT_DB_PREFIX."hotwords`";
			$result = $this->mDb->query($query, array());
			$retHotwords = array();
			while ($res = $result->fetchRow()) {
				$retHotwords[$res["word"]] = $res["url"];
			}
		}
		return $retHotwords;
	}

}

/**
 * @global HotwordsLib Hotwords library
 */
global $hotwordlib;
$hotwordlib = new HotwordsLib();

?>

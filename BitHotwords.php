<?php
/**
 * @version $Header$
 * @package hotwords
 */

/**
 * @version $Header$
 * @package hotwords
 */
class BitHotwords extends BitBase {

	/**
	 * store 
	 * 
	 * @param array $pWord Word that should be replaced with link to $pUrl
	 * @param array $pUrl Link $pWord points to
	 * @access public
	 * @return TRUE on success, FALSE on failure - mErrors will contain reason for failure
	 */
	function store( $pWord, $pUrl ) {
		if( !empty( $pWord ) && !empty( $pUrl )) {
			$query = "DELETE FROM `".BIT_DB_PREFIX."hotwords` where `word` = ?";
			$result = $this->mDb->query( $query,array( $pWord ));
			$query = "INSERT INTO `".BIT_DB_PREFIX."hotwords`( `word`, `url` ) values( ?, ? )";
			$result = $this->mDb->query( $query,array( $pWord, $pUrl ));
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * getList 
	 * 
	 * @param array $pListHash 
	 * @access public
	 * @return TRUE on success, FALSE on failure - mErrors will contain reason for failure
	 */
	function getList( &$pListHash ) {
		if( empty( $pListHash['sort_mode'] )) {
			$pListHash['sort_mode'] = 'word_desc';
		}

		BitBase::prepGetList( $pListHash );
		$whereSql = '';
		$bindVars = $ret = array();

		if( !empty( $pListHash['find'] ) ) {
			$whereSql .= empty( $whereSql ) ? ' WHERE ' : ' AND ';
			$whereSql .= " UPPER( hw.`word` ) LIKE ? ";
			$bindVars[] = '%'.strtoupper( $pListHash['find'] ).'%';
		}

		$query = "
			SELECT hw.`word`, hw.`url`
			FROM `".BIT_DB_PREFIX."hotwords` hw
			$whereSql
			ORDER BY ".$this->mDb->convertSortmode( $pListHash['sort_mode'] );
		$result = $this->mDb->query( $query, $bindVars, $pListHash['max_records'], $pListHash['offset'] );
		while( $aux = $result->fetchRow() ) {
			$ret[] = $aux;
		}

		$query = "
			SELECT COUNT( hw.`word` )
			FROM `".BIT_DB_PREFIX."hotwords` hw
			$whereSql";
		$pListHash['cant'] = $this->mDb->getOne( $query, $bindVars );
		BitBase::postGetList( $pListHash );

		return $ret;
	}

	/**
	 * expunge 
	 * 
	 * @param array $pWord Word we want to remove from the database
	 * @access public
	 * @return TRUE on success, FALSE on failure - mErrors will contain reason for failure
	 */
	function expunge( $pWord ) {
		$query = "DELETE FROM `".BIT_DB_PREFIX."hotwords` WHERE `word` = ?";
		$this->mDb->query( $query, array( $pWord ));
		return TRUE;
	}
}
?>

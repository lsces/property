<?php
/**
 * @version $Header: /cvsroot/bitweaver/_bit_property/Property.php,v 1.13 2010/04/18 02:27:23 wjames5 Exp $
 *
 * Copyright ( c ) 2006 bitweaver.org
 * All Rights Reserved. See below for details and a complete list of authors.
 * Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details
 *
 * @package property
 */

/**
 * required setup
 */
require_once( LIBERTY_PKG_PATH.'LibertyContent.php' );		// Property base class
require_once( NLPG_PKG_PATH.'lib/phpcoord-2.3.php' );

define( 'PROPERTY_CONTENT_TYPE_GUID', 'property' );

/**
 * @package property
 */
class Property extends LibertyContent {
	var $mPropertyId;
	var $mParentId;

	/**
	 * Constructor 
	 * 
	 * Build a Property object based on LibertyContent
	 * @param integer Property Id identifer
	 * @param integer Base content_id identifier 
	 */
	function Property( $pPropertyId = NULL, $pContentId = NULL ) {
		LibertyContent::LibertyContent();
		$this->registerContentType( PROPERTY_CONTENT_TYPE_GUID, array(
				'content_type_guid' => PROPERTY_CONTENT_TYPE_GUID,
				'content_name' => 'Property Entry',
				'handler_class' => 'Property',
				'handler_package' => 'property',
				'handler_file' => 'Property.php',
				'maintainer_url' => 'http://lsces.co.uk'
			) );
		$this->mPropertyId = (int)$pPropertyId;
		$this->mContentId = (int)$pContentId;
		$this->mContentTypeGuid = PROPERTY_CONTENT_TYPE_GUID;
				// Permission setup
		$this->mViewContentPerm  = 'p_property_view';
		$this->mCreateContentPerm  = 'p_property_create';
		$this->mUpdateContentPerm  = 'p_property_update';
		$this->mAdminContentPerm = 'p_property_admin';
		
	}

	/**
	 * Load a Property content Item
	 *
	 * (Describe Property object here )
	 */
	function load($pContentId = NULL) {
		if ( $pContentId ) $this->mContentId = (int)$pContentId;
		if( $this->verifyId( $this->mPropertyId ) ) {
 			$query = "select pro.*, lc.*, a.*,
				uue.`login` AS modifier_user, uue.`real_name` AS modifier_real_name,
				uuc.`login` AS creator_user, uuc.`real_name` AS creator_real_name
				FROM `".BIT_DB_PREFIX."property` pro
				INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON ( lc.`content_id` = pro.`content_id` )
				LEFT JOIN `".BIT_DB_PREFIX."users_users` uue ON (uue.`user_id` = lc.`modifier_user_id`)
				LEFT JOIN `".BIT_DB_PREFIX."users_users` uuc ON (uuc.`user_id` = lc.`user_id`)
				WHERE pro.`property_id`=?";
			$result = $this->mDb->query( $query, array( $this->mPropertyId ) );
//				LEFT JOIN `".BIT_DB_PREFIX."contact` ci ON ci.contact_id = pro.owner_id
//				LEFT JOIN `".BIT_DB_PREFIX."contact_address` a ON a.contact_id = pro.address_id
//				LEFT JOIN `".BIT_DB_PREFIX."postcode` p ON p.`postcode` = a.`postcode`

			if ( $result && $result->numRows() ) {
				$this->mInfo = $result->fields;
				$this->mContentId = (int)$result->fields['content_id'];
				$this->mPropertyId = (int)$result->fields['property_id'];
				$this->mParentId = (int)$result->fields['usn'];
				$this->mPropertyName = $result->fields['title'];
				$this->mInfo['creator'] = (isset( $result->fields['creator_real_name'] ) ? $result->fields['creator_real_name'] : $result->fields['creator_user'] );
				$this->mInfo['editor'] = (isset( $result->fields['modifier_real_name'] ) ? $result->fields['modifier_real_name'] : $result->fields['modifier_user'] );
				$this->mInfo['display_url'] = $this->getDisplayUrl();
				$os1 = new OSRef($this->mInfo['x_coordinate'], $this->mInfo['y_coordinate']);
				$ll1 = $os1->toLatLng();
				$this->mInfo['prop_lat'] = $ll1->lat;
				$this->mInfo['prop_lng'] = $ll1->lng;
			}
		}
		LibertyContent::load();
		return;
	}

	/**
	* verify, clean up and prepare data to be stored
	* @param $pParamHash all information that is being stored. will update $pParamHash by reference with fixed array of itmes
	* @return bool TRUE on success, FALSE if store could not occur. If FALSE, $this->mErrors will have reason why
	* @access private
	**/
	function verify( &$pParamHash ) {
		// make sure we're all loaded up if everything is valid
		if( $this->isValid() && empty( $this->mInfo ) ) {
			$this->load( TRUE );
		}

		// It is possible a derived class set this to something different
		if( empty( $pParamHash['content_type_guid'] ) ) {
			$pParamHash['content_type_guid'] = $this->mContentTypeGuid;
		}

		if( !empty( $this->mContentId ) ) {
			$pParamHash['content_id'] = $this->mContentId;
		} else {
			unset( $pParamHash['content_id'] );
		}

		if ( empty( $pParamHash['parent_id'] ) )
			$pParamHash['parent_id'] = $this->mContentId;
			
		// content store
		// check for name issues, first truncate length if too long
		if( empty( $pParamHash['surname'] ) || empty( $pParamHash['forename'] ) )  {
			$this->mErrors['names'] = 'You must enter a forename and surname for this property.';
		} else {
			$pParamHash['title'] = substr( $pParamHash['prefix'].' '.$pParamHash['forename'].' '.$pParamHash['surname'].' '.$pParamHash['suffix'], 0, 160 );
			$pParamHash['content_store']['title'] = $pParamHash['title'];
		}	

		// Secondary store entries
		$pParamHash['property_store']['prefix'] = $pParamHash['prefix'];
		$pParamHash['property_store']['forename'] = $pParamHash['forename'];
		$pParamHash['property_store']['surname'] = $pParamHash['surname'];
		$pParamHash['property_store']['suffix'] = $pParamHash['suffix'];
		$pParamHash['property_store']['organisation'] = $pParamHash['organisation'];

		if ( !empty( $pParamHash['nino'] ) ) $pParamHash['property_store']['nino'] = $pParamHash['nino'];
		if ( !empty( $pParamHash['dob'] ) ) $pParamHash['property_store']['dob'] = $pParamHash['dob'];
		if ( !empty( $pParamHash['eighteenth'] ) ) $pParamHash['property_store']['eighteenth'] = $pParamHash['eighteenth'];
		if ( !empty( $pParamHash['dod'] ) ) $pParamHash['property_store']['dod'] = $pParamHash['dod'];

		return( count( $this->mErrors ) == 0 );
	}

	/**
	* Store property data
	* @param $pParamHash contains all data to store the property
	* @param $pParamHash[title] title of the new property
	* @param $pParamHash[edit] description of the property
	* @return bool TRUE on success, FALSE if store could not occur. If FALSE, $this->mErrors will have reason why
	**/
	function store( &$pParamHash ) {
		if( $this->verify( $pParamHash ) ) {
			// Start a transaction wrapping the whole insert into liberty 

			$this->mDb->StartTrans();
			if ( LibertyContent::store( $pParamHash ) ) {
				$table = BIT_DB_PREFIX."property";

				// mContentId will not be set until the secondary data has commited 
				if( $this->verifyId( $this->mPropertyId ) ) {
					if( !empty( $pParamHash['property_store'] ) ) {
						$result = $this->mDb->associateUpdate( $table, $pParamHash['property_store'], array( "content_id" => $this->mContentId ) );
					}
				} else {
					$pParamHash['property_store']['content_id'] = $pParamHash['content_id'];
					if( isset( $pParamHash['property_id'] ) && is_numeric( $pParamHash['property_id'] ) ) {
						$pParamHash['property_store']['usn'] = $pParamHash['property_id'];
					} else {
						$pParamHash['property_store']['usn'] = $this->mDb->GenID( 'property_id_seq');
					}	

					$pParamHash['property_store']['parent_id'] = $pParamHash['property_store']['content_id'];
					$this->mPropertyId = $pParamHash['property_store']['content_id'];
					$this->mParentId = $pParamHash['property_store']['parent_id'];
					$this->mContentId = $pParamHash['content_id'];
					$result = $this->mDb->associateInsert( $table, $pParamHash['property_store'] );
				}
				// load before completing transaction as firebird isolates results
				$this->load();
				$this->mDb->CompleteTrans();
			} else {
				$this->mDb->RollbackTrans();
				$this->mErrors['store'] = 'Failed to store this property.';
			}
		}
		return( count( $this->mErrors ) == 0 );
	}

	/**
	 * Delete content object and all related records
	 */
	function expunge()
	{
		$ret = FALSE;
		if ($this->isValid() ) {
			$this->mDb->StartTrans();
			$query = "DELETE FROM `".BIT_DB_PREFIX."property` WHERE `content_id` = ?";
			$result = $this->mDb->query($query, array($this->mContentId ) );
			$query = "DELETE FROM `".BIT_DB_PREFIX."property_xref` WHERE `content_id` = ?";
			$result = $this->mDb->query($query, array($this->mContentId ) );
			if (LibertyContent::expunge() ) {
			$ret = TRUE;
				$this->mDb->CompleteTrans();
			} else {
				$this->mDb->RollbackTrans();
			}
		}
		return $ret;
	}
    
	/**
	 * Returns Request_URI to a Property content object
	 *
	 * @param string name of
	 * @param array different possibilities depending on derived class
	 * @return string the link to display the page.
	 */
	function getDisplayUrl( $pContentId=NULL ) {
		global $gBitSystem;
		if( empty( $pContentId ) ) {
			$pContentId = $this->mContentId;
		}

		return PROPERTY_PKG_URL.'index.php?content_id='.$pContentId;
	}

	/**
	 * Returns HTML link to display a Property object
	 * 
	 * @param string Not used ( generated locally )
	 * @param array mInfo style array of content information
	 * @return the link to display the page.
	 */
	function getDisplayLink( $pText, $aux ) {
		if ( $this->mContentId != $aux['content_id'] ) $this->load($aux['content_id']);

		if (empty($this->mInfo['content_id']) ) {
			$ret = '<a href="'.$this->getDisplayUrl($aux['content_id']).'">'.$aux['title'].'</a>';
		} else {
			$ret = '<a href="'.$this->getDisplayUrl($aux['content_id']).'">'."Property - ".$this->mInfo['title'].'</a>';
		}
		return $ret;
	}

	/**
	 * Returns title of an Property object
	 *
	 * @param array mInfo style array of content information
	 * @return string Text for the title description
	 */
	function getTitle( $pHash = NULL ) {
		$ret = NULL;
		if( empty( $pHash ) ) {
			$pHash = &$this->mInfo;
		} else {
			if ( $this->mContentId != $pHash['content_id'] ) {
				$this->load($pHash['content_id']);
				$pHash = &$this->mInfo;
			}
		}

		if( !empty( $pHash['title'] ) ) {
			$ret = "Property - ".$this->mInfo['title'];
		} elseif( !empty( $pHash['content_name'] ) ) {
			$ret = $pHash['content_name'];
		}
		return $ret;
	}

	/**
	 * Returns list of property entries
	 *
	 * @param integer 
	 * @return array of property entries
	 */
	function getList( &$pParamHash ) {
		global $gBitSystem, $gBitUser;
		
		if ( empty( $pParamHash['sort_mode'] ) ) {
			if ( empty( $_REQUEST["sort_mode"] ) ) {
				$pParamHash['sort_mode'] = 'surname_asc';
			} else {
			$pParamHash['sort_mode'] = $_REQUEST['sort_mode'];
			}
		}
		
		LibertyContent::prepGetList( $pParamHash );

		$findSql = '';
		$selectSql = '';
		$joinSql = '';
		$whereSql = '';
		$bindVars = array();
		$type = 'surname';
		
		// this will set $find, $sort_mode, $max_records and $offset
		extract( $pParamHash );

		if( isset( $find_org ) and is_string( $find_org ) and $find_org <> '' ) {
			$whereSql .= " AND UPPER( ci.`organisation` ) like ? ";
			$bindVars[] = '%' . strtoupper( $find_org ). '%';
			$type = 'organisation';
			$pParamHash["listInfo"]["ihash"]["find_org"] = $find_org;
		}
		if( isset( $find_name ) and is_string( $find_name ) and $find_name <> '' ) {
		    $split = preg_split('|[,. ]|', $find_name, 2);
			$whereSql .= " AND UPPER( ci.`surname` ) STARTING ? ";
			$bindVars[] = strtoupper( $split[0] );
		    if ( array_key_exists( 1, $split ) ) {
				$split[1] = trim( $split[1] );
				$whereSql .= " AND UPPER( ci.`forename` ) STARTING ? ";
				$bindVars[] = strtoupper( $split[1] );
			}
			$pParamHash["listInfo"]["ihash"]["find_name"] = $find_name;
		}
		if( isset( $find_street ) and is_string( $find_street ) and $find_street <> '' ) {
			$whereSql .= " AND UPPER( a.`street` ) like ? ";
			$bindVars[] = '%' . strtoupper( $find_street ). '%';
			$pParamHash["listInfo"]["ihash"]["find_street"] = $find_street;
		}
		if( isset( $find_org ) and is_string( $find_postcode ) and $find_postcode <> '' ) {
			$whereSql .= " AND UPPER( `a.postcode` ) LIKE ? ";
			$bindVars[] = '%' . strtoupper( $find_postcode ). '%';
			$pParamHash["listInfo"]["ihash"]["find_postcode"] = $find_postcode;
		}
		$query = "SELECT pro.*, a.UPRN, lc.*, ci.*, a.POSTCODE, a.SAO, a.PAO, a.NUMBER, a.STREET, a.LOCALITY, a.TOWN, a.COUNTY, pro.parent_id as uprn,
			(SELECT COUNT(*) FROM `".BIT_DB_PREFIX."property_xref` x WHERE x.content_id = ci.contact_id ) AS links, 
			(SELECT COUNT(*) FROM `".BIT_DB_PREFIX."task_ticket` e WHERE e.usn = ci.usn ) AS enquiries $selectSql 
			FROM `".BIT_DB_PREFIX."property` pro
			LEFT JOIN `".BIT_DB_PREFIX."liberty_content` lc ON lc.content_id = pro.content_id
			LEFT JOIN `".BIT_DB_PREFIX."contact_address` a ON a.contact_id = pro.address_id
			LEFT JOIN `".BIT_DB_PREFIX."contact` ci ON ci.contact_id = pro.owner_id
			$findSql
			$joinSql 
			WHERE ci.`".$type."` <> '' $whereSql ORDER BY ".$this->mDb->convertSortmode( $sort_mode );
		$query_cant = "SELECT COUNT( * )
			FROM `".BIT_DB_PREFIX."property` pro
			LEFT JOIN `".BIT_DB_PREFIX."contact_address` a ON a.contact_id = pro.address_id
			LEFT JOIN `".BIT_DB_PREFIX."contact` ci ON ci.contact_id = pro.owner_id $findSql
			$joinSql WHERE ci.`".$type."` <> '' $whereSql ";
		$result = $this->mDb->query( $query, $bindVars, $max_records, $offset );
		$ret = array();
		while( $res = $result->fetchRow() ) {
			if (!empty($parse_split)) {
				$res = array_merge($this->parseSplit($res), $res);
			}
			$ret[] = $res;
		}
		$pParamHash["cant"] = $this->mDb->getOne( $query_cant, $bindVars );

		LibertyContent::postGetList( $pParamHash );
		return $ret;
	}

	/**
	* Returns titles of the property type table
	*
	* @return array List of property type names from the property mamanger in alphabetical order
	*/
	function getPropertyTypeList() {
		$query = "SELECT `type_name` FROM `property_type`
				  ORDER BY `type_name`";
		$result = $this->mDb->query($query);
		$ret = array();

		while ($res = $result->fetchRow()) {
			$ret[] = trim($res["type_name"]);
		}
		return $ret;
	}

	/**
	 * PropertyRecordLoad( $data ); 
	 * phx seurity file import 
	 */
	function PropertyRecordLoad( &$data ) {
		$table = BIT_DB_PREFIX."property";
//		$atable = BIT_DB_PREFIX."contact_address";

		$usn = 10000 + $data[0];
		$pDataHash['property_store']['content_id'] = $data[0];
		$pDataHash['address_store']['content_id'] = $data[0];
		$pDataHash['property_store']['property_id'] = $usn;
		$pDataHash['address_store']['usn'] = $usn;
		$pDataHash['address_store']['organisation'] = $data[1];
		if ( $data[2] == 'D' ) $type = 0; else $type = 1;
		$pDataHash['address_store']['sao'] = '';
		$pDataHash['address_store']['pao'] = '';
		$pDataHash['address_store']['number'] = '';
		$pDataHash['address_store']['street'] = $data[4];
		$pDataHash['address_store']['locality'] = $data[5];
		$pDataHash['address_store']['town'] = $data[6];
		$pDataHash['address_store']['county'] = $data[7];
		$pDataHash['property_store']['postcode'] = $data[8];
		$pDataHash['address_store']['postcode'] = $data[8];

		$this->mDb->StartTrans();
		$this->mContentId = 0;
		$pDataHash['content_id'] = 0;
		if ( LibertyContent::store( $pDataHash ) ) {
			$pDataHash['property_store']['content_id'] = $pDataHash['content_id'];
			$pDataHash['address_store']['content_id'] = $pDataHash['content_id'];
			
			$result = $this->mDb->associateInsert( $table, $pDataHash['property_store'] );
//			$result = $this->mDb->associateInsert( $atable, $pDataHash['address_store'] );
			$this->mDb->CompleteTrans();				
		} else {
			$this->mDb->RollbackTrans();
			$this->mErrors['store'] = 'Failed to store this property.';
		}				
		return( count( $this->mErrors ) == 0 ); 
	}
	
	/**
	 * Delete property object and all related records
	 */
	function DataExpunge()
	{
		$ret = FALSE;
		$query = "DELETE FROM `".BIT_DB_PREFIX."property`";
		$result = $this->mDb->query( $query );
//		$query = "DELETE FROM `".BIT_DB_PREFIX."contact_address`";
//		$result = $this->mDb->query( $query );
		$query = "DELETE FROM `".BIT_DB_PREFIX."property_xref`";
		$result = $this->mDb->query( $query );
		return $ret;
	}

	/**
	 * loadProperty( &$pParamHash );
	 * Get property record 
	 */
	function loadProperty( &$pParamHash = NULL ) {
		if( $this->isValid() ) {
		$sql = "SELECT pro.*, ci.*, a.*, p.*
			FROM `".BIT_DB_PREFIX."property` pro 
			LEFT JOIN `".BIT_DB_PREFIX."content` ci ON ci.contact_id = pro.owner_id
			LEFT JOIN `".BIT_DB_PREFIX."content_address` a ON a.contact_id = pro.address_id
			LEFT JOIN `".BIT_DB_PREFIX."postcode` p ON p.`postcode` = a.`postcode`
			WHERE ci.`content_id` = ?";
			if( $rs = $this->mDb->query( $sql, array( $this->mContentId ) ) ) {
				if(	$this->mInfo = $rs->fields ) {
/*					if(	$this->mInfo['local_custodian_code'] == 0 ) {
						global $gBitSystem;
						$gBitSystem->fatalError( tra( 'You do not have permission to access this property record' ), 'error.tpl', tra( 'Permission denied.' ) );
					}
*/

					$sql = "SELECT x.`last_update_date`, x.`source`, x.`cross_reference` 
							FROM `".BIT_DB_PREFIX."property_xref` x
							WHERE x.content_id = ?";
/* Link to legacy system
							CASE
							WHEN x.`source` = 'POSTFIELD' THEN (SELECT `USN` FROM `".BIT_DB_PREFIX."caller` c WHERE ci.`caller_id` = x.`cross_reference`)
							ELSE '' END AS USN 
							
 */

					$result = $this->mDb->query( $sql, array( $this->mContentId ) );

					while( $res = $result->fetchRow() ) {
						$this->mInfo['xref'][] = $res;
						if ( $res['source'] == 'POSTFIELD' ) $ticket[] = $res['cross_reference'];
					}
					if ( isset( $ticket ) )
					{ $sql = "SELECT t.* FROM `".BIT_DB_PREFIX."task_ticket` t 
							WHERE t.caller_id IN(". implode(',', array_fill(0, count($ticket), '?')) ." )";
						$result = $this->mDb->query( $sql, $ticket );
						while( $res = $result->fetchRow() ) {
							$this->mInfo['tickets'][] = $res;
						}
					}
					$os1 = new OSRef($this->mInfo['x_coordinate'], $this->mInfo['y_coordinate']);
					$ll1 = $os1->toLatLng();
					$this->mInfo['prop_lat'] = $ll1->lat;
					$this->mInfo['prop_lng'] = $ll1->lng;
				} else {
					global $gBitSystem;
					$gBitSystem->fatalError( tra( 'Property record does not exist' ), 'error.tpl', tra( 'Not found.' ) );
				}
			}
		}
		return( count( $this->mInfo ) );
	}


	/**
	 * getXrefList( &$pParamHash );
	 * Get list of xref records for this property record
	 */
	function loadXrefList() {
		if( $this->isValid() && empty( $this->mInfo['xref'] ) ) {
		
			$sql = "SELECT x.`last_update_date`, x.`source`, 
				CASE
				WHEN x.`xorder` = 0 THEN s.`cross_ref_title`
				ELSE s.`cross_ref_title` || '-' || x.`xorder` END
				AS source_title,
				x.`xref` AS cross_reference, x.`data`, x.`xref_key` AS usn 
				FROM `".BIT_DB_PREFIX."property_xref` x
				JOIN `".BIT_DB_PREFIX."property_xref_source` s 
				ON s.`source` = x.`source`
				WHERE x.content_id = ?
				ORDER BY x.`source`, x.`xorder`";

			$result = $this->mDb->query( $sql, array( $this->mContentId ) );

			while( $res = $result->fetchRow() ) {
				$this->mInfo['xref'][] = $res;
				if ( $res['source'] == 'POSTFIELD' ) $caller[] = $res['cross_reference'];
			}

			$sql = "SELECT t.* FROM `".BIT_DB_PREFIX."task_ticket` t 
				WHERE t.usn = ?";
			$result = $this->mDb->query( $sql, array( '9000000001' ) ); //$this->mPropertyId ) );
			while( $res = $result->fetchRow() ) {
				$this->mInfo['tickets'][] = $res;
			}
		}
	}

}
?>

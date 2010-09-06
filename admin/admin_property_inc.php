<?php

// $Header: /cvsroot/bitweaver/_bit_property/admin/admin_property_inc.php,v 1.3 2009/10/01 14:16:59 wjames5 Exp $

// All Rights Reserved. See below for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See http://www.gnu.org/copyleft/lesser.html for details.

include_once( PROPERTY_PKG_PATH.'Property.php' );

$formPropertyListFeatures = array(
	"property_list_id" => array(
		'label' => 'Property Number',
	),
	"property_list_title" => array(
		'label' => 'Property Title',
	),
	"property_list_address" => array(
		'label' => 'Address',
	),
	"property_list_contact_phone" => array(
		'label' => 'Contact Phone',
	),
	"property_list_edit_details" => array(
		'label' => 'Creation and editing details',
		'help' => 'Enable the record modification data in the property list. Useful to allow checking when deatils were last changed.',
	),
	"property_list_last_modified" => array(
		'label' => 'Last Modified',
		'help' => 'Can be selected to enable filter button, without enabling the details section to allow fast checking of the last property records that have been modified.',
	),
);
$gBitSmarty->assign( 'formPropertyListFeatures',$formPropertyListFeatures );

if (isset($_REQUEST["propertylistfeatures"])) {
	
	foreach( $formPropertyListFeatures as $item => $data ) {
		simple_set_toggle( $item, PROPERTY_PKG_NAME );
	}
}

?>

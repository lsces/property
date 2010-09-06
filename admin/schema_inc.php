<?php
$tables = array(

'property' => "
  CONTENT_ID I8 NOTNULL,
  PROPERTY_ID I8 NOTNULL,
  PARENT_ID I8,
  ADDRESS_ID I8,
  OWNER_ID I8,
  POSTCODE C(10),
  LAST_UPDATE_DATE T DEFAULT 'NOW'
  ",

'property_xref' => "
  content_id I8 NOTNULL,
  xref_key C(14),
  start_date T DEFAULT 'NOW',
  last_update_date T DEFAULT 'NOW',
  entry_date T DEFAULT 'NOW',
  end_date T,
  source C(20) PRIMARY,
  cross_reference C(22) PRIMARY,
  data X
  ",

'property_type' => "
  property_type_id I4 PRIMARY,
  type_name	C(64)
",

'property_xref_source' => "
  source C(6) PRIMARY,
  cross_ref_title C(64),
  cross_ref_href C(256),
  data X
  ",

);

global $gBitInstaller;

foreach( array_keys( $tables ) AS $tableName ) {
	$gBitInstaller->registerSchemaTable( PROPERTY_PKG_NAME, $tableName, $tables[$tableName] );
}

$gBitInstaller->registerPackageInfo( PROPERTY_PKG_NAME, array(
	'description' => "Base Property management package with property xref and address books",
	'license' => '<a href="http://www.gnu.org/licenses/licenses.html#LGPL">LGPL</a>',
) );

// ### Indexes
$indices = array (
	'property_property_id_idx' => array( 'table' => 'property', 'cols' => 'property_id', 'opts' => NULL ),
);
$gBitInstaller->registerSchemaIndexes( PROPERTY_PKG_NAME, $indices );

// ### Sequences
$sequences = array (
	'property_id_seq' => array( 'start' => 1 ),
	'property_xref_seq' => array( 'start' => 1 ),
);
$gBitInstaller->registerSchemaSequences( PROPERTY_PKG_NAME, $sequences );

// ### Defaults

// ### Default User Permissions
$gBitInstaller->registerUserPermissions( PROPERTY_PKG_NAME, array(
	array('p_property_view', 'Can browse the Property List', 'basic', PROPERTY_PKG_NAME),
	array('p_property_update', 'Can update the Property List content', 'registered', PROPERTY_PKG_NAME),
	array('p_property_create', 'Can create a new Property List entry', 'registered', PROPERTY_PKG_NAME),
	array('p_property_admin', 'Can admin Property List', 'admin', PROPERTY_PKG_NAME),
	array('p_property_expunge', 'Can remove a Property entry', 'editors', PROPERTY_PKG_NAME)
) );

// ### Default Preferences
$gBitInstaller->registerPreferences( PROPERTY_PKG_NAME, array(
	array( PROPERTY_PKG_NAME, 'property_default_ordering','title_desc'),
	array( PROPERTY_PKG_NAME, 'property_list_id','y'),
	array( PROPERTY_PKG_NAME, 'property_list_title','y'),
	array( PROPERTY_PKG_NAME, 'property_list_address','y'),
	array( PROPERTY_PKG_NAME, 'property_list_contact_phone','y'),
	array( PROPERTY_PKG_NAME, 'property_list_user','y'),
) );

$gBitInstaller->registerSchemaDefault( PROPERTY_PKG_NAME, array(
"INSERT INTO `".BIT_DB_PREFIX."property_type` VALUES (0, 'Personal')",
"INSERT INTO `".BIT_DB_PREFIX."property_type` VALUES (1, 'Business')",

"INSERT INTO `".BIT_DB_PREFIX."property_xref_source`( `source`, `cross_ref_title`, `cross_ref_href` )  VALUES ('0' , 'Free format information', '../property/?xref=')",
"INSERT INTO `".BIT_DB_PREFIX."property_xref_source`( `source`, `cross_ref_title`, `cross_ref_href` )  VALUES ('#R', 'Residential Address', '../contact/?contact_id=')",
"INSERT INTO `".BIT_DB_PREFIX."property_xref_source`( `source`, `cross_ref_title`, `cross_ref_href` )  VALUES ('#T', 'Tenant Address', '../contact/?contact_id=')",
"INSERT INTO `".BIT_DB_PREFIX."property_xref_source`( `source`, `cross_ref_title`, `cross_ref_href` )  VALUES ('#C', 'Correspondence Address', '../contact/?contact_id=')",
"INSERT INTO `".BIT_DB_PREFIX."property_xref_source`( `source`, `cross_ref_title`, `cross_ref_href` )  VALUES ('#O', 'Owner Address', '../contact/?contact_id=')",
"INSERT INTO `".BIT_DB_PREFIX."property_xref_source`( `source`, `cross_ref_title`, `cross_ref_href` )  VALUES ('#K', 'Keyholder', '../contact/?contact_id=')",
"INSERT INTO `".BIT_DB_PREFIX."property_xref_source`( `source`, `cross_ref_title`, `cross_ref_href` )  VALUES ('#A', 'Alarm Maintainer', '../contact/?contact_id=')",
"INSERT INTO `".BIT_DB_PREFIX."property_xref_source`( `source`, `cross_ref_title`, `cross_ref_href` )  VALUES ('HBEN', 'Housing Benefit', '../nlpg/?uprn=')",
"INSERT INTO `".BIT_DB_PREFIX."property_xref_source`( `source`, `cross_ref_title`, `cross_ref_href` )  VALUES ('CTAX', 'Council Tax', '../nlpg/?uprn=')",
"INSERT INTO `".BIT_DB_PREFIX."property_xref_source`( `source`, `cross_ref_title`, `cross_ref_href` )  VALUES ('NNDR', 'National Non-domestic Rates', '../nlpg/?uprn=')",
"INSERT INTO `".BIT_DB_PREFIX."property_xref_source`( `source`, `cross_ref_title`, `cross_ref_href` )  VALUES ('ER', 'Electoral Roll', '../nlpg/?uprn=')",
"INSERT INTO `".BIT_DB_PREFIX."property_xref_source`( `source`, `cross_ref_title`, `cross_ref_href` )  VALUES ('CON', 'Contact', '../contact/?contact_id=')",
"INSERT INTO `".BIT_DB_PREFIX."property_xref_source`( `source`, `cross_ref_title`, `cross_ref_href` )  VALUES ('ALARM', 'Alarm System', '../contact/?contact_id=')",
) );


?>

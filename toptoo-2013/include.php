<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>
<?
// ��������� ������
//
// ���� �� ����� URL ��� ��������, �� �������
if ( !strlen($URL) )
	return;

// ����� �������� ����. �� ��������� 1 ���.
if( isset($CACHE_TIME) && intval($CACHE_TIME) >= 0 ) {
	$CACHE_TIME = intval($CACHE_TIME);
} else {
	$CACHE_TIME = 3600;
};

$obCache = new CPHPCache;
$cache_id = md5($URL);

// ���� ��� ���� � �� ��� �� �����, ��

$includeLoaded = true;

if( $obCache->InitCache( $CACHE_TIME, $cache_id, "/") ):
    // �������� �������������� ����������
    $vars = $obCache->GetVars();
    $INCLUDE = $vars["INCLUDE"];
else:
    // ����� ��������� ���� � �����
    $opts = array('http' =>
      array(
        'method'  => 'GET',
        'timeout' => 10
      )
    );
    $context  = stream_context_create($opts);
    $INCLUDE = file_get_contents($URL, false, $context);
    if ( !strlen( $INCLUDE ) ) { // ���� �� ������� ��������� ���� �� 10 �
      $includeLoaded = false;
      if ( $obCache->InitCache( 30000000, $cache_id, "/") ) { // �� ����� ��� �� ������������� ����
        $vars = $obCache->GetVars();
        $INCLUDE = $vars["INCLUDE"];
      }
    };
endif;

if( $obCache->StartDataCache() ):
    // ������� ���������� �����
    print $INCLUDE;
    if ( $includeLoaded ) {
      // ���������� �������������� ���������������� ����� � ���� ����
      // ������ � �������������� ����������
      $obCache->EndDataCache(array(
          "INCLUDE"	=> $INCLUDE
          ));
    }
endif;

unset($cache_id, $INCLUDE, $URL, $vars, $CACHE_TIME, $obCache, $opts, $context, $includeLoaded);
?>
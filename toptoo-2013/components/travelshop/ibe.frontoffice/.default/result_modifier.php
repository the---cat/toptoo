<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); //trace($arResult);
//trace($arResult['ERROR']);
//trace($_SESSION['deeplink_level'] );
//trace($arParams['IBE_SECONDARY_CALL'])?>
<? include( GetLangFileName( $_SERVER["DOCUMENT_ROOT"]."/bitrix/templates/".SITE_TEMPLATE_ID."/components/travelshop/ibe.frontoffice/.default"."/lang/", "/template.php" ) ); ?>
<?
/* //�� ������� ��������� �������� �������� ��� ��������� � ��� ������������� ������
if ( $arResult["processor"] == "form_order" // ���� ������� ��� - ����� ������
    && true != $arParams["IBE_SECONDARY_CALL"]
    //&& !preg_match( "/^\/([^\/]+\/)+$/i", $APPLICATION->GetCurDir() ) // � ������� �� �� ���-��������
    && ( "http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"] == $_SERVER["HTTP_REFERER"] // � ������� �� ����������
          || $_SERVER["HTTP_REFERER"] == "" // ��� ����� ��������
          || strlen($arResult["display_error"]) // ��� �������� ������
      )
    && strlen($arParams["REDIRECT_HOST"]) ) { // � ������� ��������
  $host = $arParams["REDIRECT_HOST"];
  $path = $arParams["REDIRECT_PATH"] ? $arParams["REDIRECT_PATH"] : "/";
  $data = "";
  if ( is_set($arResult["depart"])
        && is_set($arResult["arrival"])
        && is_set($arResult["d_to"])
        && is_set($arResult["d_back"])
    ) {
    $data = "depart=" . $arResult["depart"]
            . "&arrival=" . $arResult["arrival"]
            . "&to=" . $arResult["d_to"]
            . "&back=" . $arResult["d_back"];
  }
  if ( strlen($arResult["display_error"]) ) {
    $data .= ( strlen($data) ? "&" : "" ) . "error_text=" . $arResult["display_error"];
  }

  header("HTTP/1.1 301 Moved Permanently"); // ������ 301-��������
  header("Location: http://" . $host . $path . ( strlen( $data ) ? "?" . urlencode( $APPLICATION->ConvertCharset($data, SITE_CHARSET, "UTF-8") ) : "" ) );

  exit();
}
*/

//echo $arResult['processor'];
if ( $arResult['processor'] == 'offer' ){
  foreach($arResult['OFFER'] as &$offer){
    echo $MESS['TS_FRONTOFFICE_' . ToUpper($offer['~PENALTY'])];
    $offer['PENALTY'] = strlen($MESS['TS_FRONTOFFICE_' . ToUpper($offer['~PENALTY'])]) ? $MESS['TS_FRONTOFFICE_' . ToUpper($offer['~PENALTY'])] : $offer['PENALTY'];
  }
}

if ( $arResult['processor'] == 'order' || $arResult['processor'] == 'precommit' ){
  $arResult['PENALTY'] = strlen($MESS['TS_FRONTOFFICE_' . ToUpper($arResult['~PENALTY'])]) ? $MESS['TS_FRONTOFFICE_' . ToUpper($arResult['~PENALTY'])] : $arResult['PENALTY'];
}

if ( $arResult['processor'] == 'personal_data' ){
	// � ������ ����� ��������� �������� ���������� ������ "����� ��������" ��������� ������ ������
	foreach ( $arResult['FORM']['PASSENGERS'] as $k => $v ) {
		$fields_number = count($arResult['FORM']['PASSENGERS'][$k]['PROFILES']['FIELDS']); 
		if ( $arResult['FORM']['PASSENGERS'][$k]['~DATA']['TYPE'] == 'PASSENGER_BLOCK'  && $fields_number > 1){
			for ( $i = $fields_number; $i > 0; $i -- ) {
				$arResult['FORM']['PASSENGERS'][$k]['PROFILES']['FIELDS'][$i] = $arResult['FORM']['PASSENGERS'][$k]['PROFILES']['FIELDS'][$i-1];
			}
			$arResult['FORM']['PASSENGERS'][$k]['PROFILES']['FIELDS'][0] = $arResult['FORM']['PASSENGERS'][$k]['PROFILES']['FIELDS'][$fields_number];
			unset($arResult['FORM']['PASSENGERS'][$k]['PROFILES']['FIELDS'][$fields_number]);
		} 
	}

  foreach ( $arResult['FORM']['FIELDS'] as $k => $v ){
    if ( $arResult['FORM']['FIELDS'][$k]['~DATA']['TYPE'] == 'PASSENGER_BLOCK'){
      foreach ($arResult['FORM']['FIELDS'][$k]['FIELDS'] as $key => $value){
        if ($arResult['FORM']['FIELDS'][$k]['FIELDS'][$key]['~DATA']['TYPE'] == 'PROFILES_BLOCK' && ($fields_number = count($arResult['FORM']['FIELDS'][$k]['FIELDS'][$key]['FIELDS'])) > 1){
          for ( $i = $fields_number; $i > 0; $i -- ) {
            $arResult['FORM']['FIELDS'][$k]['FIELDS'][$key]['FIELDS'][$i] = $arResult['FORM']['FIELDS'][$k]['FIELDS'][$key]['FIELDS'][$i-1];
          }
          $arResult['FORM']['FIELDS'][$k]['FIELDS'][$key]['FIELDS'][0] = $arResult['FORM']['FIELDS'][$k]['FIELDS'][$key]['FIELDS'][$fields_number];
          unset($arResult['FORM']['FIELDS'][$k]['FIELDS'][$key]['FIELDS'][$fields_number]);
        }
      }
    }
  }
  
  //� ������ ����� ��������� �������� ���������� ������ "����� ���������� ����������" ��������� ������ ������
  if ( ($fields_number = count($arResult['FORM']['CONTACTS']['PROFILES']['FIELDS'])) > 1 ) {
		for ( $i = $fields_number; $i > 0; $i -- ) {
			$arResult['FORM']['CONTACTS']['PROFILES']['FIELDS'][$i] = $arResult['FORM']['CONTACTS']['PROFILES']['FIELDS'][$i-1];
		}
		$arResult['FORM']['CONTACTS']['PROFILES']['FIELDS'][0] = $arResult['FORM']['CONTACTS']['PROFILES']['FIELDS'][$fields_number];
		unset($arResult['FORM']['CONTACTS']['PROFILES']['FIELDS'][$fields_number]);
  }
  //trace($arResult['FORM']);
}
?>
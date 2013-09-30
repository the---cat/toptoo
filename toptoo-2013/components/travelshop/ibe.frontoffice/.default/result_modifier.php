<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); //trace($arResult);?>
<? include( GetLangFileName( $_SERVER["DOCUMENT_ROOT"]."/bitrix/templates/".SITE_TEMPLATE_ID."/components/travelshop/ibe.frontoffice/.default"."/lang/", "/template.php" ) ); ?>
<?
if ( $arResult["processor"] == "form_order" && strlen($arParams["REDIRECT_HOST"]) ) { // Если текущий шаг - форма поиска

  $host = $arParams["REDIRECT_HOST"];
  $path = $arParams["REDIRECT_PATH"] ? $arParams["REDIRECT_PATH"] : "/";
  if ( strlen($arResult["display_error"]) ) {
    $data = "error_text=" . urlencode( $APPLICATION->ConvertCharset($arResult["display_error"], SITE_CHARSET, "UTF-8") );
  }

  header("HTTP/1.1 301 Moved Permanently");
  header("Location: http://" . $host . $path . ( strlen( $data ) ? "?" . $data : "" ) );

  exit();
}

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
	// В списке ранее введенных профилей пассажиров строку "Новый пассажир" поставить вверху списка
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
  
  //В списке ранее введенных профилей контактнов строку "Новая контактная информация" поставить вверху списка
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
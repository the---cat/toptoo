<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); //trace($arResult);?>
<? include( GetLangFileName( $_SERVER["DOCUMENT_ROOT"]."/bitrix/templates/".SITE_TEMPLATE_ID."/components/travelshop/ibe.frontoffice/.default"."/lang/", "/template.php" ) ); ?>
<?
if ( $arResult["processor"] == "form_order" && strlen($arParams["REDIRECT_HOST"]) ) { // ���� ������� ��� - ����� ������

  $host = $arParams["REDIRECT_HOST"];
  $path = $arParams["REDIRECT_PATH"] ? $arParams["REDIRECT_PATH"] : "/";
  if ( strlen($arResult["display_error"]) ) {
    $data = "error_text=" . urlencode( $APPLICATION->ConvertCharset($arResult["display_error"], SITE_CHARSET, "UTF-8") );
  }

  header("HTTP/1.1 301 Moved Permanently");
  header("Location: http://" . $host . $path . ( strlen( $data ) ? "?" . $data : "" ) );

  exit();
}

if ( $arResult['~FORM_TYPE'] == 'personal_data' ){
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
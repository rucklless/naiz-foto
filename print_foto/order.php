<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?php
if(!CModule::IncludeModule("iblock")) return;

$iblockId = 10;

$arSelect = array("ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_TYPE_PAPER", "PROPERTY_TYPE_SIZE", "PROPERTY_TYPE_PRICE", "PROPERTY_TYPE_FIELD", "PROPERTY_TYPE_COUNT");
$arFilter = array("IBLOCK_ID" => $iblockId, "ACTIVE_DATE" => "Y", "ACTIVE" => "Y");
$res = CIBlockElement::GetList(array(), $arFilter, false, array(), $arSelect);
while ($ob = $res->GetNextElement()) {
	$arFields = $ob->GetFields();
	?><pre><?print_r($arFields);?></pre><?
}


/*$rsProp = CIBlockProperty::GetList(
	array('SORT' => 'ASC'),
	array(
	'IBLOCK_ID' => 11,
	'ACTIVE' => 'Y',
	'CODE' => 'ORDER_%'
	)
	);
while($arProp = $rsProp -> Fetch()){
	$arResult['ORDER_PROPS_LIST'][$arProp['CODE']] = $arProp;

	if($arProp['PROPERTY_TYPE'] == 'E'){
		$rsElems = CIBlockElement::GetList(
			array('SORT' => 'ASC'),
			array(
				'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
				'IBLOCK_ID' => $arProp['LINK_IBLOCK_ID'],
				'ACTIVE' => 'Y'
			),
			false,
			false,
			array('ID', 'NAME', 'PROPERTY_DELIVERY_DATE', 'PROPERTY_COST', 'PROPERTY_DELIVERY_FREE')
		);

		while($arElem = $rsElems -> Fetch()){
			$arResult['ORDER_PROPS_LIST'][$arProp['CODE']]['VALUE_LIST_ELEM'][$arElem['ID']] = $arElem;
		}
	}
	elseif($arProp['PROPERTY_TYPE'] == 'L'){
		$rsPropEnum = CIBlockPropertyEnum::GetList(
			array('SORT' => 'ASC'),
			array(
				'IBLOCK_ID' => 11,
				'CODE' => $arProp['CODE']
			)
		);

		while($arPropEnum = $rsPropEnum -> Fetch()){
			$arResult['ORDER_PROPS_LIST'][$arProp['CODE']]['VALUE_LIST'][$arPropEnum['ID']] = $arPropEnum;
		}
	}

}
?><pre><?print_r($arResult['ORDER_PROPS_LIST'])?></pre><?*/
/*
foreach($_REQUEST['ORDER_PROP'] as $key => $val){
	if(is_array($val) && $key == 'FOTO'){
		foreach($val as $id => $arFoto){
			foreach($arFoto as $key_prop => $val_prop){
				$arResult['ORDER_PROP'][$key][$id][$key_prop] = htmlspecialcharsEx(trim($val_prop));
			}
		}
	}
	else{
		$arResult['ORDER_PROP'][$key] = htmlspecialcharsEx(trim($val));
	}
}

$el = new CIBlockElement;

$arPropOrder = array(
	'IBLOCK_ID' => 11,
	'ACTIVE' => 'Y',
	'NAME' => date("d.m.Y")
);
foreach($arResult['ORDER_PROP'] as $key => $val){
	if(!is_array($val)){
		$arPropOrder['PROPERTY_VALUES'][$key] = $val;
	}
}

$rsElems = CIBlockElement::GetList(
	array('SORT' => 'ASC'),
	array(
		'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
		'IBLOCK_ID' => 13,
		'ID' => $arResult['ORDER_PROP']['ORDER_DELIVERY']
	),
	false,
	false,
	array('ID', 'NAME', 'PROPERTY_COST', 'PROPERTY_DELIVERY_FREE')
);

if($arElem = $rsElems -> Fetch()){
	$arDelivery = $arElem;
}

if($orderID = $el->Add($arPropOrder)){*/
	if(mkdir($_SERVER['DOCUMENT_ROOT'].'/upload/orders/'.$orderID, 0755)){
		if(!empty($arResult['ORDER_PROP']['FOTO'])){
			$countFoto = 0;
			$summFoto = 0;
			foreach($arResult['ORDER_PROP']['FOTO'] as $key => $arFoto){
				$nameFoto = $_SERVER['DOCUMENT_ROOT'].'/upload/orders/'.$orderID.'/'.$orderID.' '.$key;
				$arFile = CFile::GetFileArray($arFoto['IMG']);

				$rsProp = CIBlockPropertyEnum::GetList(
					array('SORT' => 'ASC'),
					array('ID' => $arFoto['TYPE_PAPER'])
				);
				while($arProp = $rsProp -> Fetch()){
					$nameFoto .= ' '.$arProp['VALUE'];
				}

				$rsProp = CIBlockPropertyEnum::GetList(
					array('SORT' => 'ASC'),
					array('ID' => $arFoto['SIZE'])
				);
				while($arProp = $rsProp -> Fetch()){
					$nameFoto .= ' '.$arProp['VALUE'];
				}

				$rsProp = CIBlockPropertyEnum::GetList(
					array('SORT' => 'ASC'),
					array('ID' => $arFoto['CORNERS'])
				);
				while($arProp = $rsProp -> Fetch()){
					$nameFoto .= ' '.$arProp['VALUE'];
				}

				$rsProp = CIBlockPropertyEnum::GetList(
					array('SORT' => 'ASC'),
					array('ID' => $arFoto['FIELD'])
				);
				while($arProp = $rsProp -> Fetch()){
					$nameFoto .= ' '.$arProp['VALUE'];
				}

				$nameFoto .= ' '.$arFoto['COUNT'].'шт';
				$nameFoto .= '.jpg';
				if(copy($_SERVER['DOCUMENT_ROOT'].$arFile['SRC'], $nameFoto)){
					$files = glob('/upload/'.$arFile['SUBDIR'].'/*.*');
					if (!empty($files)){
						if(count($files)>1){
							DeleteDirFilesEx($_SERVER['DOCUMENT_ROOT'].$arFile['SRC']);
						}else{
							DeleteDirFilesEx('/upload/'.$arFile['SUBDIR']);
						}
					}

					//$arrDirDel[] = '/upload/'.$arFile['SUBDIR'];
				}

				$countFoto += $arFoto['COUNT'];
				$summFoto += $arFoto['PRICE'];

			}


			$fp = fopen($_SERVER["DOCUMENT_ROOT"].'/upload/orders/'.$orderID.'/order.txt', 'w+');

			fwrite($fp, "Заказ №".$orderID."\r\n\r\n");


			$arEventFields['ORDER_ID'] = $orderID;
			$arEventFields['TEXT'] = '';
			$arEventFields['EMAIL_TO'] = $arParams['EMAIL_TO'];

			foreach($arResult['ORDER_PROPS_LIST'] as $key => $val){
				if($val['PROPERTY_TYPE'] == 'E'){
					$strProp = $val['NAME'].': '.$arDelivery['NAME']."\r\n";
					$arEventFields['TEXT'] .= $val['NAME'].': '.$arDelivery['NAME']."\r\n";
				}
				elseif($val['PROPERTY_TYPE'] == 'L'){
					$strProp = $val['NAME'].': '.$val['VALUE_LIST'][$arResult['ORDER_PROP'][$key]]['VALUE']."\r\n";
					$arEventFields['TEXT'] .= $val['NAME'].': '.$val['VALUE_LIST'][$arResult['ORDER_PROP'][$key]]['VALUE']."\r\n";
				}
				else{
					$strProp = $val['NAME'].': '.$arResult['ORDER_PROP'][$key]."\r\n";
					$arEventFields['TEXT'] .= $val['NAME'].': '.$arResult['ORDER_PROP'][$key]."\r\n";
				}
				fwrite($fp, $strProp);
			}

			fwrite($fp, "Стоимость: ".$summFoto." руб.\r\n");

			$arEventFields['TEXT'] .= "Стоимость: ".$summFoto." руб.\r\n";

			$arPropUpdate = array(
				'SYSTEM_COUNT_USER_FOTO' => count($arResult['ORDER_PROP']['FOTO']),
				'SYSTEM_COUNT_FOTO' => $countFoto,
				'SYSTEM_SUMM' => $summFoto
			);

			if($summFoto < $arDelivery['PROPERTY_DELIVERY_FREE_VALUE']){
				$arPropUpdate['SYSTEM_DELIVERY_SUMM'] = $arDelivery['PROPERTY_COST_VALUE'];
				fwrite($fp, "Стоимость доставки: ".$arDelivery['PROPERTY_COST_VALUE']." руб.\r\n");
				$arEventFields['TEXT'] .= "Стоимость доставки: ".$arDelivery['PROPERTY_COST_VALUE']." руб.\r\n";
			}
			else{
				$arPropUpdate['SYSTEM_DELIVERY_SUMM'] = 0;
			}

			fclose($fp);


			$arResult['SUMM_ORDER'] = $arPropUpdate['SYSTEM_SUMM'] + $arPropUpdate['SYSTEM_DELIVERY_SUMM'];

			CIBlockElement::SetPropertyValuesEx($orderID, 11, $arPropUpdate);

			if(file_exists($name_archiv)){

				CEvent::SendImmediate('NEW_ORDER', 's1', $arEventFields);

			}
			$arResult['ORDER_PAY'] = 'Y';
			$arResult['ORDER_ID'] = $orderID;
		}
	}/*

}*/



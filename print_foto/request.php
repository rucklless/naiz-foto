<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>

<?
$targetFolder = '/upload/uploads/'; // Relative to the root

if(!empty($_FILES)){
	$tempFile = $_FILES['file']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder; ///upload/uploads/
	$_FILES['file']['name'] = $_POST['timestamp'].'_'.$_FILES['file']['name'];
	$targetFile = rtrim($targetPath,'/') . '/' . $_FILES['file']['name'];
	// Validate the file type
	$fileTypes = array('jpg','jpeg','gif','png'); // File extensions
	$fileParts = pathinfo($_FILES['file']['name']);
	/*?><pre><?print_r($targetFile)?></pre><?*/
	if(in_array(strtolower($fileParts['extension']),$fileTypes)){
		$imgID = CFile::SaveFile(
			$_FILES['file'],
			'uploads'
		);
		$origImg = CFile::GetFileArray($imgID);
		$res = CFile::ResizeImageFile(
			$_SERVER['DOCUMENT_ROOT'].$origImg['SRC'],
			$destinationFile = $_SERVER['DOCUMENT_ROOT']."/upload/".$origImg['SUBDIR'].'/resize_'.$origImg['FILE_NAME'],
			array(
				'width' => 278,
				'height' => 200
			),
			BX_RESIZE_IMAGE_PROPORTIONAL,
			array(),
			false,
			array()
		);
		if($res){
			$destinationFile = str_replace($_SERVER['DOCUMENT_ROOT'], '', $destinationFile);
			$result = array(
				'id' => $imgID,
				'preview' => $destinationFile
			);
		}
		//move_uploaded_file($tempFile,$targetFile);

		echo json_encode($result);
	} else {
		echo 'Invalid file type.';
	}
}
$_POST = json_decode(file_get_contents('php://input'), true);
if($_REQUEST['ORDER'] == 'Y'){
	if(!CModule::IncludeModule("iblock")) return;
	$el = new CIBlockElement;

	$arPropOrder = array(
		'IBLOCK_ID' => 11,
		'ACTIVE' => 'Y',
		'NAME' => date("d.m.Y H:i:s")
	);
	foreach($_REQUEST['ORDER_PROP'] as $key => $val){
		if(!is_array($val)){
			$arPropOrder['PROPERTY_VALUES'][$key] = $val;
		}
	}

	if($orderID = $el->Add($arPropOrder)){
		if(mkdir($_SERVER['DOCUMENT_ROOT'].'/upload/orders/'.$orderID, 0755)){
			if(!empty($_REQUEST['ORDER_PROP']['FOTO'])){

				$countFoto = 0; //?
				$summFoto = 0; //?
				foreach($_REQUEST['ORDER_PROP']['FOTO'] as $key => $arFoto){
					$nameFoto = $_SERVER['DOCUMENT_ROOT'].'/upload/orders/'.$orderID.'/'.$orderID.' '.$key.' ';
					$arFile = CFile::GetFileArray($arFoto['IMG']);

					$nameFoto .= $arFoto['TYPE_PAPER'].' '.$arFoto['SIZE'].' '.$arFoto['FIELD'];

					$nameFoto .= ' '.$arFoto['COUNT'].'шт';
					$nameFoto .= '.jpg';

					if(copy($_SERVER['DOCUMENT_ROOT'].$arFile['SRC'], $nameFoto)){

						$files = glob($_SERVER['DOCUMENT_ROOT'].'/upload/'.$arFile['SUBDIR'].'/*.*');
						if (!empty($files)){

							if(count($files)>1){
								$file = '/upload/'.$arFile['SUBDIR'].'/'.$arFile['FILE_NAME'];
								$resize_file = '/upload/'.$arFile['SUBDIR'].'/resize_'.$arFile['FILE_NAME'];

								DeleteDirFilesEx($file);
								DeleteDirFilesEx($resize_file);
								$files = glob($_SERVER['DOCUMENT_ROOT'].'/upload/'.$arFile['SUBDIR'].'/*.*');
								if(empty($files)){
									DeleteDirFilesEx('/upload/'.$arFile['SUBDIR']);
								}
							}else{
								DeleteDirFilesEx('/upload/'.$arFile['SUBDIR']);
							}
						}

						//$arrDirDel[] = '/upload/'.$arFile['SUBDIR'];
					}

					/*$countFoto += $arFoto['COUNT'];
					$summFoto += $arFoto['PRICE'];*/

				}


				$fp = fopen($_SERVER["DOCUMENT_ROOT"].'/upload/orders/'.$orderID.'/order.txt', 'w+');

				fwrite($fp, "Заказ №".$orderID."\r\n\r\n");


				$arEventFields['ORDER_ID'] = $orderID;
				$arEventFields['TEXT'] = '';
				$arEventFields['EMAIL_TO'] = $arParams['EMAIL_TO'];

				$text = "Фамилия: ".$_REQUEST['ORDER_PROP']['ORDER_LAST_NAME']."\r\n";
				$text .= "Имя: ".$_REQUEST['ORDER_PROP']['ORDER_NAME']."\r\n";
				$text .= "Телефон: ".$_REQUEST['ORDER_PROP']['ORDER_PHONE']."\r\n";
				$text .= "Email: ".$_REQUEST['ORDER_PROP']['ORDER_EMAIL']."\r\n";
				$text .= "Количество фотографий: ".$_REQUEST['ORDER_PROP']['SYSTEM_COUNT_USER_FOTO']."\r\n";
				$text .= "Количество копий: ".$_REQUEST['ORDER_PROP']['SYSTEM_COUNT_FOTO']."\r\n";
				$text .= "Стоимость: ".$_REQUEST['ORDER_PROP']['SYSTEM_SUMM']."руб.\r\n";

				fwrite($fp, $text);

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

				$arEventFields['TEXT'] .= $text;

				fclose($fp);


				$arResult['SUMM_ORDER'] = $arPropUpdate['SYSTEM_SUMM'] + $arPropUpdate['SYSTEM_DELIVERY_SUMM'];



					CEvent::SendImmediate('NEW_ORDER', 's1', $arEventFields);


				$arResult['ORDER_PAY'] = 'Y';
				$arResult['ORDER_ID'] = $orderID;
			}
		}

	}


	/*?><pre><?print_r($_REQUEST['ORDER_PROP'])?></pre><?*/
}


if($_REQUEST['PAYMENT'] == 'Y'){
		$shopId = '130136';
		$scid = '95353';
		//$scid = '551390';//test
		$nAmount = $_REQUEST['SUMM'];
		$sum = number_format($nAmount, 2, '.', '');
		$customerNumber = session_id();
		$orderNumber = $arResult['ORDER_ID'];
		?>
		<form action="https://money.yandex.ru/eshop.xml" method="post">

		<!-- Обязательные поля -->
		<input name="shopId" value="<?=$shopId;?>" type="hidden"/>
		<input name="scid" value="<?=$scid;?>" type="hidden"/>
		<input name="sum" value="<?=$sum;?>" type="hidden">
		<input name="customerNumber" value="<?=$customerNumber;?>" type="hidden"/>

		<!-- Необязательные поля -->
		<input name="orderNumber" value="<?=$orderNumber;?>" type="hidden"/>
		<input name="shopFailURL" value="http://naiz-foto.ru/print_foto/" type="hidden"/>
		<input name="shopSuccessURL" value="http://naiz-foto.ru/print_foto/" type="hidden"/>
		<input class="btn" type="submit" value="Заплатить"/>
	</form>
	<?php
}
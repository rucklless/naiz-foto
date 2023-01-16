<?
	$shopId = '130136';					
	$scid = '551390';
	$nAmount = $arResult['SUMM_ORDER'];	
	$sum = "6.90";
	$customerNumber = session_id();
	$orderNumber = "1208";
?>
<?/*
	$shopId = '62636';					
	$scid = '57344';
	$nAmount = $arResult['SUMM_ORDER'];
	$sum = number_format($nAmount, 2, '.', '');
	$customerNumber = session_id();
	$orderNumber = $arResult['ORDER_ID'];*/
?>
<form action="https://demomoney.yandex.ru/eshop.xml" method="post">   
<?/*form action="https://demomoney.yandex.ru/eshop.xml" method="post"*/?>   
	<!-- Обязательные поля --> 
	<input name="shopId" value="<?=$shopId;?>" type="hidden"/> 
	<input name="scid" value="<?=$scid;?>" type="hidden"/> 
	<input name="sum" value="<?=$sum;?>" type="hidden"> 
	<input name="customerNumber" value="<?=$customerNumber;?>" type="hidden"/> 
	  
	<!-- Необязательные поля --> 
	<input name="orderNumber" value="<?=$orderNumber;?>" type="hidden"/> 
	<input name="shopFailURL" value="http://naiz-foto.ru/print_foto/" type="hidden"/> 
	<input name="shopSuccessURL" value="http://naiz-foto.ru/print_foto/" type="hidden"/> 
	<?/*<input name="shopArticleId" value="567890" type="hidden"/> 
	<input name="paymentType" value="AC" type="hidden"/> 
	<input name="orderNumber" value="abc1111111" type="hidden"/> 
	<input name="cps_phone" value="79110000000" type="hidden"/> 
	<input name="cps_email" value="user@domain.com" type="hidden"/> 
	  */?>
	<input type="submit" value="Заплатить"/> 
</form>
<?php
$ch = curl_init('https://naiz-foto.ru/print_foto/yandexkassa/github/checkorder.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
//curl_setopt($ch, CURLOPT_HEADER,true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch, CURLOPT_POSTFIELDS, "action=checkOrder&shopId=100500&scid=555777&customerNumber=32&cdd_pan_mask=444444|4448 \
&orderNumber=38&paymentType=AC&invoiceId=2000000833650&shopSumAmount=100.00&md5=2A409E2B81D7A77A2B745A2F62916C42 \
&orderSumAmount=3200.00&cdd_exp_date=1217&paymentPayerCode=4100322062290&cdd_rrn=&external_id=deposit \
&requestDatetime=2016-07-11T15:29:35.438+03:00&depositNumber=tNGTnJmP7sPdWnPiSeOXLUFLB5MZ.001f.201607 \
&cps_user_country_code=PL&orderCreatedDatetime=2016-07-11T15:29:35.360+03:00&sk=yed009c9df4e4f0a47d15e20d4af3231e \
&shopSumBankPaycash=1003&shopSumCurrencyPaycash=10643&rebillingOn=false&orderSumBankPaycash=1003&cps_region_id=213 \
&orderSumCurrencyPaycash=10643&merchant_order_id=38_110716152918_00000_64759 \
&unilabel=1f15a4dd-0009-5000-8000-0000116d476c&yandexPaymentId=2570052456918");

$html = curl_exec($ch);
curl_close($ch);

 /* if( $curl = curl_init() ) {
    curl_setopt($curl, CURLOPT_URL, 'https://naiz-foto.ru/print_foto/yandexkassa/github/checkorder.php');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, "action=checkOrder&shopId=100500&scid=555777&customerNumber=32&cdd_pan_mask=444444|4448 \
&orderNumber=38&paymentType=AC&invoiceId=2000000833650&shopSumAmount=100.00&md5=2A409E2B81D7A77A2B745A2F62916C42 \
&orderSumAmount=3200.00&cdd_exp_date=1217&paymentPayerCode=4100322062290&cdd_rrn=&external_id=deposit \
&requestDatetime=2016-07-11T15:29:35.438+03:00&depositNumber=tNGTnJmP7sPdWnPiSeOXLUFLB5MZ.001f.201607 \
&cps_user_country_code=PL&orderCreatedDatetime=2016-07-11T15:29:35.360+03:00&sk=yed009c9df4e4f0a47d15e20d4af3231e \
&shopSumBankPaycash=1003&shopSumCurrencyPaycash=10643&rebillingOn=false&orderSumBankPaycash=1003&cps_region_id=213 \
&orderSumCurrencyPaycash=10643&merchant_order_id=38_110716152918_00000_64759 \
&unilabel=1f15a4dd-0009-5000-8000-0000116d476c&yandexPaymentId=2570052456918");
    $out = curl_exec($curl);    
    curl_close($curl);
    $info = curl_getinfo($curl); 
  }*/
?>

<pre><?var_dump($html)?></pre>
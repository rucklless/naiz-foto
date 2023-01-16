<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Новая страница");
?>
<!--script src="https://unpkg.com/vue@next"></script-->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
<!--script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script-->
	<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
	<link rel="stylesheet" href="style.css">

	<style>
		.container{
			width: 900px;
			margin: auto;
		}
        #site_logo{
			display: none;
		}
	</style>
<?php
if($_REQUEST['dev'] == 'y'){
	$arItems = array();
	CModule::IncludeModule('iblock');
	$priceIbId = 10;
	$arSelect = array("ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_TYPE_PAPER", "PROPERTY_SIZE", "PROPERTY_PRICE");
	$arFilter = array("IBLOCK_ID" => IntVal($priceIbId), "ACTIVE_DATE" => "Y", "ACTIVE" => "Y");
	$res = CIBlockElement::GetList(array(), $arFilter, false, array("nPageSize" => 50), $arSelect);
	while ($ob = $res->GetNextElement()) {
		$arFields = $ob->GetFields();
		$arItems[$arFields['ID']] = $arFields;
	}
	$jsonItems = json_encode($arItems);
}
?>
<div id="app" class="container" :json="setJson({ json: 'bar' })">
	<template>
		<div class="price-list">
			<div class="new">
				<a class="price-button" @click="showPrice" onclick="yaCounter34284860.reachGoal('ceny');">Наши<br> цены</a>
			</div>
			<div class="price-table" v-if="checkShowPrice">
				<span class="close-table-price" @click="hidePrice">X</span>
				<table>
					<thead>
					<tr>
						<td>
							Формат, см
						</td>
						<td>
							10x15<br>
							(от 300 шт.)
						</td>
						<td>
							10x15<br>
							(100-300 шт.)
						</td>
						<td>
							10x15<br>
							(0-100 шт.)
						</td>
						<td>
							15x20
						</td>
						<td>
							20х30
						</td>
						<td>
							30х40
						</td>
						<td>
							30х45
						</td>
					</tr>
					</thead>
					<tbody>
					<tr>
						<td>
							Матовая фотобумага (руб.)*
						</td>
						<td>
							5.90
						</td>
						<td>
							6.40
						</td>
						<td>
							6.90
						</td>
						<td>
							17.00
						</td>
						<td>
							35.00
						</td>
						<td>
							70.00
						</td>
						<td>
							80.00
						</td>
					</tr>
					<tr>
						<td>
							Глянцевая фотобумага (руб.)*
						</td>
						<td>
							5.90
						</td>
						<td>
							6.40
						</td>
						<td>
							6.90
						</td>
						<td>
							17.00
						</td>
						<td>
							35.00
						</td>
						<td>
							70.00
						</td>
						<td>
							80.00
						</td>
					</tr>
					<tr>
						<td>
							Фотомагниты (руб.)*
						</td>
						<td>
							60.00
						</td>
						<td>
							120.00
						</td>
						<td>
							180.00
						</td>
						<td>
							N/a
						</td>
						<td>
							N/a
						</td>
						<td>
							N/a
						</td>
						<td>
							N/a
						</td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>
		<h2 >Печать фотографий онлайн от 5,90<sup>*</sup> р.<img src="/images/noun.png"></h2>
		<h3> <sup>*</sup>Цены действуют ТОЛЬКО при заказе через сайт </h3>
		<div id="payment"></div>
		<div class="container" v-if="payment != true">
			<div class="large-12 medium-12 small-12 cell">
				<label class="label-upload">Загрузить фотографии
					<input class="btn btn-light" type="file" id="files" ref="files" multiple @change="fileUpload()"/>
				</label>
			</div>
			<div class="uploadDescription">
				Загружено {{currentUploadFiles}}/{{countFiles}}
			</div>
			<div class="elem-list row">
				<div class="elem" v-for="(file, index) in fileList">
					<div class="card">
						<img class="card-img-top" :src="file.preview">
						<div class="card-body">
							<div class="mb-1">
								<select class="form-select" name="type" id="" v-model="file.type">
									<option :value="type.id" v-for="(type, index) in type">{{type.val}}</option>
								</select>
							</div>
							<div class="mb-1">
								<select class="form-select" name="size" id="" v-model="file.sizes">
									<option :value="type.id" v-for="(type, index) in sizes">{{type.val}}</option>
								</select>
							</div>
							<div class="mb-1">
								<select class="form-select" name="field" id="" v-model="file.field">
									<option :value="type.id" v-for="(type, index) in field">{{type.val}}</option>
								</select>
							</div>
							<div class="mb-1">
								<input class="form-control" type="number" id="edition" min="0" v-model.number="file.count">
							</div>
							<div class="price"><span v-text="file.cost"></span>руб.</div>
							<div class="del" @click="delFile(index)">Удалить</div>
						</div>
					</div>
				</div>
			</div>
			<div class="basket">
				<div id="sum-load"></div>
				<div class="att_summ"><span class="summ_foto">{{total}}</span> руб.</div>
				<div class="row">
					<div class="apply_to_all">
						<label for="apply_to_all_btn" class="btn">Применить ко всем</label>
						<input id="apply_to_all_btn" class="form-check-input" type="checkbox" v-model="apply_to_all_check" value="false">
						<div class="apply_to_all_form" v-if="apply_to_all_check">
							<div class="card">
								<div class="card-body">
									<div class="mb-1">
										<select class="form-select" name="type" id="" v-model="all.type">
											<option :value="type.id" v-for="(type, index) in type">{{type.val}}</option>
										</select>
									</div>
									<div class="mb-1">
										<select class="form-select" name="size" id="" v-model="all.sizes">
											<option :value="type.id" v-for="(type, index) in sizes">{{type.val}}</option>
										</select>
									</div>
									<div class="mb-1">
										<select class="form-select" name="field" id="" v-model="all.field">
											<option :value="type.id" v-for="(type, index) in field">{{type.val}}</option>
										</select>
									</div>
									<div class="mb-1">
										<input class="form-control" type="number" id="edition" min="0" v-model.number="all.count">
									</div>
								</div>
								<button class="btn btn-danger" @click="applyForAll">Отправить</button>
							</div>

						</div>

					</div>
					<div class="att-zakaz">
						<label for="show_order" class="btn order_btn">Заказать</label>
						<input id="show_order" class="form-check-input" type="checkbox" v-model="show_order_check" value="false">
					<div class="att_delete"><a class="dell_all" @click="delAll">Удалить все</a></div>
				</div>
			</div>

			<form class="order border" action="" @submit.prevent v-if="show_order_check">
				<div class="mb-1">
					<label for="name" class="form-label">Имя</label>
					<input v-model="form.name" type="text" class="form-control" id="name" placeholder="Имя">
				</div>
				<div class="mb-1">
					<label for="name" class="form-label">Фамилия</label>
					<input v-model="form.family" type="text" class="form-control" id="family" placeholder="Фамилия">
				</div>
				<div class="mb-1">
					<label for="phone" class="form-label">Телефон</label>
					<input v-model="form.phone" type="text" class="form-control" id="phone" placeholder="Телефон">
				</div>
				<div class="mb-1">
					<label for="mail" class="form-label">Email</label>
					<input v-model="form.mail" type="text" class="form-control" id="mail" placeholder="E-mail">
				</div>
				<div class="mb-1">
					<label for="delivery" class="form-label">Доставка</label>
					<select name="delivery" class="form-select">
						<option value="65" data-delivery-date="" data-cost="0" data-delivery-free="0">Только самовывоз из г. Самары, ул. Физкультурная 94</option>
					</select>
				</div>
				<div class="mb-1">
					<label for="payment" class="form-label">Оплата</label>
					<select name="payment" class="form-select">
						<option value="65" data-delivery-date="" data-cost="0" data-delivery-free="0">Банковская карта, электронные деньги, QIWI, с мобильного, Евросеть, терминал и другие способы</option>
					</select>
				</div>
				<div class="mb-1">
					<label for="comment" class="form-label">Дополнительная информация</label>
					<textarea  v-model="form.comment" name="comment" id="comment" rows="3" class="form-control"></textarea>
				</div>
				<div class="mb-1">
					<textarea name="" id="" rows="10" disabled class="form-control">
ВНИМАНИЕ!
Если ваше фото не подходит под стандарты печати, фотографии будут напечатаны с белыми полями. Если вы хотите, чтобы фото было без белых полей — указывайте в форме заказа — обрезать поля.

Если ваше фото не подходит под стандарты печати, фотографии будут напечатаны с белыми полями. Если вы хотите, чтобы фото было без белых полей — указывайте в форме заказа — обрезать поля.

Если Вы загружаете некачественное фото, то после печати оно не станет лучше. Фотографии не проходят никаких улучшений, такие дефекты на фотографиях как, красные глаза, размытость и шумы не будут отредактированы.
					</textarea>
				</div>
				<div class="mb-1">
					<label for="order_accept">С правилами согласен*</label>
					<input id="order_accept" type="checkbox" value="false" class="form-check-input" v-model="form.order_accept" required>
				</div>
				<div class="mb-1">
					<label for="agreement">Согласен на обработку персональных данных *</label>
					<input id="agreement" type="checkbox" value="false" class="form-check-input" v-model="form.agreement" required>
				</div>
				<div class="mb-1">
					<a class="agreement" href="/agreement.php" target="_blank">Соглашение об обработке персональных данных</a>
				</div>
				<div class="mb-1">
					<button class="btn btn-danger" @click="submitForm">Отправить</button>
				</div>
			</form>
		</div>
	</template>
</div>


<section class="steps">
<div class="wrapper">
	<h2>Заказать печать фотографий<br>
	 теперь просто как раз, два, три.</h2>
	<div class="numbers number-1">
		 <span>1</span><br>
		 Загрузить<br>
		 фото
	</div>
	<div class="numbers number-2">
		 <span>2</span><br>
		 Настроить<br>
		 параметры
	</div>
	<div class="numbers number-3">
		 <span>3</span><br>
		 Оформить<br>
		 заказ
	</div>
</div>
 </section> <section class="header">
<div class="wrapper">
	<h1>Наши преимущества</h1>
	<div class="price">
		<div class="block block-1">
			 Цена<br>
 <span>От 4,9 рублей<br>
			 за 1 фотографию</span>
		</div>
		<div class="block block-2">
			 Сроки<br>
 <span>Срок изготовления<br>
			 от 1 часа</span>
		</div>
		<div class="block block-3">
			 Доставка<br>
 <span>Доставим курьером<br>
			 или самовывоз.
</span>
		</div>
 <br>
 <br>
		<div class="contacts first-contacts">
			 пн-пт с 9:00 до 20:00<br>
			 Сб c 9:00 до 18:00<br>
			 Вс c 9:00 до 15:00
		</div>
		<div class="contacts">
			 Самара, ул. Физкультурная, д.94
                          <p class="contact_foto">
                            <span class="ya-phone">+7 (846) 989-31-88</span>
			</p>
                      <a href="mailto:naiz-foto@mail.ru">naiz-foto@mail.ru</a>
		</div>
		<div class="contacts last-contacts">			
                       Стоимость курьерской доставки:<br>
                          Самара – 250 р.<br>
                          п. Зубчаниновка, Мехзавод, 116 км – 300 р.<br>
                         п. Красная Глинка, Дубрава – 300 р.
		</div>
	</div>
</div>
 </section> <section class="avantages">
<div class="wrapper">
	<div class="price">
		<div class="block block-1">
			<div class="img">
			</div>
 <br>
			 Сделать заказ можно<br>
			 из любого места
		</div>
		<div class="block block-2">
			<div class="img">
			</div>
 <br>
			 Печать с любого<br>
			 носителя
		</div>
		<div class="block block-3">
			<div class="img">
			</div>
 <br>
			 Высокое качество<br>
			 фотографий
		</div>
		<div class="block block-4">
			<div class="img">
			</div>
 <br>
                           Доставка курьером,<br>
			 почтой или самовывоз.<br>
                          
			
		</div>
	</div>
</div>
 </section> <section class="order-bottom">
<div class="wrapper">
	<div class="discription">
		 За печатью фотографи к нам обращаются даже именитые фотографы города<br>
		 Мы печатаем фотографии на фотоматериалах kodak. На цифровой фотолаборатории фирмы Noritsu производство Японии.<br>
		 Это всегда великолепное качество и глубина цветопередачи.
	</div>
	<div class="images">
 <img src="/print_foto/images/other.jpg" alt="">
	</div>
	<h2>Дополнительные услуги</h2>
	<ol class="dop-services">
		<li><a href="/foto-na-dokumenty/">Фото на документы</a></li>
		<li><a href="/services/poligrafiya/">Полиграфия</a></li>
		<li><a href="/otsifrovka-video/">Оцифровка видео</a></li>
		<li><a href="/fotosemka/">Фотосъемка</a></li>
		<li><a href="/bagetnaya-masterskaya/">Багетная мастерская</a></li>
		<li><a href="/services/fotopodarki/">Сувениры</a></li>
 <a href="/services/fotopodarki/"> </a>
	</ol>
 <a href="/services/fotopodarki/"> </a>
</div>
 <a href="/services/fotopodarki/"> </a></section><a href="/services/fotopodarki/"> </a>
<script src="vue.js"></script>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
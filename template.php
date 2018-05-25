<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
    $dateFormat = 'd.m.Y';
    $timeFormat = 'h:i:s';
    $displayClinic = false;
    if (!empty($arParams['FILTER_NAME'])) {
        $filterName = $arParams['FILTER_NAME'];
        $arFilter = $GLOBALS[$filterName];
        if (count($arFilter['PROPERTY_CLINIC']) > 1) {
            $displayClinic = true;
        }
    }
?>
<?php if ($arResult['ITEMS']): ?>

<!-- TODO: Вынести label за пределы div'ов-->
<div class="b-bg_white b-widgets_footer__col b_widgets_footer__col">
    <div class="b-widgets_footer__padding">
        <div class="se_appointment_widget">
        <div class="se_appointment_widget__header">Заявка на запись на прием</div>

        <form method="post" name="se_appointment_widget_form" class="">
            <fieldset>
				<div class="se_appointment_widget_form__row">
					<div class="se_appointment_widget_form__label_container">
						<label for="id100">Имя<span class="required-star"></span></label>
					</div>
					<div class="se_appointment_widget_form__right_column">
						<div class="se_appointment_widget_form__input_container">
							<input name="name" type="text" id="id100" placeholder="Иван Иванович" value="" class="se_appointment_widget_form__input">
						</div>
					</div>
				</div>
				<div class="se_appointment_widget_form__row">
					<div class="se_appointment_widget_form__label_container">
						<label for="id101">Телефон<span class="required-star"></span></label>
					</div>
					<div class="se_appointment_widget_form__right_column">
						<div class="se_appointment_widget_form__input_container">
							<input name="phone" type="text" id="id101" placeholder="Телефон для связи" value="" class="se_appointment_widget_form__input">
						</div>
					</div>
				</div>
				<div class="se_appointment_widget_form__row">
					<div class="se_appointment_widget_form__label_container">
						<label>Клиника<span class="required-star"></span></label>
					</div>
					<div class="se_appointment_widget_form__right_column">
						<select name="MS_P_CLINIC"<?=(in_array("P_CLINIC", $arResult["FORM_ERROR_FIELDS"]) ? ' class="error"' : '');?>>
							<?foreach ($arResult['CLINICS'] as $arClinic):?>
								<?if(!empty($doctorClinics)):?>
									<?if(in_array($arClinic['NAME'], $doctorClinics)):?>
										<option value="<?=$arClinic["ID"]?>"<?if ($arResult["FORM"]["P_CLINIC"] == $arClinic["ID"]):?> selected="selected"<?endif?>><?=$arClinic["NAME"]?></option>
									<?endif;?>
								<?else:?>
									<option value="<?=$arClinic["ID"]?>"<?if ($arResult["FORM"]["P_CLINIC"] == $arClinic["ID"]):?> selected="selected"<?endif?>><?=$arClinic["NAME"]?></option>
								<?endif;?>
							<?endforeach?>
						</select>
					</div>
				</div>
				<div class="se_appointment_widget_form__row">
					<div class="se_appointment_widget_form__label_container">
						<label for="id103">Дата<span class="required-star"></span></label>
					</div>

					<div class="se_appointment_widget_form__right_column">
						<div class="text04 text-calendar js-calendar-click<?=(in_array("P_DATE", $arResult["FORM_ERROR_FIELDS"]) ? ' error' : '');?>"><input name="MS_P_DATE" id="id104" class="js-calendar-input" value="<?= $arResult["FORM"]["P_DATE"] ?>" placeholder="ДД.ММ.ГГГГ" class="se_appointment_widget_form__input"></div>
					</div>
				</div>
				<div class="se_appointment_widget_form__row">
					<div class="se_appointment_widget_form__label_container">
						<label>Время<?if (in_array("P_TIME",$arParams["REQUIRED_FIELDS"])):?><span class="required-star"></span><?endif?></label>
					</div>
					<div class="se_appointment_widget_form__right_column">
						<select name="MS_P_TIME"<?=(in_array("P_TIME", $arResult["FORM_ERROR_FIELDS"]) ? ' class="error"' : '');?>>
							<?php foreach ($arTime as $value): ?>
								<option value="<?=$value?>"<?if ($arResult["FORM"]["P_TIME"] == $value):?> selected="selected"<?endif?>><?=$value?></option>
							<?php endforeach ?>
						</select>
					</div>
				</div>
				<div class="se_appointment_widget_form__row">
					<div class="se_appointment_widget_form__label_container">
						<label>
							Желаемое время звонка
						</label>
					</div>
					<div class="se_appointment_widget_form__right_column">
						<select name="MS_P_CALLBACK_TIME">
							<option value=""<?php if (!$arResult["FORM"]["P_CALLBACK_TIME"]):?> selected="selected"<?php endif?>>Не выбрано</option>
							<?php foreach ($arTime2 as $value): ?>
								<option value="<?=$value?>"<?php if ($arResult["FORM"]["P_CALLBACK_TIME"] == $value):?> selected="selected"<?php endif?>><?=$value?></option>
							<?php endforeach ?>
						</select>
					</div>
				</div>
				<div class="se_appointment_widget_form__row">
					<div class="se_appointment_widget_form__textarea_container">
						<textarea name="text" cols="30" rows="5" class="se_appointment_widget_form__textarea">Каке услуги клиники вас интересуют</textarea>
					</div>
				</div>
				<div class="se_appointment_widget_form__row"> <!--align-right?-->
					<div class="button-holder01" style="margin-top: 10px;">
						<span class="button01 block-button"><input type="submit" name="form" value="app">Отправить</span>
					</div>
					<?php
					$propEnumValue = null;

					$rsProperty = CIBlockProperty::GetList(
						array(),
						array(
							'IBLOCK_ID' => $arParams['IBLOCK_ID'],
							'CODE' => 'SHOW_CONFIDENTIAL_CONFIRM'
						)
					);

					if($arProperty = $rsProperty->Fetch()){
						$rsPropEnums = CIBlockProperty::GetPropertyEnum($arProperty['ID']);

						if($arEnum = $rsPropEnums->Fetch()){
							$propEnumValue = $arEnum['ID'];
						}
					}
					?>
					<?if(!GReCaptcha::isUser()):?>
						<div id = "gr-zapis-na-priem-form" style="margin: -76px -12px;/*transform: scale(0.8);transform-origin: 0 0;*/" data-size="compact" class = "gr-form-block <?=$arResult["FORM_ERROR"] ? "error" : ""?>" ></div>

						<script>
							init_g_recaptha('gr-zapis-na-priem-form');
						</script>
					<?endif?>
				</div>
            </fieldset>
        </form>
        <script>
            var confidentialClinics = <?=json_encode($arResult['CONFIDENTIAL_CLINICS']);?>;

            $("input[name='MS_P_PHONE']").mask("8(999) 999-99-99");

            $.datepicker.regional['ru'] = {
                closeText: 'Закрыть',
                prevText: '&#x3c;Пред',
                nextText: 'След&#x3e;',
                currentText: 'Сегодня',
                monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь',
                    'Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
                monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн',
                    'Июл','Авг','Сен','Окт','Ноя','Дек'],
                dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
                dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
                dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
                dateFormat: 'dd.mm.yy',
                firstDay: 1,
                isRTL: false
            };

            $.datepicker.setDefaults($.datepicker.regional['ru']);

            $("input[name='MS_P_DATE']").mask("99.99.9999");

            $('.js-calendar-click').click(function () {
                $(this).find('.js-calendar-input').focus();
            });

            $('.js-time-input').each(function() {
                $(this).mask('99:99');
            });

            $('.js-calendar-input').each(function () {
                var $this = $(this);
                $this.datepicker({
                    //minDate: 2
                    minDate: 0
                });
            });

            $("select[name='MS_P_CLINIC']").on('change', function(){
                var clinicId = $(this).val();

                if(confidentialClinics[clinicId]){
                    $('.js-confidential-text').html(confidentialClinics[clinicId]);
                    $('.js-confidential-confirm').show();
                }else{
                    $('.js-confidential-confirm').hide();
                }

                $('.js-confidential-confirm')
                    .find("input[name='MS_P_SHOW_CONFIDENTIAL_CONFIRM']")
                    .prop('checked', false)
                    .trigger('change');
            });

            $('.js-confidential-text').on('click', 'a', function(e){
                e.preventDefault();

                window.open($(this).attr('href'), '_blank');
            });
        </script>
        </div>
		</div>
	</div>
<?php endif; ?>

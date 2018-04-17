<link rel="stylesheet" href="main.css">
<link rel="stylesheet" href="response.css">
<?require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("BG_IMAGE", "/local/templates/voel/i/bg/faq.jpg");
$APPLICATION->SetPageProperty("TEXT_ON_IMAGE", "Калькулятор стоимости технологического присоединения");
$APPLICATION->SetTitle("Калькулятор стоимости технологического присоединения");?>

<div class="wrapper" id="calculate" v-cloak>
    <!-- вид заявки / категория / мощность -->
    <div id="first">
        <div class="left form-box">
            <div class="select_calc">
                <select size="1" v-model="S1" class="no_styled">
                    <option value="0" disabled selected> Вид заявки</option>
                    <option value="1">1. Постоянное присоединение</option>
                    <option value="2">2. Временное присоединение</option>
                </select>
            </div>
            <div class="input_1">
                <label>Заявляемая мощность
                    <input type="number" id="N" min="0" max="99999" v-model.number="N" required autofocus>
                    кВт</label>
                <p class="errortext" v-show="N && isNValid">Вы ввели максимально допустимое количество символов</p>
            </div>
        </div>

        <div class="right">
            <div class="input">
                <p class="safe"> Категория надежности </p>
                <p>
                    <label>
                        <input class="Category" type="radio" name="Category" value="3" v-model="Category" checked> 3 –
                        <span>Питание</span> от одного <span>независимого</span> источника
                    </label>
                </p>
                <p>
                    <label v-show="S1!=2">
                        <input class="Category" type="radio" name="Category" value="2" v-model="Category"
                               :disabled="S1 == 2"> 2 – <span>Питание</span> от двух <span>независимых</span> источников
                    </label>
                </p>
            </div>
        </div>
    </div>

    <!-- при условиях -->
    <div id="second">
<!--    <div class="check" v-if="N<=15 && S1 == 1 && S1!==0 && Category==3"> Было только при временном присоединен  -->
		<div class="check" v-if="N<=15 && S1!==0 && Category==3"> <!-- Появляется при обоих условиях присоединения -->
            <p>
                <label>
                    <input type="checkbox" value="check" v-model.lazy="Conditions" key="Conditions-checkbox">
                    <span class="jq-checkbox" :class="{ checked: Conditions }"></span> При условиях:
                </label>
            </p>
            <ul>
                <li> если в границах муниципальных районов, городских округов и на внутригородских территориях городов
                    федерального значения одно и то же лицо может осуществить технологическое присоединение
                    энергопринимающих устройств, принадлежащих ему на праве собственности или на ином законном
                    основании, с платой за технологическое присоединение в размере, не превышающем 550 рублей, не более
                    одного раза в течение 3 лет.
                </li>
                <li> при технологическом присоединении энергопринимающих устройств, принадлежащих лицам, владеющим
                    земельным участком по договору аренды, заключенному на срок не более одного года, на котором
                    расположены присоединяемые энергопринимающие устройства;
                </li>
                <li> при технологическом присоединении энергопринимающих устройств, расположенных в жилых помещениях
                    многоквартирных домов.
                </li>
				<li> расстояние от границ участка заявителя до объектов электросетевого хозяйства на уровне напряжения 
				    до 20 кВ включительно необходимого заявителю класса напряжения сетевой организации, в которую подана 
				    заявка, составляет более 300 метров в городах и поселках городского типа и более 500 метров в сельской 
					местности.
                </li>
            </ul>
        </div>
    </div>

    <!-- строительство  -->
    <div id="third">
       <div id="build" v-show="N>149">
            <div class="check">
                <p>
                    <label>
                        <input type="checkbox" value="check" v-model.lazy="Build" class="no_styled">
                        <span class="jq-checkbox" :class="{ checked: Build }"></span> Необходимо строительство
                    </label>
                </p>
            </div>
		</div>
    </div>

    <!-- класс напряжения  -->	
	<div id="third">
       <div id="build" >
			<div class="select">
                <select size="1" v-model.lazy="VoltageClass" class="no_styled">
                    <option value="0" disabled selected> Класс напряжения</option>
                    <option value="1">0,4 кВ</option>
                    <option value="2">6 (10) кВ</option>
                </select>
            </div>
		</div>
    </div>	

    <!-- расчет по -->	
	<div id="third">
		<div id="build" v-show="S1 == 2 && N<15 || S1 == 1 && N>15 || Conditions && S1 !== 1 || S1 == 1 && N>149 || S1 == 1 && N<=149 && Category==2">	
		<!-- <div id="build" v-show="Conditions && S1 == 1 || S1 == 1 && N>149 || S1 == 1 && N<=149 && Category==2">	 -->
			<div class="calc">
                <select size="1" v-model.lazy="Calculate" class="no_styled">
                    <option value="0" disabled selected> Расчет по:</option>
                    <option value="1">Мощности</option>
                    <option value="2">Стандартизированной ставке</option>
                </select>
            </div>
		</div>
    </div>

    <!-- выбор территории -->	
	<div id="third">
       <div id="build" v-show="S1 == 2 && N>15 || Conditions && S1 !== 1 || S1 == 1 && N>149 || S1 == 1 && N<=149">
			<div class="calc">
                <select size="1" v-model.lazy="Territory" class="no_styled"> <!-- работает -->
                    <option value="0" disabled selected> Выбор территории:</option>
                    <option value="1">для территорий городских населенных пунктов</option>
                    <option value="2">для территорий, не относящихся к территориям городских населенных пунктов</option>
                </select>
            </div>
		</div>
    </div>
			
    <!-- по мощности - из стандартной ставке -->
    <div class="forth_2_wrap">
        <div class="left_2">
            <div class="forth_2" :class="{forth_2_small:Category==2}"
				v-show="Conditions && VoltageClass !==0 && Build && Calculate == 1 && S1 == 1 || 
				S1 == 1 && N>15 && VoltageClass !==0 && Build && Calculate == 1 || 
				S1 == 1 && N<=15 && Category==2 && VoltageClass !==0 && Build && Calculate == 1">
                <p class="options">Параметры для расчета стоимости по мощности</p>
<!--                 <div class="index">
                   <p>Индекс изменения сметной стоимости за:</p>
                    <div class="sqrt">{{(j)?j.Z.quarter : ""}}</div>
                    <p class="quart">квартал<span class="hideMobile">2017г.</span></p>
                </div> -->
                <div class="source" v-for="item in Lines_one" :key="item.id">
                    <div class="select">
                        <select class="no_styled" v-model="item.select" v-on:change="index(item, 'one')">
                            <option value="0" disabled selected> Выберите тип линии</option>
                            <option value="1" v-if="Show_Ch2_1"  v-show="VoltageClass == 1" >Воздушная линия 0,4кВ</option>
                            <option value="2" v-if="Show_Ch2_2"  v-show="VoltageClass == 1" >Воздушная линия изолированная 0,4кВ</option>
                            <option value="3" v-if="Show_Ch2_3"  v-show="VoltageClass == 2" >Воздушная линия 6-10кВ</option>
                            <option value="4" v-if="Show_Ch2_4"  v-show="VoltageClass == 2" >Воздушная линия изолированная 6-10кВ</option>
                            <option value="5" v-if="Show_Ch3_1"  v-show="VoltageClass == 1" >Кабельная линия 0,4кВ</option>
                            <option value="6" v-if="Show_Ch3_2"  v-show="VoltageClass == 2" >Кабельная линия 6-10кВ</option>
                            <option value="7" v-if="Show_Ch3_1_1"  v-show="VoltageClass == 1" >Кабельная линия 0,4кВ с применением ГНБ*</option>
                            <option value="8" v-if="Show_Ch3_2_1"  v-show="VoltageClass == 2" >Кабельная линия 6-10кВ с применением ГНБ*</option>
                        </select>
                    </div>
<!-- выбор матриалов изоляции для Линии 1 - не долделана!!!  скорирована с Выбора типа линии -->					
					<div class="select">
                        <select class="no_styled" v-model="item.Tip_VL" v-on:change="index(item, 'one')">
                            <option value="0" disabled selected> Выберите способ прокладки, материал, сечение</option>
                            <option value="1" disabled selected>изолированным проводом:</option>
							<option value="2" v-if="Show_Ch4_1" v-show="Territory == 1 && VoltageClass == 1" >- сталеалюминевый от 50 до 100 мм2</option> <!-- Город, 0,4 изолированный -->
							<option value="3" v-if="Show_Ch4_2" v-show="Territory == 1 && VoltageClass == 1" >- сталеалюминевый от 100 до 200 мм2</option> <!-- Город, 0,4 изолированный -->
							<option value="4" v-if="Show_Ch4_3" v-show="Territory == 1 && VoltageClass == 1" >- алюминевый от 100 до 200 мм2</option> <!-- Город, 0,4 изолированный -->
							<option value="5" v-if="Show_Ch4_4" v-show="Territory == 1 && VoltageClass == 2" >- сталеалюминевый до 50 мм2 включительно</option> <!-- Город, 6-10 изолированный -->
							<option value="6" v-if="Show_Ch4_5" v-show="Territory == 1 && VoltageClass == 2" >- сталеалюминевый от 50 до 100 мм2</option> <!-- Город, 6-10 изолированный -->
							<option value="7" v-if="Show_Ch4_6" v-show="Territory == 1 && VoltageClass == 2" >- алюминевый от 50 до 100 мм2</option> <!-- Город, 6-10 изолированный -->
							<option value="8" v-if="Show_Ch4_7" v-show="Territory == 1 && VoltageClass == 2" >- алюминевый от 100 до 200 мм2</option> <!-- Город, 6-10 изолированный -->
							<option value="9" v-if="Show_Ch4_8" v-show="Territory == 2 && VoltageClass == 2" >- сталеалюминевый до 50 мм2 включительно</option> <!-- НЕ Город, 6-10 изолированный -->
							<option value="10" v-if="Show_Ch4_9" v-show="Territory == 2 && VoltageClass == 2" >- алюминевый от 50 до 100 мм2</option> <!-- НЕ Город, 6-10 изолированный -->
							<option value="11" disabled selected>не изолированным проводом:</option>
							<option value="12" v-if="Show_Ch4_10" v-show="Territory == 1 && VoltageClass == 2" >- сталеалюминевый от 50 до 100 мм2</option> <!-- Город, 6-10 неизолированный -->
							<option value="13" disabled selected>в траншеях многожильным кабелем:</option>
							<option value="14" v-if="Show_Ch4_11" v-show="Territory == 1 && VoltageClass == 1" >- резиновая и пластмассовая от 100 до 200 мм2</option> <!-- Город, 0,4 траншеи многожильный -->
							<option value="15" v-if="Show_Ch4_12" v-show="Territory == 1 && VoltageClass == 1" >- бумажная от 50 до 100 мм2</option> <!-- Город, 0,4 траншеи многожильный -->
							<option value="16" v-if="Show_Ch4_13" v-show="Territory == 1 && VoltageClass == 1" >- бумажная от 100 до 200 мм2</option> <!-- Город, 0,4 траншеи многожильный -->
							<option value="17" v-if="Show_Ch4_14" v-show="Territory == 1 && VoltageClass == 2" >- резиновая и пластмассовая от 200 до 500 мм2</option> <!-- Город, 6-10 траншеи многожильный -->
							<option value="18" v-if="Show_Ch4_15" v-show="Territory == 1 && VoltageClass == 2" >- бумажная от 50 до 100 мм2</option> <!-- Город, 6-10 траншеи многожильный -->
							<option value="19" v-if="Show_Ch4_16" v-show="Territory == 1 && VoltageClass == 2" >- бумажная от 100 до 200 мм2</option> <!-- Город, 6-10 траншеи многожильный -->
							<option value="20" v-if="Show_Ch4_17" v-show="Territory == 1 && VoltageClass == 2" >- бумажная от 200 до 500 мм2</option> <!-- Город, 6-10 траншеи многожильный -->
							<option value="21" disabled selected>в каналах одножильным кабелем:</option>
							<option value="22" v-if="Show_Ch4_18" v-show="Territory == 1 && VoltageClass == 1" >- резиновая и пластмассовая от 100 до 200 мм2</option> <!-- Город, 0,4 каналы одножильный -->
							<option value="23" v-if="Show_Ch4_19" v-show="Territory == 1 && VoltageClass == 1" >- резиновая и пластмассовая от 200 до 500 мм2</option> <!-- Город, 0,4 каналы одножильный -->
							<option value="24" disabled selected>в каналах многожильным кабелем:</option>
							<option value="25" v-if="Show_Ch4_20" v-show="Territory == 1 && VoltageClass == 1" >- резиновая и пластмассовая от 100 до 200 мм2</option> <!-- Город, 0,4 каналы многожильный -->
							<option value="26" v-if="Show_Ch4_21" v-show="Territory == 1 && VoltageClass == 1" >- резиновая и пластмассовая от 200 до 500 мм2</option> <!-- Город, 0,4 каналы многожильный -->
							<option value="27" v-if="Show_Ch4_22" v-show="Territory == 1 && VoltageClass == 2" >- бумажная от 50 до 100 мм2</option> <!-- Город, 6-10 каналы многожильный -->
							<option value="28" v-if="Show_Ch4_23" v-show="Territory == 1 && VoltageClass == 2" >- бумажная от 100 до 200 мм2</option> <!-- Город, 6-10 каналы многожильный -->
							<option value="29" v-if="Show_Ch4_24" v-show="Territory == 1 && VoltageClass == 2" >- бумажная от 200 до 500 мм2</option> <!-- Город, 6-10 каналы многожильный -->
							<option value="30" disabled selected>методом ГНБ многожильным кабелем:</option>
							<option value="31" v-if="Show_Ch4_25" v-show="Territory == 1 && VoltageClass == 1" >- резиновая и пластмассовая от 100 до 200 мм2</option> <!-- Город, 0,4 ГНБ многожильный -->
							<option value="32" v-if="Show_Ch4_26" v-show="Territory == 1 && VoltageClass == 2" >- бумажная от 50 до 100 мм2</option> <!-- Город, 6-10 ГНБ многожильный -->
							<option value="33" v-if="Show_Ch4_27" v-show="Territory == 1 && VoltageClass == 2" >- бумажная от 100 до 200 мм2</option> <!-- Город, 6-10 ГНБ многожильный -->
							<option value="34" v-if="Show_Ch4_28" v-show="Territory == 1 && VoltageClass == 2" >- бумажная от 200 до 500 мм2</option> <!-- Город, 6-10 ГНБ многожильный -->
							<option value="35" disabled selected>методом ГНБ одножильным кабелем:</option>
							<option value="36" v-if="Show_Ch4_29" v-show="Territory == 1 && VoltageClass == 2" >- резиновая и пластмассовая от 200 до 500 мм2</option> <!-- Город, 6-10 ГНБ многожильный -->
                        </select>
                    </div>
<!-- 					
                    <div class="long">
                        <div class="left_text"><input type="number" step="0.01" placeholder="длина (км)" v-model="item.L">
                        
						</div>

                    </div>  -->
                </div>

                <div class="wrap_2">
                    <button type="button" class="btn-add" @click="addLine('one')">Добавить линию</button>
                    <button type="button" class="btn-delete" @click="deleteLine('one')">Удалить линию</button>
                </div>

                <p class="sourse_bottom"><b>*ГНБ -</b> Горизонтальное бурение или Горизонтальное направленное бурение —
                    управляемый бестраншейный <br>
                    метод прокладывания подземных коммуникаций, основанный на использовании специальных буровых <br>
                    комплексов.</p>
            </div>
        </div>

        <div class="right_2" v-show="Category == 2">
            <div class="forth_2_small"
                 v-show="Conditions && VoltageClass !==0 && Build && Calculate == 1 ||
                    S1 == 1 && N>15 && VoltageClass !==0 && Build && Calculate == 1 || 
                    S1 == 1 && N<=15 && Category==2 && VoltageClass !==0 && Build && Calculate == 1">
                <p class="options">Параметры для расчета стоимости по мощности для 2-го источника</p>
<!--                 <div class="index">
                    <p>Индекс изменения сметной стоимости за:</p>
                    <div class="sqrt">{{(j)?j.Z.quarter : ""}}</div>
                    <p class="quart">квартал 2017г.</p>
                </div>-->
                <div class="source" v-for="item in Lines_two" :key="item.id">
                    <div class="select">
                        <select class="no_styled" v-model="item.select" v-on:change="index(item, 'two')">
                            <option value="0" disabled selected> Выберите тип линии</option>
                            <option value="1" v-if="Show_Ch2_1"  v-show="VoltageClass == 1" >Воздушная линия 0,4кВ</option>
                            <option value="2" v-if="Show_Ch2_2"  v-show="VoltageClass == 1" >Воздушная линия изолированная 0,4кВ</option>
                            <option value="3" v-if="Show_Ch2_3"  v-show="VoltageClass == 2" >Воздушная линия 6-10кВ</option>
                            <option value="4" v-if="Show_Ch2_4"  v-show="VoltageClass == 2" >Воздушная линия изолированная 6-10кВ</option>
                            <option value="5" v-if="Show_Ch3_1"  v-show="VoltageClass == 1" >Кабельная линия 0,4кВ</option>
                            <option value="6" v-if="Show_Ch3_2"  v-show="VoltageClass == 2" >Кабельная линия 6-10кВ</option>
                            <option value="7" v-if="Show_Ch3_1_1"  v-show="VoltageClass == 1" >Кабельная линия 0,4кВ с применением ГНБ*</option>
                            <option value="8" v-if="Show_Ch3_2_1"  v-show="VoltageClass == 2" >Кабельная линия 6-10кВ с применением ГНБ*</option>
                        </select>
                    </div>
					
					<div class="select" v-for="item in Tip_VL" :key="item.id">
                        <select class="no_styled" v-model="item.Tip_VL" v-on:change="index(item, 'two')">
                            <option value="0" disabled selected> Выберите способ прокладки, материал, сечение</option>
                            <option value="1" disabled selected>изолированным проводом:</option>
							<option value="2" v-if="Show_Ch4_1" v-show="Territory == 1 && VoltageClass == 1" >- сталеалюминевый от 50 до 100 мм2</option> <!-- Город, 0,4 изолированный -->
							<option value="3" v-if="Show_Ch4_2" v-show="Territory == 1 && VoltageClass == 1" >- сталеалюминевый от 100 до 200 мм2</option> <!-- Город, 0,4 изолированный -->
							<option value="4" v-if="Show_Ch4_3" v-show="Territory == 1 && VoltageClass == 1" >- алюминевый от 100 до 200 мм2</option> <!-- Город, 0,4 изолированный -->
							<option value="5" v-if="Show_Ch4_4" v-show="Territory == 1 && VoltageClass == 2" >- сталеалюминевый до 50 мм2 включительно</option> <!-- Город, 6-10 изолированный -->
							<option value="6" v-if="Show_Ch4_5" v-show="Territory == 1 && VoltageClass == 2" >- сталеалюминевый от 50 до 100 мм2</option> <!-- Город, 6-10 изолированный -->
							<option value="7" v-if="Show_Ch4_6" v-show="Territory == 1 && VoltageClass == 2" >- алюминевый от 50 до 100 мм2</option> <!-- Город, 6-10 изолированный -->
							<option value="8" v-if="Show_Ch4_7" v-show="Territory == 1 && VoltageClass == 2" >- алюминевый от 100 до 200 мм2</option> <!-- Город, 6-10 изолированный -->
							<option value="9" v-if="Show_Ch4_8" v-show="Territory == 2 && VoltageClass == 2" >- сталеалюминевый до 50 мм2 включительно</option> <!-- НЕ Город, 6-10 изолированный -->
							<option value="10" v-if="Show_Ch4_9" v-show="Territory == 2 && VoltageClass == 2" >- алюминевый от 50 до 100 мм2</option> <!-- НЕ Город, 6-10 изолированный -->
							<option value="11" disabled selected>не изолированным проводом:</option>
							<option value="12" v-if="Show_Ch4_10" v-show="Territory == 1 && VoltageClass == 2" >- сталеалюминевый от 50 до 100 мм2</option> <!-- Город, 6-10 неизолированный -->
							<option value="13" disabled selected>в траншеях многожильным кабелем:</option>
							<option value="14" v-if="Show_Ch4_11" v-show="Territory == 1 && VoltageClass == 1" >- резиновая и пластмассовая от 100 до 200 мм2</option> <!-- Город, 0,4 траншеи многожильный -->
							<option value="15" v-if="Show_Ch4_12" v-show="Territory == 1 && VoltageClass == 1" >- бумажная от 50 до 100 мм2</option> <!-- Город, 0,4 траншеи многожильный -->
							<option value="16" v-if="Show_Ch4_13" v-show="Territory == 1 && VoltageClass == 1" >- бумажная от 100 до 200 мм2</option> <!-- Город, 0,4 траншеи многожильный -->
							<option value="17" v-if="Show_Ch4_14" v-show="Territory == 1 && VoltageClass == 2" >- резиновая и пластмассовая от 200 до 500 мм2</option> <!-- Город, 6-10 траншеи многожильный -->
							<option value="18" v-if="Show_Ch4_15" v-show="Territory == 1 && VoltageClass == 2" >- бумажная от 50 до 100 мм2</option> <!-- Город, 6-10 траншеи многожильный -->
							<option value="19" v-if="Show_Ch4_16" v-show="Territory == 1 && VoltageClass == 2" >- бумажная от 100 до 200 мм2</option> <!-- Город, 6-10 траншеи многожильный -->
							<option value="20" v-if="Show_Ch4_17" v-show="Territory == 1 && VoltageClass == 2" >- бумажная от 200 до 500 мм2</option> <!-- Город, 6-10 траншеи многожильный -->
							<option value="21" disabled selected>в каналах одножильным кабелем:</option>
							<option value="22" v-if="Show_Ch4_18" v-show="Territory == 1 && VoltageClass == 1" >- резиновая и пластмассовая от 100 до 200 мм2</option> <!-- Город, 0,4 каналы одножильный -->
							<option value="23" v-if="Show_Ch4_19" v-show="Territory == 1 && VoltageClass == 1" >- резиновая и пластмассовая от 200 до 500 мм2</option> <!-- Город, 0,4 каналы одножильный -->
							<option value="24" disabled selected>в каналах многожильным кабелем:</option>
							<option value="25" v-if="Show_Ch4_20" v-show="Territory == 1 && VoltageClass == 1" >- резиновая и пластмассовая от 100 до 200 мм2</option> <!-- Город, 0,4 каналы многожильный -->
							<option value="26" v-if="Show_Ch4_21" v-show="Territory == 1 && VoltageClass == 1" >- резиновая и пластмассовая от 200 до 500 мм2</option> <!-- Город, 0,4 каналы многожильный -->
							<option value="27" v-if="Show_Ch4_22" v-show="Territory == 1 && VoltageClass == 2" >- бумажная от 50 до 100 мм2</option> <!-- Город, 6-10 каналы многожильный -->
							<option value="28" v-if="Show_Ch4_23" v-show="Territory == 1 && VoltageClass == 2" >- бумажная от 100 до 200 мм2</option> <!-- Город, 6-10 каналы многожильный -->
							<option value="29" v-if="Show_Ch4_24" v-show="Territory == 1 && VoltageClass == 2" >- бумажная от 200 до 500 мм2</option> <!-- Город, 6-10 каналы многожильный -->
							<option value="30" disabled selected>методом ГНБ многожильным кабелем:</option>
							<option value="31" v-if="Show_Ch4_25" v-show="Territory == 1 && VoltageClass == 1" >- резиновая и пластмассовая от 100 до 200 мм2</option> <!-- Город, 0,4 ГНБ многожильный -->
							<option value="32" v-if="Show_Ch4_26" v-show="Territory == 1 && VoltageClass == 2" >- бумажная от 50 до 100 мм2</option> <!-- Город, 6-10 ГНБ многожильный -->
							<option value="33" v-if="Show_Ch4_27" v-show="Territory == 1 && VoltageClass == 2" >- бумажная от 100 до 200 мм2</option> <!-- Город, 6-10 ГНБ многожильный -->
							<option value="34" v-if="Show_Ch4_28" v-show="Territory == 1 && VoltageClass == 2" >- бумажная от 200 до 500 мм2</option> <!-- Город, 6-10 ГНБ многожильный -->
							<option value="35" disabled selected>методом ГНБ одножильным кабелем:</option>
							<option value="36" v-if="Show_Ch4_29" v-show="Territory == 1 && VoltageClass == 2" >- резиновая и пластмассовая от 200 до 500 мм2</option> <!-- Город, 6-10 ГНБ многожильный -->
                        </select>
                    </div>
<!--					
                    <div class="long">
                        <div class="left_text"><input type="number" step="0.01" placeholder="длина (км)" v-model="item.L">
                        
						</div>

                    </div> -->
                </div>

                <div class="wrap_2">
                    <button type="button" class="btn-add" @click="addLine('two')" @mouseenter="hover = true" @mouseleave="hover = false" :class="hover ? 'btn-blue' : ''">Добавить линию</button>
                    <button type="button" class="btn-delete" @click="deleteLine('two')">Удалить линию</button>
                </div>

                <p class="sourse_bottom"><b>*ГНБ -</b> Горизонтальное бурение или Горизонтальное направленное бурение —
                    управляемый бестраншейный <br>
                    метод прокладывания подземных коммуникаций, основанный на использовании специальных буровых <br>
                    комплексов.</p>
            </div>
        </div>
    </div>

    <!-- по стандартизированной ставке -->
    <div class="forth_2_wrap">
        <div class="left_2">
            <div class="forth_2" :class="{forth_2_small:Category==2}"
                 v-show="Conditions && VoltageClass !==0 && Build && Calculate == 2 && S1 == 1 || 
                 S1 == 1 && N>15 && VoltageClass !==0 && Build && Calculate == 2 || 
                 S1 == 1 && N<=15 && Category==2 && VoltageClass !==0 && Build && Calculate == 2">
                <p class="options">Параметры для расчета стоимости по стандартизированной ставке</p>
<!--                 <div class="index">
                   <p>Индекс изменения сметной стоимости за:</p>
                    <div class="sqrt">{{(j)?j.Z.quarter : ""}}</div>
                    <p class="quart">квартал<span class="hideMobile">2017г.</span></p>
                </div> -->
                <div class="source" v-for="item in Lines_one" :key="item.id">
<!--                <pre>{{item}}</pre> -->
                
                    <div class="select">
                        <select class="no_styled" v-model="item.select" v-on:change="index(item, 'one')">
                            <option value="0" disabled selected> Выберите тип линии</option>
                            <option value="1" v-if="Show_Ch2_1"  v-show="VoltageClass == 1" >Воздушная линия 0,4кВ</option>
                            <option value="2" v-if="Show_Ch2_2"  v-show="VoltageClass == 1" >Воздушная линия изолированная 0,4кВ</option>
                            <option value="3" v-if="Show_Ch2_3"  v-show="VoltageClass == 2" >Воздушная линия 6-10кВ</option>
                            <option value="4" v-if="Show_Ch2_4"  v-show="VoltageClass == 2" >Воздушная линия изолированная 6-10кВ</option>
                            <option value="5" v-if="Show_Ch3_1"  v-show="VoltageClass == 1" >Кабельная линия 0,4кВ</option>
                            <option value="6" v-if="Show_Ch3_2"  v-show="VoltageClass == 2" >Кабельная линия 6-10кВ</option>
                            <option value="7" v-if="Show_Ch3_1_1"  v-show="VoltageClass == 1" >Кабельная линия 0,4кВ с применением ГНБ*</option>
                            <option value="8" v-if="Show_Ch3_2_1"  v-show="VoltageClass == 2" >Кабельная линия 6-10кВ с применением ГНБ*</option>
                        </select>
                    </div>
<!-- выбор матриалов изоляции для Линии 1 - не долделана!!!  скопирована с Выбора типа линии -->					
					<div class="select">
<!--				<pre>{{Lines_one}}</pre>
					<pre>{{Tip_VL}}</pre> -->
                        <select class="no_styled" v-model="item.Tip_VL" v-on:change="index(item, 'one')">
                            <option value="0" disabled selected> Выберите способ прокладки, материал, сечение</option>
                            <option value="1" disabled selected>изолированным проводом:</option>
							<option value="2" v-if="Show_Ch4_1" v-show="Territory == 1 && item.select == 2" >- сталеалюминевый от 50 до 100 мм2</option> <!-- Город, 0,4 изолированный -->
							<option value="3" v-if="Show_Ch4_2" v-show="Territory == 1 && VoltageClass == 1" >- сталеалюминевый от 100 до 200 мм2</option> <!-- Город, 0,4 изолированный -->
							<option value="4" v-if="Show_Ch4_3" v-show="Territory == 1 && VoltageClass == 1" >- алюминевый от 100 до 200 мм2</option> <!-- Город, 0,4 изолированный -->
							<option value="5" v-if="Show_Ch4_4" v-show="Territory == 1 && VoltageClass == 2" >- сталеалюминевый до 50 мм2 включительно</option> <!-- Город, 6-10 изолированный -->
							<option value="6" v-if="Show_Ch4_5" v-show="Territory == 1 && VoltageClass == 2" >- сталеалюминевый от 50 до 100 мм2</option> <!-- Город, 6-10 изолированный -->
							<option value="7" v-if="Show_Ch4_6" v-show="Territory == 1 && VoltageClass == 2" >- алюминевый от 50 до 100 мм2</option> <!-- Город, 6-10 изолированный -->
							<option value="8" v-if="Show_Ch4_7" v-show="Territory == 1 && VoltageClass == 2" >- алюминевый от 100 до 200 мм2</option> <!-- Город, 6-10 изолированный -->
							<option value="9" v-if="Show_Ch4_8" v-show="Territory == 2 && VoltageClass == 2" >- сталеалюминевый до 50 мм2 включительно</option> <!-- НЕ Город, 6-10 изолированный -->
							<option value="10" v-if="Show_Ch4_9" v-show="Territory == 2 && VoltageClass == 2" >- алюминевый от 50 до 100 мм2</option> <!-- НЕ Город, 6-10 изолированный -->
							<option value="11" disabled selected>не изолированным проводом:</option>
							<option value="12" v-if="Show_Ch4_10" v-show="Territory == 1 && VoltageClass == 2" >- сталеалюминевый от 50 до 100 мм2</option> <!-- Город, 6-10 неизолированный -->
							<option value="13" disabled selected>в траншеях многожильным кабелем:</option>
							<option value="14" v-if="Show_Ch4_11" v-show="Territory == 1 && VoltageClass == 1" >- резиновая и пластмассовая от 100 до 200 мм2</option> <!-- Город, 0,4 траншеи многожильный -->
							<option value="15" v-if="Show_Ch4_12" v-show="Territory == 1 && VoltageClass == 1" >- бумажная от 50 до 100 мм2</option> <!-- Город, 0,4 траншеи многожильный -->
							<option value="16" v-if="Show_Ch4_13" v-show="Territory == 1 && VoltageClass == 1" >- бумажная от 100 до 200 мм2</option> <!-- Город, 0,4 траншеи многожильный -->
							<option value="17" v-if="Show_Ch4_14" v-show="Territory == 1 && VoltageClass == 2" >- резиновая и пластмассовая от 200 до 500 мм2</option> <!-- Город, 6-10 траншеи многожильный -->
							<option value="18" v-if="Show_Ch4_15" v-show="Territory == 1 && VoltageClass == 2" >- бумажная от 50 до 100 мм2</option> <!-- Город, 6-10 траншеи многожильный -->
							<option value="19" v-if="Show_Ch4_16" v-show="Territory == 1 && VoltageClass == 2" >- бумажная от 100 до 200 мм2</option> <!-- Город, 6-10 траншеи многожильный -->
							<option value="20" v-if="Show_Ch4_17" v-show="Territory == 1 && VoltageClass == 2" >- бумажная от 200 до 500 мм2</option> <!-- Город, 6-10 траншеи многожильный -->
							<option value="21" disabled selected>в каналах одножильным кабелем:</option>
							<option value="22" v-if="Show_Ch4_18" v-show="Territory == 1 && VoltageClass == 1" >- резиновая и пластмассовая от 100 до 200 мм2</option> <!-- Город, 0,4 каналы одножильный -->
							<option value="23" v-if="Show_Ch4_19" v-show="Territory == 1 && VoltageClass == 1" >- резиновая и пластмассовая от 200 до 500 мм2</option> <!-- Город, 0,4 каналы одножильный -->
							<option value="24" disabled selected>в каналах многожильным кабелем:</option>
							<option value="25" v-if="Show_Ch4_20" v-show="Territory == 1 && VoltageClass == 1" >- резиновая и пластмассовая от 100 до 200 мм2</option> <!-- Город, 0,4 каналы многожильный -->
							<option value="26" v-if="Show_Ch4_21" v-show="Territory == 1 && VoltageClass == 1" >- резиновая и пластмассовая от 200 до 500 мм2</option> <!-- Город, 0,4 каналы многожильный -->
							<option value="27" v-if="Show_Ch4_22" v-show="Territory == 1 && VoltageClass == 2" >- бумажная от 50 до 100 мм2</option> <!-- Город, 6-10 каналы многожильный -->
							<option value="28" v-if="Show_Ch4_23" v-show="Territory == 1 && VoltageClass == 2" >- бумажная от 100 до 200 мм2</option> <!-- Город, 6-10 каналы многожильный -->
							<option value="29" v-if="Show_Ch4_24" v-show="Territory == 1 && VoltageClass == 2" >- бумажная от 200 до 500 мм2</option> <!-- Город, 6-10 каналы многожильный -->
							<option value="30" disabled selected>методом ГНБ многожильным кабелем:</option>
							<option value="31" v-if="Show_Ch4_25" v-show="Territory == 1 && VoltageClass == 1" >- резиновая и пластмассовая от 100 до 200 мм2</option> <!-- Город, 0,4 ГНБ многожильный -->
							<option value="32" v-if="Show_Ch4_26" v-show="Territory == 1 && VoltageClass == 2" >- бумажная от 50 до 100 мм2</option> <!-- Город, 6-10 ГНБ многожильный -->
							<option value="33" v-if="Show_Ch4_27" v-show="Territory == 1 && VoltageClass == 2" >- бумажная от 100 до 200 мм2</option> <!-- Город, 6-10 ГНБ многожильный -->
							<option value="34" v-if="Show_Ch4_28" v-show="Territory == 1 && VoltageClass == 2" >- бумажная от 200 до 500 мм2</option> <!-- Город, 6-10 ГНБ многожильный -->
							<option value="35" disabled selected>методом ГНБ одножильным кабелем:</option>
							<option value="36" v-if="Show_Ch4_29" v-show="Territory == 1 && VoltageClass == 2" >- резиновая и пластмассовая от 200 до 500 мм2</option> <!-- Город, 6-10 ГНБ многожильный -->
                        </select>
                    </div>
					
                    <div class="long">
                        <div class="left_text"><input type="number" step="0.01" placeholder="длина (км)" v-model="item.L">
                        
						</div>

                    </div>
                </div>

                <div class="wrap_2">
                    <button type="button" class="btn-add" @click="addLine('one')">Добавить линию</button>
                    <button type="button" class="btn-delete" @click="deleteLine('one')">Удалить линию</button>
                </div>

                <p class="sourse_bottom"><b>*ГНБ -</b> Горизонтальное бурение или Горизонтальное направленное бурение —
                    управляемый бестраншейный <br>
                    метод прокладывания подземных коммуникаций, основанный на использовании специальных буровых <br>
                    комплексов.</p>
            </div>
        </div>

        <div class="right_2" v-show="Category == 2">
            <div class="forth_2_small"
                 v-show="Conditions && VoltageClass !==0 && Build && Calculate == 2 ||
                    S1 == 1 && N>15 && VoltageClass !==0 && Build && Calculate == 2 || 
                    S1 == 1 && N<=15 && Category==2 && VoltageClass !==0 && Build && Calculate == 2">
                <p class="options">Параметры для расчета стоимости по стандартизированной ставке для 2-го источника</p>
<!--                 <div class="index">
                    <p>Индекс изменения сметной стоимости за:</p>
                    <div class="sqrt">{{(j)?j.Z.quarter : ""}}</div>
                    <p class="quart">квартал 2017г.</p>
                </div>-->
                <div class="source" v-for="item in Lines_two" :key="item.id">
                    <div class="select">
                        <select class="no_styled" v-model="item.select" v-on:change="index(item, 'two')">
                            <option value="0" disabled selected> Выберите тип линии</option>
                            <option value="1" v-if="Show_Ch2_1"  v-show="VoltageClass == 1" >Воздушная линия 0,4кВ</option>
                            <option value="2" v-if="Show_Ch2_2"  v-show="VoltageClass == 1" >Воздушная линия изолированная 0,4кВ</option>
                            <option value="3" v-if="Show_Ch2_3"  v-show="VoltageClass == 2" >Воздушная линия 6-10кВ</option>
                            <option value="4" v-if="Show_Ch2_4"  v-show="VoltageClass == 2" >Воздушная линия изолированная 6-10кВ</option>
                            <option value="5" v-if="Show_Ch3_1"  v-show="VoltageClass == 1" >Кабельная линия 0,4кВ</option>
                            <option value="6" v-if="Show_Ch3_2"  v-show="VoltageClass == 2" >Кабельная линия 6-10кВ</option>
                            <option value="7" v-if="Show_Ch3_1_1"  v-show="VoltageClass == 1" >Кабельная линия 0,4кВ с применением ГНБ*</option>
                            <option value="8" v-if="Show_Ch3_2_1"  v-show="VoltageClass == 2" >Кабельная линия 6-10кВ с применением ГНБ*</option>
                        </select>
                    </div>
					
					<div class="select">
                        <select class="no_styled" v-model="item.Tip_VL" v-on:change="index(item, 'two')">
                            <option value="0" disabled selected> Выберите способ прокладки, материал, сечение</option>
                            <option value="1" disabled selected>изолированным проводом:</option>
							<option value="2" v-if="Show_Ch4_1" v-show="Territory == 1 && VoltageClass == 1" >- сталеалюминевый от 50 до 100 мм2</option> <!-- Город, 0,4 изолированный -->
							<option value="3" v-if="Show_Ch4_2" v-show="Territory == 1 && VoltageClass == 1" >- сталеалюминевый от 100 до 200 мм2</option> <!-- Город, 0,4 изолированный -->
							<option value="4" v-if="Show_Ch4_3" v-show="Territory == 1 && VoltageClass == 1" >- алюминевый от 100 до 200 мм2</option> <!-- Город, 0,4 изолированный -->
							<option value="5" v-if="Show_Ch4_4" v-show="Territory == 1 && VoltageClass == 2" >- сталеалюминевый до 50 мм2 включительно</option> <!-- Город, 6-10 изолированный -->
							<option value="6" v-if="Show_Ch4_5" v-show="Territory == 1 && VoltageClass == 2" >- сталеалюминевый от 50 до 100 мм2</option> <!-- Город, 6-10 изолированный -->
							<option value="7" v-if="Show_Ch4_6" v-show="Territory == 1 && VoltageClass == 2" >- алюминевый от 50 до 100 мм2</option> <!-- Город, 6-10 изолированный -->
							<option value="8" v-if="Show_Ch4_7" v-show="Territory == 1 && VoltageClass == 2" >- алюминевый от 100 до 200 мм2</option> <!-- Город, 6-10 изолированный -->
							<option value="9" v-if="Show_Ch4_8" v-show="Territory == 2 && VoltageClass == 2" >- сталеалюминевый до 50 мм2 включительно</option> <!-- НЕ Город, 6-10 изолированный -->
							<option value="10" v-if="Show_Ch4_9" v-show="Territory == 2 && VoltageClass == 2" >- алюминевый от 50 до 100 мм2</option> <!-- НЕ Город, 6-10 изолированный -->
							<option value="11" disabled selected>не изолированным проводом:</option>
							<option value="12" v-if="Show_Ch4_10" v-show="Territory == 1 && VoltageClass == 2" >- сталеалюминевый от 50 до 100 мм2</option> <!-- Город, 6-10 неизолированный -->
							<option value="13" disabled selected>в траншеях многожильным кабелем:</option>
							<option value="14" v-if="Show_Ch4_11" v-show="Territory == 1 && VoltageClass == 1" >- резиновая и пластмассовая от 100 до 200 мм2</option> <!-- Город, 0,4 траншеи многожильный -->
							<option value="15" v-if="Show_Ch4_12" v-show="Territory == 1 && VoltageClass == 1" >- бумажная от 50 до 100 мм2</option> <!-- Город, 0,4 траншеи многожильный -->
							<option value="16" v-if="Show_Ch4_13" v-show="Territory == 1 && VoltageClass == 1" >- бумажная от 100 до 200 мм2</option> <!-- Город, 0,4 траншеи многожильный -->
							<option value="17" v-if="Show_Ch4_14" v-show="Territory == 1 && VoltageClass == 2" >- резиновая и пластмассовая от 200 до 500 мм2</option> <!-- Город, 6-10 траншеи многожильный -->
							<option value="18" v-if="Show_Ch4_15" v-show="Territory == 1 && VoltageClass == 2" >- бумажная от 50 до 100 мм2</option> <!-- Город, 6-10 траншеи многожильный -->
							<option value="19" v-if="Show_Ch4_16" v-show="Territory == 1 && VoltageClass == 2" >- бумажная от 100 до 200 мм2</option> <!-- Город, 6-10 траншеи многожильный -->
							<option value="20" v-if="Show_Ch4_17" v-show="Territory == 1 && VoltageClass == 2" >- бумажная от 200 до 500 мм2</option> <!-- Город, 6-10 траншеи многожильный -->
							<option value="21" disabled selected>в каналах одножильным кабелем:</option>
							<option value="22" v-if="Show_Ch4_18" v-show="Territory == 1 && VoltageClass == 1" >- резиновая и пластмассовая от 100 до 200 мм2</option> <!-- Город, 0,4 каналы одножильный -->
							<option value="23" v-if="Show_Ch4_19" v-show="Territory == 1 && VoltageClass == 1" >- резиновая и пластмассовая от 200 до 500 мм2</option> <!-- Город, 0,4 каналы одножильный -->
							<option value="24" disabled selected>в каналах многожильным кабелем:</option>
							<option value="25" v-if="Show_Ch4_20" v-show="Territory == 1 && VoltageClass == 1" >- резиновая и пластмассовая от 100 до 200 мм2</option> <!-- Город, 0,4 каналы многожильный -->
							<option value="26" v-if="Show_Ch4_21" v-show="Territory == 1 && VoltageClass == 1" >- резиновая и пластмассовая от 200 до 500 мм2</option> <!-- Город, 0,4 каналы многожильный -->
							<option value="27" v-if="Show_Ch4_22" v-show="Territory == 1 && VoltageClass == 2" >- бумажная от 50 до 100 мм2</option> <!-- Город, 6-10 каналы многожильный -->
							<option value="28" v-if="Show_Ch4_23" v-show="Territory == 1 && VoltageClass == 2" >- бумажная от 100 до 200 мм2</option> <!-- Город, 6-10 каналы многожильный -->
							<option value="29" v-if="Show_Ch4_24" v-show="Territory == 1 && VoltageClass == 2" >- бумажная от 200 до 500 мм2</option> <!-- Город, 6-10 каналы многожильный -->
							<option value="30" disabled selected>методом ГНБ многожильным кабелем:</option>
							<option value="31" v-if="Show_Ch4_25" v-show="Territory == 1 && VoltageClass == 1" >- резиновая и пластмассовая от 100 до 200 мм2</option> <!-- Город, 0,4 ГНБ многожильный -->
							<option value="32" v-if="Show_Ch4_26" v-show="Territory == 1 && VoltageClass == 2" >- бумажная от 50 до 100 мм2</option> <!-- Город, 6-10 ГНБ многожильный -->
							<option value="33" v-if="Show_Ch4_27" v-show="Territory == 1 && VoltageClass == 2" >- бумажная от 100 до 200 мм2</option> <!-- Город, 6-10 ГНБ многожильный -->
							<option value="34" v-if="Show_Ch4_28" v-show="Territory == 1 && VoltageClass == 2" >- бумажная от 200 до 500 мм2</option> <!-- Город, 6-10 ГНБ многожильный -->
							<option value="35" disabled selected>методом ГНБ одножильным кабелем:</option>
							<option value="36" v-if="Show_Ch4_29" v-show="Territory == 1 && VoltageClass == 2" >- резиновая и пластмассовая от 200 до 500 мм2</option> <!-- Город, 6-10 ГНБ многожильный -->
                        </select>
                    </div>
					
                    <div class="long">
                        <div class="left_text"><input type="number" step="0.01" placeholder="длина (км)" v-model="item.L">
                        
						</div>

                    </div>
                </div>

                <div class="wrap_2">
                    <button type="button" class="btn-add" @click="addLine('two')" @mouseenter="hover = true" @mouseleave="hover = false" :class="hover ? 'btn-blue' : ''">Добавить линию</button>
                    <button type="button" class="btn-delete" @click="deleteLine('two')">Удалить линию</button>
                </div>

                <p class="sourse_bottom"><b>*ГНБ -</b> Горизонтальное бурение или Горизонтальное направленное бурение —
                    управляемый бестраншейный <br>
                    метод прокладывания подземных коммуникаций, основанный на использовании специальных буровых <br>
                    комплексов.</p>
            </div>
        </div>
    </div>
	
	<!-- Пункт секционирования, не работает, скопирован с "Необходимо строителство" (Section) Появляется при выборе Мощности N=>1000-->
    <div id="third">
<!--		<div id="build" v-show="Conditions && S1 == 1 || S1 == 1 && N>999 || S1 == 1 && N<=999 && Category==2">   Было раньше-->
		<div id="build" v-show="N>999"> <!--  Поправлено-->
            
 			<div class="check">
                <p>
                    <label>
                        <input type="checkbox" value="check" v-model.lazy="Rasp_punkt" class="no_styled"> <!-- работает -->
                        <span class="jq-checkbox" :class="{ checked: Rasp_punkt }"></span> Распределительный пункт
                    </label>
                </p>
            </div>

		</div>
    </div>
	
    <div class="block"
         v-show="Conditions && VoltageClass !==0  && Build && Calculate == 2 && S1 == 1 ||
                    S1 == 1 && N>15 && VoltageClass !==0 && Build && Calculate == 2">
        <div class="source" v-show="Show_BuildTP">
            <div class="in_2 check">
                <label>
                    <div class="line">
                        <div class="inLine">
                        <input type="checkbox" value="check" v-model="BuildTP">
                        <span class="jq-checkbox" :class="{ checked: BuildTP }"></span> Строительство ТП </div>
 <!--                       <div class="indx">Индекс <span class="hideMobile">изменения сметной стоимости за {{(j)?j.Z.quarter : ""}} квартал 2017г.</span> <span class="sqrt">{{(j)?j.Z.TP : ""}}</span></div>-->
                    </div>
                </label>
            </div>
			
            <div class="inner_wrap" v-show="BuildTP">
                <div class="left">
				
				<p class="BuildTP_radio_2">Однотрансформаторные</p>
				
                    <p v-show="radio1">
                        <label>
                            <input type="radio" class="BuildTP_radio_2" name="BuildTP_radio_2"
                                   v-show="radio1" value="C4_1" v-model="BuildTP_radio_2"
                                   :disabled="!BuildTP"> от 100 до 250 кВА включительно (1 Тр)
                        </label>
                    </p>
                    <p v-show="radio2">
                        <label>
                            <input type="radio" class="BuildTP_radio_2" name="BuildTP_radio_2"
                                   v-show="radio2" value="C4_2" v-model="BuildTP_radio_2"
                                   :disabled="!BuildTP"> от 250 до 500 кВА включительно (1 Тр)
                        </label>
                    </p>
                    <p v-show="radio3">
                        <label>
                            <input type="radio" class="BuildTP_radio_2" name="BuildTP_radio_2"
                                   v-show="radio3" value="C4_3" v-model="BuildTP_radio_2"
                                   :disabled="!BuildTP"> от 500 до 900 кВА включительно (1 Тр)
                        </label>
                    </p>
                    <p v-show="radio4">
                        <label>
                            <input type="radio" class="BuildTP_radio_2" name="BuildTP_radio_2"
                                                     v-show="radio4" value="C4_4" v-model="BuildTP_radio_2"
                                                     :disabled="!BuildTP"> от 500 до 900 кВА включительно (Не город)
                        </label>
                    </p>
 <!--                    <p v-show="radio5">
                        <label>
                            <input type="radio" class="BuildTP_radio_2" name="BuildTP_radio_2"
                                                     v-show="radio5" value="C4_5" v-model="BuildTP_radio_2"
                                                     :disabled="!BuildTP"> от 500 до 900 кВА включительно (2 Тр)
                        </label>
                    </p>
                    <p v-show="radio6">
                        <label>
                            <input type="radio" class="BuildTP_radio_2" name="BuildTP_radio_2"
                                                     v-show="radio6" value="C4_6" v-model="BuildTP_radio_2"
                                                     :disabled="!BuildTP"> свыше 900 кВА (2 Тр)
                        </label>
                    </p>
                    <p v-show="radio7">
                        <label>
                            <input type="radio" class="BuildTP_radio_2" name="BuildTP_radio_2"
                                                     v-show="radio7" value="C4_7" v-model="BuildTP_radio_2"
                                                     :disabled="!BuildTP"> от 100 до 250 кВа включительно (1 Тр)
                        </label>
                    </p>-->
                </div> 
                <div class="right">
				
					<p class="BuildTP_radio_2">Двухтрансформаторные</p>
				
                    <p v-show="radio5">
                        <label>
                            <input type="radio" class="BuildTP_radio_2" name="BuildTP_radio_2"
                                                     v-show="radio5" value="C4_8" v-model="BuildTP_radio_2"
                                                     :disabled="!BuildTP"> от 100 до 250 кВА включительно (2 Тр)
                        </label>
                    </p>
                    <p v-show="radio6">
                        <label>
                            <input type="radio" class="BuildTP_radio_2" name="BuildTP_radio_2"
                                                     v-show="radio6" value="C4_9" v-model="BuildTP_radio_2"
                                                     :disabled="!BuildTP"> от 250 до 500 кВА включительно (2 Тр)
                        </label>
                    </p>
                    <p v-show="radio7">
                        <label>
                            <input type="radio" class="BuildTP_radio_2" name="BuildTP_radio_2"
                                                      v-show="radio7" value="C4_10" v-model="BuildTP_radio_2"
                                                      :disabled="!BuildTP"> от 500 до 900 кВА включительно (2 Тр)
                        </label>
                    </p>
                    <p v-show="radio8">
                        <label>
                            <input type="radio" class="BuildTP_radio_2" name="BuildTP_radio_2"
                                                      v-show="radio8" value="C4_11" v-model="BuildTP_radio_2"
                                                      :disabled="!BuildTP"> свыше 900 кВА (2 Тр)
                        </label>
                    </p>
 <!--                   <p v-show="radio12">
                         <label>
                            <input type="radio" class="BuildTP_radio_2" name="BuildTP_radio_2"
                                                      v-show="radio12" value="C4_12" v-model="BuildTP_radio_2"
                                                      :disabled="!BuildTP"> Строительство ТП-6(10) кВ 2x630 кВА
                        </label>
                    </p>
                    <p v-show="radio13">
                        <label>
                            <input type="radio" class="BuildTP_radio_2" name="BuildTP_radio_2"
                                                      v-show="radio13" value="C4_13" v-model="BuildTP_radio_2"
                                                      :disabled="!BuildTP"> Строительство ТП-6(10) кВ 2x1000 кВА
                        </label>
                    </p>
                    <p v-show="radio14">
                        <label>
                            <input type="radio" class="BuildTP_radio_2" name="BuildTP_radio_2"
                                                      v-show="radio14" value="C4_14" v-model="BuildTP_radio_2"
                                                      :disabled="!BuildTP"> Строительство ТП-6(10) кВ 2x1250 кВА
                        </label>
                    </p>-->
                </div>
            </div>
        </div>
    </div>
    

    <!-- Расчет -->
    <div id="fifth">
        <div class="result">
            <p>Результаты:</p>
            <div class="total">
                <div class="wr_score">
                    <p>Итог по ставке за максимальную мощность (руб. с НДС)</p>
                    <p class="score">{{resultPw}} руб.</p>
                </div>
                <div class="wr_score">
                    <p>Итог по стандартизированной ставке (руб. с НДС)</p>
                    <p class="score">{{resultSt}} руб.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="vue.js"></script>
<!-- <script src="vue.min.js"></script> -->
<script src="calculate.js"></script>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>

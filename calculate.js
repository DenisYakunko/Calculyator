/*
 * @author  Vershkov Egor
 * @03/10/17
 */

$.getJSON('data.json', function(json) {
    app.j = json
});

Vue.config.devtools = true

var app = new Vue({
    el: '#calculate',
    data: {
        S1: 0, //вид заявки (1 - постоянное, 2 - временное)
        Category: "3",
        N: "", //заявленная мощность
        Conditions: false, //признак условия
        Build: false, //необходимо строительство
        BuildTP: false, //строительство ТП
        Show_BuildTP: true, //показывать блок строительства ТП
        BuildTP_radio_1: 0, //по мощности 
        BuildTP_radio_2: 0, //по стандартизированной
        Calculate: 0, //расчет по power-мощности, standart-стандартизированной
        VoltageClass: 0, //Класс напряжения
		Territory: 0, //Тип территории
        Rasp_punkt: false, //Пункт секционирования
        Lines_one: [{
            "id": 0,
            "select": 0,
            "index": 0,
			"Tip_VL": 0,
            "L": "",
        }], //Линии
        Lines_two: [{
            "id": 0,
            "select": 0,
            "index": 0,
			"Tip_VL": 0,
            "L": "",
        }], //Линии
		
//		Tip_VL: [{
//            "id": 0,
//            "select": 0,
//            "index": 0,
//        }], //Выбор спосбов прокладки, материалов и сечения для линии
        hover: false,

        Ch2_1: false,
        Ch2_2: false,
        Ch2_3: false,
        Ch2_4: false,

        Ch3_1: false,
        Ch3_2: false,
        Ch3_1_1: false,
        Ch3_2_1: false,

        Ch2__2_1: false,
        Ch2__2_2: false,
        Ch2__2_3: false,
        Ch2__2_4: false,

        Ch2__3_1: false,
        Ch2__3_2: false,
        Ch2__3_1_1: false,
        Ch2__3_2_1: false,

        //Способ прокладки, материал, сечение жилы		
		Ch4_1: true,
        Ch4_2: true,
        Ch4_3: true,
        Ch4_4: true,
		Ch4_5: true,
		Ch4_6: true,
		Ch4_7: true,
		Ch4_8: true,
		Ch4_9: true,
		Ch4_10: true,
		Ch4_11: true,
		Ch4_12: true,
		Ch4_13: true,
		Ch4_14: true,
		Ch4_15: true,
		Ch4_16: true,
		Ch4_17: true,
		Ch4_18: true,
		Ch4_19: true,
		Ch4_20: true,
		Ch4_21: true,
		Ch4_22: true,
		Ch4_23: true,
		Ch4_24: true,
		Ch4_25: true,
		Ch4_26: true,
		Ch4_27: true,
		Ch4_28: true,
		Ch4_29: true,


        //отображать/скрывать элементы
        Show_Ch2_1: true,
        Show_Ch2_2: true,
        Show_Ch2_3: true,
        Show_Ch2_4: true,
        Show_Ch3_1: true,
        Show_Ch3_2: true,
        Show_Ch3_1_1: true,
        Show_Ch3_2_1: true,
        Show_Ch2__2_1: true,
        Show_Ch2__2_2: true,
        Show_Ch2__2_3: true,
        Show_Ch2__2_4: true,
        Show_Ch2__3_1: true,
        Show_Ch2__3_2: true,
        Show_Ch2__3_1_1: true,
        Show_Ch2__3_2_1: true,


        //Отображать скрывать способ прокладки, материал, сечение жилы			
	    Show_Ch4_1: true,
        Show_Ch4_2: true,
        Show_Ch4_3: true,
        Show_Ch4_4: true,
		Show_Ch4_5: true,
		Show_Ch4_6: true,
		Show_Ch4_7: true,
		Show_Ch4_8: true,
		Show_Ch4_9: true,
		Show_Ch4_10: true,
		Show_Ch4_11: true,
		Show_Ch4_12: true,
		Show_Ch4_13: true,
		Show_Ch4_14: true,
		Show_Ch4_15: true,
		Show_Ch4_16: true,
		Show_Ch4_17: true,
		Show_Ch4_18: true,
		Show_Ch4_19: true,
		Show_Ch4_20: true,
		Show_Ch4_21: true,
		Show_Ch4_22: true,
		Show_Ch4_23: true,
		Show_Ch4_24: true,
		Show_Ch4_25: true,
		Show_Ch4_26: true,
		Show_Ch4_27: true,
		Show_Ch4_28: true,
		Show_Ch4_29: true,

        //Отоброжать строительство ТП
        radio1: false,
        radio2: false,
        radio3: false,
        radio4: false,
        radio5: false,
        radio6: false,
        radio7: false,
        radio8: false,
        radio9: false,
        radio10: false,
        radio11: false,
        radio12: false,
        radio13: false,
        radio14: false,

        j: null, // json с константами для расчета

    },
    watch: {
        N: function(N) {
            if (N == 0) {
                this.resultPw = 0
                this.resultSt = 0
            }

            if (N <= 99999 && N) {
                this.N = N
            } else {
                this.N = N - 100000
            }

            if (N < 0 || !N) {
                this.N = null
            }
			

			
        },
        S1: function() {
            this.resetAllBuilds()
			
        },
        VoltageClass: function() {
            this.resetAllBuilds()
            this.ResetVoltage()
        },
		
		Territory: function() {
            this.ResetVoltage()
        },
		
        Calculate: function() {
            this.resetAllBuilds()
        }
    },
    methods: {
        addLine: function(lineNumber) {
            if (lineNumber == 'one' || lineNumber == 'two') {
                this["Lines_" + lineNumber].push({
                    "id": this["Lines_" + lineNumber].length,
                    "select": 0,
                    "index": 0,
					"Tip_VL": 0,
                    "L": "",
                })
            }
        },
        deleteLine: function(lineNumber) {
            if (lineNumber == 'one' || lineNumber == 'two') {
                this["Lines_" + lineNumber].pop()
            }
        },
        resetAllBuilds: function() {
            this.BuildTP = false

            this.Ch2_1 = false
            this.Ch2_2 = false
            this.Ch2_3 = false
            this.Ch2_4 = false

            this.Ch3_1 = false
            this.Ch3_2 = false
            this.Ch3_1_1 = false
            this.Ch3_2_1 = false

            this.Ch2__2_1 = false
            this.Ch2__2_2 = false
            this.Ch2__2_3 = false
            this.Ch2__2_4 = false

            this.Ch2__3_1 = false
            this.Ch2__3_2 = false
            this.Ch2__3_1_1 = false
            this.Ch2__3_2_1 = false
        },
        showRadio: function(arr) {
            //Сбрасываем значения
            for (var i = 1; i <= 14; i++) {
                this["radio" + i] = false
            }
            if (Array.isArray(arr)) {
                arr.forEach(function(element, i) {
                    this["radio" + element] = true
                }, this);
            }
        },

        result: function(e) {
            if (e) {
                e += e * 18 / 100 // +18% НДС
                
                return (Math.round(e * 100) / 100).toLocaleString() //округляем результаты
            } else {
                return 0
            }
        },
        index: function(e, lineNumber) {
            if (lineNumber == 'one' || lineNumber == 'two') {
                if (e.select >= '1' && e.select <= '4') {
                    this["Lines_" + lineNumber][e.id].index = this.j.Z.VL
                }
                if (e.select >= '5' && e.select <= '8') {
                    this["Lines_" + lineNumber][e.id].index = this.j.Z.KL
                }
            }
        },
		
		ResetVoltage: function() {
            
            this.Lines_one[0].select = 0, 
            this.Lines_one[0].Tip_VL = 0
			this.Lines_one[0].L = 0
			
			this.Lines_two[0].select = 0, 
            this.Lines_two[0].Tip_VL = 0
			this.Lines_two[0].L = 0
			
			this.Lines_one[1].select = 0, 
            this.Lines_one[1].Tip_VL = 0
			this.Lines_one[1].L = 0
			
     		this.Lines_two[1].select = 0, 
            this.Lines_two[1].Tip_VL = 0
			this.Lines_two[1].L = 0
			
			this.Lines_one[2].select = 0, 
            this.Lines_one[2].Tip_VL = 0
			this.Lines_one[2].L = 0
			
     		this.Lines_two[2].select = 0, 
            this.Lines_two[2].Tip_VL = 0
			this.Lines_two[2].L = 0
			
			this.Lines_one[3].select = 0, 
            this.Lines_one[3].Tip_VL = 0
			this.Lines_one[3].L = 0
		
			this.Lines_two[3].select = 0, 
            this.Lines_two[3].Tip_VL = 0
			this.Lines_two[3].L = 0
			this.result()
            }
		
    },
	

	
    computed: {
        //Расчет по мощности
        resultPw: function() {
            if (this.j && this.N !== 0) {
                var x = 0
				var vr_pw = 0
				var ps_pw = 0
				var build_pw = 0
				var rpunkt_pw = 0
                var N = Number(this.N)
                var max = "max150"
                var cmax = "max15"

                //меньше 15 без строительства
                if (N <= 15 && !this.Conditions && N) {
                    if (this.Category == 3 && this.S1 !== 0|| this.Category == 0){  //добавлено && this.S1 !== 0
                        return 550
                    }
                }

                //для временного присоединения
                if (this.S1 == 2) {
                    this.Conditions == false
                    this.Build == false
								
					vr_pw = N * Number(this.j.Price_power.stavka_ps.C_1_do_15)
					console.log("временное_мощность", vr_pw)
				
					x = vr_pw
				}
				
				//для временного присоединения от 15 до 150
                if (N > 15 && N <= 150 && this.S1 == 2) {
                    this.Conditions == true
				
					ps_pw = N * Number(this.j.Price_power.stavka_vr.C_1_do_150)
					console.log("временная_мощность_от_15_до_150", ps_pw)
				
					x = ps_pw
				
				}
				
				//для временного присоединения более 150
                if (N > 150 && this.S1 == 2) {
                    this.Conditions == true
				
					ps_pw = N * Number(this.j.Price_power.stavka_vr.C_1_vyshe_150)
					console.log("временная_мощность_выше_150", ps_pw)
					
					x = ps_pw
				
				}
				
				//для постоянного присоединения с  условиями
                if (N <= 15 && this.S1 == 1) {
                    this.Conditions == true
                //  this.Build == false
                
				
					ps_pw = N * Number(this.j.Price_power.stavka_ps.C_1_do_15)
					console.log("постоянное_мощность_усл_до_15", ps_pw)
				
					x = ps_pw
				}
                
				//для постоянного присоединения от 15 до 150
                if (N > 15 && N <= 150 && this.S1 == 1) {
                    this.Conditions == true
				
					ps_pw = N * Number(this.j.Price_power.stavka_ps.C_1_do_150)
					console.log("постоянное_мощность_усл_от_15_до_150", ps_pw)
				
					x = ps_pw
				
				}
                
				//для постоянного присоединения свыше 150 без строительства
                if (N > 150 && this.S1 == 1) {
                    this.Conditions == true
				
					ps_pw = N * Number(this.j.Price_power.stavka_ps.C_1_vyshe_150)
					console.log("постоянное_мощность_усл_от_15_до_150", ps_pw)
				
					x = ps_pw
				}
                
                //Строительство
                if (this.Build && this.S1 !== 2) {


                    if (N > 150) {
						x = (Number(this.j.Price_power.stavka_ps.C_1_vyshe_150) * N) 
						console.log("постоянное_мощность_усл_свыше_150", x)
						
						//прячем строительство ТП если класс 6-10
                          if (this.VoltageClass == 2) { this.Show_BuildTP = false } else { this.Show_BuildTP = true }

						  if (this.Territory == 2) { this.showRadio([4]) }
					      if (this.Territory == 1) { this.showRadio([1, 2, 3, 5, 6, 7, 8]) }
                    }

                    ///////////////////////////// РАСЧЕТ Линий по МЩНОСТИ
                    this.Lines_one.forEach(function(e) {

                        if (e.select !== "0") {
                                                       
							//расчета для 1-й линии (пока просто выводит значение не складывая надо y +=)
                            if (e.Tip_VL == 2) { x += (Number(this.j.Price_power.cable.Ch4_1) * N) }
							if (e.Tip_VL == 3) { x += (Number(this.j.Price_power.cable.Ch4_2) * N) }
							if (e.Tip_VL == 4) { x += (Number(this.j.Price_power.cable.Ch4_3) * N) }
							if (e.Tip_VL == 5) { x += (Number(this.j.Price_power.cable.Ch4_4) * N) }
							if (e.Tip_VL == 6) { x += (Number(this.j.Price_power.cable.Ch4_5) * N) }
							if (e.Tip_VL == 7) { x += (Number(this.j.Price_power.cable.Ch4_6) * N) }
							if (e.Tip_VL == 8) { x += (Number(this.j.Price_power.cable.Ch4_7) * N) }
							if (e.Tip_VL == 9) { x += (Number(this.j.Price_power.cable.Ch4_8) * N) }
							if (e.Tip_VL == 10) { x += (Number(this.j.Price_power.cable.Ch4_9) * N) }
							
							if (e.Tip_VL == 12) { x += (Number(this.j.Price_power.cable.Ch4_10) * N) }
							
							if (e.Tip_VL == 14) { x += (Number(this.j.Price_power.cable.Ch4_11) * N) }
							if (e.Tip_VL == 15) { x += (Number(this.j.Price_power.cable.Ch4_12) * N) }
							if (e.Tip_VL == 16) { x += (Number(this.j.Price_power.cable.Ch4_13) * N) }
							if (e.Tip_VL == 17) { x += (Number(this.j.Price_power.cable.Ch4_14) * N) }
							if (e.Tip_VL == 18) { x += (Number(this.j.Price_power.cable.Ch4_15) * N) }
							if (e.Tip_VL == 19) { x += (Number(this.j.Price_power.cable.Ch4_16) * N) }
							if (e.Tip_VL == 20) { x += (Number(this.j.Price_power.cable.Ch4_17) * N) }
							
							if (e.Tip_VL == 22) { x += (Number(this.j.Price_power.cable.Ch4_18) * N) }
							if (e.Tip_VL == 23) { x += (Number(this.j.Price_power.cable.Ch4_19) * N) }
							
							if (e.Tip_VL == 25) { x += (Number(this.j.Price_power.cable.Ch4_20) * N) }
							if (e.Tip_VL == 26) { x += (Number(this.j.Price_power.cable.Ch4_21) * N) }
							if (e.Tip_VL == 27) { x += (Number(this.j.Price_power.cable.Ch4_22) * N) }
							if (e.Tip_VL == 28) { x += (Number(this.j.Price_power.cable.Ch4_23) * N) }
							if (e.Tip_VL == 29) { x += (Number(this.j.Price_power.cable.Ch4_24) * N) }
							
							if (e.Tip_VL == 31) { x += (Number(this.j.Price_power.cable.Ch4_25) * N) }
							if (e.Tip_VL == 32) { x += (Number(this.j.Price_power.cable.Ch4_26) * N) }
							if (e.Tip_VL == 33) { x += (Number(this.j.Price_power.cable.Ch4_27) * N) }
							if (e.Tip_VL == 34) { x += (Number(this.j.Price_power.cable.Ch4_28) * N) }
							if (e.Tip_VL == 36) { x += (Number(this.j.Price_power.cable.Ch4_29) * N) }
							console.log("one line=", x)

                        }
                    }, this);

                    this.Lines_two.forEach(function(e) {
                        if (e.select !== "0" && this.Category == 2) {
                            //первый источник
                            if (e.Tip_VL == 2) { x += (Number(this.j.Price_power.cable.Ch4_1) * N) }
							if (e.Tip_VL == 3) { x += (Number(this.j.Price_power.cable.Ch4_2) * N) }
							if (e.Tip_VL == 4) { x += (Number(this.j.Price_power.cable.Ch4_3) * N) }
							if (e.Tip_VL == 5) { x += (Number(this.j.Price_power.cable.Ch4_4) * N) }
							if (e.Tip_VL == 6) { x += (Number(this.j.Price_power.cable.Ch4_5) * N) }
							if (e.Tip_VL == 7) { x += (Number(this.j.Price_power.cable.Ch4_6) * N) }
							if (e.Tip_VL == 8) { x += (Number(this.j.Price_power.cable.Ch4_7) * N) }
							if (e.Tip_VL == 9) { x += (Number(this.j.Price_power.cable.Ch4_8) * N) }
							if (e.Tip_VL == 10) { x += (Number(this.j.Price_power.cable.Ch4_9) * N) }
							
							if (e.Tip_VL == 12) { x += (Number(this.j.Price_power.cable.Ch4_10) * N) }
							
							if (e.Tip_VL == 14) { x += (Number(this.j.Price_power.cable.Ch4_11) * N) }
							if (e.Tip_VL == 15) { x += (Number(this.j.Price_power.cable.Ch4_12) * N) }
							if (e.Tip_VL == 16) { x += (Number(this.j.Price_power.cable.Ch4_13) * N) }
							if (e.Tip_VL == 17) { x += (Number(this.j.Price_power.cable.Ch4_14) * N) }
							if (e.Tip_VL == 18) { x += (Number(this.j.Price_power.cable.Ch4_15) * N) }
							if (e.Tip_VL == 19) { x += (Number(this.j.Price_power.cable.Ch4_16) * N) }
							if (e.Tip_VL == 20) { x += (Number(this.j.Price_power.cable.Ch4_17) * N) }
							
							if (e.Tip_VL == 22) { x += (Number(this.j.Price_power.cable.Ch4_18) * N) }
							if (e.Tip_VL == 23) { x += (Number(this.j.Price_power.cable.Ch4_19) * N) }
							
							if (e.Tip_VL == 25) { x += (Number(this.j.Price_power.cable.Ch4_20) * N) }
							if (e.Tip_VL == 26) { x += (Number(this.j.Price_power.cable.Ch4_21) * N) }
							if (e.Tip_VL == 27) { x += (Number(this.j.Price_power.cable.Ch4_22) * N) }
							if (e.Tip_VL == 28) { x += (Number(this.j.Price_power.cable.Ch4_23) * N) }
							if (e.Tip_VL == 29) { x += (Number(this.j.Price_power.cable.Ch4_24) * N) }
							
							if (e.Tip_VL == 31) { x += (Number(this.j.Price_power.cable.Ch4_25) * N) }
							if (e.Tip_VL == 32) { x += (Number(this.j.Price_power.cable.Ch4_26) * N) }
							if (e.Tip_VL == 33) { x += (Number(this.j.Price_power.cable.Ch4_27) * N) }
							if (e.Tip_VL == 34) { x += (Number(this.j.Price_power.cable.Ch4_28) * N) }
							if (e.Tip_VL == 36) { x += (Number(this.j.Price_power.cable.Ch4_29) * N) }
							
                            console.log("two line=", x)
                        }
                    }, this);
                    

                    //строительство ТП
                    if (this.BuildTP && this.Calculate !== 0 && this.VoltageClass !== 2) {
                    //    x += (Number(this.j.Power[max][this["BuildTP_radio_" + this.Calculate]]) * N)
                        console.log("buildTP", x)						
						
						if (this.BuildTP_radio_2 == "C4_1") { build_pw = (Number(this.j.Price_power.tp.TP_1) * N)}
						if (this.BuildTP_radio_2 == "C4_2") { build_pw = (Number(this.j.Price_power.tp.TP_2) * N)}
						if (this.BuildTP_radio_2 == "C4_3") { build_pw = (Number(this.j.Price_power.tp.TP_3) * N)}
						if (this.BuildTP_radio_2 == "C4_4") { build_pw = (Number(this.j.Price_power.tp.TP_8) * N)}
						if (this.BuildTP_radio_2 == "C4_8") { build_pw = (Number(this.j.Price_power.tp.TP_4) * N)}
						if (this.BuildTP_radio_2 == "C4_9") { build_pw = (Number(this.j.Price_power.tp.TP_5) * N)}
						if (this.BuildTP_radio_2 == "C4_10") { build_pw = (Number(this.j.Price_power.tp.TP_6) * N)}
						if (this.BuildTP_radio_2 == "C4_11") { build_pw = (Number(this.j.Price_power.tp.TP_7) * N)}	


						
						
						x = x + build_pw

						console.log("build_pw", build_pw)
						
						
                    }
					
					
					//строительство Распределительного пункта
                    if (N > 999 && this.Rasp_punkt) {
                   
						rpunkt_pw = (Number(this.j.Price_power.rp.RP) * N)
					
						x = x + rpunkt_pw
                        console.log("Распределительный пункт", rpunkt_pw)
                    }
					
                }
// console.log("X", x)
                //если переключились на стандартизированную ставку то обнуляем мощность                        Lines_one[item.id].select // Lines_one[{"id":0,"select":"0","index":"0","Tip_VL":"0","L":""}] 
                if (this.Calculate == 2 && this.Build) { return 0 }
				
				//если переключились на Класс напряжения 6(10) кВ, то обнуляем параметры
				//if (this.VoltageClass == 2 && this.Build) { return 0 }

                //выводим результат
                return this.result(x)
            } else {
                return 0
            }
        },

        //Стандартизированная ставка
        resultSt: function() {
            if (this.j && this.N !== 0) {
                var y = 0
				var vr_st = 0
				var ps_st = 0
				var ps_st_vyshe_150 = 0
				var rpunkt_st = 0
				var build_st = 0
                var N = Number(this.N)
                var max = "max150"
                var cmax = "max15"
				var temp = 0



                //меньше 15 без строительства
                if (N <= 15 && !this.Conditions && N && this.Category !== 2) {
                    if (this.Category == 3 && this.S1 !== 0|| this.Category == 0){
                        return 550
                    }
                }

                //для временного присоединения с условиями
                if (this.S1 == 2) {
                    this.Conditions == true
                    this.Build == false
                
				
					vr_st = Number(this.j.Price_standart.stavka_ps.C_1)
					console.log("временное_стандарт_усл", vr_st)
					y = vr_st
				}

				
				//для постоянного присоединения без условий
                if (N <= 15 && this.S1 == 1) {
                    this.Conditions == false
                //  this.Build == false
                
				
					ps_st = 550
					console.log("постоянное_стандарт_безусл", ps_st)
				
				}
                
				//для постоянного присоединения с  условиями
                if (N <= 15 && this.S1 == 1) {
                    this.Conditions == true
                //  this.Build == false
                
				
					ps_st = Number(this.j.Price_standart.stavka_ps.C_1)
					console.log("постоянное_стандарт_усл", ps_st)
					y = ps_st
				
				}
                
				//для постоянного присоединения от 15 до 150
                if (N > 15 && N <= 150 && this.S1 == 1) {
                    this.Conditions == true
                //  this.Build == false
                
				
					ps_st = Number(this.j.Price_standart.stavka_ps.C_1)
					console.log("постоянное_стандарт_усл_от_15_до_150", ps_st)
					y = ps_st
				
				}				

				//для постоянного присоединения выше 150 без строительства
                if (N > 150 && this.S1 == 1) {
                    this.Conditions == true
                //  this.Build == false
                
				
					ps_st = Number(this.j.Price_standart.stavka_ps.C_1)
					console.log("постоянное_стандарт_усл_от_15_до_150", ps_st)
					y = ps_st
				
				}				
				
                //Строительство
                if (this.Build && this.Calculate == 2) {
					
					temp = 5
					console.log("проверка стандарт стройка", temp)


                    if (N > 150) {
                        max = "min150"
						temp = 555
						console.log("проверка стандарт стройка", temp)

						ps_st_vyshe_150 = Number(this.j.Price_standart.stavka_ps.C_1)
						console.log("постоянное_стандарт_выше_150", ps_st_vyshe_150)
						y = ps_st_vyshe_150
					
                        //прячем строительство ТП если класс 6-10
                        if (this.VoltageClass == 2) { this.Show_BuildTP = false } else { this.Show_BuildTP = true }

                       if (this.Territory == 2) { this.showRadio([4]) }
					   if (this.Territory == 1) { this.showRadio([1, 2, 3, 5, 6, 7, 8]) }
                    }

                    this.Lines_one.forEach(function(e) {
                        if (e.L && e.L !== "0" && e.select !== "0") {
                                                       
							//расчета для 1-й линии (пока просто выводит значение не складывая надо y +=)
                            if (e.Tip_VL == 2) { y += (Number(this.j.Price_standart.cable.Ch4_1) * e.L) }
							if (e.Tip_VL == 3) { y += (Number(this.j.Price_standart.cable.Ch4_2) * e.L) }
							if (e.Tip_VL == 4) { y += (Number(this.j.Price_standart.cable.Ch4_3) * e.L) }
							if (e.Tip_VL == 5) { y += (Number(this.j.Price_standart.cable.Ch4_4) * e.L) }
							if (e.Tip_VL == 6) { y += (Number(this.j.Price_standart.cable.Ch4_5) * e.L) }
							if (e.Tip_VL == 7) { y += (Number(this.j.Price_standart.cable.Ch4_6) * e.L) }
							if (e.Tip_VL == 8) { y += (Number(this.j.Price_standart.cable.Ch4_7) * e.L) }
							if (e.Tip_VL == 9) { y += (Number(this.j.Price_standart.cable.Ch4_8) * e.L) }
							if (e.Tip_VL == 10) { y += (Number(this.j.Price_standart.cable.Ch4_9) * e.L) }
							
							if (e.Tip_VL == 12) { y += (Number(this.j.Price_standart.cable.Ch4_10) * e.L) }
							
							if (e.Tip_VL == 14) { y += (Number(this.j.Price_standart.cable.Ch4_11) * e.L) }
							if (e.Tip_VL == 15) { y += (Number(this.j.Price_standart.cable.Ch4_12) * e.L) }
							if (e.Tip_VL == 16) { y += (Number(this.j.Price_standart.cable.Ch4_13) * e.L) }
							if (e.Tip_VL == 17) { y += (Number(this.j.Price_standart.cable.Ch4_14) * e.L) }
							if (e.Tip_VL == 18) { y += (Number(this.j.Price_standart.cable.Ch4_15) * e.L) }
							if (e.Tip_VL == 19) { y += (Number(this.j.Price_standart.cable.Ch4_16) * e.L) }
							if (e.Tip_VL == 20) { y += (Number(this.j.Price_standart.cable.Ch4_17) * e.L) }
							
							if (e.Tip_VL == 22) { y += (Number(this.j.Price_standart.cable.Ch4_18) * e.L) }
							if (e.Tip_VL == 23) { y += (Number(this.j.Price_standart.cable.Ch4_19) * e.L) }
							
							if (e.Tip_VL == 25) { y += (Number(this.j.Price_standart.cable.Ch4_20) * e.L) }
							if (e.Tip_VL == 26) { y += (Number(this.j.Price_standart.cable.Ch4_21) * e.L) }
							if (e.Tip_VL == 27) { y += (Number(this.j.Price_standart.cable.Ch4_22) * e.L) }
							if (e.Tip_VL == 28) { y += (Number(this.j.Price_standart.cable.Ch4_23) * e.L) }
							if (e.Tip_VL == 29) { y += (Number(this.j.Price_standart.cable.Ch4_24) * e.L) }
							
							if (e.Tip_VL == 31) { y += (Number(this.j.Price_standart.cable.Ch4_25) * e.L) }
							if (e.Tip_VL == 32) { y += (Number(this.j.Price_standart.cable.Ch4_26) * e.L) }
							if (e.Tip_VL == 33) { y += (Number(this.j.Price_standart.cable.Ch4_27) * e.L) }
							if (e.Tip_VL == 34) { y += (Number(this.j.Price_standart.cable.Ch4_28) * e.L) }
							if (e.Tip_VL == 36) { y += (Number(this.j.Price_standart.cable.Ch4_29) * e.L) }
							console.log("one line=", y)

                        }
                    }, this);

                    this.Lines_two.forEach(function(e) {
                        if (e.L && e.L !== "0" && e.select !== "0" && this.Category == 2) {
                            //первый источник
                            if (e.Tip_VL == 2) { y += (Number(this.j.Price_standart.cable.Ch4_1) * e.L) }
							if (e.Tip_VL == 3) { y += (Number(this.j.Price_standart.cable.Ch4_2) * e.L) }
							if (e.Tip_VL == 4) { y += (Number(this.j.Price_standart.cable.Ch4_3) * e.L) }
							if (e.Tip_VL == 5) { y += (Number(this.j.Price_standart.cable.Ch4_4) * e.L) }
							if (e.Tip_VL == 6) { y += (Number(this.j.Price_standart.cable.Ch4_5) * e.L) }
							if (e.Tip_VL == 7) { y += (Number(this.j.Price_standart.cable.Ch4_6) * e.L) }
							if (e.Tip_VL == 8) { y += (Number(this.j.Price_standart.cable.Ch4_7) * e.L) }
							if (e.Tip_VL == 9) { y += (Number(this.j.Price_standart.cable.Ch4_8) * e.L) }
							if (e.Tip_VL == 10) { y += (Number(this.j.Price_standart.cable.Ch4_9) * e.L) }
							
							if (e.Tip_VL == 12) { y += (Number(this.j.Price_standart.cable.Ch4_10) * e.L) }
							
							if (e.Tip_VL == 14) { y += (Number(this.j.Price_standart.cable.Ch4_11) * e.L) }
							if (e.Tip_VL == 15) { y += (Number(this.j.Price_standart.cable.Ch4_12) * e.L) }
							if (e.Tip_VL == 16) { y += (Number(this.j.Price_standart.cable.Ch4_13) * e.L) }
							if (e.Tip_VL == 17) { y += (Number(this.j.Price_standart.cable.Ch4_14) * e.L) }
							if (e.Tip_VL == 18) { y += (Number(this.j.Price_standart.cable.Ch4_15) * e.L) }
							if (e.Tip_VL == 19) { y += (Number(this.j.Price_standart.cable.Ch4_16) * e.L) }
							if (e.Tip_VL == 20) { y += (Number(this.j.Price_standart.cable.Ch4_17) * e.L) }
							
							if (e.Tip_VL == 22) { y += (Number(this.j.Price_standart.cable.Ch4_18) * e.L) }
							if (e.Tip_VL == 23) { y += (Number(this.j.Price_standart.cable.Ch4_19) * e.L) }
							
							if (e.Tip_VL == 25) { y += (Number(this.j.Price_standart.cable.Ch4_20) * e.L) }
							if (e.Tip_VL == 26) { y += (Number(this.j.Price_standart.cable.Ch4_21) * e.L) }
							if (e.Tip_VL == 27) { y += (Number(this.j.Price_standart.cable.Ch4_22) * e.L) }
							if (e.Tip_VL == 28) { y += (Number(this.j.Price_standart.cable.Ch4_23) * e.L) }
							if (e.Tip_VL == 29) { y += (Number(this.j.Price_standart.cable.Ch4_24) * e.L) }
							
							if (e.Tip_VL == 31) { y += (Number(this.j.Price_standart.cable.Ch4_25) * e.L) }
							if (e.Tip_VL == 32) { y += (Number(this.j.Price_standart.cable.Ch4_26) * e.L) }
							if (e.Tip_VL == 33) { y += (Number(this.j.Price_standart.cable.Ch4_27) * e.L) }
							if (e.Tip_VL == 34) { y += (Number(this.j.Price_standart.cable.Ch4_28) * e.L) }
							if (e.Tip_VL == 36) { y += (Number(this.j.Price_standart.cable.Ch4_29) * e.L) }
							
                            console.log("two line=", y)
                        }
                    }, this);

                    //строительство ТП
                  if (this.BuildTP && this.Calculate !== 0 && this.VoltageClass !== 2) {
                 //        y += (Number(this.j.Standart[max][this["BuildTP_radio_" + this.Calculate]]) * N)
					
					if (this.BuildTP_radio_2 == "C4_1") { build_st = (Number(this.j.Price_standart.tp.TP_1) * N)}
					if (this.BuildTP_radio_2 == "C4_2") { build_st = (Number(this.j.Price_standart.tp.TP_2) * N)}
					if (this.BuildTP_radio_2 == "C4_3") { build_st = (Number(this.j.Price_standart.tp.TP_3) * N)}
					if (this.BuildTP_radio_2 == "C4_4") { build_st = (Number(this.j.Price_standart.tp.TP_8) * N)}
					if (this.BuildTP_radio_2 == "C4_8") { build_st = (Number(this.j.Price_standart.tp.TP_4) * N)}
					if (this.BuildTP_radio_2 == "C4_9") { build_st = (Number(this.j.Price_standart.tp.TP_5) * N)}
					if (this.BuildTP_radio_2 == "C4_10") { build_st = (Number(this.j.Price_standart.tp.TP_6) * N)}
					if (this.BuildTP_radio_2 == "C4_11") { build_st = (Number(this.j.Price_standart.tp.TP_7) * N)}	


						
						
					    y = y + build_st
                        console.log("buildTP", y)
						console.log("build_st", build_st)
                  }
					
					//строительство Распределительного пункта
                    if (N > 999 && this.Rasp_punkt) {
                   
						rpunkt_st = (Number(this.j.Price_standart.rp.RP))
                        y = y + rpunkt_st
						
						console.log("Распределительный пункт", rpunkt_st)
						
                    }
					
                }

                if (this.Calculate == 1 && this.Build) { return 0 }
console.log("Y", y)
                return this.result(y)
            } else {
                return 0
            }
        },

        isNValid: function() {
            return (/\d{5}/).test(this.N)
        }

    },
    mounted: function() {
        var self = this
        this.$nextTick(function() {
            // Код, который будет запущен только после
            // отображения всех представлений

            $('.Category').change(function(n) {
                self.Category = n.target.value
            })

            $('.BuildTP_radio_1').change(function(n) {
                self.BuildTP_radio_1 = n.target.value
            })

            $('.BuildTP_radio_2').change(function(n) {
                self.BuildTP_radio_2 = n.target.value
            })
        })
    }

})
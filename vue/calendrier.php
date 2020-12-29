<?php if(!isset($_SESSION))
	{
		session_start();
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link type="text/css" rel="stylesheet" href="calendar.css">
        <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/fontawesome.css" integrity="sha384-eHoocPgXsiuZh+Yy6+7DsKAerLXyJmu2Hadh4QYyt+8v86geixVYwFqUvMU8X90l" crossorigin="anonymous"/>
        <script src="https://kit.fontawesome.com/57280e8850.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
        <script src="https://unpkg.com/vue-chartjs/dist/vue-chartjs.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.15.2/axios.js"></script>
    </head>
    <body>
        <div id="app">
            <div id="_headMainGrid">
                <div id="_headPanelSetWeek"></div>
                <div id="_headCalendar">
                    <span  v-on:click="i = i-1 <0 ? i : i-1" id="_prevBtn" class="_changeYear fas fa-angle-left"></span>
                        <span v-bind:key="yearList[i]" id="_year">{{ yearList[i] }}</span>
                    <span v-on:click="i = i+1 >=yearList.length ? i : i+1" id="_nextBtn" class="_changeYear fas fa-angle-right"></span>
                </div>
            </div>
            <div id="mainGrid">
                <div id="_panelSetWeek">
                    <div></div>
                    <bar-chart v-bind:style="width='100%', height='100%'"></bar-chart>
                </div>
                <div id="_calendar">
                    <div id=_colTitleCalendar>
                        <div class="_weekTitle" id="_weekOneTitle">Sem 1 </div>
                        <div class="_weekTitle" id="_weekTwoTitle">Sem 2</div>
                        <div class="_weekTitle" id="_weekThreeTitle">Sem 3</div>
                        <div class="_weekTitle" id="_weekFourTitle">Sem 4</div>
                    </div>
                    
                        <transition-group name="yearChange" tag="div"  id="_contentCalendar">
                                    <div v-for="(week,index) in sessionWeek[yearList[i]]" v-bind:key="week._id.$oid" class="_weekTile" v-bind:name="week._id.$oid+yearList[i]">
        
                                        <span v-if="week.user != '' " v-bind:title="getWorkerOfWeek(week.user.$oid)"><p v-on:click="setDisplayTile">{{ week['weekDate'] }}</p></span>
                                        <span v-else v-bind:style="{ border: 'solid 2px', borderColor : '#F59B9B' }"><p v-on:click="setDisplayTile">{{ week['weekDate'] }}</p></span>
                                       
                                        <div style="display:none" class="_contentWeekTile" >
                                            <p>{{ week['weekDate'] }}</p>
                                            <i v-on:click="unsetDisplayTile" style="color:red" class="fas fa-times fa-sm"></i>
                                                
                                                <ul>
                                                    <span v-for="ul in sessionEmploye.slice(0,Math.ceil(sessionEmploye.length/2))">
                                                                <li  v-if="ul._id.$oid == week.user.$oid"class="liEmp fas fa-circle" v-on:click="setWeekEmpToNull( week ,index, yearList[i] ,$event)" v-bind:style="beforeStyle(ul)">{{ ul.prenom }}</li>
                                                                <li  v-else class="liEmp fas fa-circle" v-on:click="setWeekEmp(ul._id, week ,index, yearList[i] ,$event)">{{ ul.prenom }}</li>
                                                    </span>
                                                </ul>
                                                <ul>
                                                    <span v-for="ul in sessionEmploye.slice(Math.ceil(sessionEmploye.length/2))">
                                                                <li  v-if="ul._id.$oid == week.user.$oid" class="liEmp fas fa-circle" v-on:click="setWeekEmpToNull( week ,index, yearList[i] ,$event)" v-bind:style="beforeStyle(ul)">{{ ul.prenom }}</li>
                                                                <li  v-else class="liEmp fas fa-circle" v-on:click="setWeekEmp(ul._id, week ,index, yearList[i] ,$event)">{{ ul.prenom }}</li>
                                                    </span>
                                                
                                                </ul>
                                        </div>
                                    </div>
                    </transition-group>

                </div>
            </div>
        </div>
    </body>

    <script>

Vue.component('bar-chart',{
    extends : VueChartJs.Bar,
    mounted(){
        
        this.fillData();  
    },
    data (){
        return {
            labelsName :[],
            rows :[],
            colorBorder:[],
            colorBackground:[]    
        }
    },
    methods:{
        fillData : function(){

            this.$refs.canvas.parentNode.style.width ="100%";
            this.$refs.canvas.parentNode.style.height ="100%";
            this.$refs.canvas.style.width ="100%";
            this.$refs.canvas.style.height ="100%";

            axios.get('../controller/controller.php?ctrl=calendar&fc=statistics')
            .then(response =>{
                this.labelsName.splice(0);
                this.rows.splice(0);
                this.colorBorder.splice(0);
                this.colorBackground.splice(0);
                
                for(el of response.data[this.$parent.yearList[this.$parent.i]])
                {     
                    this.labelsName.push(el.prenom);
                    this.rows.push(el.nbDayOfWork);
                    this.colorBorder.push('rgb('+el.couleur+')');
                    this.colorBackground.push('rgb('+el.couleur+',0.5)');
                }
                this.setChart();
            }).catch(error=>{
                        
            });
        },
        setChart : function(){ 
            this.renderChart({
                        labels: this.labelsName,
                        datasets: [{
                            label: 'Nombre de semaines travaillées',
                            data: this.rows,
                            backgroundColor: this.colorBackground,
                            borderColor: this.colorBorder,
                            borderWidth : 2,
                            borderSkipped:'bottom',
                            
                        }]
            }, {
                legend: {
                    labels: {
                        fontColor: "white",
                        fontSize: 12
                    }
                },
                scales :{
                    yAxes:[{
                        ticks:{
                            beginAtZero:true,
                            min:0,
                            max:55,
                            stepSize:5,
                            fontColor : 'rgba(255, 255, 255, 1)'
                            
                        },
                    }],
                    xAxes:[{
                        ticks:{
                            fontColor : 'rgba(255, 255, 255, 1)'
                        }  
                    }]
                },
                
                animations:{
                    tension:{
                        duration : 500,
                        easing:'linear',
                        from:1,
                        to:0,
                        loop : true
                    }

                },
                responsive: true,
				maintainAspectRatio: true,
				height: 200
            });

        }
    }
})

 new Vue({
        el:"#app",
        data : {
            yearList : ['2017', '2018', '2019','2020'],
            i : 0,
            sessionWeek :"",
            sessionEmploye:"",
          
            
        },
        created(){
            this.initVar();
        },
        updated(){
            this.$children[0].fillData();
        },
        methods: {
            initVar : function(){
                axios.get('../controller/controller.php?ctrl=calendar&fc=start')
                    .then(response=>{
                    
                        console.log(response);
                        this.sessionWeek = response.data.sessionWeek;
                        this.sessionEmploye = response.data.sessionEmploye;
                    }).catch(error=>{
                        
                    });
            },
            getWorkerOfWeek:function(id)
            {
                console.log(id);
                var worker="";
                for(var emp of this.sessionEmploye)
                {
                    
                    worker = emp._id.$oid == id ? emp.prenom : worker;
                    
                }
            
                return worker+=" travaille cette semaine."
            },
            beforeStyle:function(ul)
            {
                return{
                    '--clLi':'rgb('+ul.couleur+')'
                }
            },
            setDisplayTile: function(event){
                event.target.parentNode.style.display='none';
                event.target.parentNode.parentNode.children[1].style.display='grid';
                
            },
            unsetDisplayTile: function(event){
                event.target.parentNode.style.display='none';
                event.target.parentNode.parentNode.children[0].style.display='grid';
            
            },
            setWeekEmp:function(emp, week,indexWeek, year,event)
            {
         
                axios.get('../controller/controller.php?ctrl=calendar&fc=setEmpOfWeek&emp='+emp.$oid+'&week='+week._id.$oid+'&year='+year)
                    .then(response=>{
                        console.log(response);
                        this.sessionWeek[year][indexWeek]['user'] = emp;
                    }).catch(error=>{
                        
                    });;
                
            },
            setWeekEmpToNull:function(week,indexWeek,year,event)
            {
                
                axios.get('../controller/controller.php?ctrl=calendar&fc=setToNull&week='+week._id.$oid+'&year='+year)
                    .then(response=>{
                        console.log(response);
                        this.sessionWeek[year][indexWeek]['user'] = "";
                    }).catch(error=>{

                    });
            }

        }
        
    });
    </script>
</html>
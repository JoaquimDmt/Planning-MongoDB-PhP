<?php if(!isset($_SESSION))
	{
        require_once('../models/user.php');
		session_start();
    }
    if(!isset($_SESSION['user']))
        header('Location : ../controllers/controller.php?ctrl=user&fc=disconnect');
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link type="text/css" rel="stylesheet" href="style/calendar.css">
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
            <div id="_headPanelSetWeek">
                <form action="../controllers/controller.php?ctrl=user&fc=disconnect" method="post" class="account">
                    <div style="padding-left: 16px;">
                   
                </div>
                <?php echo $_SESSION['user']->getPseudo() ?>
                    <input type="submit" id="disconnectBtn" value="Se déconnecter">
                </form>
            </div>
            <div id="_headCalendar">
                <span  v-on:click="i = i-1 <0 ? i : i-1" id="_prevBtn" class="_changeYear fas fa-angle-left"></span>
                    <span v-bind:key="yearList[i]" id="_year">{{ yearList[i] }}</span>
                <span v-on:click="i = i+1 >=yearList.length ? i : i+1" id="_nextBtn" class="_changeYear fas fa-angle-right"></span>
            </div>
        </div>

        <div id="mainGrid">
            <div id="_panelSetWeek">
                <div id="_listEmployee"><h3>Liste des employé(e)s</h3>
                    <ul>
                        <li v-for="(emp,index) in sessionEmployee" :key="index">{{ emp.prenom }}</li>
                    </ul>
                </div>
                <bar-chart v-bind:style="width='100%', height='100%'" :width="100" :height="100"></bar-chart>
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
                        <span  v-if="week.user != '' " v-bind:title="getWorkerOfWeek(week.user.$oid,'prenom') " v-bind:style="{ backgroundColor: computedColor(getWorkerOfWeek(week.user.$oid,'couleur'), 0.6), borderColor: computedColor(getWorkerOfWeek(week.user.$oid,'couleur'), 1) }">
                            <p v-on:click="setDisplayTile">{{ week['weekDate'] }}</p>
                        </span>
                        <span v-else><p v-on:click="setDisplayTile">{{ week['weekDate'] }}</p></span>
                        
                        <div style="display:none" class="_contentWeekTile" >
                            <p>{{ week['weekDate'] }}</p>
                            <i v-on:click="unsetDisplayTile" style="color:red" class="fas fa-times fa-sm"></i>
                                
                            <ul>
                                <span v-for="ul in sessionEmployee.slice(0,Math.ceil(sessionEmployee.length/2))">
                                        <li  v-if="ul._id.$oid == week.user.$oid"class="liEmp fas fa-circle" v-on:click="setWeekEmpToNull( week ,index, yearList[i])" v-bind:style="beforeStyle(ul)">{{ ul.prenom }}</li>
                                        <li  v-else class="liEmp fas fa-circle" v-on:click="setWeekEmp(ul._id, week ,index, yearList[i] ,$event)">{{ ul.prenom }}</li>
                                </span>
                            </ul>
                            <ul>
                                <span v-for="ul in sessionEmployee.slice(Math.ceil(sessionEmployee.length/2))">
                                        <li  v-if="ul._id.$oid == week.user.$oid" class="liEmp fas fa-circle" v-on:click="setWeekEmpToNull( week ,index, yearList[i])" v-bind:style="beforeStyle(ul)">{{ ul.prenom }}</li>
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
/*This component is extends from bar-chart and used by VueChartJs*/
Vue.component('bar-chart',{

    extends : VueChartJs.Bar,
    name:'statDayOfWork',
    mounted(){
        //Once the application has been mounted the chart is filled so the data can be displayed on the chart
        this.fillData();  
    },
    data (){
    //data used by the chart
        return {
            labelsName :[],//x Axes with the name
            rows :[],//y Axes with number of week
            colorBorder:[],
            colorBackground:[]    
        }
    },
    methods:{
        /**
            this method fill the data into the labelsName and rows data
            By sendinf a get request to the controller and the manager then, we can get back statistics
         */
        fillData : function(){

            this.$refs.canvas.parentNode.style.width ="100%";
            this.$refs.canvas.parentNode.style.height ="100%";
            this.$refs.canvas.style.width ="100%";
            this.$refs.canvas.style.height ="100%";

            axios.get('../controllers/controller.php?ctrl=calendar&fc=statistics')
                .then(response =>{
                    /**
                    So the data can be reload because at each either add or remove event detected the chart is reset
                     */
                    this.labelsName.splice(0);
                    this.rows.splice(0);
                    this.colorBorder.splice(0);
                    this.colorBackground.splice(0);
                    

                    //Iterate on the result to fill the data 
                    for(el of response.data[this.$parent.yearList[this.$parent.i]])
                    {     
                        this.labelsName.push(el.prenom);
                        this.rows.push(el.nbDayOfWork);
                        this.colorBorder.push('rgb('+el.couleur+')');
                        this.colorBackground.push('rgb('+el.couleur+',0.5)');
                    }
                    //Once it's done, we set the chart with the news datas.
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
            }, 
            {
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
				maintainAspectRatio: false,
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
        sessionEmployee:"",
    },
    created(){
        this.initVar();
    },
    updated(){
        this.$children[0].fillData();
    },
    methods: {
        /**
        init the required data to set all the application.
        A get request has been done to do so.
            */
        initVar : function(){
            axios.get('../controllers/controller.php?ctrl=calendar&fc=start')
                .then(response=>{
                    this.sessionWeek = response.data.sessionWeek;
                    this.sessionEmployee = response.data.sessionEmployee;
                }).catch(error=>{
                    
                });
                
        },

        /**
        return the employee of the week with a specific information about him (2nd argument)
            */
        getWorkerOfWeek:function(id, propToReturn){
            
            let worker="";
            for(var emp of this.sessionEmployee)
            {
                worker =  id==emp._id.$oid ? emp : worker;
            }
            return worker[propToReturn];
        },

        /**
        to return a color with the rgb value. Used to set the bg Color of the little tile. As it we can see immediatly who's working at one specific day
            */
        computedColor : function(valColor, opacity){
            return `rgba(${valColor}, ${opacity})`
        },

        /**
        To set some style color for each point in front of each name in the big tile
            */
        beforeStyle:function(ul)
        {
            return{
                '--clLi':'rgb('+ul.couleur+')'
            }
        },

        /**
        Here, the opposit from unsetDisplayTile
            */
        setDisplayTile: function(event){
            event.target.parentNode.style.display='none';
            event.target.parentNode.parentNode.children[1].style.display='grid';
            
        },

        /**
        To close the big tile with all the names to replace it by the little tile with the date only
            */
        unsetDisplayTile: function(event){
            event.target.parentNode.style.display='none';
            event.target.parentNode.parentNode.children[0].style.display='flex';
        
        },

        /**
        When the user pull of the current worker and set it by the new one
        Then a get request is done so the controller inform the model about the change.
        we need the id of the week, the indexOf The week to set the sessionWeek table with new value at the exact week. 
        we need the id of the employee and the year as well
            */
        setWeekEmp:function(emp, week,indexWeek, year)
        {
        
            axios.get(`../controllers/controller.php?ctrl=calendar&fc=setEmpOfWeek&emp=${emp.$oid}&week=${week._id.$oid}&year=${year}`)
                .then(response=>{
                    this.sessionWeek[year][indexWeek]['user'] = emp;
                }).catch(error=>{
                    
                });
            
        },

        /**
        When the user pull of the current worker and set it to null
        Then a get request is done so the controller inform the model about the change.
        we need the id of the week, the indexOf The week to set the sessionWeek table with new value at the exact week, and we nedd the year as well.
            */
        setWeekEmpToNull:function(week,indexWeek,year)
        {
            
            axios.get(`../controllers/controller.php?ctrl=calendar&fc=setToNull&week=${week._id.$oid}&year=${year}`)
                .then(response=>{
                    this.sessionWeek[year][indexWeek]['user'] = "";
                }).catch(error=>{

                });
        }

    }
        
});
</script>

</html>
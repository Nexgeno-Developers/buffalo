
<style>
    .sell_position1 {
    position: absolute;
    right: 25px;
    top: 63px;
}

.sell_position2 {
    position: absolute;
    right: 25px;
    top: 85px;
}



.dashboard_box .col-lg-3.col-md-6.col-sm-6.col-6:nth-child(odd) {
    padding-right: 7px;
}
.dashboard_box .col-lg-3.col-md-6.col-sm-6.col-6:nth-child(even) {
    padding-left: 7px;
}

@media(max-width:767px)
{
    .dashboard_box .card-footer {
    padding-left: 0;
            padding-right: 0;
}
.dashboard_box .card-header {
    padding: 0px;
}
.sell_position1 {
    position: relative;
    right: 0;
    text-align: left !important;
    top: 6px;
}
.sell_position2 {
    position: relative;
    right: 0;
    top: 6px;
    text-align: left !important;
    padding-bottom:15px;
}
.mt30
{
    padding-top:15px;
}
}

.view_report {
    text-align: center;
    display: flex;
    height: 92%;
    align-items: center;
    justify-content: center;

}

.view_report p
{
  font-size: 20px !important;
    font-weight: 900;
}

    
</style>
<!-- start page title -->
<div class="row ">
  <div class="col-xl-12 breadcrumes">
    <div class="card">
      <div class="card-body">
        <h4 class="page-title pb-1"> <i class="mdi mdi-home title_icon"></i> <?php echo get_phrase('Buffalo Dashboard'); ?> </h4>
      </div> <!-- end card body-->
    </div> <!-- end card -->
  </div><!-- end col-->
</div>
<!-- end page title -->



<div class="dashboard_box">
  <div class="row ">
    <?php /*
    <div class="col-lg-4 col-md-6 col-sm-6 col-6 pddleft_0">
        <div class="card card-stats two">
              <a class="dashbox" href="reports/inward">
            <div class="card-header card-header-warning card-header-icon">
                <div class="card-icon">
                    <img src="../assets/backend/images/arrow_icon1.svg" alt="">
                </div>
                <p class="card-category fs18"><?php echo get_phrase('Buffalo Total Arrival'); ?></p>
                <h3 class="card-title"> 
                <?php
                  echo $unblock;
                  ?>
      </h3>
            </div>
            <div class="card-footer">
                <div class="stats">
                    <i class="fa fa-clock-o"></i> Just Updated
                </div>
            </div>
            </a>
        </div>
    </div>
    */ ?>
    <div class="col-md-4 col-6 pl-md-1 pddleft_0">
      <div class="card card-stats two">
        <a class="dashbox" href="javascript:void(0)">
          <div class="card-header card-header-warning card-header-icon">
            <div class="card-icon">
              <img src="../assets/backend/images/arrow_icon14.svg" alt="">
            </div>
            <p class="card-category fs18">
              <?php echo get_phrase('Buffalo Total Qurbani Arrival'); ?>
            </p>
            <h3 class="card-title">
              <?php echo $selling_total; ?>
            </h3>
            <p class="card-category text-center sell_position1">
              MB: <?php echo $selling_m ?>
            </p>
            <p class="card-category text-center sell_position2">
              SB: <?php echo $selling_f ?>
            </p>
          </div>
          <div class="card-footer">
            <div class="stats">
              <i class="fa fa-clock-o"></i> Just Updated
            </div>
          </div>
        </a>
      </div>
    </div> 
    <?php /* 
    <div class="col-md-4 col-6 pl-md-1 pddright_0">
      <div class="card card-stats" style="background: #7e5f54;">
        <a class="dashbox" href="javascript:void(0)">
          <div class="card-header card-header-warning card-header-icon">
            <div class="card-icon">
              <img src="../assets/backend/images/arrow_icon16.svg" alt="">
            </div>
            <p class="card-category fs18">
              <?php echo get_phrase('Regular - Total Slaughter'); ?>
            </p>
            <h3 class="card-title">
              <?php echo $slaughter_total; ?>
            </h3>
            <p class="card-category text-center sell_position1">
              MB: <?php echo $slaughter_m ?>
            </p>
            <p class="card-category text-center sell_position2">
              SB: <?php echo $slaughter_f ?>
            </p>
          </div>
          <div class="card-footer">
            <div class="stats">
              <i class="fa fa-clock-o"></i> Just Updated
            </div>
          </div>
        </a>
      </div>
    </div>  
    */ ?>  
    <div class="col-lg-4 col-md-6 col-sm-6 col-6 pl-md-1 pddright_0">
      <div class="card card-stats four">
        <a class="dashbox" href="reports/outward">
          <div class="card-header card-header-warning card-header-icon">
            <div class="card-icon">
              <img src="../assets/backend/images/arrow_icon3.svg" alt="">
            </div>
            <p class="card-category fs18">
              <?php echo get_phrase('Buffalo Qurbani Slaughter'); ?>
            </p>
            <h3 class="card-title">
              <?php echo $exit; ?>
            </h3>
          </div>
          <div class="card-footer">
            <div class="stats">
              <i class="fa fa-clock-o"></i> Just Updated
            </div>
          </div>
        </a>
      </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6 col-6 pl-md-1 pddleft_0">
      <div class="card card-stats five">
        <a class="dashbox" href="reports/blocked">
          <div class="card-header card-header-warning card-header-icon">
            <div class="card-icon padd5">
              <img src="../assets/backend/images/arrow_icon7.svg" alt="">
            </div>
            <p class="card-category fs18">
              <?php echo get_phrase('Buffalo Qurbani Balance'); ?>
            </p>
            <h3 class="card-title">
              <?php echo $selling_total - $exit; ?>
            </h3>
          </div>
          <div class="card-footer">
            <div class="stats">
              <i class="fa fa-clock-o"></i> Just Updated
            </div>
          </div>
        </a>
      </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6 col-6 pddright_0">
      <div class="card card-stats three">
        <a class="dashbox" href="reports/blocked">
          <div class="card-header card-header-warning card-header-icon">
            <div class="card-icon">
              <img src="../assets/backend/images/arrow_icon8.svg" alt="">
            </div>
            <p class="card-category fs18">
              <?php echo get_phrase('Pass Blocked'); ?>
            </p>
            <h3 class="card-title">
              <?php echo $block; ?>
            </h3>
          </div>
          <div class="card-footer">
            <div class="stats">
              <i class="fa fa-clock-o"></i> Just Updated
            </div>
          </div>
        </a>
      </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6 col-6 pddright_0">
      <div class="card card-stats one">
        <a class="dashbox" href="manage_vyapari">
          <div class="card-header card-header-warning card-header-icon">
            <div class="card-icon">
              <img src="../assets/backend/images/arrow_icon9.svg" alt="">
            </div>
            <p class="card-category fs18">
              <?php echo get_phrase('Registered Total Vyapari'); ?>
            </p>
            <h3 class="card-title">
              <?php echo $vyapari; ?>
            </h3>
          </div>
          <div class="card-footer">
            <div class="stats">
              <i class="fa fa-clock-o"></i> Just Updated
            </div>
          </div>
        </a>
      </div>
    </div>

    <div class="col-md-12">
      <div class="chart_box">
        
        <div class="color_represent">
          <div class="inward">
            <p>Qurbani Inward</p>
            <div class="circle"></div>
          </div>

          <div class="outward">
            <p>Qurbani Outward</p>
            <div class="circle1"></div>
          </div>
        </div>

        <canvas id="myChart"></canvas>
        
      </div>
    </div>

    
    <div class="col-md-12 mb-3 mt30">
        <div class="chart_box">
            <p>Buffalo Qurbani Inward and Outward Daily Report</p>
            <table class="table table-striped dt-responsive nowrap">
            	<thead class="chart-table-header">
            		<tr>
            		    <th>Sr. No.</th>
            			<th>Date</th>
            			<th>Inward</th>
            			<th>Outward</th>
            		</tr>
            	</thead>
            	<tbody class="chart-table-body" id="chart-table-body">
            	</tbody>
            </table>  
        </div>
    </div>
</div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>

<script>
var currentDate = new Date();

//var day = 20;
var day = new Date().getDate();
var current_month = currentDate.getMonth() + 1;

var start_date =  <?php echo $startdate ?>;
var start_month = <?php echo $month ?>;
var daysInMonth = <?php echo $days ?>;

var animalin = <?php echo $aniamalin ?>;
var animalout = <?php echo $aniamalout ?>;

const yValuesIN = [];
const yValuesOut = []; 
const xValues = [];

if(current_month == start_month){
    for (let i = start_date; i <= day; i++) {
        xValues.push(i);
    }
} else {
    for (let i = start_date; i <= daysInMonth + 1; i++) {
        if(i !== daysInMonth + 1){
            xValues.push(i);
        } else {
           
          for (let j = 1; j <= day; j++) {
               xValues.push(j);
          }
              
           
        }
    }
}


for (var i = 0; i < xValues.length; i++) {
  var found = false;

  for (var j = 0; j < animalout.length; j++) {
    if (animalout[j]['DATE'] == xValues[i]) {
      animalout[j].sr_no = i + 1;
      found = true;
      break;
    }
  }

  if (!found) {
    animalout.push({ DATE: xValues[i].toString(), count: '0', sr_no: i + 1 });
  }
  
}


for (var i = 0; i < xValues.length; i++) {
  var found = false;

  for (var j = 0; j < animalin.length; j++) {
    if (animalin[j]['DATE'] == xValues[i]) {
      animalin[j].sr_no =i + 1;
      found = true;
      break;
    }
  }

  if (!found) {
    animalin.push({ DATE: xValues[i].toString(), count: '0', sr_no: i + 1 });
  }
  
}



animalin.sort(function(a, b) {
  var animalinA = parseInt(a.DATE);
  var animalinB = parseInt(b.DATE);
  return animalinA - animalinB;
});

animalout.sort(function(a, b) {
  var animaloutA = parseInt(a.DATE);
  var animaloutB = parseInt(b.DATE);
  return animaloutA - animaloutB;
});


animalin.sort(function(a, b) {
  var animalinA = parseInt(a.sr_no);
  var animalinB = parseInt(b.sr_no);
  return animalinA - animalinB;
});

animalout.sort(function(a, b) {
  var animaloutA = parseInt(a.sr_no);
  var animaloutB = parseInt(b.sr_no);
  return animaloutA - animaloutB;
});





for (var i = 0; i < animalin.length; i++) {
  var count = animalin[i].count;
  yValuesIN.push(count); 
}

for (var i = 0; i < animalout.length; i++) {
  var count = animalout[i].count;
  yValuesOut.push(count); 
}


new Chart("myChart", {
  type: "line",
  data: {
    labels: xValues,
    datasets: [{ 
      data: yValuesIN,
      borderColor: "#2F2F2F", 
      fill: false
    }, { 
      data: yValuesOut,
      borderColor: "#319ea7",
      fill: false
    }]
  },
  options: {
    legend: {display: false}
  }
});


$(document).ready(function() {
    var today = new Date().getDate();
    for (var i = 0; i < xValues.length; i++) {

        var dayx = xValues[i];
        var labelMonthYear = (dayx >= 21) ? 'May 2025' : 'June 2025';

        if (xValues[i] <= today) {
            var row = '<tr>' +
                '<td>' + (i + 1)  + '</td>' +
                '<td>' + xValues[i] + ' ' + labelMonthYear + '</td>' +
                '<td>' + yValuesIN[i] + '</td>' +
                '<td>' + yValuesOut[i] + '</td>' +
                '</tr>';
            $('#chart-table-body').append(row);
        }
    }
});
</script>


<script>
$(document).ready(function() {
  initDataTable("expense-datatable");
});

$(".widget-flat").mouseenter(function() {
  var id = $(this).attr('id');
  $('#'+id+'_list').show();
}).mouseleave(function() {
  var id = $(this).attr('id');
  $('#'+id+'_list').hide();
});
</script>

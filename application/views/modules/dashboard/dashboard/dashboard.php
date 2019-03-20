<style type="text/css">

</style>







<!-- Sub Header End -->
<div class="subheader">
  <div class="d-flex align-items-center">
    <div class="title mr-auto">
      <h3><i class="icon left la <?php echo $current_module['icon'] ?>"></i> <?php echo $current_module['title'] ?></h3>
    </div>

  </div>
</div>
<!-- Sub Header Start -->
<div class="content">
  <div class="row">


    <!-- Mini card bos start -->

    <div class="col-lg-6 col-xl-3">
      <div class="card-box card-statistic gr-primary">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <i class="icon left la la-users"></i>
            <div class="text text-right">
              <span>Orders Received</span>

              <h2 class="count">
                <?php
                $this->db->where('status >', 0);
                $ordersReceived = $this->db->count_all_results('soheaders');
                echo $ordersReceived;
                ?>
              </h2>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-6 col-xl-3">
      <div class="card-box card-statistic gr-success">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <i class="icon left la la-user"></i>
            <div class="text text-right">
              <span>Orders Completed</span>

              <h2 class="count">
                <?php
                $this->db->where_in('status', array(4));
                $completedOrders = $this->db->count_all_results('soheaders');
                echo $completedOrders;
                ?>
              </h2>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-6 col-xl-3">
      <div class="card-box card-statistic gr-info">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <i class="icon left la la-suitcase"></i>
            <div class="text text-right">
              <span>New Orders</span>

              <h2 class="count">
                <?php
                $this->db->where_in('status', array(0,1,2,3));
                $newOrders = $this->db->count_all_results('soheaders');
                echo $newOrders;
                ?>
              </h2>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-6 col-xl-3">
      <div class="card-box card-statistic gr-warning">
        <div class="card-body">
          <div class="d-flex align-items-center">
            <i class="icon left la la-black-tie"></i>
            <div class="text text-right">
              <span>Total Earning</span>

              <h2 class="count">

                <?php
                $this->db->select_sum('amount');
                $this->db->where('status', 2);
                $totalCollections = $this->db->get('collection_details')->row()->amount;

                $this->db->select_sum('amount');
                $this->db->where('status', 2);
                $totalPayments = $this->db->get('payment_details')->row()->amount;
                echo '₱ '.number_format($totalCollections - $totalPayments, 2);
                ?>

            </h2>
            </div>
          </div>
        </div>
      </div>
    </div>



    <!-- Mini Card box end -->








    <div class="col-12">
      <div class="card-box">
       <div class="card-head">
        <div class="head-caption">
          <div class="head-title">
           <div class="head-title">
            <h4 class="head-text"><?php echo $current_module['module_label'] ?></h4>
          </div>
        </div>
      </div>
      <div class="card-head-tools">

      </div>
    </div>
    <div class="card-body">
      <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

    </div>
  </div> <!-- Card box end -->
</div> <!-- col-9 end -->


</div> <!-- row end -->
</div> <!-- content end -->


<script type="text/javascript">
$(function () {
        $('#container').highcharts({
            chart: {
                zoomType: 'xy'
            },
            title: {
                text: 'Average Monthly Sales and Purchases'
            },
            subtitle: {
                text: 'Kitrol Agusan'
            },
            xAxis: [{
                category: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            }],
            yAxis: [{ // Primary yAxis
                labels: {
                    format: '{value}₱',
                    style: {
                        color: '#89A54E'
                    }
                },
                title: {
                    text: 'Payments',
                    style: {
                        color: '#89A54E'
                    }
                }
            }, { // Secondary yAxis
                title: {
                    text: 'Sales',
                    style: {
                        color: '#4572A7'
                    }
                },
                labels: {
                    format: '{value} ₱',
                    style: {
                        color: '#4572A7'
                    }
                },
                opposite: true
            }],
            tooltip: {
                shared: true
            },
            legend: {
                layout: 'vertical',
                align: 'left',
                x: 120,
                verticalAlign: 'top',
                y: 100,
                floating: true,
                backgroundColor: '#FFFFFF'
            },
            series: [{
                name: 'Sales',
                color: '#4572A7',
                type: 'column',
                yAxis: 1,
                data: [<?php
                $months = array('01','02', '03','04','05', '06','07','08', '09','10','11', '12');
    $monthlyPayments = array();
    foreach ($months as $i=>$month) {
      $this->db->select_sum('amount');
      $this->db->where('status', 2);
      $this->db->like('dateInserted', date('Y-m', strtotime(date('Y').'-'.$month)));
      $monthlyPayment = $this->db->get('collection_details')->row();
        echo $monthlyPayment->amount.',';
    }
    ?>],
                tooltip: {
                    valueSuffix: '₱'
                }
    
            }, {
                name: 'Payments',
                color: '#89A54E',
                type: 'spline',
                data: [<?php
                $months = array('01','02', '03','04','05', '06','07','08', '09','10','11', '12');
    $monthlyPayments = array();
    foreach ($months as $i=>$month) {
      $this->db->select_sum('amount');
      $this->db->where('status', 2);
      $this->db->like('dateInserted', date('Y-m', strtotime(date('Y').'-'.$month)));
      $monthlyPayment = $this->db->get('payment_details')->row();
        echo $monthlyPayment->amount.',';
    }
    ?>],
                tooltip: {
                    valueSuffix: '₱'
                }
            }]
        });
    });


    

    </script>
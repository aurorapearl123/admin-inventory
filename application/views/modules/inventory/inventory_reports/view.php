<div class="subheader">
	<div class="d-flex align-items-center">
		<div class="title mr-auto">
			<h3><i class="icon left la <?php echo $current_module['icon'] ?>"></i> <?php echo $current_module['title'] ?></h3>
		</div>
		<div class="subheader-tools">
			<a href="<?php echo site_url('inventory_reports/show') ?>" class="btn btn-primary btn-raised btn-xs pill"><i class="icon ti-angle-left"></i> Back to List</a>
		</div>
	</div>
</div>
<div class="content">
	<div class="row">
		<div class="col-12">
			<div class="card-box">
				<div class="card-head">
					<div class="head-caption">
						<div class="head-title">
							<h4 class="head-text">View <?php echo $current_module['module_label'] ?>
								
							</h4>
						</div>
					</div>
					<div class="card-head-tools">
						
					</div>
				</div>
				<div class="card-body">
					<center>DR.JOSE RIZAL MEMORIAL HOSPITAL</center>
					<center>LAWA-AN, DAPITAN</center>
					<br>
					<center>INVENTORY REPORT</center>
				
					<div class="table-row mt-30">
							<table class="table-form" border="1">
								<thead>
									<tr>
										<th>ANCILLARY</th>
										<th>ITEM</th>
										<th>QUANTITY</th>
										<th>REORDER LEVEL</th>
										<th>CRITICAL LEVEL</th>
									</tr>
								</thead>
								<tbody>
								<tr>
									<td class="form-label">
										<?php echo $records[0]->division;?>
									</td>
									<td class="form-label">
										<?php echo $records[0]->name?>
									</td>
									<td class="form-label">
										<?php echo $records[0]->qty;?>
									</td>
									<td class="form-label">
										<?php echo $records[0]->reorderLvl;?>
									</td>
									<td class="form-label">
										<?php echo $records[0]->criticalLvl;?>
									</td>
									
									
								</tr>
							
								</tbody>
								<tfoot>
								
							</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		console.log("hellow");
	});
	
</script>

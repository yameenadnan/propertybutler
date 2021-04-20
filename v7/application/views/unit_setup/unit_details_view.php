<?php $this->load->view('header');?>
<?php $this->load->view('sidebar');?>
  
<!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" id="content_area">
    <!-- Content Header (Page header) -->
    <section class="content-header" >
      <h1>
        <?php echo isset($page_header) && $page_header != '' ? $page_header : ''; ?>
        <!--small>Optional description</small-->
      </h1>
      
    </section>

    <!-- Main content -->
    <section class="content container-fluid cust-container-fluid">

      <!--------------------------
        | Your Page Content Here |
        -------------------------->
        
        <!-- general form elements -->
          <div class="box box-primary">
            <!--div class="box-header with-border">
              <h3 class="box-title">Quick Example</h3>
            </div-->
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" id="bms_frm" action="<?php echo base_url('index.php/bms_unit_setup/add_unit_submit');?>" method="post" enctype="multipart/form-data">
              <div class="box-body">
                  <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12" >
                        
                      <!-- <div class="col-md-6 col-sm-12 col-xs-12 " style="padding-left: 0px;" >
                            <div class="col-md-12 col-sm-12 col-xs-12 no-padding left-box" style="border: 1px solid #999;border-radius: 5px;">
                                <div class="box-header" style="padding-left:237px ;">
                                  <h3 class="box-title"><b>UNIT DETAILS</b></h3>
                                </div>
                                
                               
								 <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        
										
                                          <td>Unit type:&nbsp;&nbsp;&nbsp;</td>
										  
                                           <td><?php //echo isset($unit_info['unti_type']) && $unit_info['unti_type'] != '' ? $unit_info['unti_type'] : '';?></td>
										                                      
                                    </div>
                                    
                                </div>
								&nbsp;
								 <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                          <td>Size:&nbsp;&nbsp;&nbsp;</td>
										  
                                           <td>N/A</td>
                                        </div>
                                    </div>
                                   
                                </div>
								&nbsp;
								<div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                          <td>Share Unites:&nbsp;&nbsp;&nbsp;</td>
										  
                                           <td>N/A</td>
                                        </div>
                                    </div>
                                   
                                </div>
								&nbsp;
								<div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                          <td>Maint Fees:&nbsp;&nbsp;&nbsp;</td>
										  
                                           <td>RM 00</td>
                                        </div>
                                    </div>
                                   
                                </div>
								&nbsp;
								<div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                          <td>Siking Fund:&nbsp;&nbsp;&nbsp;</td>
										  
                                           <td>RM 00</td>
                                        </div>
                                    </div>
                                   
                                </div>
								&nbsp;
								<div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                          <td>Insurance:&nbsp;&nbsp;&nbsp;</td>
										  
                                           <td>-N/A-</td>
                                        </div>
                                    </div>
                                   
                                </div>
								&nbsp;
								<div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                          <td>Quit Rent:&nbsp;&nbsp;&nbsp;</td>
										  
                                           <td>-N/A-</td>
                                        </div>
                                    </div>
                                   
                                </div>
								&nbsp;
								<div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                          <td>Mailing Address:&nbsp;&nbsp;&nbsp;</td>
										  <td>8-12 Levle..............</td>
										  <td><a href="" style="padding: 90%;">Update</a></td>
										  
                                           
                                        </div>
                                    </div>
                                   
                                </div>
								
                               
								
                               
                               
								
                                
                               
                                
                               
                                
                                
                            </div>
                            
                        </div>-->
                        &nbsp;
                        <div class="col-md-6 col-xs-6 ">
                            <div class="col-md-12 col-sm-12 col-xs-12 right-box" style="border: 1px solid #999;border-radius: 5px;background-color: #efefef;">
                                <div class="box-header" >
                                  <h3 class="box-title" style="padding-left: 40%;"><b>Unit Info</b></h3>
                                </div>
                                <!--<div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="form-group">
                                      <div class="checkbox">
                                        <label><input type="checkbox" name="unit[is_defaulter]" value="1" <?php echo isset($unit_info['is_defaulter']) && $unit_info['is_defaulter'] == '1' ? 'checked="checked"' : ''; ?> ><p class="text-danger">Defaulter Resident</p></label>
                                      </div>
                                    </div>
                                </div>-->
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                          <label>Service Charge</label>&nbsp;&nbsp;&nbsp;
										  
                                           <td>RM <?php echo isset($unit_info['service_charge']) && $unit_info['service_charge'] != '' ? $unit_info['service_charge'] : '-';?></td>
                                        </div>
                                    </div>
                                   
                                </div>
								<div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                          <label>Sinking Fund</label>&nbsp;&nbsp;
										  
                                           <td>RM <?php echo isset($unit_info['sinking_fund']) && $unit_info['sinking_fund'] != '' ? $unit_info['sinking_fund'] : '-';?></td>
                                        </div>
                                    </div>
                                   
                                </div>
								<div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                          <label>Insurance</label>&nbsp;&nbsp;&nbsp;
										  
                                           <td><?php echo isset($unit_info['insurance_prem']) && $unit_info['insurance_prem'] != '' ? $unit_info['insurance_prem'] : '-';?></td>
                                        </div>
                                    </div>
                                   
                                </div>
								<div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                          <label>Quit Rent</label>&nbsp;&nbsp;&nbsp;
										  
                                           <td><?php echo isset($unit_info['quit_rent']) && $unit_info['quit_rent'] != '' ? $unit_info['quit_rent'] : '-';?></td>
                                        </div>
                                    </div>
                                   
                                </div>       
                                                            
                                
                            </div>
                            
                        </div>
                        <div class="col-md-6 col-xs-6 ">
                            <div class="col-md-12 col-sm-12 col-xs-12 right-box" style="border: 1px solid #999;border-radius: 5px;background-color: #efefef; margin-top: -18px;">
                                <div class="box-header" style="padding-left: 40%;">
                                  <h3 class="box-title"><b>PAYMENT</b></h3>
                                </div>
                                <!--<div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="form-group">
                                      <div class="checkbox">
                                        <label><input type="checkbox" name="unit[is_defaulter]" value="1" <?php echo isset($unit_info['is_defaulter']) && $unit_info['is_defaulter'] == '1' ? 'checked="checked"' : ''; ?> ><p class="text-danger">Defaulter Resident</p></label>
                                      </div>
                                    </div>
                                </div>-->
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                          <td></td>
										  
                                           <td></td>
                                        </div>
                                    </div>
                                   
                                </div>
                                
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding"  style="padding-top: 10px !important;">
                                    <div class="col-md-12 col-sm-12 col-xs-12 no-padding">                                    
                                        <div class="form-group">
                                            
                                            <input type="text" name="unit[ic_passport_no]" class="form-control" value="Amount Due &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; N/A">  
                                
                                        </div>
                                    </div>
                                    
                                </div>
								<div class="box-header" >
                                  <h3 class="box-title" style="padding-left: 35%;"><b>Payment History</b></h3>
                                </div>
                                <table id="datatable" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>Receipt No</th>
                          <th>Date</th>
                          <th>Amount</th>
                          <th>Status</th>
                          
                        </tr>
                      </thead>


                      <tbody>
                       
					   
					   
					   
					   
                        
                      </tbody>
                    </table>
                               
                               
                               
                                                            
                                
                            </div>
                            
                        </div>
                      

						<!-- . right side box resident details -->
                    </div> <!-- . col-md-12 -->
                  </div><!-- . row -->
                  
                  <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12 " >
                         <div class="col-md-12 col-sm-12 col-xs-12 no-padding" style="margin-top:15px;border: 1px solid #999;border-radius: 5px;" >
                            
							
<div class="col-md-12" style="margin-top: 15px;">
  
  <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#home">Owners</a></li>
    <li><a data-toggle="tab" href="#menut">Tenants</a></li>
    <li><a data-toggle="tab" href="#menuv">Vehicles</a></li>
    <li><a data-toggle="tab" href="#menup">Parking Lot</a></li>
    <li><a data-toggle="tab" href="#menu1">Billings</a></li>
    <li><a data-toggle="tab" href="#menu2">Accounts</a></li>
   
  </ul>

  <div class="tab-content">
    <div id="home" class="tab-pane fade in active">
      <h3>Owners</h3>
      <table id="datatable" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>Name</th>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th></th>
                           <th></th>
                        </tr>
                      </thead>


                      <tbody>
                       
					   
					   
					   
					   
                        
                      </tbody>
                    </table>
	
         <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal">
            Add Owner
          </button>	
    </div>
	&nbsp;
    <div id="menut" class="tab-pane fade">
      <h3>Tenants</h3>
      <table id="datatable" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>Name</th>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th></th>
                           <th></th>
                        </tr>
                      </thead>


                      <tbody>
                       
					   
					   
					   
					   
                        
                      </tbody>
                    </table>
    </div>
    <div id="menuv" class="tab-pane fade">
      <h3>Vehicles</h3>
      <table id="datatable" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>Name</th>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th></th>
                           <th></th>
                        </tr>
                      </thead>


                      <tbody>
                       
					   
					   
					   
					   
                        
                      </tbody>
                    </table>
    </div>
    <div id="menup" class="tab-pane fade">
      <h3>Parking Lot</h3>
        <table id="datatable" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>Name</th>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th></th>
                           <th></th>
                        </tr>
                      </thead>


                      <tbody>
                       
					   
					   
					   
					   
                        
                      </tbody>
                    </table>
    </div>
    <div id="menu1" class="tab-pane fade">
      <h3>Billings</h3>
      
      <table id="datatable" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Bill No</th>
                          <th>Date</th>
                          <th>Due Date</th>
                          <th>Overdue</th>
                          <th>Payment Status</th>
                          
                        </tr>
                      </thead>


                      <tbody>
                       
					   
					   
					   
					   
                        
                      </tbody>
                    </table>
                    
    </div>
    <div id="menu2" class="tab-pane fade">
      <h3>Account Details</h3>
	  &nbsp;
	  
	  
	 <div>
	  <td><b>Range</b></td>
	  <td><input type="text"  name="" id="" value=""></td><b>To</b>
	   <td><input type="text"  name="" id="" value=""></td>
	   <td><input type="submit"  name="" class="btn btn-primary" id="" value="view"></td><br>
	  </div>
	    &nbsp;  &nbsp;
	   <div style="padding-left: 1%;">Last Refreshed:3-sep-2018 1:27:39PM</div>
	   &nbsp;
       
					 <div class="box-body">
              <table id="example2" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th class="hidden-xs">Date</th>   
                  <th>Description</th>               
                  <th>Doc No</th>
                  <th>Debit</th>
				  <th>Credit</th>
                </tr>
                </thead>
                <tbody>
                
                  
                        <tr>
                            <td class="text-center" colspan="4">No Record Found</td>                            
                        </tr>                    
                              
                </tbody>                
              </table>
            </div>
			<h3>Notes</h3>
			<tr>
			<td>1.All cheques/TT should made payable to "*******". please indicate your NAME,UNIT NO.and CONTACT NO. on the reverse side of your cheque. </td><br>
			<td>2.for bank transfer, kindly credit to our building maintenance account at MBB Account No:564810521802 </td>
			</tr>
			
					
					
					
    </div>
    
	
	
  </div>
</div>



							
                            
                         </div>
                    </div> <!-- . col-md-12 -->
                  </div><!-- . row -->
                        
                
              <div class="col-md-12" >
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <p class="help-block"> * Required Fields.</p>
                        </div>
                    </div>
              </div>
          </div><!-- /.box-body -->
          
          <div class="row" style="text-align: right;"> 
            <div class="col-md-12">
              <div class="box-footer">
               <!-- <button type="submit" class="btn btn-primary">Submit</button> &ensp;&ensp;
                <button type="Reset" class="btn btn-default reset_btn" >Reset</button> &ensp;&ensp;-->
                <!--button type="button" class="btn btn-success" onclick="window.history.go(-1); return false;">Back</button>&ensp;&ensp;&ensp;-->
              </div>
            </div>
          </div>
        </form>
      </div>
          <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  

 
 


 <div class="modal fade" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header" style="background-color: #337ab7;">
          <h4 class="modal-title" style="color: #fff;">Add Owner</h4>
          <button type="button" style="color:#fff;margin-top: -23px;" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
		<h3 class="modal-title" style="padding-left: 36%;" >Add Tenants</h3>
		<hr/>
        <div class="modal-body" >
		
          <form action="#" id="form" class="form-horizontal">
		 
          <input type="hidden" value="" name="book_id"/>
          <div class="form-body">
            <div class="form-group">
              <label class="control-label col-md-3">Full Name*</label>
              <div class="col-md-9">
                <input name="book_isbn" placeholder="" class="form-control" type="text">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Title*</label>
              <div class="col-md-9">
                <input name="book_title" placeholder="" class="form-control" type="text">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Type*</label>
              <div class="col-md-9">
								
								<input type="radio" name="gender" value="male"> Individual
								<input type="radio" name="gender" value="male"> Organization

              </div>
            </div>
						<div class="form-group">
							<label class="control-label col-md-3">Sex*</label>
							<div class="col-md-9">
								<input type="radio" name="gender" value="male"> Male
								<input type="radio" name="gender" value="male"> Female
								<input type="radio" name="gender" value="male"> N/A

							</div>
						</div>
                       <div class="form-group">
							<label class="control-label col-md-3">Nationality*</label>
							<div class="col-md-9">
								<select class="form-control" id="property_id" name="unit[property_id]">
                                            <option value="">Select</option>
											 <option value="">Afghanistan</option>
											  <option value="">Albania</option>
											   <option value="">American Samoa</option>
											    <option value="">Anguilla</option>
												 <option value="">Antarctica</option>
                                            <?php 
                                                //foreach ($properties as $key=>$val) { 
                                                 //   $selected = isset($unit_info['property_id']) && $unit_info['property_id'] == $val['property_id'] ? 'selected="selected" ' : '';  
                                                 //   echo "<option value='".$val['property_id']."' ".$selected.">".$val['property_name']."</option>";
                                                //} ?> 
                                        </select>

							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">ID Number*</label>
							<div class="col-md-9">
								<input name="book_title" placeholder="" class="form-control" type="text">

							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Notes*</label>
							<div class="col-md-9">
								<input name="book_title" placeholder="" class="form-control" type="text">

							</div>
						</div>
						<h4 class="modal-title" style="padding-left: 27%;" >Contact</h4>
						&nbsp;
						<div class="form-group">
							<label class="control-label col-md-3">Phone #1*</label>
							<div class="col-md-9">
								<input name="book_title" placeholder="" class="form-control" type="text">

							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Phone #2*</label>
							<div class="col-md-9">
								<input name="book_title" placeholder="" class="form-control" type="text">

							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Email*</label>
							<div class="col-md-9">
								<input name="book_title" placeholder="" class="form-control" type="text">

							</div>
						</div>
						<hr/>
						<h4 class="modal-title" style="padding-left: 27%;" >Vehicle <button>Show</button></h4>
						<hr/>
						
						<h4 class="modal-title" style="padding-left: 27%;">Tenancy</h4>
						&nbsp;
						<div class="form-group">
							<label class="control-label col-md-3">Usage Category</label>
							<div class="col-md-9">
								<input name="book_title" placeholder="" class="form-control" type="text">

							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Start Date</label>
							<div class="col-md-9">
								<input name="date" placeholder="" class="form-control" type="date">

							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">End Date</label>
							<div class="col-md-9">
								<input name="date" placeholder="" class="form-control" type="date">

							</div>
						</div>
						<h4 class="modal-title" style="padding-left: 27%;">Billing</h4>
						&nbsp;
						<div class="form-group">
							<label class="control-label col-md-3">Include in billing</label>
							<div class="col-md-9">
								<input type="checkbox" name="vehicle1" value="Bike"> 

							</div>
						</div>
						<h4 class="modal-title" style="padding-left: 27%;">Address <button>Show</button></h4>
          </div>
		  </table>
        </form>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer" style="text-align: center;">
		<button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>
  
<?php $this->load->view('footer');?>  
<!-- bootstrap datepicker -->
<script src="<?php echo base_url();?>bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- overlay loader for ajax call -->
<script src="<?php echo base_url();?>assets/js/loadingoverlay.js"></script>  
<script src="<?php echo base_url();?>assets/js/jquery.validate.js"></script>
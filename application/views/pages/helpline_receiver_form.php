<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/selectize.css">
<script type="text/javascript" src="<?php echo base_url();?>assets/js/bootbox.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.selectize.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/metallic.css" >
<script type="text/javascript" src="<?php echo base_url();?>assets/js/zebra_datepicker.js"></script>
<script type="text/javascript">
$(function(){
	$('[data-toggle="tooltip"]').tooltip();

	window['userList'] = [];
	//edit scenario...
	if(window['edit_data']){
		var helpline_receiver = window['edit_data'].helpline_receiver;
		var helpline_receiver_link = window['edit_data'].helpline_receiver_link.map((hrl) => hrl.helpline_id);
		window['userList'] = res = transformUser(window['edit_data']['userList']);

		$.each(Object.keys(helpline_receiver), function(i, k){
			if(helpline_receiver[k]){
				if($('input#' + k).attr('type') == 'checkbox'){
					if(helpline_receiver[k] == "1") $('input#' + k).attr('checked', 'checked');
				} else { // if($('input#' + k).attr('type') == 'text' || $('select#' + k).length > 0){
					$('#' + k).val(helpline_receiver[k]);
				}
			}
		})

		if(helpline_receiver_link && helpline_receiver_link.length > 0){
			$('#helpline_receiver_link').val(helpline_receiver_link);
		}

		$('#phone').attr('disabled', 'disabled');
		$('#user_id').attr("data-previous-value", helpline_receiver['user_id']);

	}
	defaultHelplineOnChange();
	initUserSelectize();
});

function transformUser(res){
	if(res){
		res.map(function(d){
			d.custom_data = d.first_name + ' '+ d.last_name + ' - ' + d.phone + ' - ' + d.username;
		    return d;
		});
	}
	return res;
}

function defaultHelplineOnChange(){
	$('#helpline_receiver_link option').show();
	$('#helpline_receiver_link option[value="'+$('#helpline_id').val()+'"]').removeAttr('selected').hide();
}

function initUserSelectize(){
	var selectize = $('#user_id').selectize({
	    valueField: 'user_id',
	    labelField: 'custom_data',
	    searchField: 'custom_data',
	    options: window['userList'],
	    create: false,
	    render: {
	        option: function(item, escape) {
	        	return '<div>' +
	                '<span class="title">' +
	                    '<span class="prescription_drug_selectize_span">' + escape(item.custom_data) + '</span>' +
	                '</span>' +
	            '</div>';
	        }
	    },
	    load: function(query, callback) {
	        if (!query.length) return callback();
	        $.ajax({
	            url: '<?php echo base_url();?>helpline/search_staff_user',
	            type: 'POST',
				dataType : 'JSON',
				data : { query: query },
	            error: function(res) {
	                callback();
	            },
	            success: function(res) {
	            	res = transformUser(res);
	                callback(res.slice(0, 10));
	            }
	        });
		},

	});
	if($('#user_id').attr("data-previous-value")){
		selectize[0].selectize.setValue($('#user_id').attr("data-previous-value"));
	}
}
</script>

</script>
<style type="text/css">
	.form-horizontal{
		margin-bottom: 20px;
	}
	.info_icon{
    	width: 15px;
    	height: 15px;
    	margin-right: 5px;
    	margin-left: 5px;
    }
    .selectize-control.repositories .selectize-dropdown > div {
		border-bottom: 1px solid rgba(0,0,0,0.05);
	}
textarea {
  resize: none;
}
</style>
<center>
	<?php
		echo validation_errors();
		if (isset($msg)){?>
		<div class="alert alert-info">
		<?php echo $msg ?>
		</div>
		<?php } ?>
		
		
		<?php if(isset($edit_data)) { ?>
			<script> var edit_data = <?php echo $edit_data; ?>; </script>
        	<h3>  Edit Helpline Receivers</h3>
	    <?php } else { ?>
	        <h3>  Add Helpline Receivers</h3>
	    <?php }?>
</center></br>
	<?php echo form_open($submitLink, array('class'=>'form-horizontal','role'=>'form','id'=>'add_form')); ?>
	
	<div class="col-xs-12">
		<div class="container">
			<div class="row">							
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3">
					<div class="form-horizontal">
						<label for="full_name">Full Name<font style="color:red">*</font></label>
						<input type="text" class="form-control" placeholder="Enter Full Name" id="full_name" name="full_name" required/>
					</div>
				</div>

				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3">
					<div class="form-horizontal">
						<label for="short_name">Display Name<font style="color:red">*</font></label>
						<input type="text" class="form-control" placeholder="Enter Display Name" id="short_name" name="short_name" required/>
					</div>
				</div>

				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3">
					<div class="form-horizontal">
						<label for="phone">Phone<font style="color:red">*</font><img src="<?php echo base_url();?>assets/images/information-icon.png" class="info_icon" title="If it is an Indian Mobile, Please add 0 as prefix." data-toggle="tooltip"/></label>
						<input type="text"  class="form-control" placeholder="Enter Phone" id="phone" name="phone" required/>
					</div>
				</div>

				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3">
					<div class="form-horizontal">
						<label for="email">Email<font style="color:red">*</font></label>
						<input type="email" class="form-control" placeholder="Enter Email" id="email" name="email" required/>
					</div>
				</div>

				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3">
					<div class="form-horizontal">
						<label for="district_id">District<font style="color:red">*</font></label>
						<select id="district_id" name="district_id" class="form-control" onchange="" required/>
							<option value="">Select District</option>
							<?php 
								foreach($districts as $district){
									echo "<option value='".$district->district_id."'";
									echo ">".$district->district. "-" .$district->state. "</option>";
								}
								?>
						</select>
					</div>
				</div>

				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3">
					<div class="form-horizontal">
						<label for="category">Category<font style="color:red">*</font></label>
						<input type="text" class="form-control" placeholder="Enter Category" id="category" name="category" required/>
					</div>
				</div>

				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3">
					<div class="form-horizontal">
						<label for="user_id">User</label>
						<select id="user_id" name="user_id" class="" placeholder="-Enter User Name/Phone-">
							<option value="">-Enter User Name/Phone-</option>
						</select>
					</div>
				</div>

				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3">
					<div class="form-horizontal">
						<label></label>
						<div class="form-control" style="box-shadow: none; border: 0;padding-left: 0;"><label for="doctor"><input type="checkbox" id="doctor" name="doctor" value="1" /> Is Doctor?</label></div>
					</div>
				</div>

				
				<div class="clearfix"></div>

				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3">
					<div class="form-horizontal">
						<label></label>
						<div class="form-control" style="box-shadow: none; border: 0;padding-left: 0;"><label for="enable_outbound"><input type="checkbox" id="enable_outbound" name="enable_outbound" value="1" /> Enable Outbound Calls?</label></div>
					</div>
				</div>

				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3">
					<div class="form-horizontal">
						<label for="app_id">App ID</label>
						<input type="text" class="form-control" placeholder="Enter App Id" id="app_id" name="app_id" />
					</div>
				</div>

				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3">
					<div class="form-horizontal">
						<label for="helpline_id">Default Helpline</label>
						<select id="helpline_id" name="helpline_id" class="form-control" onchange="defaultHelplineOnChange()">
							<option value="">Select Helpline</option>
							<?php 
								foreach($helplines as $helpline){
									echo "<option value='".$helpline->helpline_id."'";
									// if($this->input->post('helpline_id') && $this->input->post('helpline_id') == $helpline->helpline_id) echo " selected ";
									echo ">".$helpline->note. " - " .$helpline->helpline."</option>";
								}
								?>
						</select>
					</div>
				</div>

				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3">
					<div class="form-horizontal">
						<label></label>
						<div class="form-control" style="box-shadow: none; border: 0;padding-left: 0;"><label for="activity_status"><input type="checkbox" id="activity_status" name="activity_status" value="1" /> Enable Activity Status?</label></div>
					</div>
				</div>

			<div class="clearfix"></div>
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3">
					<div class="form-horizontal">
						<label for="helpline_receiver_link">Helpline Links<img src="<?php echo base_url();?>assets/images/information-icon.png" class="info_icon" title="Hold CTRL key and Click for multiple selections" data-toggle="tooltip"/></label>
						<select id="helpline_receiver_link" name="helpline_receiver_link[]" class="form-control" multiple="">
							<?php 
								foreach($helplines as $helpline){
									echo "<option value='".$helpline->helpline_id."'";
									// if($this->input->post('helpline_id') && $this->input->post('helpline_id') == $helpline->helpline_id) echo " selected ";
									echo ">".$helpline->note. " - " .$helpline->helpline."</option>";
								}
								?>
						</select>
					</div>
				</div>
				
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3">
				
				<button type="button" class="btn btn-info" style="margin-bottom:12px" data-toggle="modal" data-target="#addLanguageModal">Add Language</button>
				<div style="height: 133px; overflow-y: auto;overflow-x: hidden;">
				
					<div class="form-horizontal">
					<table class="table table-bordered table-striped"   id="table-sort" name="language_table_id">
					<thead style="position: sticky; top: 0; background: #ffff;">
						<th>Receiver Language</th>
						<th>Proficiency</th>
					</thead>	
					<tbody>
					<?php if(isset($receiver_languages) && count($receiver_languages)>0)
					{ ?>
						<?php 
						foreach($receiver_languages as $s){
						?>
					    <tr>
						<td><?php echo $s->language;?></td>
						<td><?php echo $proficiency[$s->proficiency];?></td>
					     </tr>
						<?php } ?>
						<?php } ?> 						
					</tbody>
					</table>
					</div>
				     </div> 
				</div> 
					
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-3">
					<div class="form-horizontal" style="margin-top:20px;">
					<label for="helpline_receiver_note">Helpline Receiver Note </label> 
					<textarea rows="6" cols="50" class="form-control" id="helpline_receiver_note" name="helpline_receiver_note"></textarea>
					</div>
				</div>
			</div>
			
		</div>
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<center><button class="btn btn-md btn-primary" type="submit" value="submit">Submit</button></center>
				</div>
			</div>
		</div>
		</form>
	</div>
</div>

<div class="modal fade" id="addLanguageModal" role="dialog">
	<div class="modal-dialog">
	<!-- Modal content-->
	<div class="modal-content">
		<div class="modal-header bg-primary text-white">
		      <button type="button" class="close" data-dismiss="modal">&times;</button>
		      <h4 class="modal-title">Update/Edit Languages</h4>
		</div>
		<div class="modal-body">
				<div class="row">
					<div class="col-md-4"> 
						<label for="language_id_1">Language</label>
						<select id="language_id_1" name="language_id_1" class="form-control" >
							<option value="">Select Language</option> 
							<?php 
								foreach($languages as $language){ 
									echo "<option value='".$language->language_id."'";
									echo ">".$language->language."</option>"; 
							}
							?>
						</select>
					</div>
					<div class="col-md-4"> 
						<label for="proficiency_id_1"> Proficiency</label>
						<select id="proficiency_id_1" name="proficiency_id_1" class="form-control" >
							<option value="">Select Proficiency</option> 
							<?php 
								foreach($proficiency as $key=>$val){ 
									echo "<option value='".$key."'";
									echo ">".$val."</option>"; 
							}
							?>
						</select>
					</div>
				<div class="col-md-4"> 
					<button type="button" class="btn btn-success" data-dismiss="modal">ADD</button>
				</div>
				</div>
		   </div>
	     </div>
	</div>
</div>
<!-- Modal -->
<script type="text/javascript">
var languages = <?php echo json_encode($languages); ?>;
var proficiencies = <?php echo json_encode($proficiency); ?>;
var current_languages = <?php echo json_encode($receiver_languages); ?>;
$('#addLanguageModal').on('hidden.bs.modal', function () {
  // do something...
//	console.log("I am here after the close ");
 //	console.log(document.getElementById("table-sort"));
//	console.log(document.getElementById("language_id_1").value);
//	console.log(document.getElementById("proficiency_id_1").value);
	var language_id = document.getElementById("language_id_1").value;
	var proficiency_id = document.getElementById("proficiency_id_1").value;
	if (proficiency_id.length === 0 || language_id === 0) {
		bootbox.alert("Please fill all the values");
		document.getElementById("language_id_1").reset();
		document.getElementById("proficiency_id_1").reset();
		return;	
	}
	
	const language = languages.filter(language => language.language_id == language_id);
	const proficiency = proficiencies[proficiency_id];
	if(current_languages.filter(current_languages => current_languages.language_id === language_id).length > 0){
		return;
	}
	if ($('#mytext'+language[0].language_id).length > 0) {
 		return;
	}
	// console.log(language[0].language);
	// console.log(proficiency);
	var input = document.createElement('input');
	input.name = 'mytext';
	input.type = 'text';
	input.value = language[0].language_id;
	input.id = 'mytext'+language[0].language_id;
	input.disabled=true;
	input.hidden=true;
	$('#add_form').append(input);
	newRow = "<tr><td> <input type='text' name='mylanguage[]' id='mylanguage' required hidden value=" + language[0].language_id +">"+ language[0].language + "</td> <td> " + 
			  " <input type='text' name='myproficiency[]' id='myproficiency' required hidden value=" + proficiency_id + ">" +  proficiency + "</td></tr>";
	var tbl = $("#table-sort");
	tbl.append(newRow);
	
	// reset the values
	document.getElementById("language_id_1").reset();
	document.getElementById("proficiency_id_1").reset();
	// console.log(newRow);
   	//$('#table-sort > tbody > tr:last').after(newRow);

});

function submitOnClick() {
	// document.getElementById("table_values_id").value = document.getElementById("table-sort");
	// console.log(document.getElementById("table-sort"));
}
</script>

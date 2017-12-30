
<!--
	Iterate through the view model and create the fields specified.
	This skeleton allows me to provide a loose model of how a form should look and operate.
	Then I can take its postback and validate it against the view model.
-->
<?php 
foreach($ViewModel as $name => $input) { ?>
	<div class="form-group<?php
		if(array_key_exists("hidden", $input)
			&& $input['hidden']) {
			echo " hidden-fields";
		}
	?>">
		<div class="row"> 
			<label class="col-md-4" for="<?php echo $name; ?>">
				<?php 
					echo $input['label'];
					if(array_key_exists("required", $input)) {
						echo $input['required']?'*':''; 
					}
				?>
			</label>

			<div class="col-md-8">
				<?php 
				#If the input reqired a dropdown selection.
				if(strcmp($input['type'], 'dropdown')==0) { ?>
					<select 
					name="User['<?php echo $name; ?>']"
					id="<?php echo $name; ?>"
					<?php if(array_key_exists("default", $input)) {
						echo 'readonly';
					} ?>
					class="form-control">

						<?php foreach($CountryList as $country) { ?>
							<option 
							value="<?php echo $country['ABBR'].'"';  
							if(array_key_exists("default", $input) 
								&& strcmp($input['default'], $country['ABBR'])==0) {
								echo "selected";
							}
						?>>
								<?php echo $country['FullName']; ?>
							</option>

						<?php } ?>
					</select>
				<?php } 
				#If the input requires textarea
				else if(strcmp($input['type'], 'textarea')==0) { ?>
					<textarea 
					class="form-control"
					name="User['<?php echo $name; ?>']"
					><?php
							if(array_key_exists("default", $input)) {
								echo ' value="' . $input['default'] . '"';
							}
					?></textarea>
				<?php } 
				#If the input requires an input tag branch to the else stmt.
				else { ?>

					<input 
					type="<?php echo $input['type']; ?>"
					name="User['<?php echo $name; ?>']" 
					id="<?php echo $name; ?>"
					<?php 
						if(array_key_exists("maxlength", $input)) {
							echo ' maxlength="' . $input['maxlength'] . '"';
						}
						if(array_key_exists("minlength", $input)) {
							echo ' data-minlength="' . $input['minlength'] . '"';
						}
						if(array_key_exists("matches", $input)) {
							echo ' data-match="#'.$input['matches'].'"'; 
						}
						if(array_key_exists("required", $input)) {
							echo $input['required']?" required":"";
						}
						if(array_key_exists("default", $input)) {
							echo ' value="' . $input['default'] . '"';
							echo ' readonly';
						}
						if(array_key_exists("onclick", $input)) {
							echo ' onclick="' . $input['onclick'] . '"';
						}
						if(array_key_exists("disabled", $input)) {
							echo $input['disabled']?" disabled":"";
						}
					?>
					class="form-control<?php
						if(array_key_exists("hidden", $input)
							&& $input['hidden']) {
							echo " hidden-fields";
						}
					?>" 
					autofocus />
				<?php } ?>
			</div>
		</div>
		<div class="help-block with-errors"></div>
	</div>
<?php } ?>
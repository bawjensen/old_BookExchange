<?php echo validation_errors(); ?>

<?php echo form_open(current_url()) ?>

<label for="text">Password</label>
<input type="password" name="adminpass" >

<input type="submit" name="submit" value="Authenticate Deletion">

</form>
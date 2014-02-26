<span id="loginregistererrormessages"><?php echo validation_errors(); ?></span>


<h2>Log In</h2>

<?php echo form_open('login') ?>

<label for="title">Username</label>
<input type="text" name="username" >

<label for="text">Password</label>
<input type="password" name="password" >

<input type="submit" name="submit" value="Log In">

</form>


<h2>Register an account</h2>

<?php echo form_open('register') ?>

<label for="title">Username</label>
<input type="text" name="username" value="<?php echo set_value('username'); ?>">

<label for="text">Password</label>
<input type="password" name="password" value="<?php echo set_value('password'); ?>">

<label for="text">Confirm Password</label>
<input type="password" name="passconf" value="<?php echo set_value('passconf'); ?>">

<input type="submit" name="submit" value="Register">

</form>
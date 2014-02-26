<?php if ($loggedin): ?>
	<?php echo validation_errors(); ?>
	<?php echo form_open('selling') ?>

	<div class="sellingoffers" id="newoffer">
		<legend>Create offer:</legend>
		<div id="offermain">
			<span class="sellingtext">Selling</span><input class="createofferinput" type="text" name="title" placeholder="Book Title" value="<?php echo set_value('title'); ?>">
			<span class="sellingtext">at</span><input class="createofferinput" type="text" name="price" placeholder="Price" value="<?php echo set_value('price'); ?>">
			<span class="sellingtext">used for</span><input class="createofferinput" type="text" name="course" placeholder="Course" value="<?php echo set_value('course'); ?>">
		</div>
		<div id="offersecondary">
			<textarea class="createofferinput" name="description" placeholder="Description"><?php echo set_value('description'); ?></textarea>
			<input class="createofferinput" type="text" name="isbn" placeholder="ISBN" value="<?php echo set_value('isbn'); ?>">
		</div>

		<input class="createofferinput" type="submit" value="Create">
	</div>

	</form>

<?php else: ?>
	<a href="/login"><span id="loginredirect">Log in to create offers.</span></a>

<?php endif ?>
<?php if ($loggedin): ?>
	<div class="sellingoffers" id="existingoffers">
	<legend>My offers:</legend>
	<?php include 'application/views/displayoffersarray.php'; ?>

	</div>

<?php else: ?>
	<div id="notloggedindiv"><a href="/login">Log in to view your account and create new offers</a></div>
	
<?php endif ?>
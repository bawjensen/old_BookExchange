<?php if($loggedin): ?>
	<div id="offerbook">Own this book? Offer it up for sale</div>

	<?php echo validation_errors(); ?>
	<?php echo form_open('/offerbook/' . $googleid); ?>

	<div class="<?php echo isset($_POST['price']) ? '' : 'hidden' ?>" id="bookoffer">
		<label for="title">Title</label>
		<?php $titletext = isset($subtitle) ? $title . ': ' . $subtitle : $title; ?>
		<input type="text" name="title" id="title" value="<?php echo $titletext; ?>" placeholder="Title">
		
		<label for="authors">Authors</label>
		<?php $authortext = is_array($authors) ? implode(', ', $authors) : $authors; ?>
		<input type="text" name="authors" id="authors" value="<?php echo $authortext; ?>" placeholder="Author(s)">
		
		<label for="price">Price</label>
		<input type="number" step="any" name="price" id="price" value="<?php echo @$_POST['price']; ?>" placeholder="Post price">
		
		<label for="edition">Edition #</label>
		<input type="number" step="any" name="edition" id="edition" value="<?php echo @$_POST['edition']; ?>" placeholder="Book edition (Optional)">
		
		<label for="course">Course</label>
		<input type="text" name="course" id="course" value="<?php echo @$_POST['course']; ?>" placeholder="Course Code (i.e. COMP-115) (Optional)">
		
		<label for="isbn">ISBN-10</label>
		<input type="number" step="any" name="isbn" id="isbn" value="<?php echo $ISBN_10; ?>" placeholder="ISBN-10">
		
		<label for="isbnv2">ISBN-13</label>
		<input type="number" step="any" name="isbnv2" id="isbnv2" value="<?php echo $ISBN_13; ?>" placeholder="ISBN-13">

		<input type="submit" id="createbookoffer" value="Create Offer">
	</div>

	</form>
<?php else: ?>
	<div id="notloggedindiv">
	<a href="/login"><span id="loginredirect">Log in to offer this book for sale</span></a>
	</div>
<?php endif ?>
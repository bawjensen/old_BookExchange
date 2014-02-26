<?php echo validation_errors(); ?>

<div id="searchingdiv">
<legend>Search books:</legend>
<?php echo form_open('search'); ?>

<input type="search" class="searchfield" name="title" value="<?php echo set_value('title'); ?>" placeholder="Search by Title">
<input type="search" class="searchfield" name="author" value="<?php echo set_value('author'); ?>" placeholder="Search by Author">
<input type="search" class="searchfield" name="isbn" value="<?php echo set_value('isbn'); ?>" placeholder="Search by ISBN">
<input type="search" class="searchfield" name="wildcard" value="<?php echo set_value('wildcard'); ?>" placeholder="Search Everything">
<input type="submit" value="Search">

</form>
</div>

<?php if(isset($searchresults)): ?>
	<div id="resultsheader">Showing a maximum of <?php echo $numresults; ?> results:</div>

	<ol id="searchresults">
	<?php foreach($searchresults as $index => $book): ?>
		<li class="<?php echo $index % 2 == 0 ? 'odd' : 'even'; ?>">
			<a href="viewbook/<?php echo $book['id']; ?>">
			<div class="resultimagediv"><img class="searchresultimage" src="<?php echo $book['imageLink']; ?>"></div>
			<div class="resulttextdiv"><?php echo $book['text']; ?></div>
			</a>
		</li>
	<?php endforeach ?>	
	</ol>
<?php endif ?>
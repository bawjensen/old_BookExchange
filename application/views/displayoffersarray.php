<div id="savealloffers">Save Changes</div>

<?php foreach ($activeoffers as $offer): ?>
	<div class="offerdiv">
		<div id="<?php echo "id_price_".$offer['id'] ?>" class="offerpricediv editable"><?php echo '$' . $offer['price']; ?></div>
		<div id="<?php echo "id_course_".$offer['id'] ?>" class="offercoursediv editable"><?php echo $offer['course']; ?></div>
		<div id="<?php echo "id_title_".$offer['id'] ?>" class="offertitlediv editable"><?php echo $offer['title']; ?></div>
		<div id="<?php echo "id_text_".$offer['id'] ?>" class="offertextdiv editable"><?php echo $offer['description']; ?></div>
		<div class="offersellerdiv">created <?php echo timeAgo($offer['datecreated']); ?> by <?php echo $offer['seller']; ?></div>
		<?php if (strtolower($offer['seller']) == strtolower($username)): ?>
			<a href="deleteoffer/<?php echo $offer['id'] ?>"><img class="alterlink offerdelete" src="glyphicons_207_remove_2.png" alt="delete"></a>
			<!-- <a href="editoffer/<?php echo $offer['id'] ?>"><img class="alterlink offeredit" src="glyphicons_150_edit.png" alt="edit"></a> -->
			<img class="alterlink offeredit" src="glyphicons_150_edit.png" alt="edit">
		<?php endif ?>
	</div>
<?php endforeach ?>
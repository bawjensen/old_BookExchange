<?php foreach ($users as $user): ?>

	<?php echo $user['username'] ?> :
	<?php echo $user['password'] ?> ---
	<a href="deleteuser/<?php echo urlencode($user['username']); ?>">Delete</a>

<br>
<?php endforeach ?>
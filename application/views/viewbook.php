<div id="content"></div>

<div id="titlesdiv"><?php echo $title; echo $subtitle != '' ? ': ' . $subtitle : NULL; ?></div>
<div id="bookimagediv"><img id="bookimage" src="<?php echo $imagelink; ?>" alt="Image:Book Cover"></div>

<?php include 'application/views/offerbook.php'; ?>

<table id="bookinfotable">
<tr>
	<td class="bookheadercolumn">Author(s)</td>
	<td class="bookinfocolumn"><?php echo implode(', ', $authors) ?></td>
</tr>
<tr>
	<td class="bookheadercolumn">Publisher</td>
	<td class="bookinfocolumn"><?php echo $publisher; ?></td>
</tr>
<tr>
	<td class="bookheadercolumn">Publish Date</td>
	<td class="bookinfocolumn"><?php echo $publishdate; ?></td>
</tr>
<tr>
	<td class="bookheadercolumn">ISBN-10</td>
	<td class="bookinfocolumn"><?php echo $ISBN_10; ?></td>
</tr>
<tr>
	<td class="bookheadercolumn">ISBN-13</td>
	<td class="bookinfocolumn"><?php echo $ISBN_13; ?></td>
</tr>
<tr>
	<td class="bookheadercolumn">Description</td>
	<td class="bookinfocolumn"><?php echo $description; ?></td>
</tr>
<tr>
	<td class="bookheadercolumn">More Info (Google Books)</td>
	<td class="bookinfocolumn"><a href="<?php echo $infolink; ?>">Click here</a></td>
</tr>
<tr>
	<td class="bookheadercolumn">List Price<sup class="googlepricetooltip">[?]</sup></td>
	<td class="bookinfocolumn"><?php echo $listpricetype . ' ' . $listprice; ?></td>
</tr>
<tr>
	<td class="bookheadercolumn">Retail Price<sup class="googlepricetooltip">[?]</sup></td>
	<td class="bookinfocolumn"><?php echo $retailpricetype . ' ' . $retailprice; ?></td>
</tr>
</table>
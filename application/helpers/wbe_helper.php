<?php 

if (!function_exists('generateSearchResults')) {
	function generateSearchResults($bookarray) {
		$resultArray = array();
		foreach ($bookarray as $bookinfo) {
			$imageLink = getLargestImage($bookinfo);

			$text = $bookinfo->volumeInfo->title;
			if(isset($bookinfo->volumeInfo->subtitle)) {
				$text .= ': ' . $bookinfo->volumeInfo->subtitle;
			}
			$text .= ' by ';

			if(isset($bookinfo->volumeInfo->authors)) {
				$text .= implode(', ', $bookinfo->volumeInfo->authors);
			}
			else {
				$text .= 'Unknown';
			}

			$resultArray[] = array('text' => $text,
				'id' => $bookinfo->id,
				'imageLink' => $imageLink);
		}
		return $resultArray;
	}
}

if (!function_exists('getLargestImage')) {
	function getLargestImage($bookinfo) {
		// Attempting to get the largest image - if it fails it moves on to the next
		$imageLink = isset($bookinfo->volumeInfo->imageLinks->extraLarge) ? $bookinfo->volumeInfo->imageLinks->large : FALSE;
		$imageLink = !$imageLink ? (isset($bookinfo->volumeInfo->imageLinks->large) ? $bookinfo->volumeInfo->imageLinks->large : $imageLink) : $imageLink;
		$imageLink = !$imageLink ? (isset($bookinfo->volumeInfo->imageLinks->medium) ? $bookinfo->volumeInfo->imageLinks->medium : $imageLink) : $imageLink;
		$imageLink = !$imageLink ? (isset($bookinfo->volumeInfo->imageLinks->small) ? $bookinfo->volumeInfo->imageLinks->small : $imageLink) : $imageLink;
		$imageLink = !$imageLink ? (isset($bookinfo->volumeInfo->imageLinks->thumbnail) ? $bookinfo->volumeInfo->imageLinks->thumbnail : $imageLink) : $imageLink;
		$imageLink = !$imageLink ? (isset($bookinfo->volumeInfo->imageLinks->smallThumbnail) ? $bookinfo->volumeInfo->imageLinks->smallThumbnail : $imageLink) : $imageLink;
		$imageLink = !$imageLink ? '' : $imageLink;

		return $imageLink;
	}
}

if (!function_exists('timeAgo')) {
	function timeAgo($timeStamp) {
		$elapsedTime = time() - strtotime($timeStamp);

		if ($elapsedTime < 1) {
			return '0 seconds';
		}

		$secondsArray = array( 12 * 30 * 24 * 60 * 60  =>  'year',
							   30 * 24 * 60 * 60       =>  'month',
							   24 * 60 * 60            =>  'day',
							   60 * 60                 =>  'hour',
							   60                      =>  'minute',
							   1                       =>  'second'
							   );

		foreach ($secondsArray as $secs => $unit) {
			$timeInUnit = $elapsedTime / $secs;

			if ($timeInUnit >= 1) {
				$rounded = round($timeInUnit);
				return $rounded . ' ' . $unit . ($rounded > 1 ? 's' : '') . ' ago';
			}
		}
	}
}
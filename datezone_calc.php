<?php

include('view/header.php');
?>
<form  action="start.php" method="POST">
  <label for="date1">First date:</label><br>
  <input type="date" id="date1" name="date1"><br>

  <label for="id1">Country id:</label><br>
  <input type="text"  id="id1" name="id1"><br>

  <label for="date2">second date:</label><br>
  <input type="date" id="date2" name="date2">

  <label for="id2">Country id:</label><br>
  <input type="text" id="id2" name="id2"><br>

  <input type="submit" name="submit" value="submit">
</form>

<?php


if (isset($_POST['date1']) &&
    isset($_POST['id1']) &&
    isset($_POST['date2']) &&
    isset($_POST['id2'])  &&
    isset($_POST['submit'])) 
    {
      $date1= $_POST['date1'];
      $id1=   $_POST['id1'];
      $date2= $_POST['date2'];
      $id2=   $_POST['id2'];      
echo $date1 . "<br>" . $id1 . "<br>" . $date2 . "<br>" . $id2 . "<br>" ;


$timezone1 = $id1;
$timezone2 = $id2;
// Create DateTimeZone objects for each time zone.
$tzObj1 = new DateTimeZone($timezone1);
$tzObj2 = new DateTimeZone($timezone2);
// Use $tzObj1 to get the current date and time in $timezone1.
// Notice that you need the date and time in only one time zone
// to work out the time difference between them.
$now = new DateTime('now', $tzObj1);

// Work out the offset from UTC in time zone 1.
$offset1 = $tzObj1->getOffset($now);
// Use the date and time in time zone 1 to get the UTC offset in the other time zone.
$offset2 = $tzObj2->getOffset($now);
// Work out the time difference by subtracting one offset from the other.
$diff = $offset1 - $offset2;
if ($diff == 0) {
	echo 'Both cities are in the same time zone';
} else {
	// Convert the time difference from seconds.
	// Time zones to the west of the prime meridian produce a negative
	// offset, so use abs() to convert negative numbers to positive.
	// Not all time zones are an exact number of hours from UTC, so
	// use floor() to get an integer. 
	$hours = floor(abs($diff)/60/60);
	// Use modulo to see if there is a remainder.
	// The remainder will still be in seconds,
	// so it needs to be divided by 60. 
	$minutes = ((abs($diff)%3600)/60);
	// Format the time difference as a string.
	$gap = "$hours hour(s) $minutes minutes";
	// Work out which timezone has a bigger offset
	$whichWay = ($offset1 > $offset2) ? 'ahead of' : 'behind';
	echo "<p>$timezone1 is $gap $whichWay $timezone2.</p>";
} 
// Now that the calculation has been performed, get the current
// date and time in the second time zone for confirmation that
// the calculation is accurate.
$otherTime = new DateTime('now', $tzObj2);
echo "<p>For confirmation, the time in $timezone1 is " . $now->format('g:i A');
echo ", and in $timezone2, it's " . $otherTime->format('g:i A') . '</p><br>';

echo "<p>the difference between the counties is </p>";
  // Creates DateTime objects
  $datetime1 = date_create($date1);
  $datetime2 = date_create($date2);
  
  // Calculates the difference between DateTime objects
  $interval = date_diff($datetime1, $datetime2);
  
  // Display the result
  $hours = floor(abs($diff)/60/60);
	// Use modulo to see if there is a remainder.
	// The remainder will still be in seconds,
	// so it needs to be divided by 60. 
	$minutes = ((abs($diff)%3600)/60);
	// Format the time difference as a string.
	$gap = "$hours hour(s) $minutes minutes";
  $gap = "$hours hour(s) $minutes minutes";
  echo $interval->format('Difference between two dates: %R%a days') . $gap;

}
else{
  echo '<pre>';
   print_r(DateTimeZone::listIdentifiers());
   echo '</pre>';
  
}

/*
#####################################################
	# STATIC METHOD                                     #
	#####################################################
	/**
	 * Static method to calculate the number of days between two dates.
	 *
	 * @param Pos_Date $startDate Starting date.
	 * @param Pos_Date $endDate Finishing date.
	 * @return int Number of days between start and end dates.
	 
	static public function dateDiff(Pos_Date $startDate, Pos_Date $endDate) {
		$start = gmmktime ( 0, 0, 0, $startDate->_month, $startDate->_day, $startDate->_year );
		$end = gmmktime ( 0, 0, 0, $endDate->_month, $endDate->_day, $endDate->_year );
		return ($end - $start) / (60 * 60 * 24);
	}

/*

// Assign two PHP time zone identifiers to variables.
$timezone1 = 'America/New_York';
$timezone2 = 'America/Los_Angeles';
// Create DateTimeZone objects for each time zone.
$tzObj1 = new DateTimeZone($timezone1);
$tzObj2 = new DateTimeZone($timezone2);
// Use $tzObj1 to get the current date and time in $timezone1.
// Notice that you need the date and time in only one time zone
// to work out the time difference between them.
$now = new DateTime('now', $tzObj1);

// Work out the offset from UTC in time zone 1.
$offset1 = $tzObj1->getOffset($now);
// Use the date and time in time zone 1 to get the UTC offset in the other time zone.
$offset2 = $tzObj2->getOffset($now);
// Work out the time difference by subtracting one offset from the other.
$diff = $offset1 - $offset2;
if ($diff == 0) {
	echo 'Both cities are in the same time zone';
} else {
	// Convert the time difference from seconds.
	// Time zones to the west of the prime meridian produce a negative
	// offset, so use abs() to convert negative numbers to positive.
	// Not all time zones are an exact number of hours from UTC, so
	// use floor() to get an integer. 
	$hours = floor(abs($diff)/60/60);
	// Use modulo to see if there is a remainder.
	// The remainder will still be in seconds,
	// so it needs to be divided by 60. 
	$minutes = ((abs($diff)%3600)/60);
	// Format the time difference as a string.
	$gap = "$hours hour(s) $minutes minutes";
	// Work out which timezone has a bigger offset
	$whichWay = ($offset1 > $offset2) ? 'ahead of' : 'behind';
	echo "<p>$timezone1 is $gap $whichWay $timezone2.</p>";
} 
// Now that the calculation has been performed, get the current
// date and time in the second time zone for confirmation that
// the calculation is accurate.
$otherTime = new DateTime('now', $tzObj2);
echo "<p>For confirmation, the time in $timezone1 is " . $now->format('g:i A');
echo ", and in $timezone2, it's " . $otherTime->format('g:i A') . '</p>';
*/
?>


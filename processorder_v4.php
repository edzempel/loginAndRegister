<?php
// create short variable names
$tireqty = $_POST['tireqty'];
$oilqty = $_POST['oilqty'];
$sparkqty = $_POST['sparkqty'];
$notes = $_POST['notes'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Bob's Auto Parts - Order Results</title>
</head>
<body>
<h1>Bob's Auto Parts</h1>
<h2>Order Results</h2>
<?php

echo "<p>Order processed at ";
$date = date('H:i, jS F Y');
echo "$date";
echo "</p>";

echo '<p>Your order is as follows: </p>';
echo htmlspecialchars($tireqty).' tires<br />';
echo htmlspecialchars($oilqty).' bottles of oil<br />';
echo htmlspecialchars($sparkqty).' spark plugs<br />';
echo "Customer notes: ".htmlspecialchars($notes).'<br />';
echo "How did you find Bob's: ";
$how_found;
$find = htmlspecialchars($_POST['find']);
switch ($find) {
    case "a":
        $how_found = 'regular customer' ;
        echo "$how_found";
        break;
    case "b":
        $how_found = 'TV advert';
        echo "$how_found";
        break;
    case "c":
        $how_found = 'phone directory';
        echo "$how_found";
        break;
    case "d":
        $how_found = 'word of mouth';
        echo "$how_found";
        break;
    default:
        $how_found = 'source unknown';
        echo "$how_found";
        break;
}
echo '<br/>';

$totalqty = 0;
$totalqty = $tireqty + $oilqty + $sparkqty;

if ($tireqty < 0) {
    echo "Error: tire quantity cannot be less than zero.";
} elseif ($oilqty < 0) {
    echo "Error: oil quantity cannot be less than zero.";
} elseif ($sparkqty <0) {
    echo "Error: spark quantity cannot be less than zero.";
} elseif ($totalqty <=0) {
    echo "Error: no items were entered in your order.";
}else{


    $totalamount = 0.00;

    define('TIREPRICE', 100);
    define('OILPRICE', 10);
    define('SPARKPRICE', 4);

    $totalamount = $tireqty * TIREPRICE
        + $oilqty * OILPRICE
        + $sparkqty * SPARKPRICE;
    $taxrate = 0.10;  // local sales tax is 10%
    $totalamount = $totalamount * (1 + $taxrate);

    if (writeOrder($date, $tireqty, $oilqty, $sparkqty, $totalamount, $how_found, $notes)) {
        echo '<p><h2>Order Summary</h2>';
        echo "Items ordered: " . $totalqty . "<br />";
        echo "Subtotal: $" . number_format($totalamount, 2) . "<br />";
        echo "Total including tax: $" . number_format($totalamount, 2) . "</p>";
    }

}

function writeOrder($date, $tire_qty, $oil_qty,$spark_qty, $total_cost, $how_find, $notes): bool
{
    $fp = fopen('orders.txt', 'ab');
    if ($fp === false) {
        echo 'Error recording your order. Order cancelled<br>';
        return false;
    }
    if (flock($fp, LOCK_EX) === false) {
        echo 'Unable to get file lock. Order cancelled.<br/>';
        return false;
    }
    if (fputs($fp, "$date\t$tire_qty\t$oil_qty\t$spark_qty\t$total_cost\t$how_find\t$notes".PHP_EOL) === false) {
        echo 'Unable to write to file. Order cancelled.<br/>';
        return false;
    } else {
        fflush($fp);
        flock($fp, LOCK_UN);
        fclose($fp);
        echo '<p>Order successful!</p>';
        return true;
    }
    return false;
}


?>
</body>
</html>

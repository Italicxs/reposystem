<?php
require_once '../../inc/config/constants.php';
require_once '../../inc/config/db.php';

if (isset($_POST['id'])) {
    $productID = htmlentities($_POST['id']);

    $defaultImgFolder = 'data/item_images/';

    // Define a constant for the repeated string
    define('ITEM_DETAILS_FORMAT', '<span>%s: %s</span><br>');

    // Get all item details
    $sql = 'SELECT * FROM item WHERE productID = :productID';
    $stmt = $conn->prepare($sql);
    $stmt->execute(['productID' => $productID]);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $output = '<p><img src="';

        if ($row['imageURL'] === '' || $row['imageURL'] === 'imageNotAvailable.jpg') {
            $output .= 'data/item_images/imageNotAvailable.jpg" class="img-fluid"></p>';
        } else {
            $output .= 'data/item_images/' . $row['itemNumber'] . '/' . $row['imageURL'] . '" class="img-fluid"></p>';
        }

        $output .= sprintf(ITEM_DETAILS_FORMAT, 'Name', $row['itemName']);
        $output .= sprintf(ITEM_DETAILS_FORMAT, 'Price', $row['unitPrice']);
        $output .= sprintf(ITEM_DETAILS_FORMAT, 'Discount', $row['discount'] . ' %');
        $output .= sprintf(ITEM_DETAILS_FORMAT, 'Stock', $row['stock']);
    }

    echo $output;
}

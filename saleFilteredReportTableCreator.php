<?php
	require_once'../../inc/config/constants.php';
	require_once'../../inc/config/db.php';
	
	// Definir una constante para "</td>"
	define('CLOSE_TABLE_DATA', '</td>');
	
	$uPrice = 0;
	$qty = 0;
	$totalPrice = 0;
	
	if(isset($_POST['startDate'])){
		$startDate = htmlentities($_POST['startDate']);
		$endDate = htmlentities($_POST['endDate']);
		
		$saleFilteredReportSql = 'SELECT * FROM sale WHERE saleDate BETWEEN :startDate AND :endDate';
		$saleFilteredReportStatement = $conn->prepare($saleFilteredReportSql);
		$saleFilteredReportStatement->execute(['startDate' => $startDate, 'endDate' => $endDate]);

		$output = '<table id="saleFilteredReportsTable" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
					<thead>
						<tr>
							<th>Sale ID</th>
							<th>Item Number</th>
							<th>Customer ID</th>
							<th>Customer Name</th>
							<th>Item Name</th>
							<th>Sale Date</th>
							<th>Discount %</th>
							<th>Quantity</th>
							<th>Unit Price</th>
							<th>Total Price</th>
						</tr>
					</thead>
					<tbody>';
		
		// Crear filas de tabla a partir de los datos seleccionados
		while($row = $saleFilteredReportStatement->fetch(PDO::FETCH_ASSOC)){
			$uPrice = $row['unitPrice'];
			$qty = $row['quantity'];
			$discount = $row['discount'];
			$totalPrice = $uPrice * $qty * ((100 - $discount)/100);
		
			$output .= '<tr>' .
							'<td>' . $row['saleID'] . CLOSE_TABLE_DATA .
							'<td>' . $row['itemNumber'] . CLOSE_TABLE_DATA .
							'<td>' . $row['customerID'] . CLOSE_TABLE_DATA .
							'<td>' . $row['customerName'] . CLOSE_TABLE_DATA .
							'<td>' . $row['itemName'] . CLOSE_TABLE_DATA .
							'<td>' . $row['saleDate'] . CLOSE_TABLE_DATA .
							'<td>' . $row['discount'] . CLOSE_TABLE_DATA .
							'<td>' . $row['quantity'] . CLOSE_TABLE_DATA .
							'<td>' . $row['unitPrice'] . CLOSE_TABLE_DATA .
							'<td>' . $totalPrice . CLOSE_TABLE_DATA .
						'</tr>';
		}
		
		$saleFilteredReportStatement->closeCursor();
		
		$output .= '</tbody>
						<tfoot>
							<tr>
								<th>Total</th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
							</tr>
						</tfoot>
					</table>';
		echo $output;
	}
?>

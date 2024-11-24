<?php
	require_once'../../inc/config/constants.php';
	require_once'../../inc/config/db.php';

	// Definir constante para la cadena "</td>"
	define('CLOSE_TABLE_DATA', '</td>');
	
	$itemDetailsSearchSql = 'SELECT * FROM item';
	$itemDetailsSearchStatement = $conn->prepare($itemDetailsSearchSql);
	$itemDetailsSearchStatement->execute();

	$output = '<table id="itemDetailsTable" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
				<thead>
					<tr>
						<th>Product ID</th>
						<th>Item Number</th>
						<th>Item Name</th>
						<th>Discount %</th>
						<th>Stock</th>
						<th>Unit Price</th>
						<th>Status</th>
						<th>Description</th>
					</tr>
				</thead>
				<tbody>';

	// Crear las filas de la tabla a partir de los datos seleccionados
	while($row = $itemDetailsSearchStatement->fetch(PDO::FETCH_ASSOC)) {
		$output .= '<tr>' .
						'<td>' . $row['productID'] . CLOSE_TABLE_DATA .
						'<td>' . $row['itemNumber'] . CLOSE_TABLE_DATA .
						'<td><a href="#" class="itemDetailsHover" data-toggle="popover" id="' . $row['productID'] . '">' . $row['itemName'] . '</a></td>' . CLOSE_TABLE_DATA .
						'<td>' . $row['discount'] . CLOSE_TABLE_DATA .
						'<td>' . $row['stock'] . CLOSE_TABLE_DATA .
						'<td>' . $row['unitPrice'] . CLOSE_TABLE_DATA .
						'<td>' . $row['status'] . CLOSE_TABLE_DATA .
						'<td>' . $row['description'] . CLOSE_TABLE_DATA .
					'</tr>';
	}

	$itemDetailsSearchStatement->closeCursor();

	$output .= '</tbody>
					<tfoot>
						<tr>
							<th>Product ID</th>
							<th>Item Number</th>
							<th>Item Name</th>
							<th>Discount %</th>
							<th>Stock</th>
							<th>Unit Price</th>
							<th>Status</th>
							<th>Description</th>
						</tr>
					</tfoot>
				</table>';
	echo $output;
?>

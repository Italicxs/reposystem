<?php
	require_once'../../inc/config/constants.php';
	require_once'../../inc/config/db.php';
	
	$vendorDetailsSearchSql = 'SELECT * FROM vendor';
	$vendorDetailsSearchStatement = $conn->prepare($vendorDetailsSearchSql);
	$vendorDetailsSearchStatement->execute();

	// Definir constantes para los literales repetidos
	define('TD_CLOSE', '</td>');

	$output = '<table id="vendorReportsTable" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
				<thead>
					<tr>
						<th>Vendor ID</th>
						<th>Full Name</th>
						<th>Email</th>
						<th>Mobile</th>
						<th>Phone 2</th>
						<th>Address</th>
						<th>Address 2</th>
						<th>City</th>
						<th>District</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>';
	
	// Crear filas de la tabla desde los datos seleccionados
	while($row = $vendorDetailsSearchStatement->fetch(PDO::FETCH_ASSOC)){
		$output .= '<tr>' .
						'<td>' . $row['vendorID'] . TD_CLOSE .
						'<td>' . $row['fullName'] . TD_CLOSE .
						'<td>' . $row['email'] . TD_CLOSE .
						'<td>' . $row['mobile'] . TD_CLOSE .
						'<td>' . $row['phone2'] . TD_CLOSE .
						'<td>' . $row['address'] . TD_CLOSE .
						'<td>' . $row['address2'] . TD_CLOSE .
						'<td>' . $row['city'] . TD_CLOSE .
						'<td>' . $row['district'] . TD_CLOSE .
						'<td>' . $row['status'] . TD_CLOSE .
					'</tr>';
	}
	
	$vendorDetailsSearchStatement->closeCursor();
	
	$output .= '</tbody>
					<tfoot>
						<tr>
							<th>Vendor ID</th>
							<th>Full Name</th>
							<th>Email</th>
							<th>Mobile</th>
							<th>Phone 2</th>
							<th>Address</th>
							<th>Address 2</th>
							<th>City</th>
							<th>District</th>
							<th>Status</th>
						</tr>
					</tfoot>
				</table>';
	echo $output;
?>

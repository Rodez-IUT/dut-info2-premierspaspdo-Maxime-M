<!doctype html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>PHP BD</title>
		<!-- Bootstrap CSS -->
		<link href="bootstrap/css/bootstrap.css" rel="stylesheet">
		<link href="font-awesome/css/font-awesome.css" rel="stylesheet">
		<!-- CSS -->
		<link href="css/monStyle.css" rel="stylesheet">
		
		<?php
			/* link a la BD */
			$host = 'localhost';
			$db   = 'my_activities';
			$user = 'root';
			$pass = 'root';
			$charset = 'utf8mb4';
			$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
			$options = [
				PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
				PDO::ATTR_EMULATE_PREPARES   => false,
			];
			try {
				 $pdo = new PDO($dsn, $user, $pass, $options);
			} catch (PDOException $e) {
				 throw new PDOException($e->getMessage(), (int)$e->getCode());
			}
		?>
	</head>
	
	<body>
		<!--  -->
		<div class="container">
			<div class="row">
				<!-- entete -->
				<div class="col-xs-12 cadre">
					<h1>ALL User</h1>
					<table>
						<tr>
							<th>ID</th>
							<th>Username</th>
							<th>Email</th>
							<th>Status</th>
						</tr>
						<?php
							$stmt = $pdo->query('SELECT id, username, email, status_id FROM users');
							while ($row = $stmt->fetch()) {
								$stmt2 = $pdo->query('SELECT name FROM status WHERE id = '.$row['status_id']);
								$tmp = $stmt2->fetch();
								
								echo "<tr>";
									echo "<td>". $row['id'] . "</td>";
									echo "<td>". $row['username'] . "</td>";
									echo "<td>". $row['email'] . "</td>";
									echo "<td>". $tmp['name'] . "</td>";
								echo "</tr>";
							}
						?>
					</table>
				</div>
			</div>
		</div>
	</body>
</html>
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
					
					<!-- formulaire -->
					<form action="index.php" method="post">
						<!-- premiere lettre -->
						<label>Premiere lettre : </label>
						<input type="textbox" name="lettre"/>
						
						<!-- menu deroulant -->
						<label>, status : </label>
						<select name="status" >
							<option value="2">Active account</option>
							<option value="1">Waiting for account validation</option>
						</select>
						
						<!-- btn submit -->
						<input type="submit" value="submit">
					</form>
					
					<!-- table resultat -->
					<table class="table table-bordered table-striped">
						<tr>
							<th>ID</th>
							<th>Username</th>
							<th>Email</th>
							<th>Status</th>
						</tr>
						<?php
							/* afficher user filtrer */
							$status_id = 0;
							$lettreDebut = '0';
							
							if (!empty($_POST['lettre']) && isset($_POST['status'])) {
								
							}
							
							$stmt = $pdo->query('SELECT users.id AS id, username, email, name 
							                     FROM users 
												 JOIN status 
												 ON users.status_id = status.id 
												 WHERE status.id = '. $status_id .'
												 AND username LIKE \''. $lettreDebut . '%' .'\'
												');
												 
							while ($row = $stmt->fetch()) {
								echo "<tr>";
									echo "<td>". $row['id'] . "</td>";
									echo "<td>". $row['username'] . "</td>";
									echo "<td>". $row['email'] . "</td>";
									echo "<td>". $row['name'] . "</td>";
								echo "</tr>";
							}
						?>
					</table>
				</div>
			</div>
		</div>
	</body>
</html>
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
					<form action="index.php" method="GET">
						<!-- premiere lettre -->
						<label>Premiere lettre : </label>
						<input type="textbox" name="lettre" value="<?php if(isset($_GET['lettre'])) {echo $_GET['lettre'];} ?>"/>
						
						<!-- menu deroulant -->
						<label>, status : </label>
						<select name="status" >
							<option value="1">Waiting for account validation</option>
							<option value="2">Active account</option>
							<option value="3">Waiting for account deletion</option>
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
							<th colspan="2">Status</th>
						</tr>
						
						<?php
							/* si un ask deletion est demandé */
							if (isset($_GET['status']) && isset($_GET['user_id']) && isset($_GET['action'])) {
								$stmt = $pdo->prepare("INSERT INTO action_log (action_date,action_name,user_id) 
								                       VALUES (NOW(),:action,:id);");
								$stmt->execute(['action' => $_GET['action'], 
								                'id' => $_GET['user_id']]);
												
								$stmt = $pdo->prepare("UPDATE users SET status_id = :status WHERE users.id = :id;");
								$stmt->execute(['status' => $_GET['status'],
								                'id' => $_GET['user_id']]);
							}
						
							/* si un filtre est mis */
							if (isset($_GET['lettre']) && isset($_GET['status'])) {
								$lettreDebut = $_GET['lettre'];
								$status_id = $_GET['status'];
								
								$stmt = $pdo->prepare("SELECT users.id AS id, username, email, name, status.id AS idStatus
													   FROM users 
												       JOIN status 
												       ON users.status_id = status.id
													   WHERE status.id = :status_id
												       AND username LIKE :lettreDebut
												     ;");
								
								$stmt->execute(['status_id' => $status_id, 
								                'lettreDebut' => $lettreDebut.'%']);
													 
							} else { // sinon on affiche tout
								$stmt = $pdo->query("SELECT users.id AS id, username, email, name, status.id AS idStatus
							                         FROM users 
												     JOIN status 
												     ON users.status_id = status.id 
												   ;");					  
							}
								
							/* affichage */
							while ($row = $stmt->fetch()) {
								echo "<tr>";
									echo "<td>". $row['id'] . "</td>";
									echo "<td>". $row['username'] . "</td>";
									echo "<td>". $row['email'] . "</td>";
									echo "<td>". $row['name'] . "</td>";
									echo "<td>";
									if ($row['idStatus'] != 3) {
										echo "<a href=\"index.php?status=3&user_id=".$row['id']."&action=askDeletion\">Ask deletion</a>";
									}
									echo "</td>";
								echo "</tr>";
							}
						?>
					</table>
				</div>
			</div>
		</div>
	</body>
</html>
\<?php
	$host = 'localhost';
	$username = 'antonel';
	$password = '1234underworld1234';
	$database = 'ids';

	$conn = new mysqli($host, $username, $password, $database);

	$data1 = file_get_contents('alert');
	$lines1 = explode("\n", $data1);

	foreach ($lines1 as $line) 
	{
		if (!empty($line)) 
		{
			$columns = explode(" ", $line);

			$timestamp = $columns[0];
			$id_rules = trim($columns[3], '[]');
			$message = $columns[4];
			$classification = trim($columns[7], ']');
			$severity = trim($columns[9], ']');
			$protocol = trim($columns[10], '{}');
			$src = explode(':', $columns[11]);
			$ip_src = $src[0];
			$port_src = $src[1];
			$dest = explode(':', $columns[13]);
			$ip_dest = $dest[0];
			$port_dest = $dest[1];

			$checkQuery = "SELECT * FROM snort WHERE timestamp='$timestamp'";
			$checkResult = $conn->query($checkQuery);

			if ($checkResult->num_rows == 0) 
			{
				$query = "INSERT INTO snort (timestamp, id_rules, message, tool, classification, severity, protocol, ip_src, port_src, ip_dest, port_dest, status, status2) 
								VALUES ('$timestamp', '$id_rules', '$message', 'Snort', '$classification', '$severity', '$protocol', '$ip_src', '$port_src', '$ip_dest', '$port_dest', 'Terdeteksi', '-')";

				if($conn->query($query) === TRUE) 
				{
					$snortQuery = "SELECT * FROM suricata WHERE timestamp='$timestamp'";
					$snortResult = $conn->query($snortQuery);
				} 
				else 
				{

				}
			} 
			else 
			{

			}
		}
	}

	$data2 = file_get_contents('fast.log');
	$lines2 = explode("\n", $data2);

	foreach ($lines2 as $line) 
	{
		if (!empty($line)) 
		{
			$columns = explode(" ", $line);

			$timestamp = $columns[0];
			$id_rules = trim($columns[3], '[]');
			$message = $columns[4];
			$classification = trim($columns[7], ']');
			$severity = trim($columns[9], ']');
			$protocol = trim($columns[10], '{}');
			$src = explode(':', $columns[11]);
			$ip_src = $src[0];
			$port_src = $src[1];
			$dest = explode(':', $columns[13]);
			$ip_dest = $dest[0];
			$port_dest = $dest[1];

			$checkQuery = "SELECT * FROM suricata WHERE timestamp='$timestamp'";
			$checkResult = $conn->query($checkQuery);

			if($checkResult->num_rows == 0)
			{
				$query = "INSERT INTO suricata (timestamp, id_rules, message, tool, classification, severity, protocol, ip_src, port_src, ip_dest, port_dest, status, status2) 
								VALUES ('$timestamp', '$id_rules', '$message', 'Suricata', '$classification', '$severity', '$protocol', '$ip_src', '$port_src', '$ip_dest', '$port_dest', '-', 'Terdeteksi')";

				if($conn->query($query) === TRUE)
				{
					$snortQuery = "SELECT * FROM snort WHERE timestamp='$timestamp'";
					$snortResult = $conn->query($snortQuery);

					if($snortResult->num_rows > 0)
					{
						$status2 = "Terdeteksi";

						$updateQuery = "UPDATE snort SET status2='$status2' WHERE timestamp='$timestamp'";
						$conn->query($updateQuery);
					} 
					else 
					{
						$status2 = "-";
					}
					$updateQuery = "UPDATE suricata SET status='$status2' WHERE timestamp='$timestamp'";
					$conn->query($updateQuery);
				}
				else
				{

				}
			}
			else
			{

			}
		}
	}

	$snortQuery = "SELECT * FROM snort";
	$snortResult = $conn->query($snortQuery);

	if ($snortResult->num_rows > 0)
	{
		while ($row = $snortResult->fetch_assoc())
		{
			$timestamp = $row['timestamp'];
			$id_rules = $row['id_rules'];
			$message = $row['message'];
			$tool = $row['tool'];
			$classification = $row['classification'];
			$severity = $row['severity'];
			$protocol = $row['protocol'];
			$ip_src = $row['ip_src'];
			$port_src = $row['port_src'];
			$ip_dest = $row['ip_dest'];
			$port_dest = $row['port_dest'];
			$status = $row['status'];
			$status2 = $row['status2'];

			$checkQuery = "SELECT * FROM monitor WHERE timestamp='$timestamp' AND tool='$tool'";
			$checkResult = $conn->query($checkQuery);

			if($checkResult->num_rows == 0)
			{
				$insertQuery = "INSERT INTO monitor (timestamp, id_rules, message, tool, classification, severity, protocol, ip_src, port_src, ip_dest, port_dest, status, status2)
								VALUES ('$timestamp', '$id_rules', '$message', '$tool', '$classification', '$severity', '$protocol', '$ip_src', '$port_src', '$ip_dest', '$port_dest', '$status', '$status2')";
				$conn->query($insertQuery);
			}
			else
			{
	
			}
		}
	}

	$suricataQuery = "SELECT * FROM suricata";
	$suricataResult = $conn->query($suricataQuery);

	if ($suricataResult->num_rows > 0)
	{
		while ($row = $suricataResult->fetch_assoc())
		{
			$timestamp = $row['timestamp'];
			$id_rules = $row['id_rules'];
			$message = $row['message'];
			$tool = $row['tool'];
			$classification = $row['classification'];
			$severity = $row['severity'];
			$protocol = $row['protocol'];
			$ip_src = $row['ip_src'];
			$port_src = $row['port_src'];
			$ip_dest = $row['ip_dest'];
			$port_dest = $row['port_dest'];
			$status = $row['status'];
			$status2 = $row['status2'];

			$checkQuery = "SELECT * FROM monitor WHERE timestamp='$timestamp' AND tool='$tool'";
			$checkResult = $conn->query($checkQuery);

			if($checkResult->num_rows == 0)
			{
				$insertQuery = "INSERT INTO monitor (timestamp, id_rules, message, tool, classification, severity, protocol, ip_src, port_src, ip_dest, port_dest, status, status2)
								VALUES ('$timestamp', '$id_rules', '$message', '$tool', '$classification', '$severity', '$protocol', '$ip_src', '$port_src', '$ip_dest', '$port_dest', '$status', '$status2')";
				$conn->query($insertQuery);
			}
			else
			{
	
			}
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@700&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="style.css">
	<script type="text/javascript" src="script.js"></script>  
    <title></title>
	<style>
		*{
			margin: 0px;
			padding: 0px;
			font-family: 'Open Sans', sans-serif;
			text-decoration: none;
		}

		header{
			text-align: center;
			font-size: 30px;
			font-weight: bold;
			margin: 30px
		}

		.navbar{
			display: flex;
			justify-content: center;
			background-color: #36304A;
		}

		.navbar > a{
			color: white;
			padding: 25px;
			font-size: 15px;
			font-weight: bold;
			transition: 1s;
		}

		.navbar > a:hover{
			background-color: white;
			color: black;
		}

		.monitor{
			display: flex;
			justify-content: center;
			margin: 50px;
		}

		table{
			overflow: auto;
			max-height: 370px;
		}

		table, th, td{
			border-collapse: collapse;
			font-size: 13px;
			height: 60px;
		}

		th, td{
			width: 180px;
			text-align: center;
		}

		th{
			background-color: #36304A;
			color: white;
			padding: 5px;
			letter-spacing: 1px;
		}

		tr:last-child td:last-child{
			border-bottom-right-radius: 15px;
		}

		tr:last-child td:first-child{
			border-bottom-left-radius: 15px;
		}

		th:first-child{
			border-top-left-radius: 15px;
		}

		th:last-child{
			border-top-right-radius: 15px;
		}

		tr:nth-child(odd){
			background-color: #F5F5F5;
		}

		.par-add{
			display: flex;
			justify-content: space-between;
			margin-left: 65px;
			margin-right: 65px;
			margin-bottom: 50px;
		}

		.additional, .additional2{
			margin-top: 50px;
			background-color: #9a97a4;
			padding: 15px;
			width: 210px;
			color: white;
			font-weight: 600;
			font-size: 13px;
			letter-spacing: 1px;
			border-radius: 4px;
			word-spacing: 3px;
			border-left: 3px solid #36304A;
		}

		.additional2{
			display: flex;
			align-items: center;
		}

		.anima{
			display: flex;
			justify-content: space-between;
			align-items: center;
			overflow: hidden;
			width: 93px;
			transition: 1s;
		}

		.anima > a, .anima > p{
			color: white;
			padding: 25px;
			font-size: 15px;
			font-weight: bold;
			transition: 1s;
		}

		.anima:hover{
			width: 293px;
			transition: 1s;
		}

		.anima > p:hover, .anima > a:hover{
			background-color: white;
			color: black;
			transition: 1s;
		}

		.titleTable{
			text-align: center;
			font-size: 30px;
			font-weight: bold;
			margin: 50px;
		}
	</style>
</head>
<body>
    <header>
        <p>Monitoring Snort & Suricata</p>
    </header>

    <main>
        <div class="navbar">
            <a href="http://localhost/ids/home.php">Home</a>
            <a href="http://localhost/ids/snort_alert.php">Monitor</a>
			<div class="anima">
				<p>Rules</p>
				<a href="http://localhost/ids/rules_snort.php">Snort</a>
				<a href="http://localhost/snort/rules_suricata.php">Suricata</a>
			</div>
        </div>
		
		<div class="par-add">
			<div class="additional">
				<?php
					date_default_timezone_set('Asia/Jakarta');
					
					$zona_waktu = date_default_timezone_get();
					$tanggal = date('d');
					$bulan = date('m');
					$tahun = date('Y');
					
					echo "<p>" . $zona_waktu . " </p>";
					echo "<p>" . $tahun . " - " . $bulan . " - " . $tanggal . "</p>";
				?>
				<p id="jam"></p>
			</div>
			
			<div class="additional2">
				<?php
					$query = "SELECT COUNT(*) AS total_rows FROM snort";
					$result = mysqli_query($conn, $query);
					
					if($result)
					{
						$rows = mysqli_fetch_assoc($result);
						$total_rows = $rows['total_rows'];
					
						echo "<p>". "Jumlah Alerts Snort : " . $total_rows . "</p>";
					}
				?>
			</div>
			
			<div class="additional2">
				<?php
					$query = "SELECT COUNT(*) AS total_rows FROM suricata";
					$result = mysqli_query($conn, $query);
					
					if($result)
					{
						$rows = mysqli_fetch_assoc($result);
						$total_rows = $rows['total_rows'];
					
						echo "<p>". "Jumlah Alerts Suricata : " . $total_rows . "</p>";
					}
				?>
			</div>

			<div class="additional2">
				<?php
					$query = "SELECT COUNT(*) AS total_rows FROM monitor";
					$result = mysqli_query($conn, $query);
					
					if($result)
					{
						$rows = mysqli_fetch_assoc($result);
						$total_rows = $rows['total_rows'];
					
						echo "<p>". "Jumlah Semua Alerts : " . $total_rows . "</p>";
					}
				?>
			</div>

		</div>
        
		<div class="monitor" style="overflow: auto; max-height: 1000px;">
			
			<?php
				$query = "SELECT * FROM monitor";
				
				$result = $conn->query($query);
				
				if ($result->num_rows > 0)
				{
					echo "<table id='iniTable'>
							<tr>
								<th>Timestamp</th>
								<th>Message</th>
								<th>Tool</th>
								<th>Classification</th>
								<th>Severity</th>
								<th>Source IP</th>
								<th>Source Port</th>
								<th>Destination IP</th>
								<th>Destination Port</th>
								<th>Snort</th>
								<th>Suricata</th>
							</tr>";
					
					while($row = $result->fetch_assoc())
					{
						echo "<tr>
								<td>" . $row['timestamp'] . "</td>
								<td>" . $row['message'] . "</td>
								<td>" . $row['tool'] . "</td>
								<td>" . $row['classification'] . "</td>
								<td>" . $row['severity'] . "</td>
								<td>" . $row['ip_src'] . "</td>
								<td>" . $row['port_src'] . "</td>
								<td>" . $row['ip_dest'] . "</td>
								<td>" . $row['port_dest'] . "</td>
								<td>" . $row['status'] . "</td>
								<td>" . $row['status2'] . "</td>
							</tr>";
					}
					echo "</table>";           
				}
			?>
        </div>
    </main>

	<script>
        let table1 = document.getElementById("iniTable");

		applyColorToTable(table1);

		function applyColorToTable(table)
		{
			let rows = table.getElementsByTagName("tr");

			for (let i = 0; i < rows.length; i++)
			{
				let cols = rows[i].getElementsByTagName("td");

				for (let j = 0; j < cols.length; j++)
				{
					if (cols[j].innerText === '0')
					{
						cols[j].style.backgroundColor = "#caf0f8";
					}
					else if (cols[j].innerText === '1')
					{
						cols[j].style.backgroundColor = "#C7E8CA";
					}
					else if (cols[j].innerText === '2')
					{
						cols[j].style.backgroundColor = "#F3E99F";
					}
					else if (cols[j].innerText === '3')
					{
						cols[j].style.backgroundColor = "#ffbaba";
					}
				}
			}
		}
    </script>

	<script>
		window.onload = function() { jam(); }
				
		function jam()
		{
			var e = document.getElementById('jam'),
			d = new Date(), h, m, s;
			h = d.getHours();
			m = set(d.getMinutes());
			s = set(d.getSeconds());
			
			e.innerHTML = h +' : '+ m +' : '+ s;
				
			setTimeout('jam()', 1000);
		}
		
		function set(e)
		{
			e = e < 10 ? '0'+ e : e;
			return e;
		}
	</script>
</body>
</html>

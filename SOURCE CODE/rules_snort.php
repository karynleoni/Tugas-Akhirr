<?php
	$host = 'localhost';
	$username = 'antonel';
	$password = '1234underworld1234';
	$database = 'ids';
	
	$conn = new mysqli($host, $username, $password, $database);
	
	$data = file_get_contents('/etc/snort/rules/local.rules');
	
	$lines = explode("\n", $data);
	
	foreach($lines as $line)
	{
		if(!empty($line))
		{				
			if($line[0] !== '#')
			{
				$columns = explode(" ", $line);

				$action = $columns[0];
				$protocol = $columns[1];
				$ip_src = $columns[2];
				$port_src = $columns[3];
				$direction = $columns[4];
				$ip_dest = $columns[5];
				$port_dest = $columns[6];

				$msg = explode('"', $columns[7]);
				$message = trim($msg[1], ';');
				
				$ctp = explode(':', $columns[8]);
				$classtype = trim($ctp[1], ';');

				$ids = explode(':', $columns[9]);
				$ids2 = explode(';', $ids[1]);
				$id_rules = $ids2[0];

				$checkQuery = "SELECT idrules FROM snortrules WHERE idrules = '$id_rules'";
				$result = $conn->query($checkQuery);

				if($result->num_rows == 0)
				{
					$query = "INSERT INTO snortrules (action, protocol, ip_src, port_src, direction, ip_dest, port_dest, message, classtype, idrules) 
					VALUES ('$action', '$protocol', '$ip_src', '$port_src', '$direction', '$ip_dest', '$port_dest', '$message', '$classtype', '$id_rules')";
		
					if($conn->query($query) === TRUE)
					{

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
	}
	if(isset($_POST['delete']))
	{
        $deleteQuery = "DELETE FROM snortrules";
        if($conn->query($deleteQuery) === TRUE)
		{

        }
		else
		{

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
    <title>Rules | Snort</title>
</head>
<body>
    <header>
        <p>Rules Snort</p>
    </header>

    <main>
        <div class="navbar">
            <a href="http://localhost/ids/home.php">Home</a>
            <a href="http://localhost/ids/snort_alert.php">Snort</a>
            <a href="http://localhost/ids/suricata_alert.php">Suricata</a>
			<div class="anima">
				<p>Rules</p>
				<a href="http://localhost/ids/rules_snort.php">Snort</a>
				<a href="http://localhost/ids/rules_suricata.php">Suricata</a>
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
					$query = "SELECT COUNT(*) AS total_rows FROM snortrules";
					$result = mysqli_query($conn, $query);
					
					if($result)
					{
						$rows = mysqli_fetch_assoc($result);
						$total_rows = $rows['total_rows'];
					
						echo "<p>". "Jumlah Rules : " . $total_rows . "</p>";
					}
				?>
			</div>
			
			<div class="additional3">
                <form method="post">
                    <input type="submit" name="delete" value="Hapus Semua Data">
                </form>
            </div>
		</div>
		
        <div class="monitor">
			<?php
				$query = "SELECT * FROM snortrules";
				
				$result = $conn->query($query);
				
				if ($result->num_rows > 0)
				{
					echo "<table id='iniTable'>
							<tr>
								<th>Action</th>
								<th>Protocol</th>
								<th>Source IP</th>
								<th>Sorce Port</th>
								<th>Direction</th>
								<th>Destination IP</th>
								<th>Destination Port</th>
								<th>Message</th>
								<th>Classtype</th>
								<th>ID Rules</th>
							</tr>";
					
					while($row = $result->fetch_assoc())
					{
						echo "<tr>
								<td>" . $row['action'] . "</td>
								<td>" . $row['protocol'] . "</td>
								<td>" . $row['ip_src'] . "</td>
								<td>" . $row['port_src'] . "</td>
								<td>" . $row['direction'] . "</td>
								<td>" . $row['ip_dest'] . "</td>
								<td>" . $row['port_dest'] . "</td>
								<td>" . $row['message'] . "</td>
								<td>" . $row['classtype'] . "</td>
								<td>" . $row['idrules'] . "</td>
							</tr>";
					}
					echo "</table>";           
				}
				$conn->close();
			?>
        </div>
    </main>

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

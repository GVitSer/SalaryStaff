<!DOCTYPE html>
<html>
<head>
<title>Список всех работ в выбранный день</title>
<meta charset="utf-8" />
</head>
<body>
<h2>Список всех работ, проделанных Чистых Еленой в выбранный день</h2>
<?php
$conn = new PDO("mysql:host=localhost;dbname=hoteliq2", "root", "");
if($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["daym"]))
{
    $dnm = $_GET["daym"];
    $sql2 = "SELECT DAYOFMONTH(statistics.created) AS dmcr, rooms.num AS `nmr`, rooms.build AS `kps`, rooms.type AS `knm`, works.name AS `tub`, 
statistics.start AS `nub`, statistics.end AS `kub`, statistics.bed, statistics.towels, prices.price,
IF(works.id=3, 
   IF(statistics.bed=1, 
      (IF(statistics.towels=1, prices.price+10, prices.price)+30), 
      (IF(statistics.towels=1, prices.price+10, prices.price))), 
   prices.price) AS `sub` 
FROM `statistics` LEFT OUTER JOIN `rooms` ON statistics.room=rooms.id
	INNER JOIN `prices` ON rooms.type=prices.room_type AND statistics.work=prices.work
    INNER JOIN `works` ON works.id=statistics.work
WHERE DAYOFMONTH(statistics.created) = :dnm";
    $result2 = $conn->prepare($sql2);
    $result2->bindValue(":dnm", $dnm);
    $result2->execute();
	$sum=0;
    if($result2->rowCount() > 0){
	echo "<table><tr>
	<th>Номер</th><td>      </td>
	<th>Корпус</th><td>   </td>
	<th>Категория номера</th><td>   </td>
	<th>Тип уборки</th><td>   </td>
	<th>Начало уборки</th><td>   </td>
	<th>Конец уборки</th><td>   </td>
	<th>Сумма за уборку</th></tr>";
        foreach ($result2 as $row) {
            echo "<tr>";
            echo "<td>" . $row["nmr"] . "</td>";
			echo "<td>   </td>";
			echo "<td>" . $row["kps"] . "</td>";
			echo "<td>   </td>";
            echo "<td>" . $row["knm"] . "</td>";
			echo "<td>   </td>";
            echo "<td>" . $row["tub"] . "</td>";
			echo "<td>   </td>";
			echo "<td>" . $row["nub"] . "</td>";
			echo "<td>   </td>";
			echo "<td>" . $row["kub"] . "</td>";
			echo "<td>   </td>";
			echo "<td>" . $row["sub"] . "</td>";
			echo "<td>   </td>";
        echo "</tr>";
		$sum += $row["sub"];
        }
    echo "</table>";
	echo "<p>Итоговая сумма за день: $sum</p>";
    }
    else{
        echo "Данных не найдено";
    }
}
?>
</body>
</html>
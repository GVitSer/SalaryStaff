<!DOCTYPE html>
<html>
<head>
<title>Тестовое задание HoteliQ</title>
<meta charset="utf-8" />
</head>
<body>
<h2>Расчёт зарплаты горничной</h2>
<form method="POST">
    <input type="submit" name="reportMonth" value="Отчёт по всем работам, произведенным горничной Чистых Еленой за сентябрь" />
</form>
<?php
$conn = new mysqli("localhost", "root", "", "hoteliq2");
if( isset( $_POST['reportMonth'] ) )
    {
if($conn->connect_error){
    die("Ошибка: " . $conn->connect_error);
}
$sql1 = "SELECT DAYOFMONTH(query11.created) AS daym, query11.created AS `Дата`, `start` AS `Начало рабочего дня`, `end` AS `Конец рабочего дня`, 
	q2gen AS `Кол-во генеральных уборок`,
    q3tek AS `Кол-во текущих уборок`,
    q4zzd AS `Кол-во заездов`,
    sumoplzub+q3sb*30+q3tw*10 AS `Сумма оплаты за день`
    FROM 
    (SELECT `created`, `start`, `end`, query2.gen AS q2gen, query3.tek AS q3tek, query3.sb AS q3sb, query3.tw AS q3tw, query4.zzd AS q4zzd, query1.dm AS q1dm
    	FROM (SELECT created, DAYOFMONTH(created) AS dm, `start`, `end` FROM `statistics` WHERE `work`=0 GROUP BY DAYOFMONTH(created))query1
    	LEFT OUTER JOIN
    	(SELECT DAYOFMONTH(created) AS dm, COUNT(`work`) AS gen FROM `statistics` WHERE `work`=2 GROUP BY DAYOFMONTH(created))query2 ON query1.dm=query2.dm
    	LEFT OUTER JOIN
    	(SELECT DAYOFMONTH(created) AS dm, COUNT(`work`) AS tek, SUM(bed) AS sb, SUM(towels) AS tw FROM `statistics` WHERE `work`=3 GROUP BY DAYOFMONTH(created))query3 ON query2.dm=query3.dm
    	LEFT OUTER JOIN
    	(SELECT DAYOFMONTH(created) AS dm, COUNT(`work`) AS zzd FROM `statistics` WHERE `work`=1 GROUP BY DAYOFMONTH(created))query4 ON query3.dm=query4.dm)
    query11
    
    INNER JOIN
    
    (SELECT   
	SUM(prices.price) AS sumoplzub,
    DAYOFMONTH(statistics.created) AS dm2
	FROM `statistics` INNER JOIN `rooms` ON statistics.room=rooms.id
	INNER JOIN `prices` ON rooms.type=prices.room_type AND statistics.work=prices.work
	GROUP BY statistics.created
	ORDER BY statistics.created)
    query22 
    ON query11.q1dm=query22.dm2;";
	$sum=0;
if($result1 = $conn->query($sql1)){
    echo "<p></p>";
    echo "<table><tr><th>Дата</th><td>      </td>
	<th>Начало рабочего дня</th><td>   </td>
	<th>Конец рабочего дня</th><td>   </td>
	<th>Кол-во генеральных уборок</th><td>   </td>
	<th>Кол-во текущих уборок</th><td>   </td>
	<th>Кол-во заездов</th><td>   </td>
	<th>Сумма оплаты за день</th></tr>";
    foreach($result1 as $row){
        echo "<tr>";
            echo "<td><a href='day.php?daym=" . $row["daym"] . "'>" . $row["Дата"] . "</a></td>";
			echo "<td>   </td>";
            echo "<td>" . $row["Начало рабочего дня"] . "</td>";
			echo "<td>   </td>";
            echo "<td>" . $row["Конец рабочего дня"] . "</td>";
			echo "<td>   </td>";
			echo "<td>" . $row["Кол-во генеральных уборок"] . "</td>";
			echo "<td>   </td>";
			echo "<td>" . $row["Кол-во текущих уборок"] . "</td>";
			echo "<td>   </td>";
			echo "<td>" . $row["Кол-во заездов"] . "</td>";
			echo "<td>   </td>";
			echo "<td>" . $row["Сумма оплаты за день"] . "</td>";
			echo "<td>   </td>";
        echo "</tr>";
		$sum += $row["Сумма оплаты за день"];
    }
    echo "</table>";
	echo "<p>итоговая сумма за сентябрь: $sum</p>";
    $result1->free();
} else{
    echo "Ошибка: " . $conn->error;
}
}
$conn->close();
?>
</body>
</html>
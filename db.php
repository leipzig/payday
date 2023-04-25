<?PHP
define ("DBNAME", "payday");
define ("HOSTNAME", "mysql.ece.ncsu.edu");
define ("USERNAME", "jnleipzi");
define ("PASSWORD", "yxpqha8E");

class db
{
	function query($sql)
	{
		$db=DBNAME;	
		$mysql_link=MYSQL_CONNECT(HOSTNAME,USERNAME,PASSWORD);
		mysql_select_db($db, $mysql_link);
		$result = mysql_query($sql);
		mysql_close($mysql_link);
		return $result;
	}
}
?>
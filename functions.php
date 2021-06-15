<?php
include('connection.php');
$con = getdb();


   if(isset($_POST["Import"])){		
		 $filename=$_FILES["file"]["tmp_name"];	
		
		 $q='(';
		 if($_FILES["file"]["size"] > 0)
		 {
		  	$file = fopen($filename, "r");
			$i=0;
			$colcount=0;
			$pass=0;
	        while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
	         {
				if($i==0)
				{	
					   $colcount = count($getData);
					   $j=0;
					   $sql = "CREATE TABLE ".$_POST["dbname"]." (";
						while($j!=$colcount)
						{
							$q.=$getData[$j].",";
							
							if("password"==$getData[$j])
							{
								$sql.= $getData[$j]." VARCHAR(100) ,";
								$pass=$j;
							}
							else{
								$sql.= $getData[$j]." VARCHAR(50) ,";
							}
							$j++;
						}
						$q=substr($q, 0, -1);
						$q.=')';
						$sql=substr($sql, 0, -1);
						$sql.= " )";
						// echo $sql;
						$i++;
						$result = mysqli_query($con, $sql);
						if(!$result)
						{
							
							echo "<script type=\"text/javascript\">
							alert(\"Db name already exists.\");
							window.location = \"index.php\"
						  </script>";	
						  exit();
						

						}
						

				}else
				{
					if($colcount==count($getData))
					{
						
						$sql = "INSERT into  ".$_POST["dbname"]." ".$q. " values (";
						$j=0;
						while($j!=$colcount)
						{
							if($pass==$j)
							{
								$sql.= "'".hash('sha256',$getData[$j])."'"." ,";
								$j++;
							}
							else{
								$sql.= "'$getData[$j]'"." ,";
								$j++;
							}
							
						}

						$sql=substr($sql, 0, -1);
						$sql.= " )";
						// echo $sql;
						$result = mysqli_query($con, $sql);
						
					}
					$i++;
					
					
				}
	          
	         }
			 echo "<script type=\"text/javascript\">
					 alert(\"CSV File has been successfully Imported.\");
			 		 window.location = \"index.php\"
			 	 </script>";
			
	         fclose($file);	
		 }
		 else{
			echo "<script type=\"text/javascript\">
						 alert(\"Invalid File:Please Upload CSV File.\");
						 window.location = \"index.php\"
					   </script>";	
		 }
	}	 
	
 
?>
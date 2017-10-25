<?php


	class DB{

		private $_hostname = "localhost";
		private $_hostuser = "root";
		private $_hostpass = "";
		private $_hostdb = "laravel_4";

		public $conn;

		public function __construct(){

			$this->connection();

		}



		public function connection(){

			$this->conn = new mysqli($this->_hostname,$this->_hostuser,$this->_hostpass,$this->_hostdb);
			return $this->conn?true:false;

			/*if($this->conn){
				return $this->conn;
			}
			else
			{
				return false;

			}*/

		}
		public function in_marksheet($t,$r)
		{
			$sql = "Insert into marksh(mark,remark) values('$t',$r)";
			var_dump($sql);
			$result=$this->conn->query($sql);
			// var_dump($result);
		}
		
		public function out_marksheet()
		{
			$sql = "Select * from marksh order by id desc limit 1";
			$result = $this->conn->query($sql);
			if ($result->num_rows > 0) {
			    // output data of each row
			    while($row = $result->fetch_assoc()) {


			        return  $row['mark'];

			    }
			}
			 else {
			    return "No records were found!! Please enter roll number again";
			}
		}
		

		public function marksheet($roll,$sem)
		{
			var_dump($roll);

			$sql = "SHOW COLUMNS FROM `$sem`";
			$result = $this->conn->query($sql);
			$fields[]=null;
			$res[]=null;
		
			if ($result->num_rows > 0)
			{
				while($row =$result->fetch_array())
				{
					 $fields[] = $row['0'];  
				    }

					
			}

			
			

			$sql = "Select * from `$sem` where roll_no=$roll";
			$result = $this->conn->query($sql);

			if ($result->num_rows > 0) {
			    // output data of each row
			    while($row = $result->fetch_array()) {
			    	

			      return "Your marks are as follows ::           ".
			      $fields[4]."=".$row[3]."                                       ".
			      $fields[5]."=".$row[4]."                                              ".
			      $fields[6]."=".$row[5]."                                       ".
			      $fields[7]."=".$row[6]."                                       ".
			      $fields[8]."=".$row[7]."                                                                     ".
			      $fields[9]."=".$row[8]."                                       ".
			      $fields[10]."=".$row[9];
			  }

			    
			}
			 else {
			    return "No records were found!! Please enter roll number again";
			}

			// return $roll;
		}

	public function result($roll,$sem)
		{
			
			$sql = "Select result from `$sem` where roll_no=$roll ";
			
			$result = $this->conn->query($sql);
			// var_dump($sql);
			if ($result->num_rows > 0) {
			    // output data of each row

			    while($row = $result->fetch_assoc()) {
			    	if($row['result']=="p")
					{
						$reply="Congrats You have passed!! 🎈🎉🎉🎈";
					}
					else
					{
						$reply="Sorry you have failed . Better luck next time 👍 ";
					}
			        return  $reply;

			    }

			}
			 else {
			    return "No records were found!!  :( Please enter roll number again";
			}
			
			 
		}

		public function marks($r,$s,$sub)
		{
			$sql = "Select * from `$s` where roll_no=$r";
			$result = $this->conn->query($sql);
			var_dump($result);
			if ($result->num_rows > 0) {
			    // output data of each row
			    while($row = $result->fetch_array()) 
			    {
			    	if(!$row[$sub])
			    	{
						$reply= "Marks of ".$sub." not found!!";


			    	}
			    	else
					{
					    $reply= "Your marks of ".$sub." is ".$row[$sub];
					}
			    }
			 }
			
			return $reply;

		}
		public function sem($s)
		{
			$sql = "Insert into sem(sem) values('$s')";
			// var_dump($sql);
			$result=$this->conn->query($sql);
			// var_dump($result);
		}


		public function roll($roll)
		{
			$sql = "Insert into roll(roll) values($roll)";
			// var_dump($roll);
			$result=$this->conn->query($sql);
			// var_dump($result);

			    
		}

		public function getroll()
		{
			$sql = "Select * from roll order by id desc limit 1";
			$result = $this->conn->query($sql);
			if ($result->num_rows > 0) {
			    // output data of each row
			    while($row = $result->fetch_assoc()) {

			    	// var_dump($row['roll']);
			        return  $row['roll'];

			    }
			}
			 else {
			    return "No records were found!! Please enter roll number again";
			}

		}
		public function getSem()
		{
			$sql = "Select * from sem order by id desc limit 1";
			$result = $this->conn->query($sql);
			if ($result->num_rows > 0) {
			    // output data of each row
			    while($row = $result->fetch_assoc()) {


			        return  $row['sem'];

			    }
			}
			 else {
			    return "No records were found!! Please enter roll number again";
			}

		}


	}

<?php  



	include('DB.php');
	require 'porterstemmer.php';

	$access_token = "EAARV2tvxESUBAIhBkkLHOYdjOLKET5xXyt7wDYd3cpI203qVlGcYypn8xx8PTNCNX51eWzN4uf6n9LQUsNA9sGkYfZBx5biF8p252HPCRVK2V7xWkhSR1ZBSo7kQnljjQB3FLf7euGBLqwb142wWVtVVq93YAGjQfx5IqAIZAe3vGkVeYvr";

	
	$challenge = $_REQUEST['hub_challenge'];
	$token = $_REQUEST['hub_verify_token'];
	$msgs = null;
	$reply = " No";

	
		
	if($token == 'abc123'){
		echo $challenge;
	}
	$ne=0;
	$db = new DB;
	$input = json_decode(file_get_contents('php://input'),true);

	$userId = $input['entry'][0]['messaging'][0]['sender']['id'];

	$message = $input['entry'][0]['messaging'][0]['message']['text'];
	$message = strtolower($message);
	$messagingArray = $input['entry'][0]['messaging'][0];
	


	//stop word removal	
	$question = array("where","how","when","has","can","is","no","yes",null);
	$sub = array("have","know","want","give","get","need",null);
	$sem = array("1st","2nd","3rd","4th","5th","6th","7th","8th");
	$subs = array("publish","out","available",null);
	$emoji=array(":)",":(","😍");


	$stop_words = array("to","an","the","is","are","i","been","my","me","so","and","of","okay","you","?");
	
	$msg = explode(' ', $message);//array conversion
	// var_dump($msg);

	//stop words removal
	for($i=0;$i<count($stop_words);$i++)
	{
		for($j=0;$j<count($msg);$j++)
		{

			if($msg[$j]==$stop_words[$i])
			{
				
				unset($msg[$j]);
				$msg = array_values($msg);

			}
		 	if(preg_match('/(.*?)(\\?)(.*?)/', $msg[$j]))
			{
				$msg[$j]=preg_replace('/[-?]/', '', $msg[$j]);
				var_dump($msg[$j]);


			}

		}
		
	}

	
	// $s= end($msg);

	// var_dump($s);
			
	//stemming
	for($a=0;$a<count($msg);$a++)
		{
			if($msg[$a]=="res")
				{

					$msg[$a]="result";
				}

			if($msg[$a]=="published"||$msg[$a]=="results")
				{
					var_dump($msg[$a]);

					$msg[$a] = PorterStemmer::stem($msg[$a]);
					
				}
			// var_dump($msg[$a]);
			if($msg[$a]=="donot"||$msg[$a]=="don't")
				{
					$msg[$a]="dont";

				}
			if($msg[$a]=="neednot"||$msg[$a]=="needn't")
				{
					$msg[$a]="neednt";

				}
			
		}//for $a end
	$msg1 = implode(" ", $msg);//string conversion
	$mesg = implode($msg);
	// var_dump($msg1);
	
//greetings
	if(preg_match('/(hi|hello|hey|hiya)(.*?)/', $msg1))
	{
		$greet = array(
			"Hello there !! :)",
			"Hiya 😊",
			"Hey there 😄",
			"Hello",
			"Hi",
			);
		$reply = $greet[array_rand($greet)];
				
	}

	//results
	else if(preg_match('/(.*?)(result)(.*?)/', $msg1))
	{

		
		$neg = array("dont","neednt");
		$mesg = implode($msg);



			
			for($b=0;$b<count($sub);$b++)
			{
				for($c=0;$c<count($neg);$c++)
					{
						
						if($mesg==$neg[$c].$sub[$b]."result")
						{
							$ne = 1;
						}
					}//for c closing
			}//for$b closing
			
			if($ne==1)
			{
				$reply="Okay then.But I am a bot for result so I cannot help you";

			}
			elseif(preg_match('/(.*?)(sem|semester)(.*?)/', $msg1))
			{
				for($r=0;$r<count($msg);$r++)
				{

					if($msg[$r]=="sem")
					{
						$msg[$r]="semester";

					}
					if($msg[$r]=="first"||$msg[$r]=="1")
						{
							$msg[$r]="1st";

						}
					if($msg[$r]=="second"||$msg[$r]=="2")
						{
							$msg[$r]="2nd";

						}
					if($msg[$r]=="third"||$msg[$r]=="3")
						{
							$msg[$r]="3rd";

						}
					if($msg[$r]=="fourth"||$msg[$r]=="4")
						{
							$msg[$r]="4th";

						}
					if($msg[$r]=="fifth"||$msg[$r]=="5")
						{
							$msg[$r]="5th";

						}
					if($msg[$r]=="sixth"||$msg[$r]=="6")
						{
							$msg[$r]="6th";

						}
					if($msg[$r]=="seventh"||$msg[$r]=="7")
						{
							$msg[$r]="7th";

						}
					if($msg[$r]=="eighth"||$msg[$r]=="8")
						{
							$msg[$r]="8th";

						}
				}
			
				
				$mesg1 = implode($msg);
				

				for($i=0;$i<count($question);$i++)
					{
						for($j=0;$j<count($sub);$j++)
							{

								for($k=0;$k<count($subs);$k++)
									{	
										for($l=0;$l<count($sem);$l++)
										{	
											if($mesg1==$question[$i].$sub[$j]."result".$sem[$l]."semester".$subs[$k])
											{
												$reply = "Roll number please";
												$s=$sem[$l]." "."semester";
												
												$db->sem($s);

											}//end of if

									}//end of l
								}//end of $k
							}//end of j
						}//end of i
			}


		
			else
				{
					

					for($i=0;$i<count($question);$i++)
					{
						for($j=0;$j<count($sub);$j++)
							{

							for($k=0;$k<count($subs);$k++)
								{	
									// var_dump($mesg);


								if($mesg==$question[$i].$sub[$j]."result".$subs[$k])
								{
									$re="Sure.Please choose your semester";
									if($question[$i]=="is"||$question[$i]=="has")
									{
										$re = "Yes.Please choose your semseter";
									}
									
									$reply = ' {
												  "recipient":{
												    "id":"'.$userId.'"
												  },
												  "message":{
												    "text": "'.$re.'",
												    "quick_replies":[
												      {
												        "content_type":"text",
												        "title":"1st semester",
												        "payload":"semster"

												      },
												      {
												        "content_type":"text",
												        "title":"2nd semester",
												        "payload":"semster"
												      },
												      {
												        "content_type":"text",
												        "title":"3rd semester",
												        "payload":"semster"
												      },
												      {
												        "content_type":"text",
												        "title":"4th semester",
												        "payload":"semster"
												      },
												      {
												        "content_type":"text",
												        "title":"5th semester",
												        "payload":"semster"
												      },
												      {
												        "content_type":"text",
												        "title":"6th semester",
												        "payload":"semster"
												      },
												      {
												        "content_type":"text",
												        "title":"7th semester",
												        "payload":"semster"
												      },
												      {
												        "content_type":"text",
												        "title":"8th semester",
												        "payload":"semster"
												      }

												    ]
												  }
												}' ;
												sendMessage($reply);
											
								}//end of if
								

							}//end of for k
						}//end of for j
					}//end of for i

			}//end of else
			
			
		
			
	}//end of preg match if
	
						

						

	elseif(preg_match('/(.*?)(marks|marksheet)(.*?)/', $msg1))
	{
		if(preg_match('/(.*?)(marks) (.+?)/', $msg1))
		{
			$s= end($msg);
			switch ($s) {
				case 'toc':
					$s="Theory Of Computation";

					break;
				case 'sad':
					$s="System Analysis and Design";

					break;
				case 'dbms':
					$s="Database Management system";

					break;
				case 'cg':
					$s="Computer Graphics";

					break;
				case 'cog':
					$s="Cognitive Science";

					break;
				case 'it':
					$s="InformationTechnology";

					break;
				case 'c':
					$s="Cprogramming";
				case 'logic':
					$s="DigitalLogic";
				

					break;
				

				

				
				default:
					
					$s=$s;

			}
			
			$roll = $db->getroll();
			$sem = $db->getSem();
			$reply = $db->marks($roll,$sem,$s);
		}
	

				
			
			else{
				$sem = $db->getSem();

				$roll = $db->getroll();
				var_dump($roll);
				var_dump($sem);
				$reply=$db->marksheet($roll,$sem);

			}
			
		}
		
							
	

	

	//roll number 

	elseif(preg_match('/(.*?)((roll number|roll no.) [0-9]+)(.*?)/', $msg1)||preg_match('/^[0-9]*$/', $msg1))
		{
			$int = filter_var($msg1, FILTER_SANITIZE_NUMBER_INT);

			$db->roll($int);
			
			// $m= $db->out_marksheet();

			// var_dump($msg1);
			// if($m=='t')
			// {

			// 	$sem = $db->getSem();

			// 	$roll = $db->getroll();
			// 	insert('f',0);
			// 	 // var_dump($roll);
			// 	$reply=$db->marksheet($roll,$sem);
				
				
			// }
			

			// else
			// {
			// 	$sem = $db->getSem();
			// 	// var_dump($sem);
			// 	$reply = $db->result($int,$sem);
			// }
			$sem = $db->getSem();
				var_dump($sem);
				$reply = $db->result($int,$sem);
			


		}

		elseif(preg_match('/(.*?)(thank you|thanks)(.*?)/', $msg1))
		{
			var_dump($msg1);
			$reply = "You're welcome . Good bye";
		}
		else
		{
			$reply = "Sorry i think I fail to understand you 😵";
		}

	
		
	
	//semester select

	$me = $input['entry'][0]['messaging'][0]['message']['quick_reply'];

	if(isset($me))
	{
		

		if($me['payload'] == 'semster')
		{

			$semester = $input['entry'][0]['messaging'][0]['message']['text'];
			
			// var_dump($semester);
			
			$db->sem($semester);
			$reply = "Roll number please";
			
		}
		
		
	}
	//emoji
	for($e=0;$e<count($emoji);$e++)
	{
		for($d=0;$d<count($msg);$d++)
		{

			if($msg[$d]==$emoji[$e])
			{
			var_dump($emoji[$e]);
				
				$reply = $emoji[$e];
			}
		}
		
	}

	//get started button
	
	if(isset($messagingArray['postback']))
	{
		if($messagingArray['postback']['payload'] == 'first hand shake')
		{

			$reply = "Hi I am Result-Bot . I help you to find results!! :)";
			
		}
	}


	function insert($char,$re)
		{
			$d = new DB;
			$d->in_marksheet($char,$re);
		}
		

	//processing
	
	 $url = 'https://graph.facebook.com/v2.8/me/messages?access_token='.$access_token;

	$json_data  = "{
		'recipient':{
			'id':'$userId'
		},
		'message':{
			'text':'$reply'
		}

		}";
		// var_dump($reply);

		
	
		function sendMessage($rawResponse)
		{
			 $url = 'https://graph.facebook.com/v2.8/me/messages?access_token=EAARV2tvxESUBAOHG0Ixv4kJQx1pCRHaz1FQuJiEOeABvYCBmxSvg7P8xfuX1u3gXeZAnlqxGoJxj15CYpTkUWH24CK61eIo71ZAQwcTSV058WAicu21SbdVYn95cyXZBZCZBtKGcsw3A9zevz0GPXrFjGT3k6u5AvegL6b7DRMwZDZD';

			$ch = curl_init($url);

			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch,CURLOPT_POSTFIELDS,$rawResponse);
			curl_setopt($ch, CURLOPT_HTTPHEADER,['content-type:application/json']);
			
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_exec($ch);
		}



	$ch = curl_init($url);

	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch,CURLOPT_POSTFIELDS,$json_data);
	curl_setopt($ch, CURLOPT_HTTPHEADER,['content-type:application/json']);
	
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	if(!empty($input['entry'][0]['messaging'][0]['message']||!empty($messagingArray['postback'])))
	{
		curl_exec($ch);
	}

     

	
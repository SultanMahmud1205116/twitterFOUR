<?php
session_start();
require 'db/connect.php';
require_once('twitteroauth/twitteroauth.php');
require_once('TwitterAPIExchange.php');
include('config.php');


if(isset($_SESSION['name']) && isset($_SESSION['twitter_id'])) //check whether user already logged in with twitter
{

	

	$settings = array(
    'oauth_access_token' => "3146903359-n1BViBTMztqSd0QIuUGfx8Toa109JqB9h7Czwh3",
    'oauth_access_token_secret' => "jc00yyRHq5tu3pzgGZGEFHjij6eG0ciDviYdq82s3Wg7s",
    'consumer_key' => "w5TqlKvu2baoRKxZm6xPgUZfs",
    'consumer_secret' => "Mc1klDqUy380T41HiE3famsxprnul73Eo2k99GtBtmsttbyCVi"
);

    //the following two lines are for getting the date
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$prev_date = date('Y-m-d', strtotime($date .' -1 day'));
 
$url = "https://api.twitter.com/1.1/statuses/home_timeline.json";
 
$requestMethod = "GET";
//this is the creation of get_field
$getfield = '?screen_name='.$_SESSION['twitter_id'].'&since:'.$prev_date;
$homeid=$_SESSION['idstr'];
$homename=$_SESSION['name'];

 
$twitter = new TwitterAPIExchange($settings);

$string = json_decode($twitter->setGetfield($getfield)
->buildOauth($url, $requestMethod)
->performRequest(),$assoc = TRUE);
if($string["errors"][0]["message"] != "") {echo "<h3>Sorry, there was a problem.</h3><p>Twitter returned the following error message:</p><p><em>".$string[errors][0]["message"]."</em></p>";exit();}
foreach($string as $items)
    {
        

        


        /*
        //database starts here


        //insertion into post_info starts here

        $userID=$homeid;
        $postID=$itemsp['id_str'];
        $posterID=$items['user']['id_str'];
        $post=$items['text'];
        $post_time=$items['created_at'];
        $numberOfRetweets=$items['retweet_count'];
        $numberOfFavourites=$items['user']['favourites_count'];

        //the following portion is for inserting into post_info
        $insert=$db->prepare("INSERT INTO post_info (postID, userID, post, posterID, time, numberOfFavourites, numberOfRetweets) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $insert->bind_param('sssssss',$postID,$userID,$post,$posterID,$post_time,$numberOfFavourites,$numberOfRetweets);

        $insert->execute();


        //insertion into post_info ends here......



        //the following is for inserting into user_info

        $insert2=$db->prepare("INSERT INTO user_info (userID, screen_name) VALUES (?, ?)");
        $insert2->bind_param('ss',$homeid,$homename);
        $insert2->execute();


        //insertion into user_info ends here


        //database ends here..........
        */





        // the dollwoing does the echoing
        echo "user id : ".$homeid."<br>";
        echo "user name: ".$homename."<br>";
        echo "post id : ".$items['id_str']."<br />";
        echo "poster ID : ".$items['user']['id_str']."<br />";
        echo "post : ".$items['text']."<br />";
        echo "post provider : ".$items['user']['name']."<br />";
        echo "post time : ".$items['created_at']."<br />";
        echo " # of retweets : ".$items['retweet_count']."<br />";
        echo " # of likes : ".$items['user']['favourites_count']."<br /><hr />";
        

        



    }

  


}
else // Not logged in
{

	$connection = new TwitterOAuth($CONSUMER_KEY, $CONSUMER_SECRET);
	$request_token = $connection->getRequestToken($OAUTH_CALLBACK); //get Request Token

	if(	$request_token)
	{
		$token = $request_token['oauth_token'];
		$_SESSION['request_token'] = $token ;
		$_SESSION['request_token_secret'] = $request_token['oauth_token_secret'];
		
		switch ($connection->http_code) 
		{
			case 200:
				$url = $connection->getAuthorizeURL($token);
				//redirect to Twitter .
		    	header('Location: ' . $url); 
			    break;
			default:
			    echo "Coonection with twitter Failed";
		    	break;
		}

	}
	else //error receiving request token
	{
		echo "Error Receiving Request Token";
	}
	

}



?>
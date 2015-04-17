<?php
session_start();
require_once('twitteroauth/twitteroauth.php');
require_once('TwitterAPIExchange.php');
include('config.php');


if(isset($_SESSION['name']) && isset($_SESSION['twitter_id'])) //check whether user already logged in with twitter
{

	//echo "Name :".$_SESSION['name']."<br>";
	//echo "Twitter ID :".$_SESSION['twitter_id']."<br>";
	//echo "Image :<img src='".$_SESSION['image']."'/><br>";
	//echo "<br/><a href='logout.php'>Logout</a>";

    //echo "twitter Id is: ".$_SESSION['twitter_id'];

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

//echo $getfield."<br>";

 
//$getfield = '?screen_name=1205116_sm&count=30';
 
$twitter = new TwitterAPIExchange($settings);
/*
echo "<pre>";
print_r($twitter->setGetfield($getfield)
             ->buildOauth($url, $requestMethod)
             ->performRequest()) ;
echo "</pre>";
*/
$string = json_decode($twitter->setGetfield($getfield)
->buildOauth($url, $requestMethod)
->performRequest(),$assoc = TRUE);
if($string["errors"][0]["message"] != "") {echo "<h3>Sorry, there was a problem.</h3><p>Twitter returned the following error message:</p><p><em>".$string[errors][0]["message"]."</em></p>";exit();}
foreach($string as $items)
    {
        /*
        echo "Time and Date of Tweet: ".$items['created_at']."<br />";
        echo "Tweet: ". $items['text']."<br />";
        echo "Tweeted by: ". $items['user']['name']."<br />";
        echo "Screen name: ". $items['user']['screen_name']."<br />";
        echo "Followers: ". $items['user']['followers_count']."<br />";
        echo "Friends: ". $items['user']['friends_count']."<br />";
        echo "Listed: ". $items['user']['listed_count']."<br /><hr />";
        */

        /*

        $data = array (
            'postID' => $items['id_str'],
            'userID' => $items['user']['id_str'],
            'post'   => $items['text'],
            'postProvider' => $items['user']['name'],
            'time' => $items['created_at'],
            'numberOfFavourites' => $items['user']['favourites_count'],
            'numberOfRetweets'   => $items['user']['favourites_count']
            );

        $this->db->insert('tPOst',$data);

        
        */

        echo "user id :".$_SESSION['idstr']."<br />"
        echo "post id : ".$items['id_str']."<br />";
        echo "poster ID : ".$items['user']['id_str']."<br />";
        echo "post : ".$items['text']."<br />";
        echo "post provider : ".$items['user']['name']."<br />";
        echo "post time : ".$items['created_at']."<br />";
        echo " # of retweets : ".$items['retweet_count']."<br />";
        echo " # of likes : ".$items['user']['favourites_count']."<br /><hr />";


        



    }

    //header('Location: echos.php');

    //echo "<br/><a href='echos.php'>Echos</a>";


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
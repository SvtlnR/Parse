<?php
error_reporting(E_ALL);
mb_internal_encoding("UTF-8");
use Symfony\Component\DomCrawler\Crawler;
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
$curl = new \parser\utils\Curl();
$list=[];
$baseUrl = 'https://top100.rambler.ru';
$isContent=true;
$pageIndex=1;
$con=parser\utils\Db::getInstance();
$db=new parser\ParseDb($con);
do{
	$params=[
		'query'=>'веб-студия',
 		'page'=>$pageIndex
 	];
	$response=$curl->call("https://top100.rambler.ru/?".http_build_query( $params));
	$crawler = new Crawler($response);
	if($crawler->filter('table.projects-table_catalogue')===null){
	 	$isContent=false;
	}
	else{	
		$crawler->filter('tr.projects-table__row')->each(function(Crawler $node,$i) use (&$list,$baseUrl) {
			 $_node = $node->getNode(0);
        	$list[$i] = isset($list[$i]) ? $list[$i] : [];
        	$list[$i]['url'] = '';
        	$list[$i]['name'] = '';
        	$list[$i]['uid'] = '';    
        	$list[$i]['visitors'] = 0;
        	$list[$i]['popularity'] = 0;   
        	$list[$i]['views'] = 0;   
        	$node
            	->filter('.link_catalogue-site-link')
            	->each(function (Crawler $node) use ($i, &$list, $baseUrl) {
                	$_node = $node->getNode(0);
	                $url = $_node->getAttribute('href');
	                $name = $_node->getAttribute('title');
	                $id = $_node->getAttribute('name'); 
	                $list[$i]['url'] = $url;
	                $list[$i]['name'] = $name;
	                $list[$i]['uid'] = $id;

            });
            $node
	            ->filter('.projects-table__cell[data-content="visitors"] .projects-table__textline')
                ->each(function (Crawler $node) use ($i, &$list, $baseUrl) {
                    $_node = $node->getNode(0); 
                    $vstrs = $_node->nodeValue;
                    $vstrs = preg_replace("/[^x\d|*\.]/", "", $vstrs);
                    $list[$i]['visitors'] = $vstrs; 

                });	
            $node
               ->filter('.projects-table__cell[data-content="views"] .projects-table__textline')
               ->each(function (Crawler $node) use ($i, &$list, $baseUrl) {
                   $_node = $node->getNode(0);
                    $vws = $_node->nodeValue;
                    $vws = preg_replace("/[^x\d|*\.]/", "", $vws);
                    $list[$i]['views'] = $vws;
                      
                });
            $node
            ->filter('.projects-table__cell[data-content="popularity"] .projects-table__textline')
            ->each(function (Crawler $node) use ($i, &$list, $baseUrl) {
                $_node = $node->getNode(0);
                $pop = $_node->nodeValue;
                $pop = preg_replace("/[^x\d|*\.]/", "", $pop);
                $list[$i]['popularity'] =  $pop;
                  
            });
        });	
        foreach ($list as $i =>$item) {   	 
    		$db->addRow([
    			'name' =>$item['name'],
    			'url' =>$item['url'],
    			'uid' =>$item['uid'],
    			'visitors' =>$item['visitors'],
    			'views' =>$item['views'],
    			'popularity' =>$item['popularity'],
    		]);
        }
		$pageIndex++;
	}
}while($isContent);


        
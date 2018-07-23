<?php
namespace parser;
class ParseDb{
	private $db;
	public function __construct($db){
		$this->db=$db;
	}
	public  function selectRow($url,$uid){
		$s=$this->db->prepare('SELECT * FROM parse_table WHERE url=:url AND uid=:uid');
		$s->bindParam(':url',$url);
		$s->bindParam(':uid',$uid);
		$s->execute();
		$rows = $s->fetchAll();
		if($rows!==null && isset($rows[0])){
			return $rows[0];
		}
		return null;
	}
	public function addRow($params = []){
		$url=$params['url'];
		$name=$params['name'];
		$uid=$params['uid'];
		$visitors=$params['visitors'];
		$views=$params['views'];
		$popularity=$params['popularity'];
		$row=$this->selectRow($url,$uid);
		if($row!==null){
			$this->updateRow($url,$name,$uid,$visitors,$views,$popularity);

		}
		else{
			$this->insertRow($url,$name,$uid,$visitors,$views,$popularity);
		}
	}
	public function insertRow($url,$name,$uid,$visitors,$views,$popularity){
		$s = $this->db->prepare("INSERT INTO parse_table (url,name,uid,visitors,views,popularity) values (:url,:name, :uid,:visitors,:views,:popularity)");
		$s->bindParam(':url', $url);
		$s->bindParam(':name', $name);
		$s->bindParam(':uid', $uid);
		$s->bindParam(':visitors', $visitors);
		$s->bindParam(':views', $views);
		$s->bindParam(':popularity', $popularity);
		$s->execute();
	}
	public function updateRow($url,$name,$uid,$visitors,$views,$popularity){
		$s=$this->db->prepare('UPDATE parse_table SET name='.$name.', visitors='.$visitors.', views='.$views.', popularity='.$popularity.' WHERE url='.$url.' AND $uid='.$uid);
		$s->execute();
	}
}
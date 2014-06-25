<?php
/**
 * 短网址生成类
 */
class ShortUrl{
	
	private $_mongodb = null;

	# 编码表
	private $_tableCodeArr = array ( 0 => 'I', 1 => '6', 2 => 'q', 3 => 's', 4 => '7', 5 => 'W', 6 => 'e', 7 => 'Z', 8 => 'S', 9 => 'G', 10 => '2', 11 => 'H', 12 => '4', 13 => 'T', 14 => 'K', 15 => 'f', 16 => 'J', 17 => '3', 18 => 'C', 19 => 'U', 20 => 'Y', 21 => 'R', 22 => '5', 23 => 'm', 24 => 'k', 25 => 'v', 26 => 'h', 27 => 't', 28 => 'X', 29 => 'P', 30 => 'A', 31 => 'b', 32 => 'i', 33 => 'N', 34 => 'n', 35 => 'a', 36 => 'x', 37 => '8', 38 => '1', 39 => 'O', 40 => 'V', 41 => 'F', 42 => 'd', 43 => '9', 44 => 'u', 45 => 'o', 46 => 'y', 47 => 'c', 48 => 'j', 49 => 'p', 50 => 'E', 51 => 'B', 52 => 'l', 53 => 'L', 54 => 'w', 55 => 'r', 56 => 'Q', 57 => 'D', 58 => 'M', 59 => '0', 60 => 'z', 61 => 'g'); 
	
	/**
	 * 类的构造方法
	 */
	function __construct(){
		$mongoClient    = new MongoClient('mongodb://127.0.0.1:27017');
		$this->_mongodb = $mongoClient->test;
	}


	/**
	 * 给一个数字,转成62位进制的简短字符串
	 * @param  int $num 数字
	 * @return String
	 */
	private function trans($num){
		$res = '';
		while($num > 62){
			$res = $this->_tableCodeArr[($num%62)].$res;
			$num = floor($num/62);
		}
		if($num>0) $res = $this->_tableCodeArr[$num].$res;
		return $res;
	}

	/**
	 * 获得并累加全局计算器
	 * @return Int
	 */
	private function getSn(){
		$table = $this->_mongodb->cnt;
		$row = $table->findAndModify(array('_id'=>1),array('$inc'=>array('sn'=>1)));
		return $row['sn'];
	}


	/**
	 * 查看短网址表中是否存在输入的URL地址
	 * @param  String $oriurl 源URL地址
	 * @return array | NULL
	 */
	private function getId($oriurl){
		$table = $this->_mongodb->urltable;
		$row   = $table->findOne(array('oriurl'=>$oriurl),array('_id'=>1,'url'=>1));
		return $row;
	}


	/**
	 * 添加短网址,若存在,直接返回短网址字符串
	 * @param String $oriurl 源URL
	 */
	function addUrl($oriurl){
		$res = $this->getId($oriurl);
		if(!empty($res)){
			return $res['url'];
		}
		
		$cnt   = $this->getSn();
		$url   = $this->trans($cnt);
		$table = $this->_mongodb->urltable;

		$bool = $table->insert(array('_id'=>$cnt,'url'=>$url,'oriurl'=>$oriurl,'hits'=>0));

		if($bool){
			return $url;
		}else{
			return false;
		}
	}


	/**
	 * URL的302跳转
	 * @param  String $url 简短进制的字符
	 * @return String | NULL
	 */
	function gotoUrl($url){
		$table = $this->_mongodb->urltable;
		$table->update(array('url'=>$url),array('$inc'=>array('hits'=>1)));
		$row   = $table->findOne(array('url'=>$url),array('_id'=>1,'oriurl'=>1));
		return $row;
	}
}
?>
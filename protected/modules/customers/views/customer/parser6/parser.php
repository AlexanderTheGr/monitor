<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);
require("simple_html_dom.php");


$parser = new parser;
$parser->step1();


class parser {
	
	function step1() {
		$html = file_get_html('http://www.aftermarketbg.com/');
		$rets = $html->find('ul[class=makes] a'); 
		$this->csv = "SKU;OEM;Marelli;Valeo;Hella"."\n";
		foreach($rets as $ret) {
			if ($i++ > 38) {
				$this->step2($ret->href);
			}
			//break;
		}
	}

	function step2($url,$page=1) {
		//echo 'http://www.aftermarketbg.com'.$url."?page=".$page."<BR>";
		$html = file_get_html('http://www.aftermarketbg.com'.$url."?page=".$page);
		if ($html) {
			$rets = $html->find('div.autopart');
			$page++;
			if (count($rets) == 0) return;
			foreach($rets as $ret) {
				$obj = $ret->find("span[class=label1]");		
				$sku = str_replace("Код: ","",$obj[0]->plaintext);
				$obj = $ret->find("div[class=image] img");
				$img = $obj[0]->src;			
				$image = "files/".trim($sku).".jpg";
				if (!file_exists($image) AND $img != "") {
					$img_file = file_get_contents($img);
					file_put_contents($image,$img_file);
				}
			}
			$this->step2($url,$page);
		}
	}
}



//print_r($ret);
?>
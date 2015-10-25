<?php

error_reporting(E_ALL & ~E_NOTICE); //错误抑制
/**
 * 分页类，以组件形式存在
 * 13-5-15 下午8:26 
 */
class Page {
		private $total; //数据表中总记录数
		private $listRows; //每页显示行数
		private $limit;
		private $uri;
		private $style;
		private $nowstyle;
		private $miao;	
		private $pageNum; //页数
		private $config=array('header'=>"个记录", "prev"=>"上一页", "next"=>"下一页", "first"=>"首 页", "last"=>"尾 页");
		private $listNum=8;
		/*
		 * $total 
		 * $listRows
		 */
		public function __construct($total, $listRows=10, $pa="",$style=array(),$miao){
			$this->total=$total;
			$this->listRows=$listRows;
			$this->uri=$this->getUri($pa);
			$this->style = $style[0];
			$this->nowstyle =$style[1];
			$this->miao = $miao;
			$this->page=!empty($_GET["page"]) ? $_GET["page"] : 1;
			$this->pageNum=ceil($this->total/$this->listRows);
			$this->limit=$this->setLimit();
		}

		private function setLimit(){
			return ($this->page-1)*$this->listRows.", {$this->listRows}";
		}

		private function getUri($pa){
			$url=$_SERVER["REQUEST_URI"].(strpos($_SERVER["REQUEST_URI"], '?')?'':"?").$pa;
			$parse=parse_url($url);

		

			if(isset($parse["query"])){
				parse_str($parse['query'],$params);
				unset($params["page"]);
				$url=$parse['path'].'?'.http_build_query($params);
				
			}

			return $url;
		}

		function __get($args){
			if($args=="limit")
				return $this->limit;
			else
				return null;
		}

		private function start(){
			if($this->total==0)
				return 0;
			else
				return ($this->page-1)*$this->listRows+1;
		}

		private function end(){
			return min($this->page*$this->listRows,$this->total);
		}

		private function first(){

			if($this->page==1)
				$html.='';
			else
				$html.="<a href='{$this->uri}&page=1{$this->miao}' class='{$this->style}'>{$this->config["first"]}</a>";

			return $html;
		}

		private function prev(){
			if($this->page==1)
				$html.='';
			else
				$html.="<a href='{$this->uri}&page=".($this->page-1)."{$this->miao}' class='{$this->style}'>{$this->config["prev"]}</a>";

			return $html;
		}

		private function pageList(){
			$linkPage="";
			
			$inum=floor($this->listNum/2);
		
			for($i=$inum; $i>=1; $i--){
				$page=$this->page-$i;

				if($page<1)
					continue;

				$linkPage.="<a href='{$this->uri}&page={$page}{$this->miao}' class='{$this->style}'>{$page}</a>";

			}
		
			$linkPage.="<a href='' class='{$this->nowstyle}'>{$this->page}</a>";
			

			for($i=1; $i<=$inum; $i++){
				$page=$this->page+$i;
				if($page<=$this->pageNum)
					$linkPage.="<a href='{$this->uri}&page={$page}{$this->miao}' class='{$this->style}'>{$page}</a>";
				else
					break;
			}

			return $linkPage;
		}

		private function next(){
			if($this->page==$this->pageNum)
				$html.='';
			else
				$html.="<a href='{$this->uri}&page=".($this->page+1)."' class='{$this->style}'>{$this->config["next"]}</a>";

			return $html;
		}

		private function last(){
			if($this->page==$this->pageNum)
				$html.='';
			else
				$html.="<a href='{$this->uri}&page=".($this->pageNum)."{$this->miao}' class='{$this->style}'>{$this->config["last"]}</a>";

			return $html;
		}

		private function goPage(){
			return '<input type="text" onkeydown="javascript:if(event.keyCode==13){var page=(this.value>'.$this->pageNum.')?'.$this->pageNum.':this.value;location=\''.$this->uri.'&page=\'+page+\'\'}" value="'.$this->page.'" style="width:25px ;"><input type="button" value="GO" onclick="javascript:var page=(this.previousSibling.value>'.$this->pageNum.')?'.$this->pageNum.':this.previousSibling.value;location=\''.$this->uri.'&page=\'+page+\'\'">';
		}
		function fpage($display=array(0,1,2,3,4,5,6,7,8)){
			$html[0]="共有<b>{$this->total}</b>{$this->config["header"]}&nbsp;";
			$html[1]="每页显示<b>".($this->end()-$this->start()+1)."</b>条，本页<b>{$this->start()}-{$this->end()}</b>条&nbsp;";
			$html[2]="<b>{$this->page}/{$this->pageNum}</b>页&nbsp;";
			
			$html[3]=$this->first();
			$html[4]=$this->prev();
			$html[5]=$this->pageList();
			$html[6]=$this->next();
			$html[7]=$this->last();
			$html[8]=$this->goPage();
			$fpage='';
			foreach($display as $index){
				$fpage.=$html[$index];
			}

			return $fpage;

		}

	

	}
	?>

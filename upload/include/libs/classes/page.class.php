<?php
 /*
 * 分页
 * ============================================================================
 * 版权所有: 快范网络，并保留所有权利。
 * 网站地址: http://www.kuaifan.net；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
*/
if(!defined('IN_KUAIFAN')) die('Access Denied!');
class page{
 var $page_name="page";
 var $next_page='下一页';
 var $pre_page='上一页';
 var $first_page='首页';
 var $last_page='尾页';

 var $page_url= array('next'=>'','pre'=>'','first'=>'','last'=>'','now'=>'');
 
 var $pre_bar='<<';
 var $next_bar='>>';
 var $format_left='';
 var $format_right='';
 var $pagebarnum=12;
 var $totalpage=0;
 var $nowindex=1;
 var $url="";
 var $offset=0;
 
 function page($array)
 {
  if(is_array($array))
  {
     if(!array_key_exists('total',$array))$this->error(__FUNCTION__,'need a param of total');
     $total=intval($array['total']);
     $perpage=(array_key_exists('perpage',$array))?intval($array['perpage']):10;
     $nowindex=(array_key_exists('nowindex',$array))?intval($array['nowindex']):'';
     $url=(array_key_exists('url',$array))?$array['url']:'';
     $alias = (array_key_exists('alias', $array)) ? $array['alias'] : '';
     $getarray = (array_key_exists('getarray', $array)) ? $array['getarray'] : '';
     $page_name = (array_key_exists('page_name', $array)) ? $array['page_name'] : $page_name;
  }
  else
  	{
     $total=$array;
     $perpage=10;
     $nowindex='1';
     $url='';
     $alias = '';
     $getarray = '';
     $page_name = 'page';
  }
  if((!is_int($total))||($total<0))$this->error(__FUNCTION__,$total.' is not a positive integer!');

  if((!is_int($perpage))||($perpage<=0))$this->error(__FUNCTION__,$perpage.' is not a positive integer!');
  if(!empty($array['page_name']))$this->set('page_name',$array['page_name']);
  $nowindex = intval($_POST[$array['page_name']])?intval($_POST[$array['page_name']]):$nowindex;
  $this->_set_nowindex($nowindex);
  $this->_set_url($url);
  $this->total=$total;
  $this->totalpage=ceil($total/$perpage);
  $this->offset=($this->nowindex-1)*$perpage;
  $this->alias = $alias;
  if ($this->nowindex > $this->totalpage) $this->nowindex = $this->totalpage;
  $this->getarray = $getarray;
  
  if ($this->nowindex < $this->totalpage) $this->page_url['next'] = $this->_get_url($this->nowindex+1);
  if ($this->nowindex > 1) $this->page_url['pre'] = $this->_get_url($this->nowindex-1);
  $this->page_url['first'] = $this->_get_url(1);
  $this->page_url['last'] = $this->_get_url($this->totalpage);
  $this->page_url['now'] = $this->_get_url($this->nowindex);
  
 }
 function set($var,$value)
 {
  if(in_array($var,get_object_vars($this)))
     $this->$var=$value;
  else {
   $this->error(__FUNCTION__,$var." does not belong to PB_Page!");
  }
 }
 function next_page($style='', $no1=''){
 	if($this->nowindex<$this->totalpage){
		return $this->_get_link($this->page_url['next'],$this->next_page,$style);
	}
	if ($no1) return '';
	return '<a class="'.$style.'">'.$this->next_page.'</a>';
 }
 function pre_page($style='', $no1=''){
 	if($this->nowindex>1){
		return $this->_get_link($this->page_url['pre'],$this->pre_page,$style);
	}
	if ($no1) return '';
	return '<a class="'.$style.'">'.$this->pre_page.'</a>';
 }
 function first_page($style=''){
 	if($this->nowindex==1){
    	return '<a class="'.$style.'">'.$this->first_page.'</a>';
 	}
  return $this->_get_link($this->_get_url(1),$this->first_page,$style);
 }
 function last_page($style=''){
 	if($this->nowindex==$this->totalpage||$this->totalpage==0){
      return '<a class="'.$style.'">'.$this->last_page.'</a>';
  }
  return $this->_get_link($this->_get_url($this->totalpage),$this->last_page,$style);
 }

 function nowbar($style='',$nowindex_style='')
 {
  $plus=ceil($this->pagebarnum/2);
  if($this->pagebarnum-$plus+$this->nowindex>$this->totalpage)$plus=($this->pagebarnum-$this->totalpage+$this->nowindex);
  $begin=$this->nowindex-$plus+1;
  $begin=($begin>=1)?$begin:1;
  $return='';
  for($i=$begin;$i<$begin+$this->pagebarnum;$i++)
  {
   if($i<=$this->totalpage){
    if($i!=$this->nowindex)
        $return.=$this->_get_text($this->_get_link($this->_get_url($i),$i,$style));
    else
        $return.=$this->_get_text('<a class="'.$nowindex_style.'">'.$i.'</a>');
   }else{
    break;
   }
   $return.="\n";
  }
  unset($begin);
  return $return;
 }
 /**
  * 获取显示跳转按钮的代码
  *
  * @return string
  */
 function select()
 {
   $return='<select name="PB_Page_Select">';
  for($i=1;$i<=$this->totalpage;$i++)
  {
   if($i==$this->nowindex){
    $return.='<option value="'.$i.'" selected>'.$i.'</option>';
   }else{
    $return.='<option value="'.$i.'">'.$i.'</option>';
   }
  }
  unset($i);

  $return.='</select>';
  return $return;
 }

 /**
  * 获取mysql 语句中limit需要的值
  *
  * @return string
  */
 function offset()
 {
  return $this->offset;
 }

 /**
  * 控制分页显示风格（你可以增加相应的风格）
  *
  * @param int $mode
  * @return string
  */
 function show($mode=1)
 {
  switch ($mode)
  {
   case '1':
    $this->next_page='下一页';
    $this->pre_page='上一页';
    return $this->pre_page().$this->nowbar().$this->next_page().'第'.$this->select().'页';
    break;
   case '2':
    $this->next_page='下一页';
    $this->pre_page='上一页';
    $this->first_page='首页';
    $this->last_page='尾页';
    return $this->first_page().$this->pre_page().'[第'.$this->nowindex.'页]'.$this->next_page().$this->last_page().'第'.$this->select().'页';
    break;
   case '3':
    $this->next_page='下一页';
    $this->pre_page='上一页';
    $this->first_page='首页';
    $this->last_page='尾页';
    return $this->first_page()."".$this->pre_page()."".$this->nowbar("","select")."".$this->next_page()."".$this->last_page()."<a>".$this->nowindex."/".$this->totalpage."页</a><div class=\"clear\"></div>";
    break;
   case '4':
    $this->next_page='下一页';
    $this->pre_page='<';
    return "<span>".$this->nowindex."/".$this->totalpage."页</span>".$this->pre_page().$this->next_page()."<div class=\"clear\"></div>";
    break;
   case '5':
    return $this->pre_bar().$this->pre_page().$this->nowbar().$this->next_page().$this->next_bar();
    break;
   case 'fujian':
    $this->next_page='下一页';
    $this->pre_page='上一页';
    return $this->pre_page("","1").' '.$this->next_page("","1");
    break;
   case 'wap':
    $this->next_page='下一页';
    $this->pre_page='上一页';
    $__timestr = $this->pre_page("","1").' '.$this->next_page("","1");
	if (trim($__timestr)) $__timestr.= '<br/>';
	$__timestr.= '第'.$this->nowindex.'/'.$this->totalpage.'页.共'.$this->total.'条<br/>';
	$__get=$this->getarray;
	if ($__get['vs']=='1'){
		$__timestr.= '<input name="'.$this->page_name.fenmiao().'" format="*N" maxlength="10" size="5" value="'.$this->nowindex.'" emptyok="true" />
		<anchor title="跳到该页">跳到该页
			<go href="'.$this->_get_url($this->nowindex).'" method="post">
				<postfield name="'.$this->page_name.'" value="$('.$this->page_name.fenmiao().')" />
			</go>
		</anchor>';
	}else{
		$__timestr.= '<form name="frm_'.$this->page_name.fenmiao().'" method="post" action="'.$this->_get_url($this->nowindex).'">
			<input name="'.$this->page_name.fenmiao().'" type="text" value="'.$this->nowindex.'" style="width: 45px;" />
			<input name="submit_jump" type="submit" value="跳到该页" class="but" emptyok="true" />
		</form>';
	}
	return $__timestr;
    break;
  }

 }
 function _set_url($url="")
 {
  if(!empty($url)){
   $this->url=$url.((stristr($url,'?'))?'&':'?').$this->page_name."=";
  }else{
   if(empty($_SERVER['QUERY_STRING'])){
    $this->url = $this->request_url()."?".$this->page_name."=";
   }else{
    if(stristr($_SERVER['QUERY_STRING'],$this->page_name.'=')){
     $this->url = get_link($this->page_name,'&','',$this->getarray,'1');
     $last=$this->url[strlen($this->url)-1];
     if($last=='?'||$last=='&'){
         $this->url.= $this->page_name."=";
     }else{
         $this->url.= '&'.$this->page_name."=";
     }
    }else{
     $this->url = get_link('','&','',$this->getarray,'1').'&'.$this->page_name.'=';
    }
   }
  }
 }
function _set_nowindex($nowindex)
{
	if(empty($nowindex))
	{
		   if(isset($_GET[$this->page_name]))
		   {
			$this->nowindex=intval($_GET[$this->page_name]);
		   }
	}
	else
	{
	   $this->nowindex=intval($nowindex);
	}
	$this->nowindex=$this->nowindex===0?1:$this->nowindex;
}
 function _get_url($pageno=1,$rewrite=true)
 {
 	if ($this->alias && $this->getarray)
	{
	$get=$this->getarray;
	$get['page']=$pageno;
	if ($get['key']) $get['key']=rawurlencode($get['key']);
	return url_rewrite($this->alias,$get,$rewrite);
	}
	else
	{
	return str_replace("&", "&amp;", $this->url).$pageno;
	}
 }
 function _get_text($str)
 {
  return $this->format_left.$str.$this->format_right;
 }
 function _get_link($url,$text,$style='')
 {
  $style=(empty($style))?'':'class="'.$style.'"';
  return '<a '.$style.' href="'.$url.'">'.$text.'</a>';
 }
 function error($function,$errormsg)
 {
     die('Error in file <b>'.__FILE__.'</b> ,Function <b>'.$function.'()</b> :'.$errormsg);
 }
 function request_url()
 {     
  	if (isset($_SERVER['REQUEST_URI']))     
    {        
   	 $url = $_SERVER['REQUEST_URI'];    
    }
	else
	{    
		  if (isset($_SERVER['argv']))        
			{           
			$url = $_SERVER['PHP_SELF'] .'?'. $_SERVER['argv'][0];      
			}         
		  else        
			{          
			$url = $_SERVER['PHP_SELF'] .'?'.$_SERVER['QUERY_STRING'];
			}  
    }    
    return $url; 
}
}
?>
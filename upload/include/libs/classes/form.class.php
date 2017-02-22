<?php
class form {
	/**
	 * 栏目选择
	 * @param string $file 栏目缓存文件名
	 * @param intval/array $catid 别选中的ID，多选是可以是数组
	 * @param string $str 属性
	 * @param string $default_option 默认选项
	 * @param intval $modelid 按所属模型筛选
	 * @param intval $type 栏目类型
	 * @param intval $onlysub 只可选择子栏目
	 * @param intval $siteid 如果设置了siteid 那么则按照siteid取
	 */
	public static function select($array = array(), $id = 0, $str = '', $default_option = '') {
		$string_selected = !empty($id) ? ' value="'.$id.'"' : '';
		$string = '<select '.$str.$string_selected.'>';
		$default_selected = (empty($id) && $default_option && $_GET['vs']!='1') ? 'selected="selected"' : '';
		if($default_option) $string .= "<option value='' $default_selected>$default_option</option>";
		if(!is_array($array) || count($array)== 0) return false;
		$ids = array();
		if(isset($id)) $ids = explode(',', $id);
		foreach($array as $key=>$value) {
			$selected = (in_array($key, $ids) && $_GET['vs']!='1') ? 'selected="selected"' : '';
			$string .= '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
		}
		$string .= '</select>';
		return $string;
	}
	/**
	 * 栏目选择
	 * @param string $file 栏目缓存文件名
	 * @param intval/array $catid 别选中的ID，多选是可以是数组
	 * @param string $str 属性
	 * @param string $default_option 默认选项
	 * @param intval $modelid 按所属模型筛选
	 * @param intval $type 栏目类型
	 * @param intval $onlysub 只可选择子栏目
	 * @param intval $siteid 如果设置了siteid 那么则按照siteid取
	 */
	public static function select_category($file, $id = 0, $str = '', $default_option = '', $modelid = 0, $type = -1, $onlysub = 0,$siteid = 0,$is_push = 0) {
		$result = getcache(KF_ROOT_PATH.'caches/'.$file.'.php');
		$string = ($_GET['vs']==1 && is_numeric($id)) ? " value='$id'":"";
		$string = '<select '.$str.$string.'>';
		if($default_option) $string .= "<option value='0'>$default_option</option>";
		if (is_array($result)) {
			foreach($result as $r) {
				$r['selected'] = '';
				if(is_numeric($id)) {
					$r['selected'] = ($id==$r['id'] && $_GET['vs']!=1) ? 'selected=\'selected\'' : '';
				}
				$r['html_disabled'] = "0";
				if (!empty($onlysub) && $r['arrchildid'] != '0') {
					$r['html_disabled'] = "1";
				}
				$categorys[$r['id']] = $r;
				if($modelid && $r['modelid']!= $modelid ) unset($categorys[$r['id']]);
			}
		}
		$str  = "<option value='\$id' \$selected>\$spacer \$title</option>";
		$str2 = "<optgroup label='\$spacer \$title'></optgroup>";

    kf_class::run_sys_class('tree','',0);
		$tree= new tree();
		$tree->init($categorys);
		$string .= $tree->get_tree_category(0, $str, $str2);
			
		$string .= '</select>';
		return $string;
	}
}

?>
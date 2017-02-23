<?php
	$_SESSION;
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=1" name="viewport">
	<style type="text/css">
		.all{}
	</style>
</head>
<body>
	<section class="all">
		<label>url:</label>
		<input type="text" name="">
		<label>cookie:</label>
		<input type="text" name="">
		<label>data:</label>
		<input type="text" name="">
		<input type="button" value="定期">
	</section>
	<section>
		<div id="responseText">
			<p>daas</p>
			<p>daas</p>
		</div>
	</section>
</body>
<script type="text/javascript">
	/**
	 * ajax 请求
	 */
	function ajaxRequest(data,callback){
		var xhr;
		if(window.XMLHttpRequest){
			xhr = new XMLHttpRequest();
		}else if(window.ActiveXObject){
			xhr = new ActiveXObject('Microsoft.XMLHTTP');
		}
		if(!xhr) return;
		xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		xhr.onreadystatechange = function (){
			if(xhr.readyState == 4 && xhr.status == 200){
				var result =  eval('(' + xhr.responseText + ')');
				callback(result);
			}
		};
		xhr.send(data);
	}
	/**
	 * 定期执行
	 */
	var _regular;
	function regular(data,time,callback){
		_regular.data = data;
		_regular.id = setInterval(function(){
			ajaxRequest(_regular.data,callback);
		},time);
	}
	/**
	 * 取消定期执行
	 */
	function clearRegular(){
		clearInterval(_regular.id);
		_regular = {};
	}
</script>
</html>
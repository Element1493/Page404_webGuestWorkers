<?php
/*
 * Конвертирует SVG в JSON
 * Автор скрипта: Сергей Зверев <element1493@yandex.ru>
 */
if(isset($_GET['svg'])){
	if(file_exists($_GET['svg'])){
		$svg = str_replace(array("\r","\n","\t"),"",file_get_contents($_GET['svg']));
		$array = [];

		function tag($attributes,$name) {
			preg_match('/'.$name.'="(.+?)"/', $attributes, $result);
			return $result[1];
		}

		preg_match_all('#<g id="(.*?)">(.*?)</g>#is', $svg, $parent, PREG_SET_ORDER);
		foreach($parent as $child){
			preg_match_all('#<(.*?)>#is', $child[2], $tags, PREG_SET_ORDER);
			foreach($tags as $tag){
				switch(substr($tag[1],0,strpos($tag[1],' '))){
					case 'ellipse':
					case 'circle':
						$array[$child[1]]["cx"][] = tag($tag[1],'cx');
						$array[$child[1]]["cy"][] = tag($tag[1],'cy');
					break;
					case 'path':
						$array[$child[1]]["d"][] = tag($tag[1],'d');
					break;
				}
			}
		}
		echo json_encode($array);
	}else{
		echo 'Файл отсутствие';
	}
}
?>
<?php

function createSupportArticleItem($token, $title, $content, $faq = 0, $published = 1) {
	global $db;
	
	$uid = $token['uid'];
	$session = $token['session'];
	
	$output = $db->query("INSERT INTO `supportArticles` (`author_id`, `title`, `content`, `faq`, `published`) VALUES({$uid}, '{$title}', '{$content}', {$faq}, {$published})");
	
	if($db->numOfRows) {
		return true;
	}
	
	return false;
}

function deleteSupportArticle($aid) {
	global $db;
	
	$output = $db->query("UPDATE supportArticles SET supportArticles.published = 0 WHERE supportArticles.article_id = {$aid}");
	
	if($db->numOfRows) {
		return true;
	}
	
	return false;
}

function publishSupportArticle($aid) {
	global $db;
	
	$output = $db->query("UPDATE supportArticles SET supportArticles.published = 1 WHERE supportArticles.article_id = {$aid}");
	
	if($db->numOfRows) {
		return true;
	}
	
	return false;
}

function getFaqArticles() {
	global $db;
	
	$output = $db->query("SELECT * FROM supportArticles WHERE supportArticles.faq = 1 AND supportArticles.published = 1");
	
	if($db->numOfRows) {
		return $output;
	}
	
	return false;
}

function getFaqArticleById($aid) {
	global $db;
	
	$sql = "SELECT supportArticles.*, users.* FROM supportArticles, users WHERE supportArticles.author_id = users.user_id AND supportArticles.faq = 1 AND supportArticles.published = 1 AND supportArticles.article_id = {$aid} LIMIT 0,1";
	$output = $db->query($sql);
	
	if($db->numOfRows) {
		return $output[0];
	}
	
	return false;
}

function getHelpArticles() {
	global $db;
	
	$output = $db->query("SELECT * from supportArticles WHERE supportArticles.faq = 0 AND supportArticles.published = 1");
	
	if($db->numOfRows) {
		return $output;
	}
	
	return false;
}

function getHelpArticleById($aid) {
	global $db;
	
	$sql = "SELECT supportArticles.*, users.* from supportArticles, users WHERE supportArticles.author_id = users.user_id AND supportArticles.article_id = {$aid} LIMIT 0,1";
	$output = $db->query($sql);

	if($db->numOfRows) {
		return $output[0];
	}
	
	return false;
}


?>
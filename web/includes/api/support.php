<!--
 * Copyright (c) 2011, iSENSE Project. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * Redistributions of source code must retain the above copyright notice, this
 * list of conditions and the following disclaimer. Redistributions in binary
 * form must reproduce the above copyright notice, this list of conditions and
 * the following disclaimer in the documentation and/or other materials
 * provided with the distribution. Neither the name of the University of
 * Massachusetts Lowell nor the names of its contributors may be used to
 * endorse or promote products derived from this software without specific
 * prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE REGENTS OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
 * OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH
 * DAMAGE.
 -->
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
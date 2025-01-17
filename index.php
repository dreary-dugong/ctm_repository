<?php

require($_SERVER['DOCUMENT_ROOT'] . "/config.php");
$action = isset ($_GET['action']) ? $_GET['action'] : "";

switch ($action) {
	case 'popular':
		popular();
		break;
	case 'recent':
		recent();
		break;
	case 'search':
		search();
		break;
	case 'browse':
		browse();
		break;
	case 'viewMap':
		viewMap();
		break;
	default:
		homepage();
}

function cmpPopular ($a, $b) {
	if ($a->popScore == $b->popScore)
		return 0;
	return ($a->popScore > $b->popScore) ? -1 : 1;
}

function cmpRecent ($a, $b) {
	if ($a->dateAdded == $b->dateAdded)
		return 0;
	return ($a->dateAdded > $b->dateAdded) ? -1 : 1;
}

function popular () {
	$results = array();
	$data = Map::getList(-1);
	$results['maps'] = $data['results'];

	// Sort by popularity
	$mapsToSort = $results['maps'];
	usort($mapsToSort, "cmpPopular");
	$results['maps'] = $mapsToSort;

	$results['pageTitle'] = "Popular CTM Maps | CTM Map Repository";
	require($_SERVER['DOCUMENT_ROOT'] . "/popResults.php");
}

function recent () {
	$results = array();
	$data = Map::getList(-1);
	$results['maps'] = $data['results'];

	// Sort by date added
	$mapsToSort = $results['maps'];
	usort($mapsToSort, "cmpRecent");
	$results['maps'] = $mapsToSort;

	$results['pageTitle'] = "Recently Added CTM Maps | CTM Map Repository";
	require($_SERVER['DOCUMENT_ROOT'] . "/popResults.php");
}


function search () {
	header("Location: search.php");
}

function browse () {
	header("Location: browse.php");
}

function viewMap () {
	if (!isset($_GET["id"]) || !$_GET["id"]) {
		homepage();
		return;
	}

	$results = array();
	$dispMap = Map::getByIdPublished((int) $_GET["id"]);
	if (is_object($dispMap)) {
		$results['pageTitle'] = $dispMap->name . " | CTM Map Repository";
	}
	else {
		$results['pageTitle'] = "Error | CTM Map Repository";
	}
	require($_SERVER['DOCUMENT_ROOT'] . TEMPLATE_PATH . "/viewMap.php");
}

function homepage () {
	$results = array();
	$results['featured'] = Map::getById(315);
	$results['random'] = Map::getRandomMap();
	$results['pageTitle'] = "CTM Maps Repository";
	require($_SERVER['DOCUMENT_ROOT'] . TEMPLATE_PATH . "/homepage.php");
}
?>

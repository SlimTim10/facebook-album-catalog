<?php

class FacebookAlbumCatalog {
	public $fb;

	public $test;

	public function albumName($name) {
		$response = $this->fb->get('/me?fields=albums');
		$node = $response->getGraphNode();
		$albums = $node->getField('albums');
		$album_id = '';

		foreach ($albums as $a) {
			if ($a['name'] == $name) {
				$album_id = $a['id'];
				break;
			}
		}

		$response = $this->fb->get("/$album_id?fields=photos");
		$node = $response->getGraphNode();
		$photos = $node->getField('photos');
		$photo_ids = array();

		foreach ($photos as $p) {
			$photo_ids[] = $p['id'];
			if ($p['name'] != null) {
				$photo_ids[] = $p['name'];
			}
		}

		$this->test = $photo_ids;
	}
}
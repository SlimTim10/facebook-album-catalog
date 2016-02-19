<?php

function fb_graph_json($id, $access_token, $fields) {
	$url = "https://graph.facebook.com/$id/?access_token=$access_token&fields=$fields";
	$resp = wp_remote_get(esc_url_raw($url));
	return json_decode(wp_remote_retrieve_body($resp), true);
}

class FacebookAlbumCatalog {
	public $fb = [];
	public $html = '';

	public function getAlbum($name) {
		$access_token = $this->fb['app_id'] . '|' . $this->fb['app_secret'];
		
		$response = fb_graph_json($this->fb['page_id'], $access_token, 'albums');
		if (isset($response['error'])) {
			$this->html = '<p>Error: ' . $response['error']['message'] . '</p>';
			return;
		}
		if (empty($response['albums'])) {
			$this->html = '<p>Error: Could not find albums';
			return;
		}
		$albums = $response['albums']['data'];
		$album_id = $this->findAlbumId($albums, $name);
		if (empty($album_id)) {
			$this->html = "<p>Error: Album $name not found</p>";
			return;
		}
		
		$response = fb_graph_json($album_id, $access_token, 'photos');
		if (isset($response['error'])) {
			$this->html = '<p>Error: ' . $response['error']['message'] . '</p>';
			return;
		}
		if (empty($response['photos'])) {
			$this->html = '<p>Error: Could not find photos';
			return;
		}
		$photos = $response['photos']['data'];

		$this->html = $this->createCatalog($photos);
	}

	protected function findAlbumId($albums, $name) {
		foreach ($albums as $a) {
			if ($a['name'] == $name) {
				return $a['id'];
			}
		}
		return;
	}

	protected function createCatalog($photos_json) {
		$html = '<div class="catalog">' . "\n";
		foreach ($photos_json as $p) {
			$album_photo = new Photo($p['id'], $this->fb);
			$full_img = $album_photo->sources[0]->url;
			$small_img = $album_photo->getImgURL(900, 900);
			$html .= '<a href="' . $full_img . '">' . "\n";
			$html .= '<div class="catalog-box">' . "\n";
			$html .= '<span class="catalog-box-img" style="background-image: url(' . $small_img . ');"></span>' . "\n";
			// $html .= '<img src="' . $src->url . '" alt="' . $album_photo->title . '">';
			$html .= '</div>' . "\n";
			$html .= '</a>' . "\n";
			// $html .= '<pre>' . var_export($album_photo, true) . '</pre>'; // DEBUGGING
		}
		$html .= '</div>';

		return $html;
	}
}

class Photo {
	public $id;
	public $name;
	public $price;
	public $title;
	public $desc;
	public $sources = [];

	protected $fb;

	public function __construct($id, $fb) {
		$this->id = $id;
		$this->fb = $fb;
		
		$this->getInfo();
		$this->parseName();
	}

	/* Return the source URL of the image with the maximum given dimensions or the next one smaller */
	public function getImgURL($max_width, $max_height) {
		$sorted_sources = $this->sources;
		usort($sorted_sources, function($a, $b) {
			return $b->height - $a->height;
		});
		foreach ($sorted_sources as $src) {
			$width = intval($src->width);
			$height = intval($src->height);
			if ($width <= $max_width && $height <= $max_height) {
				return $src->url;
			}
		}
	}

	protected function getInfo() {
		$access_token = $this->fb['app_id'] . '|' . $this->fb['app_secret'];
		$response = fb_graph_json($this->id, $access_token, 'name,images');
		$this->name = $response['name'];
		$images = $response['images'];

		foreach ($images as $img) {
			$this->sources[] = new PhotoSource($img['width'], $img['height'], $img['source']);
		}
	}

	protected function parseName() {
		$lines = explode("\n", str_replace("\r", '', $this->name));
		$this->price = $lines[0];
		unset($lines[0]);
		$this->title = $lines[1];
		unset($lines[1]);
		$this->desc = implode("\n", $lines);
	}
}

class PhotoSource {
	public $width;
	public $height;
	public $url;

	public function __construct($width, $height, $url) {
		$this->width = $width;
		$this->height = $height;
		$this->url = $url;
	}
}

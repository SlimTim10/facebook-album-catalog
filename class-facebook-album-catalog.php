<?php

class FacebookAlbumCatalog {
	public $fb;
	public $html = '';

	public function getAlbum($name) {
		$response = $this->fb->get('/me?fields=albums');
		$node = $response->getGraphNode();
		$albums = $node->getField('albums');

		// Find specified album
		foreach ($albums as $a) {
			if ($a['name'] == $name) {
				$album_id = $a['id'];
				break;
			}
		}

		if ($album_id == false) {
			$this->html = "<p>Album \"$name\" not found.</p>";
			return;
		}

		$this->html .= '<div class="catalog">' . "\n";
		
		$response = $this->fb->get("/$album_id?fields=photos");
		$node = $response->getGraphNode();
		$photos = $node->getField('photos');

		foreach ($photos as $p) {
			$album_photo = new Photo($p['id'], $this->fb);
			$full_img = $album_photo->sources[0]->url;
			$small_img = '';
			foreach ($album_photo->sources as $src) {
				if ($src->height == '225') {
					$small_img = $src->url;
					break;
				}
			}
			$this->html .= '<a href="' . $full_img . '">' . "\n";
			$this->html .= '<div class="catalog-box">' . "\n";
			$this->html .= '<span class="catalog-box-img" style="background-image: url(' . $small_img . ');"></span>' . "\n";
			// $this->html .= '<img src="' . $src->url . '" alt="' . $album_photo->title . '">';
			$this->html .= '</div>' . "\n";
			$this->html .= '</a>' . "\n";
			// $this->html .= '<pre>' . var_export($album_photo, true) . '</pre>'; // DEBUGGING
		}

		$this->html .= '</div>';
	}
}

class Photo {
	public $id;
	public $name;
	public $price;
	public $title;
	public $desc;
	public $sources = array();

	protected $fb;

	public function __construct($id, $fb) {
		$this->id = $id;
		$this->fb = $fb;
		
		$this->getInfo();
		$this->parseName();
	}

	protected function getInfo() {
		$response = $this->fb->get("/$this->id?fields=name,images");
		$node = $response->getGraphNode();
		$this->name = $node->getField('name');
		$images = $node->getField('images');

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
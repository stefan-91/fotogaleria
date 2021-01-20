<?php

class aktualizaciaRSS {

	private function createNewRSS($filenameRSS) {
		$dom = new DOMDocument('1.0');
		$dom->formatOutput = false;
		$dom->loadXML(
	'<?xml version="1.0"?>
	<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
		<channel>
			<title>Galéria na ukážku</title>
			<atom:link href="' . 'http://' . $_SERVER['HTTP_HOST'] . '/' . $filenameRSS . '" rel="self" type="application/rss+xml" />
			<link>http://' . $_SERVER['HTTP_HOST'] . '</link>
			<description>RSS channel</description>
		</channel>
	</rss>');
		$dom->save($filenameRSS);
	}
// subdom/fotogaleria/
	private function addRSSitem($filenameRSS, $picture_url, $gallery_url) {
		$g_galeria = $gallery_url;
		$dom = new DOMDocument;
//		$dom->preserveWhiteSpace = false; // aby doplnalo linefeed na konci riadkov
		$dom->formatOutput = true;
		if ($dom->load($filenameRSS) == true) {
			$channel = $dom->getElementsByTagName('channel')[0];
			// Najprv skontrolovat, kolko je <item>
			$items = $dom->getElementsByTagName('item');
			$len = $items->length;
			if ($len >= 5) { // ak je viac alebo rovne 5, najstarsie <item>'s vymazat
					  for ($i = 0; $i < ($len - 4); $i++) {
								 $items[$i]->parentNode->removeChild($items[$i]);
					  }
			}
			$pubDate = new DateTime();

			$item = $dom->createElement('item');

			$item->appendChild($dom->createElement('title',       $picture_url));
			$item->appendChild($dom->createElement('link',        'http://' .  $_SERVER['HTTP_HOST'] . "/" . urlencode($g_galeria) . '/' . $picture_url));
			$item->appendChild($dom->createElement('guid',        'http://' .  $_SERVER['HTTP_HOST'] . "/" . urlencode($g_galeria) . '/' . $picture_url));
			$item->appendChild($dom->createElement('pubDate',     $pubDate->format(DateTime::RSS)));
			$item->appendChild($dom->createElement('description', $picture_url));

			$channel->appendChild($item);

			$dom->save($filenameRSS);
		}
	}

	//Aktualizuje RSS. Vstupom je novy obrazok a prislusna galeria
	public function pridajRSS($obrazok, $galeria) {
		 $filenameRSS = "GaleriaRSS.xml";
		 if (!file_exists($filenameRSS)) {
					$this->createNewRSS($filenameRSS);
		 }
		 $this->addRSSitem($filenameRSS, $obrazok, $galeria);
	}
}

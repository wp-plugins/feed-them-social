<?php
class FTS_Pinterest_Feed extends feed_them_social_functions {
	
	function __construct() {
		add_shortcode( 'fts pinterest', array( $this, 'fts_pinterest_board_feed' ));
		add_action('wp_enqueue_scripts', array( $this, 'fts_pinterest_head'));
	}
	//**************************************************
	// Add Styles and Scripts functions
	//**************************************************
	function fts_pinterest_head() {
		wp_enqueue_style( 'fts_pinterest_css', plugins_url( 'pinterest/css/styles.css',  dirname(__FILE__) ) );
	    wp_enqueue_script( 'fts_pinterest_masonry_js', plugins_url( 'feed-them-social/feeds/instagram/js/masonry.pkgd.min.js' ), array( 'jquery' ) );
	    wp_enqueue_script( 'fts_pinterest_images_loaded', plugins_url( 'feed-them-social/js/imagesloaded.pkgd.min.js' ));
	}
	function fts_pinterest_board_feed($atts) {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		
	//Premium Plugin
	if(is_plugin_active('feed-them-premium/feed-them-premium.php')) {
	   include(WP_CONTENT_DIR.'/plugins/feed-them-premium/feeds/pinterest/pinterest-feed.php');
	}
	else 	{ 
		extract( shortcode_atts( array(
					'pinterest_name' => '',
					'board_id' => '',
					//type can equal 1 of 3 things; boards_list, single_board_pins, pins_from_user
					'type' => 'boards_list',
				), $atts ) );
				$pins_count = '6';
				$boards_count = '6';
	}
		ob_start();
		
		//Which Display Type
		switch ($type) {
			 case 'pins_from_user':
		        echo $this->getPins($pinterest_name,$board_id=NULL,$pins_count,$type);
		         break;
		     case 'single_board_pins':
		       	echo $this->getPins($pinterest_name,$board_id,$pins_count,$type);
		         break;
		     case 'boards_list':
		     default:
		        echo $this->getBoards($pinterest_name,$boards_count);
			   	 break;
		}
		
		return ob_get_clean();
	}
	//**************************************************
	// Get Pinterest Boards
	//**************************************************
	function getBoards($pinterest_name,$boards_count, $pins_count=NULL) {
		//Pinterest Boards Cache Folder
		$pin_cache_boards_url = WP_CONTENT_DIR.'/plugins/feed-them-social/feeds/pinterest/cache/pin-boards_list-'.$pinterest_name.'-bnum'.$boards_count.'.cache';
		//Pinterest Boards' Pins Cache Folder
		$pin_cache_boards_pins_url = WP_CONTENT_DIR.'/plugins/feed-them-social/feeds/pinterest/cache/pin-boards_list-'.$pinterest_name.'-bpnum'.$boards_count.'-pnum3.cache';
		
		//Get Boards
		if(file_exists($pin_cache_boards_url) && !filesize($pin_cache_boards_url) == 0 && filemtime($pin_cache_boards_url) > time() - 900 && false !== strpos($pin_cache_boards_url,'-bnum'.$boards_count)) {
			$boards_returned = $this->fts_get_feed_cache($pin_cache_boards_url);
		}
		else{
			$board_data['boards'] = 'http://pinterestapi.co.uk/'.$pinterest_name .'/boards';
			$boards_returned = $this->fts_get_feed_json($board_data);
			//Create Cache
			$this->fts_create_feed_cache($pin_cache_boards_url, $boards_returned );
		}
		$boards = json_decode($boards_returned['boards']);
		//Get Boards Pins
		if(file_exists($pin_cache_boards_pins_url) && !filesize($pin_cache_boards_pins_url) == 0 && filemtime($pin_cache_boards_pins_url) > time() - 900 && false !== strpos($pin_cache_boards_pins_url,'-bpnum'.$boards_count.'-pnum3')) {
			$pinfo = $this->fts_get_feed_cache($pin_cache_boards_pins_url);
		}
		else{
			$pinfo = $this->getPinsFromBoards($boards, $pinterest_name,$boards_count, $pins_count);
			//Create Cache
			$this->fts_create_feed_cache($pin_cache_boards_pins_url, $pinfo);
		}
		
		$output ='';
		$count = 0;
		$output ='<div class="fts-pinterest-wrapper">';
		//Setup Boards
		foreach ($boards->body as $key => $board) {
			if($count <= $boards_count) {
				$board_pinfo = isset($pinfo[$count.'pins']) ? json_decode($pinfo[$count.'pins']) : '';
				$pins = isset($board_pinfo->data->pins) ? $board_pinfo->data->pins : array();
				$board_pins_count = isset($board_pinfo->data->board->pin_count) ? '<div class="fts-pin-board-pin-count">'.$board_pinfo->data->board->pin_count.'</div>': '';
				$output .= '<a class="fts-pin-board-wrap" href="http://pinterest.com/'.$board->href.'" target="_blank">';
				$output .= '<h3 class="fts-pin-board-board_title"><span>'.$board->name.'</span></h3>';
				$output .= '<div class="fts-pin-board-img-wrap"><span class="hoverMask">'.$board_pins_count.'</span>';
				$output .=  '<img class="fts-pin-board-cover" src="'.$board->src.'"/>';
				$output .= '</div>';
				$output .= '<div class="fts-pin-board-thumbs-wrap">';
				//Get Thumbs for this Board
				$number_output = 0;
				foreach ($pins as $key => $pin) {
					$number_output++;
					$output .= '<div class="pinterest-single-thumb-wrap" style="background-image:url('.$pin->images->{'237x'}->url.');"><span class="hoverMask"></span></div>';
					if ($number_output > 2) break;
				}
				$output .= '</div>';
				$output .= '</a>';
			}
			$count++;
		}
		$output .= '<div class="clear"></div></div>';
		return  $output;
	}
	//**************************************************
	// Get Pins from Pinterest Boards
	//**************************************************
	function getPinsFromBoards($boards, $pinterest_name, $pins_count) {
		$pins_data = array();
		foreach ($boards->body as $key => $board) {
			// Check if the board is full url or just a single board name
			$board = !preg_match("/\/(.*)\/(.*)\//", $board->href) ? "/" . $pinterest_name . "/" . $board->href . "/": $board->href;
			// Create get request and put it in the cache
			$pins_data[$key.'pins'] = 'https://api.pinterest.com/v3/pidgets/boards'.$board.'pins/';
		}
		$pins_returned = $this->fts_get_feed_json($pins_data);
		return $pins_returned;
	}
	//**************************************************
	// Get Pins from Users/Single Board
	//**************************************************
	function getPins($pinterest_name, $board_id, $pins_count, $type) {
		 //Pinterest Pins Cache Folder
		$pin_cache_pins_url = WP_CONTENT_DIR.'/plugins/feed-them-social/feeds/pinterest/cache/pin-'.$type.'-'.$pinterest_name.(!empty($board_id) ? '-board'.$board_id : '').($type == 'single_board_pins' || $type == 'pins_from_user' ? '-pnum'.$pins_count : '-unum'.$pins_count).'.cache';

		//Get Boards Pins
		if(file_exists($pin_cache_pins_url) && !filesize($pin_cache_pins_url) == 0 && filemtime($pin_cache_pins_url) > time() - 900 && (false !== strpos($pin_cache_pins_url,'-pnum'.$pins_count) || false !== strpos($pin_cache_pins_url,'-unum'.$pins_count))) {
			$pins_returned = $this->fts_get_feed_cache($pin_cache_pins_url);
		}
		else{
			$single_board = isset($board_id) && !preg_match('/\/(.*)\/(.*)\//', $board_id) ? '/' . $pinterest_name . '/' . $board_id . '/' : '';
			//Get Boards
			$pins_data['pins'] = !isset($board_id) ? 'https://api.pinterest.com/v3/pidgets/users/'.$pinterest_name .'/pins/' : 'https://api.pinterest.com/v3/pidgets/boards'.$single_board.'pins/';
			$pins_returned = $this->fts_get_feed_json($pins_data);
			//Create Cache
			$this->fts_create_feed_cache($pin_cache_pins_url, $pins_returned);
		}
		
		$pins = json_decode($pins_returned['pins']);
	//	echo'<pre>';
	//	 print_r($pins);
	//	echo'</pre>'; 
	
		$output ='';
		$count = 1;
	//	$pins_count = 5;
		$fts_dynamic_class_name = "fts-pinterest-wrapper";
		$output ="<div class='fts-pinterest-wrapper fts-pins-wrapper masonry js-masonry' style='margin:0 auto' data-masonry-options='{\"itemSelector\": \".fts-single-pin-wrap\", \"isFitWidth\": true, \"transitionDuration\": 0 }'>";
		//Setup Boards  
		foreach ($pins->data->pins as $key => $pin) {
			if($count <= $pins_count) {
			//Pin Display
			$output .='<div class="fts-single-pin-wrap">';
			$output .= '<a class="fts-single-pin-link" href="http://pinterest.com/pin/'.$pin->id.'" target="_blank">';
			//Pin Main Image
			$output .= '<div class="fts-single-pin-img-wrap"><img class="fts-single-pin-cover" src="'.$pin->images->{'237x'}->url.'"/></div>';
			$output .= '</a>';
				//Pin Meta wrap
				$output .= '<div class="fts-single-pin-meta-wrap">';
						//Pin Description
						$output .= isset($pin->description) ? '<div class="fts-single-pin-description">'.$pin->description.'</div>': '';
						//Pinned To (Single Board view ONLY)
						$output .= isset($board_id) && !empty($pin->attribution) && !empty($pin->attribution->author_url) && !empty($pin->attribution->provider_icon_url) && !empty($pin->attribution->author_name) ? '<a class="fts-single-attribution-wrap" href="'.$pin->attribution->author_url.'" target="_blank"><img class="fts-single-pin-attribution-icon" src="'.$pin->attribution->provider_icon_url.'"/><div class="fts-single-pin-attribution-provider">by '.$pin->attribution->author_name.'</div></a>': '';
						//Pin Social Meta Wrap
						$output .= '<div class="fts-single-pin-social-meta-wrap">';
							//Pin Repin Count
							$output .= isset($pin->repin_count) ? '<span class="fts-single-pin-repin-count">'.$pin->repin_count.'</span>': '';
							//Pin Repin Count
							$output .= isset($pin->like_count) ? '<span class="fts-single-pin-like-count">'.$pin->like_count.'</span>': '';
						$output .= '</div>';
				$output .= '</div>';
			
				//Pinned To (User view ONLY)
				$output .= !isset($board_id) ? '<a class="fts-single-pin-pinned-to-wrap" href="http://pinterest.com'.$pin->board->url.'" target="_blank"><img class="fts-single-pin-pinned-to-img" src="'.$pin->board->image_thumbnail_url.'"/><div class="fts-single-pin-pinned-to-text">Pinned onto</div><div class="fts-single-pin-pinned-to-title">'.$pin->board->name.'</div></a>': '';
				
				
				$output .='</div>';	
			}
			$count++;
		}
		$output .= '</div><div class="clear"></div>';
		return  $output;
	}
}//END FTS_Pinterest_Feed
new FTS_Pinterest_Feed();
?>
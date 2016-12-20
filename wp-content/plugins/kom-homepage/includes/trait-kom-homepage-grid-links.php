<?php

trait Kom_Homepage_Grid_Links
{
	/**
     * Call this on the frontend
     * Builds the links and retuns the HTML
     * 
     * @return string 
     */
	public function show_grid_links() {

		$page = $_GET['page'];
		if(!empty($page)) {
			$page = $page;
		} else {
			$page = 1;
		}

		$grid_items = self::get_grid_posts_array(29, $page);

		// Sorting the array by date and id
		$sort = array();
		foreach($grid_items as $k=>$v) {
			$sort['date'][$k] = $v['date'];
			$sort['id'][$k] = $v['id'];
		}

		array_multisort($sort['date'], SORT_DESC, $sort['id'], SORT_DESC, $grid_items);

		$i = 1;

		foreach ($grid_items as $item) {

			if($i == 2) {

				// If first page show the ad else grab a block
				if($page == 1) { ?>

					<div class="grid-item-ad">
						<div class="ad-container clearfix">
							<div id="ad-rectangle-grid-1" style="width:300px; height:250px; margin:auto;">
								<script type='text/javascript'>
								googletag.cmd.push(function() { googletag.display('ad-rectangle-grid-1'); });
								</script>
							</div>
						</div>
					</div>

				<?php } else { 

					next_grid_block($page - 2);

				} 

				self::get_post_html($item);

			} elseif ($i == 3 || $i == 9 || $i == 13 || $i == 19 || $i == 23 || $i == 29) {

				self::get_post_html($item, true);
				
			} else {

				self::get_post_html($item);

			}

			$i++;
			if($i > 29) { break; }
		}

		if($page >= 2) {
			die();
		}
	}
}
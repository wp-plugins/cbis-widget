<?php
/*
Plugin Name: CBIS Widget
Plugin URI: http://wordpress.org/extend/plugins/cbis-widget/
Description: CBIS search widget.
Author: Jonas Hjalmarsson, Hultsfreds kommun
Version: 0.9.2
Author URI: http://www.hultsfred.se
*/

/*  Copyright 2013 Jonas Hjalmarsson (email: jonas.hjalmarsson@hultsfred.se)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/* 
 * CBIS WIDGET 
 */ 
 class hk_cbis_rss_widget extends WP_Widget {
	protected $vars = array();

	public function __construct() {
		parent::__construct(
	 		'hk_cbis_rss_widget', // Base ID
			'CBIS widget', // Name
			array( 'description' => "Widget showing available jobs from cbisrecruit." ) // Args
		);
	}

 	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {	$title = $instance[ 'title' ];
		} else { $title = ""; }
		if ( isset( $instance[ 'show_cbis' ] ) ) {	$show_cbis = $instance[ 'show_cbis' ];
		} else { $show_cbis = ""; }
		if ( isset( $instance[ 'src' ] ) ) {	$src = $instance[ 'src' ];
		} else { $src = ""; }

		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">Widget title</label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'show_cbis' ); ?>">Show only in category (in format 23,42,19)</label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'show_cbis' ); ?>" name="<?php echo $this->get_field_name( 'show_cbis' ); ?>" type="text" value="<?php echo esc_attr( $show_cbis); ?>" />
		</p>
		<p>
		<label title="http://www2.visithultsfred.se/sv/accommodationwidget/searchform" for="<?php echo $this->get_field_id( 'src' ); ?>">CBIS widget source</label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'src' ); ?>" name="<?php echo $this->get_field_name( 'src' ); ?>" type="text" value="<?php echo esc_attr( $src); ?>" />
		</p>

		<?php

	}

	public function update( $new_instance, $old_instance ) {
		$instance['show_cbis'] = strip_tags( $new_instance['show_cbis'] );
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['src'] = strip_tags( $new_instance['src'] );
		
		return $instance;
	}

	public function widget( $args, $instance ) {
	    extract( $args );
		
		// show widget if not empty and this category not is included
		if ($instance["src"] != "" && ( $instance["show_cbis"] == "" || in_array(get_query_var("cat"), split(",",$instance["show_cbis"])) ) ) {
			echo "<div id='cbiswidget' class='widget'><h3 class='widget-title'><span>" . $instance["title"] . "</span></h3>";
			?>
				<div id="citybreak_accommodation_searchform_widget" class="entry-wrapper"></div>
				<script type="text/javascript">
				//<![CDATA[ 
				(function () {
				var widget = document.createElement('script'); 
				widget.type = 'text/javascript'; widget.async = true;
				<?php 
					$src = $instance["src"];
					if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
						$src = str_replace("http://","https://"); 
					}
				?>
				widget.src = '<?php echo $src; ?>';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(widget, s);
				})();
				//]]>
				</script>

			<?php
			echo "</div>";
		}

	}
}
/* add the widget  */
add_action( 'widgets_init', create_function( '', 'register_widget( "hk_cbis_rss_widget" );' ) );

?>
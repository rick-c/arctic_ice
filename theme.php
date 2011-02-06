<?php 

/**
 * ArcticIce is a custom Theme class for the Arctic Ice theme.
 * 
 */ 

// We must tell Habari to use MyTheme as the custom theme class: 
define( 'THEME_CLASS', 'ArcticIceTheme' );

/**
 * A custom theme for Arctic Ice output
 */ 
class ArcticIceTheme extends Theme
{
	public function action_init_theme( $theme )
	{
		// Apply Format::autop() to post content...
		Format::apply( 'autop', 'post_content_out' );
		// Apply Format::autop() to comment content...
		Format::apply( 'autop', 'comment_content_out' );
		// Apply Format::tag_and_list() to post tags...
		Format::apply( 'tag_and_list', 'post_tags_out' );
		// Only uses the <!--more--> tag, with the 'more' as the link to full post
		Format::apply_with_hook_params( 'more', 'post_content_out', 'more' );
		// Creates an excerpt option. echo $post->content_excerpt;
		Format::apply( 'autop', 'post_content_excerpt');
		Format::apply_with_hook_params( 'more', 'post_content_excerpt', ' Read more...', 60, 1 );

		// Apply Format::nice_date() to post date...
		Format::apply( 'nice_date', 'post_pubdate_out', 'F j, Y' );
		Format::apply( 'nice_date', 'post_pubdate_datetime', 'c');
		// Apply Format::nice_time() to post date...
		//Format::apply( 'nice_time', 'post_pubdate_out', 'g:ia' );
		// Apply Format::nice_date() to comment date
		Format::apply( 'nice_date', 'comment_date_out', 'F j, Y g:ia');
	}

	/**
	 * Add additional template variables to the template output.
	 * 
	 * This function gets executed *after* regular data is assigned to the
	 * template.  So the values here, unless checked, will overwrite any existing 
	 * values.
	 */
	public function action_add_template_vars( $theme, $handler_vars )
	{
		if( !$this->template_engine->assigned( 'pages' ) ) {
			$this->assign( 'pages', Posts::get( array( 'content_type' => 'page', 'status' => 'published', 'vocabulary' => array( 'tags:not:tag' => 'site-policy' ), 'nolimit' => 1 ) ) );
		}
		if( !$this->template_engine->assigned( 'recent_comments' ) ) {
			//for recent comments loop in sidebar.php
			$this->assign( 'recent_comments', Comments::get( array( 'limit'=>5, 'status'=>Comment::STATUS_APPROVED, 'orderby'=>'date DESC' ) ) );
		}
		if( !$this->template_engine->assigned( 'more_posts' ) ) {
			//Recent posts in sidebar.php
			//visiting page/2 will offset to the next page of posts in the footer /3 etc
			$page = $this->page;
			$pagination = Options::get( 'pagination' );
			$this->assign( 'more_posts', Posts::get( array ( 'content_type' => 'entry', 'status' => 'published', 'vocabulary' => array( 'tags:not:tag' => 'asides' ),'offset' => ( $pagination )* ( $page ), 'limit' => 5 ) ) );
		}
		if( !$this->template_engine->assigned( 'all_tags' ) ) {
			// List of all the tags
			$this->assign( 'all_tags', Tags::vocabulary()->get_tree() );
		}
		if( !$this->template_engine->assigned( 'all_entries' ) ) {
			$this->assign( 'all_entries', Posts::get( array( 'content_type' => 'entry', 'status' => 'published', 'nolimit' => 1 ) ) );
		}
	}

	public function action_template_header( $theme )
	{
		// Add the stylesheets to the stack for output
		Stack::add( 'template_stylesheet', array( Site::get_url( 'theme') . '/style.css', 'screen') );
		Stack::add( 'template_stylesheet', array( Site::get_url( 'theme') . '/custom.css', 'screen') );
		Stack::add( 'template_stylesheet', array( Site::get_url( 'theme') . '/print.css', 'print') );
	}

	public function theme_title( $theme )
	{
		if( $theme->request->display_entry || $theme->request->display_page && isset( $theme->post ) ) {
			$out = $theme->post->title;
		}
		else if( $theme->request->display_entries_by_tag && isset( $theme->posts ) ) {
			$out = $theme->tag;
		}
		else {
			$out = Options::get( 'title' );
		}
		return $out;
	}

	public function theme_next_post_link( $theme )
	{
		$next_link = '';
		if( isset( $theme->post ) ) {
			$next_post = $theme->post->ascend();
			if( ( $next_post instanceOf Post ) ) {
				$next_link = '<a href="' . $next_post->permalink. '" title="' . $next_post->title .'" >' . '&laquo; ' .$next_post->title . '</a>';
			}
		}

		return $next_link;
	}

	public function theme_prev_post_link( $theme )
	{
		$prev_link = '';

		if( isset( $theme->post ) ) {
		$prev_post = $theme->post->descend();
		if( ( $prev_post instanceOf Post) ) {
			$prev_link= '<a href="' . $prev_post->permalink. '" title="' . $prev_post->title .'" >' . $prev_post->title . ' &raquo;' . '</a>';
		}
		}
		return $prev_link;
	}

	public function theme_commenter_link( $comment )
	{
		$link = '';
		if( strlen( $comment->url ) && $comment->url != 'http://' ) {
			$link = '<a href="' . $comment->url . '" >' . $comment->name . '</a>';
		}

		return $link;
	}

	public function theme_feed_site( $theme )
	{
		return URL::get( 'atom_feed', array( 'index' => '1' ) );
	}

	public function theme_comment_form( $theme )
	{
		$theme->post->comment_form()->out();
	}

	public function action_form_comment( $form, $post, $context )
	{
		$form->cf_content->cols = 57;
		$form->cf_commenter->size = 50;
		$form->cf_email->size = 50;
		$form->cf_url->size = 50;
	}
}

?>

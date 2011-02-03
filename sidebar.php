<!--begin secondary content-->
<div id="secondary">
	<aside>
	<h3>{hi:"Subscribe"}</h3>
	<ul class="rss">
		<li><a href="{hi:@feed_site}">{hi:"Subscribe to the Feed"}</a></li>
	</ul>
	</aside>

	{hi:@sidebar_top}

	{hi:?count(more_posts)}
	<aside>
		<h3>{hi:"More Posts"}</h3>
		<ul class="moreposts">
			{hi:more_posts}
				<li><a href="{hi:permalink}" >{hi:title_out}</a></li>
			{/hi:more_posts}
		</ul>
	</aside>
	{/hi:?}

	{hi:?count(recent_comments)}
	<aside>
		<h3>{hi:"Recent Comments"}</h3>
		<ul class="recentcomments">
		{hi:recent_comments}
			<li>
				<a href="{hi:url}">{hi:name}</a>
				 <?php _e( 'on' ); ?> <a href="{hi:post.permalink}">{hi:post.title}</a>
			</li>
		{/hi:recent_comments}
		</ul>
	</aside>
	{/hi:?}

	{hi:?count(all_tags)}
	<aside>
		<h3>{hi:"Tags"}</h3>
		<ul class="tags">
			{hi:all_tags}
				<li><a href="{hi:siteurl:habari}/tag/{hi:term}/" rel="tag" title="{hi:term_display}" >{hi:term_display}</a></li>
			{/hi:all_tags}
		</ul>
	</aside>
	{/hi:?}

	{hi:@sidebar}
	{hi:@sidebar_bottom}
</div><!--end secondary content-->
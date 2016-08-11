<?php
define ("RSSHEAD", '<link rel="alternate" type="application/rss+xml" title="'.$postsperpage['site_name'].'" href="https://'.$_SERVER['HTTP_HOST'].'/feed" />');
$global = new DB_global;
$retrive = new DB_retrival;
$postsperpageinit = $global->sqlquery("SELECT * FROM dd_settings;");
$postsperpage = $postsperpageinit->fetch_assoc();
$ppp = $postsperpage['postsperpage'];

if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; }; 
$start_from = ($page-1) * $ppp; 

$result = $global->sqlquery("SELECT * FROM dd_content ORDER BY content_date DESC LIMIT $start_from, $ppp;");
$result2 = $global->sqlquery("SELECT COUNT(*) FROM dd_content");
$row2 = $result2->fetch_row(); 
$total_records = $row2[0];
$total_pages = ceil($total_records / $ppp);

$check = new DB_check;
pluginClass::hook( "content_top" );
echo '<div class="contentpostscroll">';
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
		$date=date_create($row['content_date']);
		// Comments
		echo '<div class="contentcomment"><a class="contentcomment" href="'; echo $row['content_permalink']; echo '#comments" title="Comment & share!" alt="Comment & share!">Comments (';echo $check->retrieve_comment_count($row['content_id']); echo')'; echo'<a/></div>';
		// Title
        echo '<a href="';echo $row['content_permalink']; echo '" class="contenttitle" title="';echo $row['content_title']; echo '" alt="';echo $row['content_title']; echo '"><div class="contenttitle">';echo $row['content_title']; echo '</div></a>';
		// Date
		echo '<div class="contentdate">Posted on '.date_format($date, $postsperpage['date_format']." ".$postsperpage['time_format']).' by '.$retrive->realname($row['content_author']).'</div>';
		// Post
		echo '<div class="contentpost">'; echo $row['content_embedcode'];
		echo '<br />';
		echo $row['content_summary']; echo '</div>';
		// Category
		echo '<div class="contentcategory">Categorized under: <a href="/category?name=';
		$catlowcase = strtolower($row['content_category']);
		echo str_replace(" ", "_", $catlowcase);
		echo '" alt="'; echo $row['content_category']; echo '" title="'; echo $row['content_category']; echo'">'; echo $row['content_category']; echo '</div></a>';
		// Tags
		echo '<div class="contenttags">Tags: ';
		$tags = explode (", ", $row['content_tags']);
		foreach ($tags as $tag) {
			echo '<a href="/tag?name=';
		$taglowcase = strtolower($tag);
		echo str_replace(" ", "_", $taglowcase);
		echo '" alt="'; echo $tag; echo '" title="'; echo $tag; echo'">'; echo $tag; echo '</a> ';
		}
		echo '</div>';
    }
} else {
    echo "This blog currently has no posts.";
}

echo pagebar($page, $total_pages, $ppp, '5');
		echo '</div>';
<?php
$retrive = new DB_retrival;
if ($retrive->isLoggedIn() == true){
echo consolemenu();
echo "<div id='page'>";
echo '<div class="center">Posts | <a href="/console/posts/drafts/" title="Drafts" alt="Drafts">Drafts</a></div><br />';
echo "<div class='center'>There are "; echo number_format($retrive->numberofposts()); echo " posts on this blog.</div>";
if (!$retrive->restrictpermissionlevel('2')){
echo "<a class='nounderline' href='/console/posts/new' alt='Create New Post' title='Create New Post'><div class='createnewpost'>Create New Post</div></a>";}
else {}

$global = new DB_global;

		$ss1 = $global->sqlquery("SELECT * FROM dd_settings");
		$ss2 = $ss1->fetch_assoc();

$ppp = $ss2['postsperpage'];

if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; }; 
$start_from = ($page-1) * $ppp; 

$result = $global->sqlquery("SELECT * FROM dd_content ORDER BY content_id DESC LIMIT $start_from, $ppp;;");
$result2 = $global->sqlquery("SELECT COUNT(*) FROM dd_content");
$row2 = $result2->fetch_row(); 
$total_records = $row2[0];
$total_pages = ceil($total_records / $ppp);
	if ($page == '1'){
	$count = $page;
	}
	else {
	$count = $page + $ppp - '1';
	}
echo dbsearchbar();
echo '<div class="contentpostscroll">';
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
	echo '<div class="postbox">';
	echo '<div class="postoptions">';
if ($retrive->restrictpermissionlevel('2')){
} else {
echo '<a href="/console/posts/delete?postid='.$row['content_id'].'" title="Delete" alt="Delete">Delete</a> | <a href="/console/posts/edit?postid='.$row['content_id'].'" title="Edit" alt="Edit">Edit</a> | ';}
echo '<a href="/console/posts/comments?postid='.$row['content_id'].'"  title="Comments" alt="Comments">Comments (';echo retrieve_comment_count($row['content_id']); echo')'; echo'</a>';
	echo '</div>';
	echo '<div class="postnumber">';
	echo $count;
	echo '</div>';
	echo '<div class="postinfobox">';
	echo '<div class="posttitle">';
	echo '<a href="/'.$row['content_permalink'].'" alt="View post '.$count.'", title="View post '.$count.'">'.$row['content_title'].'</a>';
	echo '</div>';
	echo '<div class="postdate">Posted on '.$row['content_date'].'.</div>';
	echo '<div class="postcategory">Category: '.$row['content_category'].'</div>';
	echo '<div class="posttags">Tags: '.$row['content_tags'].'</div>';
	echo '</div>';
	echo '</div>';
	$count++;
	}
}
echo pagebar($page, $total_pages, $ppp, '5');
		echo '</div>';
		}else {
	header('Location: https://'.$_SERVER['HTTP_HOST'].'/console');
}
?>

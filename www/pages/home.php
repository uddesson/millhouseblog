<?php
require 'parts/database.php';
require 'parts/functions.php';
require 'parts/fetchposts.php';

 
//if statement checking if there is a session message (parts/deletepost.php)
//if true, display message
display_notification();
?>

<!-- FEED CONTAINER - WRAPS ENTIRE HOME, POSTS AND SIDEBAR -->
<div class="container-fluid feed_container"> 
    <span class="uppercase">   
        <h1 class="light_spacious">Senaste inläggen</h1>
    </span>

    <div class="row">
        <div class="five_latest_posts_container col-12 col-md-10 offset-md-1 col-lg-8 offset-lg-1">
        <?php


        
        $query = "";    
        if(isset($_GET['query'])) {
            $q=$_GET['query'];
            $query = "WHERE title like '%$q%'";
        }    


        //PAGINATION
        //var lägga denna function? 
        //PAGINATION.PHP?
        function get_page_number(){
            //if a page number has been selected, get that value
            if(isset($_GET['pagination_page']))
                {
                    //store it in $page_number
                    $page_number = $_GET['pagination_page'];
                }
            else
                {
                    //else, user landed on home-page and page is 1
                    $page_number = 1;
                }        
                return $page_number;
            }

        $page_number= get_page_number();
        
        //set $limit as the number of posts to show per page
        $limit = 5;
        
        //start limit(=which post to start to get from database) is set by the page number and the $limit of the posts to show
        $start_limit = ($page_number - 1) * $limit;  
        //fetch 5 latest posts, from $start_limit to start_limit + number set as limit, depending on which page youre on, using pagination.
        $posts = fetch_posts_from_start_to_limit($start_limit, $limit);

        $keys = array_keys($posts);

        //Looping out 5 posts, starting from the latest posts.
        //Information about the author of the post=user.
        //How many comments there is on each post. 
        //Link to each specific post
        for($i=0; $i<count($posts); $i++):
        
        //check if the index $i is less than the total number of posts
        if ($i < count($posts)):
     
            //storing user_id to get to get user_name from user-table
            $user_id = $posts[$keys[$i]]['userid'];
            //storing post_id for the link to view the specific post
            //storing post_id to count how many comments it has, stored in number_of_comments
            $post_id = $posts[$keys[$i]]['postid'];
            //storing category_id to get the category_name from category table
            $category_id = $posts[$keys[$i]]['categoryid'];
    

            //FUNCTIONS is in functions.php
            $category_name = get_column_with_input('name', 'category', 'categoryid', $category_id);
            $username = get_column_with_input('username', 'user', 'userid', $user_id);
            $user_email = get_column_with_input('email', 'user', 'userid', $user_id);
            $date = $posts[$keys[$i]]['date'];
            $dt = new datetime($date); 
            $image = $posts[$keys[$i]]['image'];
            $title = $posts[$keys[$i]]['title'];

            //if post-text is longer than 120ch, shorten it
            $post_text = make_string_shorter($posts[$keys[$i]]['text'], 150);
                
                //if title-text is longer than 30ch, shorten it
            $post_title = make_string_shorter($posts[$keys[$i]]['title'], 30);

            //count comments of this post
            $number_of_comments = count_comments($post_id);
            
            //LOOPING OUT THE CONTENT OF THE POSTS:
            ?>  
            <article class="single_post_in_feed">
                <div class="row">
                    <div class="thumbnail_wrapper col-md-4">
                        <div class="thumbnail">
                            <a href="/millhouseblog/www/?page=viewpost&id=<?=$post_id?>">
                            <img src="/millhouseblog/www/postimages/<?=$image?>" 
                            class="post_image" alt="<?=$title;?>"></a>
                        </div>
                    </div>

                    <div class="post_content col-md-8">
                        <header>  
                            <span class="uppercase grey"><?=$category_name?></span>
                            <!--<meta>kategorierna som meta???-->
                            <h2 class=”postheading”><a href="/millhouseblog/www/?page=viewpost&id=<?=$post_id?>">
                            <?=$post_title;?></a></h2>
                            <span class="grey">
                                Publicerat 
                            <time>
                                <?= $dt->format('Y-m-d'); ?>
                            </time>
                            av
                            </span>
                            <span class="uppercase grey"><?= $username?></span>
                        </header>

                        <p id="post_paragraph"><?=$post_text?></p>
                        <nav>
                            <a href="/millhouseblog/www/?page=viewpost&id=<?=$post_id?>">
                            Läs hela inlägget</a>
                            <span class=lightblue>|</span> 
                            <a href="/millhouseblog/www/?page=viewpost&id=<?= $post_id ?>#comments">
                            <?= '(' . $number_of_comments . ')'; 
                            if($number_of_comments == 1)
                                {
                                    echo ' kommentar'; 
                                } 
                            else
                                {
                                    echo ' kommentarer';
                                } ?> 
                            </a>
                        </nav>
                    </div> <!-- Closing post-content column -->
                </div> <!-- Closing row for post-->
            </article> <!-- Closing article (works as wrapper for post-row) -->
        <?php endif; ?>
        <?php endfor; ?>  <!-- Ends loop -->
        
        
       <?php  
        
        /* Message if there is no posts in selected month */

        if (empty($posts)){
            echo 
                '<div class="no_post">' . 
                'Tyvärr finns det inga inlägg på den här sidan än...' . '<br>' .
                '<a href="/millhouseblog/www/?page=createpost">' . 'Klicka här för att bli först med att skriva ett inlägg' . '</a>' .
                '</div>';
        } ?>

    </div> <!-- Closing five latests posts container -->
    
    <!-- Sidebar -->
    <div class="col-lg-2 d-none d-md-block sidebar">
        <?php require 'components/sidebar.php'; ?>
    </div> 
    

    <!-- Pagination links-->
    <?php
    //diving the total number of posts in db with the limit of posts per page to get total number of pages.
    //using ceil so if its fex 6.5 its going to be 7 pages

    $total_pages = ceil($number_of_posts_in_db["count"] / $limit);
    ?>
    <div class="col-8 offset-md-1 pagination_container">
        <nav>
            <ul class="pagination">
            <?php 
                    //settig the values for start_page and end_page when looping out pagination links
                

                    //if there is only 1 page, set start_page to 1 to make sure it wont loop out -1
                    //and set end_page to 1 so it wont loop out more pages than it is
                    if($total_pages == 1){
                            $start_page = 1;
                            $end_page = 1;
                        }
                        //do the same if there is only 2 pages, but set end_page=2
                        elseif ($total_pages == 2)
                            {
                            $start_page = 1;
                            $end_page = 2;
                            }
                            //if age_number is 1 and there is at least 3 pages, set start_page to 1
                            //and end page to 3
                            elseif($page_number == 1)
                                    {
                                    $start_page = 1;
                                    $end_page = 3;
                                    } 
                                    //if youre on the last page, set start_page to -2 and end_page as the total_pages
                                    elseif($page_number == $total_pages)
                                            {
                                            $start_page = $page_number - 2; 
                                            $end_page = $total_pages; 
                                            }
                        //else start_page should be -1 and end_page +1 :)                    
                        else
                            {
                            $start_page = $page_number -1;
                            $end_page = $page_number + 1;
                            }
                    
             //looping out page links with id of each page.
            for ($i=$start_page; $i<=$end_page; $i++):
                //if index==page_number set class=active to show that thatss the page user is on
                if($i == $page_number)
                    {?>
                    <li class="page-item active">
                        <a class="page-link" href="/millhouseblog/www/?page=home&pagination_page=<?=$i?>"><?=$i?><span class="sr-only">(current)</span></a>
                    </li>
                    <?php
                    }   
                    //else loop out "regular" page link
                    else 
                        {?>
                        <li class="page-item">
                            <a class="page-link" href="/millhouseblog/www/?page=home&pagination_page=<?=$i?>"><?=$i?></a>
                        </li>
                        <?php
                        }
            endfor; ?>
            </ul>
        </nav> 
    </div>
</div><!-- Closing entire feed container-->
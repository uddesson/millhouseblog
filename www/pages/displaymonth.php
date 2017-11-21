<? require 'parts/database.php';
   require 'parts/functions.php';

    $month = $_GET["month"];
    $number_of_comments = count_comments($month);

$statement = $pdo->prepare(
"SELECT * FROM post"
);

 $statement->execute(array(
        ":month" => $month
    ));

$months = $statement->fetchAll(PDO::FETCH_ASSOC);

//var_dump($months); 

?>

<div class="container landingpage">

<h1 class="heading"> MÅNAD </h1>

<div class="row">
    <div class="col-lg-9">

<? foreach($months as $monthpost){ 
        $userid = $monthpost["userid"];
        $username = get_row_with_input("username", "user", "userid", $userid);
        ?>

<article class="post">
    
    <header>
        
        <span class="uppercase grey">
            <?= $monthpost["category_name"] ?>
        </span>
        <h2 class=”postheading”> 
            <?= $monthpost["title"] ?>
        </h2>
        <time>
            <?= $monthpost["date"] ?>
        </time>
        
        <span class="uppercase grey"> <?= $username ?></span>
        
        <a href="/millhouseblog/www/?page=viewpost&id=<?= $monthpost["postid"]; ?>#comments"><!--#comments anchor-->
        
        <?= '(' . $number_of_comments . ')'; 
        
        if($number_of_comments == 1){
            echo ' kommentar'; } else{
            echo ' kommentarer';
        } ?> 
        </a>
       
       <p> <?= $monthpost["text"] ?> </p>
       <nav class=””>
            <a href="/millhouseblog/www/?page=viewpost&id=<?= $monthpost["postid"]; ?>">Läs hela inlägget</a>
        </nav> 
        
        
        
    </header>
    
</article>


<? } 


if (empty($monthpost)){
    echo 'There are no posts in this month.';
}
        
?>
        
</div><!--/col-md-8-->

<div class="col-lg-3 sidebar hidden-xs-down">
        <?php
        require 'components/sidebar.php';
        ?>
    </div><!--/sidebar-->
    
</div><!--/col-md-8-->
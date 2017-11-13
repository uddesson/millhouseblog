<?php
        /*------     IF ISSET = IF USER IS LOGGED IN     ------*/

        if(isset($_SESSION["user"])){

            echo '<h1>' . "Welcome " . $_SESSION["user"]["username"] . '!' . '</h1>';
            echo '<h2>' . "THIS IS THE HOMEPAGE" . '</h2>';
        
    /*------     EVERYTHING THAT HAPPENS BEFORE ELSE, IS IF USER LOGGED IN    ------*/
    
        } else {
        
            /*------     LOG OUT MESSAGE BELOW, AND LOGIN-FORM IF NOT LOGGED IN     ------*/

            if(isset($_GET['logout'])){
                echo $_GET['logout'];
            }
            
        require 'pages/loginform.php';
        
        }
    
//FETCH POSTS FROM 
//PUT THIS IN PARTS...? AS FETCH_POST T.EX..
require 'parts/database.php';
$statement = $pdo->prepare("SELECT * FROM post ORDER BY 'date' DESC");
  $statement->execute();
  $post = $statement->fetchAll(PDO::FETCH_ASSOC);
  $keys = array_keys($post);
?>

  <h1>Senaste blogginläggen</h1>

<?php
//LOOPING OUT THE POSTS THROUGH $post
//hur ska man göra ifall det endast skulle finnas mindre än 5 inlägg inte skrivs ut felmeddelande "unknown offset..."
  for($i=0; $i<5; $i++){
      //spara $user_id. loopa igenom user tabell och hämta ut name FROM user där $userid == $userid och lagra i $user_name.
      //join userid och postid. lagra i array 
      //$number_of_comments = count() på arrayen comments som man hämta ut förra.
      ?>
      <article class="">
      <header class=””>
          <!--<meta>kategorierna som meta???-->
          <h2 class=””><?=$post[$keys[$i]]['title'];?></h2>
          <time class=""><?=$post[$keys[$i]]['date'];?></time> 
          <span>Categories</span>
          <span class="">$number_of_comments</span> 
          <span class="">$user_name</span>
      </header>
      <p class=””><?=$post[$keys[$i]]['text'];?></p>
      <footer class=””>
      <nav class=””><a href="">Läs hela inlägget.. skicka värde postid?</a></nav>
      </footer>
          <article class=””>(comment)</article>    
  </article>
  --------------< hr >--------------
  <?php } ?>


<!--Blogpost skeleton to be looped out
<article class="">
<header class=””>
<meta kategorierna som meta???
<h2 class=””>titel</h2>
<time class="">date</time> 
<span>Categories</span>
<span class="">number comments</span> 
<span class="">user</span>
</header>
<p class=””>post text häär</p>
<footer class=””>
<nav class=””>link to more comments</nav>
</footer>
<article class=””>(comment)</article>    
</article>
-->
<br>SIDEBAR(components) GOES HERE
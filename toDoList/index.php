<?php
session_start();
@include('query.php');
?>
<!doctype html>
<html lang="ru">
<head>    
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>list</title>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.3/css/uikit.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.3/js/uikit.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.3/js/uikit-icons.min.js"></script>

 </head>
 <body>


<style>
	
:active, :hover, :focus {
  outline: none !important;
  box-shadow: none !important;
}

</style>



<input class="uk-search-input" type="search" placeholder="">


<div style="width:600px; margin:auto; padding:30px; border: 0px solid black;">


	<form method="POST" action=" " autocomplete="off" enctype="multipart/form-data" >
    	<div  class="uk-container" style="padding:0px; margin-bottom:20px;">
			<input class="uk-search-input" type="search" style="  padding-right:15px; float:left; height:40px; width:450px;" 
			name="task" placeholder="Добавьте заметку...">
			<button style=" border-radius:3px; float:right;" class="uk-button uk-button-primary"  name="one">Добавить</button>
		</div>
	</form>	

		<hr >

		<form method="GET" action=" " autocomplete="off" enctype="multipart/form-data" >
		<ul class="uk-list uk-list-striped">

			<?php while($row = $result->fetch_assoc()) { ?>

       		<li ><?= $row["name_task"]?>	
       		<!-- <button name="trash" class="uk-align-right" style="outline: none; border: 0; background: transparent;"> -->
  			<a href="?q=<?= $row["id"]?>"><span  uk-icon="trash"  class="uk-align-right" style="cursor:pointer; "></span></a>
			<!-- </button> -->
			</li>
			
    		<?php } ?>
		</ul>
		</form>	


</div>




<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.3/js/uikit.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.3/js/uikit-icons.min.js"></script>
</body>
</html>
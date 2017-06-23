<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
</head>
<body>
	<h3 class="header">Manga</h3>
	<br>
	<div class="container">
	<?php
			$colCounter = 0;
			foreach ($manga as $value) {
				$reformattedMangaName = str_replace(':','',str_replace('\'','',$value['name'])); //remove ":" and "'"
				if ($colCounter==0) {
					echo '<div class="row">';	
					echo '<div class="col-sm-3 Product-item">';
					echo '<img class="Image-item" src="'.base_url('assets/images/manga/'.$reformattedMangaName.'.jpg').'">';
					echo '<br><div class="Product-item-titleOnly">'.$value['name'].'</div>';
					echo '<label class="Product-item-details">';

					if ($value['price']!=null) {
						echo '<label class="Product-item-price">₱'.$value['price'].'</label>';
						echo '</label>';
					}
					else {
						echo '<br><label class="Product-item-price">out of stock</label>';
						echo '</label>';
					}
					echo '</div>';
					$colCounter++;				
				}
				else if ($colCounter<4){
					echo '<div class="col-sm-3 Product-item">';
					echo '<img class="Image-item" src="'.base_url('assets/images/manga/'.$reformattedMangaName.'.jpg').'">';
					echo '<br><div class="Product-item-titleOnly">'.$value['name'].'</div>';
					echo '<label class="Product-item-details">';
					
					if ($value['price']!=null) {
						echo '<label class="Product-item-price">₱'.$value['price'].'</label>';
						echo '</label>';
					}
					else {
						echo '<br><label class="Product-item-price">out of stock</label>';
						echo '</label>';
					}
					echo '</div>';					
					$colCounter++;
				}
				else {
					echo '</div>';
					$colCounter=0;
				}
			}
			echo '</div>';			
	?>
	</div>
</body>
</html>

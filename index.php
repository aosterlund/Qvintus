<?php
  // include "includes/header.php";
  require_once "includes/db.php"; // Your PDO connection

?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="css/style.css">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <script src="js/script.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>framsida</title>
</head>
<body>


 <?php echo "<div style='height: 1200px; width:full-width ;'>
        <div class='text-center' >
    <label class='margin 0 auto' for='look-up-txt';'>Vad letar du efter?</label><br>
    <input type='text' class='look-up-txt ' name='look-up-txt'>
    </div>
    </div>"
?>
 <textarea> knkregkresgjlsegkslegrejsl</textarea>
 
<div id="surrounding-div">
<H2 style='text-align:center;'>Sällsynt och värdefullt</h2>
 <?php 
 $datafromdbksl = $db; 
 

 foreach ()echo"<div>

<div class='container-fluid text-center'>
    <div class='row'>
        <div class='col'>
<div class='card' style='width: 18rem;'>
  <img src='...' class='card-img-top' alt='...'>
  <div class='image-separator'>
  <div class='card-body'>
    <h5 class='card-title'>Card title</h5>
    <p class='card-text'>Some quick example text to build on the card title and make up the bulk of the \card’s\ content.</p>
    <a href='#' class='btn btn-primary'>Go somewhere</a>
  </div>
</div>
    </div>
        </div>"?>

        <div class='col'>
<div class='card' style='width: 18rem;'>
  <img src='...' class='card-img-top' alt='...'>
  <div class='image-separator'>
  <div class='card-body'>
    <h5 class='card-title'>Card title</h5>
    <p class='card-text'>Some quick example text to build on the card title and make up the bulk of the \card’s\ content.</p>
    <a href='#' class='btn btn-primary'>Go somewhere</a>
  </div>
</div>
    </div>
        </div>
        <div class='col'>
<div class='card' style='width: 18rem;'>
  <img src='...' class='card-img-top' alt='...'>
  <div class='image-separator'>
  <div class='card-body'>
    <h5 class='card-title'>Card title</h5>
    <p class='card-text'>Some quick example text to build on the card title and make up the bulk of the \card’s\ content.</p>
    <a href='#' class='btn btn-primary'>Go somewhere</a>
  </div>
</div>
    </div>
        </div>
        <div class='col'>
<div class='card' style='width: 18rem;'>
  <img src='...' class='card-img-top' alt='...'>
  <div class='image-separator'>
  <div class='card-body'>
    <h5 class='card-title'>Card title</h5>
    <p class='card-text'>Some quick example text to build on the card title and make up the bulk of the \card’s\ content.</p>
    <a href='#' class='btn btn-primary'>Go somewhere</a>
  </div>
</div>
    </div>
        </div>
</div>
  </div>
</div>"?>

<?php echo"<div>
    <h2 style='text-align:center;'>Populära genrer</h2>
<div class='container-fluid'>
    <div class='row'>
        <div class='col'>
    <div class='card' style='width: 18rem; height:210px;'>
  <img src='...' class='card-img-top' alt='...'>
  <div class='card-body'>
    <h5 class='card-header'>ANTIK</h5>
  </div>
</div>
        </div>
        <div class='col'>
<div class='card' style='width: 18rem; height:210px;'>
  <img src='...' class='card-img-top' alt='...'>
  <div class='card-body'>
    <h5 class='card-header'>ANTIK</h5>
  </div>
</div>
        </div>
        <div class='col'>

<div class='card' style='width: 18rem; height:210px;'>
  <img src='...' class='card-img-top' alt='...'>
  <div class='card-body'>
    <h5 class='card-header'>ANTIK</h5>
  </div>
</div>
        </div>
        <div class='col'>
<div class='card' style='width: 18rem; height:210px;'>
  <img src='downloads/pen-28702_1280.png' class='card-img-top' alt='...'>
  <div class='card-body'>
    <h5 class='card-header'>ANTIK</h5>
  </div>
</div>
        </div>
    </div>
</div>
</div>

<div>
    <h2 style='text-align:center;'>Populärt just nu</h2>
<div class='container-fluid'>
  <div class='row'>
    <div class='col'>
    <div class='card' style='width: 120px; height:140px; padding:auto; margin:10px;'>
    <img src='' class='card-img-top' alt='...'>
    <div class='card-body'>
        <h5 class='card-header'>book</h5>
    </div>
</div>
</div>
    <div class='col'>
    <div class='card' style='width: 120px; height:140px; padding:auto; margin:10px;'>
    <img src='' class='card-img-top' alt='...'>
    <div class='card-body'>
        <h5 class='card-header'>book</h5>
    </div>
</div>
</div>
    <div class='col'>
    <div class='card' style='width: 120px; height:140px; padding:auto; margin:10px;'>
    <img src='' class='card-img-top' alt='...'>
    <div class='card-body'>
        <h5 class='card-header'>book</h5>
    </div>
</div>
</div>
    <div class='col'>
    <div class='card' style='width: 120px; height:140px; padding:auto; margin:10px;'>
    <img src='' class='card-img-top' alt='...'>
    <div class='card-body'>
        <h5 class='card-header'>book</h5>
    </div>
</div>
</div>

    <div class='col'>
    <div class='card' style='width: 120px; height:140px; padding:auto; margin:10px;'>
    <img src='' class='card-img-top' alt='...'>
    <div class='card-body'>
        <h5 class='card-header'>book</h5>
    </div>
</div>
</div>
    <div class='col'>
    <div class='card' style='width: 120px; height:140px; padding:auto; margin:10px;'>
    <img src='' class='card-img-top' alt='...'>
    <div class='card-body'>
        <h5 class='card-header'>book</h5>
    </div>
</div>
</div>
    <div class='col'>
    <div class='card' style='width: 120px; height:140px; padding:auto; margin:10px;'>
    <img src='' class='card-img-top' alt='...'>
    <div class='card-body'>
        <h5 class='card-header'>book</h5>
    </div>
</div>
</div>
    <div class='col'>
    <div class='card' style='width: 120px; height:140px; padding:auto; margin:10px;'>
    <img src='' class='card-img-top' alt='...'>
    <div class='card-body'>
        <h5 class='card-header'>book</h5>
    </div>
</div>
</div>
  </div>
    </div>
</div>
"?>

  <div class="container text-center" style="margin-bottom:40px;">
    <h3 >Hittar du inte det du söker?</h3>
    <h4 style="margin-bottom:20px;">Inga problem, vi klarar de flesta önskemål, stora som små.</h4>
    <btn class="button" >Gör ett önskemål</btn>

  </div>

  <div class="container-fluid text-center border border-4 border-black">
  <div class="row">
    <div class="col-6 ps-0 pe-0">
      <div  class="container-fluid border border-3 border-black  ps-5 pe-5 pt-5 pb-5">
      <h2 class="fw-bold mb-3">Hälsning från quintus</h2>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed nunc purus, efficitur ut lorem at, porta interdum felis. In quis mi ac arcu facilisis sodales eu at lorem. Aenean sed ante urna. Interdum et malesuada fames ac ante ipsum primis in faucibus. Morbi placerat blandit urna, nec condimentum ligula aliquet eu. Nunc posuere pharetra tellus varius convallis. Quisque sit amet lobortis arcu, eu scelerisque dui.

Nam sed mauris neque. Quisque molestie orci arcu, consectetur mollis sem elementum ac. In ornare ligula nec nulla vulputate facilisis. Integer sit amet massa tempor, laoreet nisi vel, bibendum nisi. In hac habitasse platea dictumst. Mauris malesuada urna laoreet enim malesuada sodales. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Curabitur tincidunt rutrum turpis ut ullamcorper. Integer metus turpis, vehicula sit amet vulputate in, iaculis ullamcorper metus. Nulla risus metus, molestie eu ipsum in, mattis condimentum ligula. Pellentesque scelerisque, nulla at ullamcorper pellentesque, neque neque cursus enim, eget ultrices turpis nisl congue magna. Morbi scelerisque, neque quis pulvinar aliquet, dui dolor bibendum felis, sit amet bibendum augue ante sit amet eros. Aenean luctus augue lorem, eget pulvinar orci scelerisque in. Vestibulum quis pellentesque nisi. Sed et congue nunc.

Vestibulum id elit eget eros consequat vulputate eu ac velit. Etiam molestie nibh sed elit vulputate, vel venenatis sapien vestibulum. Phasellus euismod faucibus nulla, eu aliquam nisi tincidunt vel. Vivamus lobortis nulla in massa posuere cursus. Nam tincidunt consectetur nulla, non molestie eros dapibus sit amet. Vivamus iaculis est quam, eget tempor libero euismod a. Donec ex ipsum, placerat eget ipsum id, fermentum facilisis risus. Duis fringilla leo ex. In bibendum felis ac ex dapibus egestas. Donec rutrum erat ac felis molestie, condimentum ullamcorper arcu tincidunt. Donec id neque vitae nibh aliquam efficitur eget ut nulla. Interdum et malesuada fames ac ante ipsum primis in faucibus. Curabitur scelerisque cursus lacus vel bibendum.

Maecenas tempor ligula ipsum, non tincidunt dolor eleifend ac. Donec accumsan vel risus ut posuere. Curabitur cursus erat nibh, non convallis augue aliquet eget. Maecenas iaculis ac libero et maximus. Vestibulum maximus ipsum non nisl tincidunt cursus. Nam dui neque, lacinia et neque nec, congue volutpat lorem. Integer laoreet, nibh rutrum dictum malesuada, enim felis condimentum est, eget ultricies felis mauris id magna. Sed pharetra viverra sapien, eget egestas enim.</p>
    </div>
  </div>
    <div class="col-6 ps-0 pe-0">
      <div  class="container-fluid border border-3 border-black">
      <img src="..." class="img-fluid" alt="...">
      </div>
    </div>
  </div>
</div>

  <div id="kundhistorier-sektion">
    
  </div>
</div>
</body>
</html>

<?php include "includes/footer.php"; ?>

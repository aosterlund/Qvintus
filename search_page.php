<h3 class="mt-5">Populära genrer</h3>
<div class="swiper">
    <div class="swiper-wrapper">
<?php
$stmt = $conn->prepare("SELECT * FROM table_genres WHERE is_populare > 0 ORDER BY is_populare DESC LIMIT 20");
$stmt->execute();
$popular_genres = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($popular_genres as $genre):
    $cover = "images/genre_" . $genre['genre_id'] . ".jpg";
    if (!file_exists($cover)) $cover = "images/placeholder.jpg";
?>
        <div class="swiper-slide card" style="height:180px; width:140px; text-align:center; padding:5px;">
            <img src="<?= $cover ?>" alt="" style="height:100px; width:auto; object-fit:cover;">
            <div class="card-content" style="font-size:0.9em; margin-top:5px;">
                <h5 style="margin:0;"><?= htmlspecialchars($genre['genre_name']) ?></h5>
            </div>
        </div>
<?php endforeach; ?>
    </div>

    <!-- Swiper navigation -->
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
</div>

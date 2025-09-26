<?php include "includes/header.php"; ?>

<div class="recommend-container">
  <form class="request-form" action="front_page.php" method="POST">
    <label for="wish">Vad önskar du?</label>
    <input type="text" id="wish" name="wish" required>
    <button type="submit">Skicka önskemål</button>
  </form>
</div>

<?php include "includes/footer.php"; ?>
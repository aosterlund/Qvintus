<?php include "includes/header.php"; ?>

<main class="container py-5">
    <h2 class="mb-4 text-center">Bokpanel</h2>

    <div class="row justify-content-center g-4">
        <!-- Card 1: Manage Books -->
        <div class="col-md-4 d-flex">
            <div class="card h-100 shadow-sm w-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Hantera Böcker</h5>
                    <p class="card-text">Visa, redigera eller ta bort böcker i systemet.</p>
                    <a href="manage_books.php" class="btn btn-success">Gå</a>
                </div>
            </div>
        </div>

        <!-- Card 2: User List -->
        <div class="col-md-4 d-flex">
            <div class="card h-100 shadow-sm w-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Användar Lista</h5>
                    <p class="card-text">Visa alla användare registrerade i systemet.</p>
                    <a href="user_list.php" class="btn btn-info">Gå</a>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include "includes/footer.php"; ?>

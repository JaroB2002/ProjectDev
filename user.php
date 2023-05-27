<?php
session_start();

include_once("bootstrap.php");

if (!isset($_SESSION['username'])) {
    header("location: index.php");
    exit();
}

if ($_SESSION['username'] == $_GET['id']) {
    header("location: editprofile.php");
    exit();
}

if (!empty($_GET["id"])) {
    $u = new User();
    $user_prompts = $u->getUserPrompts();
}

$f = new Follow();

$user = new User();
$user->setEmail($_SESSION['username']);
$userDetails = $user->getUserDetails();
$isModerator = $userDetails['is_admin'] == 1;

if ($isModerator) {
    if (isset($_POST['add'])) {
        $moderator = new Moderator();
        $moderator->addModerator($_POST['add']);
        $success = "Moderator added";
    }

    if (isset($_POST['remove'])) {
        $moderator = new Moderator();
        $moderator->removeModerator($_POST['remove']);
        $success = "Moderator removed";
    }
}

if (isset($_POST['report'])) {
    $reportedUser = $_GET['id'];
    // Voeg de gebruikersnaam en gegevens toe aan de databank
    // Code om de gebruiker te rapporteren en op te slaan in de databank

    // Stuur de gebruikersnaam door naar flaggedusers.php via een URL-parameter
    header("location: flaggedusers.php?user=" . urlencode($reportedUser));
    exit();
}

if (isset($_POST['ban'])) {
    $userToBan = $_GET['id'];

    // Add code to ban the user and mark their account as banned in the database
    $stmt = $pdo->prepare("UPDATE users SET is_banned = 1 WHERE username = :username");
    $stmt->execute([':username' => $userToBan]);

    // Delete the user's account if it is banned
    if ($user->isBanned()) {
        $user->deleteAccount();
        header("location: index.php"); // Redirect to the homepage or any other appropriate page
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Head sectie -->
</head>

<body class="ml-10">
    <!-- Navigatiebalk -->
    <nav>
        <?php include_once("navigation.php") ?>
    </nav>

    <h1 class="text-3xl font-semibold mt-5"><?php echo $_GET["id"]; ?></h1>

    <?php if ($_SESSION['username'] !== $_GET['id']) : ?>
        <div class="mt-10">
            <a href="#" data-id="<?php echo $_GET['id']; ?>" class="follow bg-fadedpurple px-5 py-3 rounded font-semibold mt-5"><?php if (Follow::getAll($_GET['id']) == true) {
                                                                                                                                                                echo 'Unfollow';
                                                                                                                                                            } else {
                                                                                                                                                                echo 'Follow';
                                                                                                                                                            } ?></a>
        </div>
    <?php endif; ?>

    <?php if ($isModerator) : ?>
        <div class="flex">
            <form method="post">
                <button class="bg-fadedblue px-5 py-3 mt-5 rounded font-semibold text-white mr-5" type="submit" name="add" value="<?= isset($_GET['id']) ? $_GET['id'] : '' ?>">Add Moderator</button>
            </form>

            <form method="post">
                <button class="bg-fadedblue px-5 py-3 mt-5 rounded font-semibold text-white mr-5" type="submit" name="remove" value="<?= isset($_GET['id']) ? $_GET['id'] : '' ?>">Remove Moderator</button>
            </form>
        </div>
    <?php endif; ?>

    <?php if (isset($success)) : ?>
        <p><?php echo htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <form method="post">
        <button class="bg-fadedblue px-5 py-3 mt-5 rounded font-semibold text-white" type="submit" name="report">Report User</button>
    </form>

    <form method="post">
        <button class="bg-red-500 px-5 py-3 mt-5 rounded font-semibold text-white" type="submit" name="ban">Ban User</button>
    </form>

    <article class="flex flex-wrap">
        <!-- Artikelen -->
    </article>

    <script src="js/follow.js"></script>
</body>

</html>

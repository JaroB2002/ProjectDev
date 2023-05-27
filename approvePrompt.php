<?php
include_once("bootstrap.php");
session_start();
$admin = new User();
$admin->isAdmin();
$approve = new Moderator();

if (isset($_GET["approve"])) {
    $approve->approvePrompt();
    $addCredit = $admin->addCreditsIfApproved(true);
}
if (isset($_GET["disapprove"])) {
    $reason = $_GET["reason"];
    $approve->unapprovePrompt($reason);
}

$allPrompts = Prompt::getAllUnapproved();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approve prompt</title>
    <link rel="stylesheet" href="./style.css">
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            screens: {
                sm: '480px',
                md: '768px',
                lg: '1024px',
                xl: '1280px',
            },
            extend: {
                colors: {
                    fadedpurple: '#C688F4',
                    fadedblue: '#5C69AA',
                    offgrey: '#fdfcfd',
                    offblack: '#313639',
                    offwhite: '#f9f9f9'
                }
            }
        }
    }
    </script>
</head>
<body class="bg-slate-200">
<nav><?php include_once("navigation.php")?></nav>
    <h1 class="text-2xl mx-5 font-semibold mt-7">Approve prompts</h1>
    <article class="text-white flex flex-wrap">
        <?php foreach ($allPrompts as $prompt): ?>
            <div class="my-5 mx-5 bg-offblack px-8 py-8 rounded max-w-sm">
                <div>
                    <p class="font-semibold text-xl text-fadedpurple"><strong>Name: </strong><?php echo htmlspecialchars($prompt["name"]);?></p>
                    <img class="object-none h-96 w-96 mb-5" src="<?php echo htmlspecialchars($prompt["image"]); ?>" alt="input image">
                    <p class="mb-3 text-lg text-offwhite"><strong>Description: </strong><?php echo htmlspecialchars($prompt["description"]);?></p>
                    <p class="mb-3 text-lg text-offwhite"><strong>tags: </strong><?php echo htmlspecialchars($prompt["tags"]);?></p>
                    <p class="mb-3 text-lg text-offwhite"><strong>Type: </strong><?php echo htmlspecialchars($prompt["type"])?></p>
                    <p class="mb-3 text-lg text-offwhite" ><strong>Price: </strong><?php echo htmlspecialchars($prompt["price"]);?></p>
                </div>
                <form action="" class="mt-3">
                    <div class="flex mt-5">
                        <button class="bg-fadedpurple px-5 py-3 rounded font-semibold ml-5" type="submit" name="approve" value="<?php echo $prompt['id']; ?>">Approve</button>
                        <button class="bg-fadedpurple px-5 py-3 rounded font-semibold ml-5" type="submit" name="disapprove" value="<?php echo $prompt['id']; ?>">Deny content</button>
                    </div>
                    <input type="text" name="reason" placeholder="Reason for denial" class="text-black mt-5 ml-5">

                </form>
            </div>
        <?php endforeach; ?>
    </article>
</body>
</html>

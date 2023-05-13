<?php
    include_once("bootstrap.php");
   //alleen admin logt in op deze pagina
    session_start();
    $admin = new User();
    $admin->isAdmin();
    $approve = new Moderator();

    if(isset($_GET["approve"])){
        $approve->approvePrompt();
        $addCredit = $admin->addCreditsIfApproved(true);
    }
    if(isset($_GET["disapprove"])){
        $approve->unapprovePrompt();
    }

    //prompts printen
    $allPrompts = Prompt::getAllUnapproved();
    //var_dump($allPrompts);

   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approve prompt</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
        theme: {
            extend: {
            colors: {
                clifford: '#da373d',
            }
            }
        }
        }
    </script>
        <style type="text/tailwindcss">
        @layer utilities {
        .content-auto {
            content-visibility: auto;
        }
        }
    </style>

    <link rel="stylesheet" href="./style.css">
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script>
</head>
<body class="bg-slate-200" >
    <h1 class="text-2xl mx-5 font-semibold mt-7" >Approve prompts</h1>
    <article class="text-white flex flex-wrap">
        <?php foreach($allPrompts as $prompt): ?>
            <div class="my-5 mx-5 bg-stone-700 px-8 py-8 rounded max-w-sm">
                <div>
                    <p> <strong>Name: </strong> <?php echo htmlspecialchars($prompt["name"]);?></p>
                    <img class="mb-5" src="<?php echo htmlspecialchars($prompt["image"]); ?>" alt="input image">
                    <p> <strong>description: </strong> <?php echo htmlspecialchars($prompt["description"]);?></p>
                    <p> <strong>type: </strong> <?php echo htmlspecialchars($prompt["type"])?> </p>
                    <p><strong>price: </strong> <?php echo htmlspecialchars($prompt["price"]);?></p>
                </div>
                <form action="" class="mt-3">
                    <button class="bg-sky-500 px-5 py-3 rounded font-semibold" type="submit" name="approve" value="<?php echo $prompt['id']; ?>">Approve</button>
                    <button class="bg-sky-500/50 px-5 py-3 rounded font-semibold" type="submit" name="disapprove" value="<?php echo $prompt['id']; ?>">Unapprove</button>
                </form>
            </div>
        <?php endforeach; ?>
    </article>
</body>
</html>
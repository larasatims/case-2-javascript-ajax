<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];

    $_SESSION['username'] = $username;

    header("Location: chat.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            width: 100%;
            height: 100%;
            padding: 0;
            margin: 0;
        }

        .custom-box {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); 
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <section class="w-screen h-screen flex justify-center items-center">
    <div class="max-w-md relative flex flex-col p-4 rounded-md text-black bg-white custom-box" style="background-color: #608cff; color: #608cff;">
            <div class="font-bold mb-2 text-center">Welcome to ChatApp</div>
            <form class="flex flex-col gap-5" method="post" enctype="multipart/form-data">
                <div class="block relative">
                    <label for="username" class="block text-gray-600 cursor-text text-sm leading-[140%] font-normal mb-2" style="color: #608cff;">Username</label>
                    <input type="text" id="username" name="username" class="rounded border border-gray-200 text-sm w-full font-normal leading-[18px] text-gray-600 tracking-[0px] appearance-none block h-11 m-0 p-[11px] focus:ring-2 ring-offset-2  ring-blue-700 outline-none">
                </div>
                <button type="submit" class="bg-[#000000] w-max m-auto px-36 py-3 rounded text-white text-sm font-normal" style="background-color: #608cff;">Submit</button>
            </form>
        </div>
    </section>
    <?php include 'footer.php'; ?>
</body>

</html>
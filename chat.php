<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$currentUsername = $_SESSION['username'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pesan'])) {
    $pesan = $_POST['pesan'];
    $username = $_SESSION['username'];

    if (!empty(trim($pesan))) {
        $chatFile = "chat.txt";
        $newContent = $username . ": " . $pesan . ": " . time() . "\n";
        file_put_contents($chatFile, $newContent, FILE_APPEND | LOCK_EX);
    }
}

$chatContent = file_get_contents("chat.txt");
$pesanArray = explode("\n", $chatContent);

$pesanArray = array_filter($pesanArray, function ($pesan) {
    return !empty(trim($pesan));
});

?>

<?php include 'header.php' ?>
<!DOCTYPE html>
<html>

<head>
    <title>AJAX Chat App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            width: 100%;
            height: 100%;
            padding: 0;
            margin: 0;
        }
        
        /* biar ga ngeblok warna pas ngetik */
        input:focus {
            outline: none;
        }

        section {
            height: 100vh;
            width: 100%;
            display: flex;
            flex-direction: row;
            background-color: white;
            justify-content: center;
            align-items: center;
            gap: 40px;
            background-color: #eeeeee;
        }

        .button-chat {
            width: 4em;
            height: 4em;
            border: none;
            outline: none;
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            box-shadow: 6px 6px 12px #c5c5c5, -6px -6px 12px #ffffff;
            cursor: pointer;
            background-color: #FFE53B;
            background-image: linear-gradient(147deg, #3994f0, #0e3fe1, #56d8dc);
            background-position: left;
            background-size: 300%;
            transition-duration: 1s;
        }

        .button-chat svg {
            fill: white;
        }

        .button-chat:hover {
            border-color: #c5c5c5;
            background-position: right;
            transition-duration: 1s;
        }

        .button-chat:hover svg {
            fill: black;
            transition: all 0.3s ease-in-out;
        }

        .tooltip {
            position: absolute;
            opacity: 0;
            padding-top: 4px;
            padding-bottom: 4px;
            padding-right: 8px;
            padding-left: 8px;
            transform: translateY(-40px);
            color: white;
            border-radius: 6px;
            background-color: rgb(82, 151, 255);
            transition: all ease-in-out 0.5s;
            animation: cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .button-chat:hover .tooltip {
            transform: translateY(-56px);
            transition: all 1s;
            opacity: 1;
        }

        .sign:hover .tooltip-logout {
            transition: all 0.6s ease-in-out;
            opacity: 1;
        }

        .sign .tooltip-logout {
            transition: all 0.6s ease-in-out
        }

        .chatMessages {
            max-height: 350px;
            overflow-y: auto;
            border-bottom: 1px solid #bcbcbc;
            margin-bottom: 3px;
        }

        .send-icon {
            width: 17px;
        }

        .custom-shape {
            top: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
        }

        .custom-shape svg {
            position: relative;
            display: block;
            width: calc(153% + 1.3px);
            height: 50px;
        }
    </style>
</head>

<body>
    <section class="container_chat">
        <button class="aboslute translate-x-[400px] button-chat" id="toggleButton">
            <svg height="1.6em" xml:space="preserve" viewBox="0 0 1000 1000" y="0px" x="0px" version="1.1">
                <path d="M881.1,720.5H434.7L173.3,941V720.5h-54.4C58.8,720.5,10,671.1,10,610.2v-441C10,108.4,58.8,59,118.9,59h762.2C941.2,59,990,108.4,990,169.3v441C990,671.1,941.2,720.5,881.1,720.5L881.1,720.5z M935.6,169.3c0-30.4-24.4-55.2-54.5-55.2H118.9c-30.1,0-54.5,24.7-54.5,55.2v441c0,30.4,24.4,55.1,54.5,55.1h54.4h54.4v110.3l163.3-110.2H500h381.1c30.1,0,54.5-24.7,54.5-55.1V169.3L935.6,169.3z M717.8,444.8c-30.1,0-54.4-24.7-54.4-55.1c0-30.4,24.3-55.2,54.4-55.2c30.1,0,54.5,24.7,54.5,55.2C772.2,420.2,747.8,444.8,717.8,444.8L717.8,444.8z M500,444.8c-30.1,0-54.4-24.7-54.4-55.1c0-30.4,24.3-55.2,54.4-55.2c30.1,0,54.4,24.7,54.4,55.2C554.4,420.2,530.1,444.8,500,444.8L500,444.8z M282.2,444.8c-30.1,0-54.5-24.7-54.5-55.1c0-30.4,24.4-55.2,54.5-55.2c30.1,0,54.4,24.7,54.4,55.2C336.7,420.2,312.3,444.8,282.2,444.8L282.2,444.8z">
                </path>
            </svg>
            <span class="tooltip">Chat</span>

        </button>
        <div class="absolute container_card w-[370px] h-[550px] rounded-xl flex-col" id="cardChat" style="display: flex">
            <div class="pt-4 pb-2 px-6 header-card w-full flex items-center justify-between gap-6 rounded-t-xl bg-gradient-to-r from-[#2A37D7] via-[#1c73d6] via-[#1c73d6] via-[#1c73d6] to-[#21C6FB]">
                <div class="bg-red-400 rounded-full h-[40px] w-[40px]">
                    <img class="h-[40px] object-cover rounded-full" src="Assets/Images/Profile_group.png" alt="">
                </div>
                <div class="flex flex-col">
                <h2 class="font-bold text-base tracking-wide text-slate-300" style="font-size: 0.9em; text-align: center; margin-top: -5px;">ChatApp - Pemweb SI-E Kel 7</h2>
                    </p>
                </div>

                <form action="logout.php" method="post">
                    <button type="submit" id="logoutButton" class="cursor-pointer">
                        <div class="sign">
                            <svg class="bg-transparent w-4" style="fill: #ffffff;" viewBox="0 0 512 512">
                                <path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z">
                                </path>
                            </svg>
                            <span class="tooltip-logout opacity-0 absolute text-xs text-white -translate-y-9 -translate-x-7 w-12 font-medium">Log
                                Out</span>
                        </div>
                    </button>
                </form>


            </div>


            <div class="custom-shape ">
            </div>
            <div class="bg-white w-full rounded-b-xl px-6 pb-4 flex flex-col">
                <div class="chatMessages flex flex-col min-h-[350px] pb-6 border-b-[1px] border-b-gray-200 mb-3" id="chatMessages">
                </div>

                <form id="chatForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="flex justify-between items-center mb-3">
                        <input id="messageInput" name="pesan" class="w-[260px] outline-none placeholder:text-xs placehplder:text-gray-300 placeholder:opacity-60 placeholder:tracking-wide text-sm text-gray-500 tracking-wide" type="text" placeholder="Masukkan Pesan ...">
                        <input type="hidden" id="currentUsername" value="<?php echo $_SESSION['username']; ?>">
                        <input type="hidden" id="currentProfilePic" value="<?php echo $_SESSION['profile_picture'] ?? null; ?>">

                        <button type="submit" id="sendMessage">
                            <svg class="send-icon" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                                <g>
                                    <g>
                                        <path fill="#6B6C7B" d="M481.508,210.336L68.414,38.926c-17.403-7.222-37.064-4.045-51.309,8.287C2.86,59.547-3.098,78.551,1.558,96.808 L38.327,241h180.026c8.284,0,15.001,6.716,15.001,15.001c0,8.284-6.716,15.001-15.001,15.001H38.327L1.558,415.193 c-4.656,18.258,1.301,37.262,15.547,49.595c14.274,12.357,33.937,15.495,51.31,8.287l413.094-171.409 C500.317,293.862,512,276.364,512,256.001C512,235.638,500.317,218.139,481.508,210.336z">
                                        </path>
                                    </g>
                                </g>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php include 'footer.php' ?>
    </section>
    
    <script src="script.js"></script>

</body>

</html>

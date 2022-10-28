<?php
include "core.php";

$error = [];
$success = false;


// Соединямся с БД
$link = dbConnect();

if(isset($_POST['log_in']))
{
  if($_POST['email'] !== "" && $_POST['password']!== ""){
    // Вытаскиваем из БД запись, у которой логин равняеться введенному
    $query = mysqli_query($link,"SELECT `id`, `full_name`, `email`, `password` FROM users WHERE email='".mysqli_real_escape_string($link,$_POST['email'])."' LIMIT 1");
    $data = mysqli_fetch_assoc($query);
    if ($data) {
      if($data['password'] === ($_POST['password']))
      {
        $success = true;
        setcookie("id", $data['id'], time()+60*60*24*30, "/");
        setcookie("full_name", $data['full_name'], time()+60*60*24*30, "/", null, null, true);
      } else {
        $error[] = 'Вы ввели неправильный логин или пароль';
      }
    } else {
      $error[] = 'Неправильный логин или пароль';
    }
    
  } else {
    $error[] = 'Введите Mail и пароль';
  }
}
if(isset($_POST['log_out'])){
  setcookie("id", "", time()-60*60*24*30, "/");
  setcookie("full_name", "", time()-60*60*24*30, "/", null, null, true);
}
?>


<!doctype html>
<html class="antialiased" lang="ru">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="assets/css/form.min.css" rel="stylesheet">
    <link href="assets/css/tailwind.css" rel="stylesheet">
    <link href="assets/css/base.css" rel="stylesheet">

    <title>Главная страница</title>
    <link href="assets/favicon.ico" rel="shortcut icon" type="image/x-icon">
</head>
<body class="bg-white text-gray-600 font-sans leading-normal text-base tracking-normal flex min-h-screen flex-col">
<div class="wrapper flex flex-1 flex-col bg-gray-100">
    <header class="bg-white">
        <div class="border-b">
            <div class="container mx-auto block overflow-hidden px-4 sm:px-6 sm:flex sm:justify-between sm:items-center py-4 space-y-4 sm:space-y-0">
                <div class="flex justify-center">
                    <a href="index.php" class="inline-block sm:inline hover:opacity-75">
                        <img src="assets/images/test.png" width="100" height="30" alt="">
                    </a>
                </div>
                <?php if($success){?>
                    <div>
                        <ul class="flex justify-center sm:justify-end items-center space-x-8 text-sm">
                            <li>
                                
                                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block text-orange h-4 w-4" fill="currentColor" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <?= $data['full_name'] ?>
                                
                            </li>
                            <li>
                                <a class="text-gray-500 hover:text-orange" name="log_out" href="index.php">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block text-orange h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                    </svg>
                                    Выход
                                </a>
                            </li>
                        </ul>
                    </div>
                <?php } else {?>
                    <div>
                        <ul class="flex justify-center sm:justify-end items-center space-x-8 text-sm">
                            <li>
                                <a class="text-gray-500 hover:text-orange" href="register.php">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block text-orange h-4 w-4" fill="currentColor" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Регистрация
                                </a>
                            </li>
                            <li>
                                <a class="text-gray-500 hover:text-orange" href="index.php">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block text-orange h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                    </svg>
                                    Авторизация
                                </a>
                            </li>
                        </ul>
                    </div>
                <?php }?>  
            </div>
        </div>
    </header>
    <main class="flex-1 container mx-auto bg-white overflow-hidden px-4 sm:px-6">
        <div class="py-4 pb-8">
            <h1 class="text-black text-3xl font-bold mb-4">Авторизация</h1>

            <?php if($error) { ?>
            <div class="my-4">
                <div class="px-4 py-3 leading-normal text-red-700 bg-red-100 rounded-lg" role="alert">
                    <p><?= $error[0];?></p>
                </div>
            </div>
            <?php }
            if($success) { ?>
            <div class="my-4">
                <div class="px-4 py-3 leading-normal text-green-700 bg-green-100 rounded-lg" role="alert">
                    <p>Вы успешно авторизовались</p>
                </div>
            </div>
            <?php } ?>
            <form action="index.php" method="POST">
                <div class="mt-8 max-w-md">
                    <div class="grid grid-cols-1 gap-6">
                        <div class="block">
                            <label for="fieldEmail" class="text-gray-700 font-bold">Email</label>
                            <input id="fieldEmail" type="email" name="email" value="<?= $success ? $data['email'] : ''?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="john@example.com">
                        </div>
                        <div class="block">
                            <label for="fieldPassword" class="text-gray-700 font-bold">Пароль</label>
                            <input id="fieldPassword" type="password" name="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="******">
                        </div>
                        <div class="block">
                            <button class="inline-block bg-orange hover:bg-opacity-70 focus:outline-none text-white font-bold py-2 px-4 rounded" name="log_in">
                                Войти
                            </button>
                            <a href="register.php" class="inline-block hover:underline focus:outline-none font-bold py-2 px-4 rounded">
                                У меня нет аккаунта
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>
    <footer class="border-t bg-white">
        <div class="container mx-auto overflow-hidden px-4 sm:px-6">
            <div class="space-y-4 sm:space-y-0 sm:flex sm:justify-between items-center py-6 px-2 sm:px-0">
                <div class="copy pr-8">© Сайт для изучения php</div>
            </div>
        </div>
    </footer>
</div>

</body>
</html>
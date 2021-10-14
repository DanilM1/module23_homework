<?php

$function = (empty($_POST['function'])) ? '' : $_POST['function'];

$a = '';
$b = '';
$e = '';

if ($function != '') {
    $answer = 'Answer: ';

    $example_persons_array = [
        [
            'fullname' => 'Иванов Иван Иванович',
            'job' => 'tester',
        ],
        [
            'fullname' => 'Степанова Наталья Степановна',
            'job' => 'frontend-developer',
        ],
        [
            'fullname' => 'Пащенко Владимир Александрович',
            'job' => 'analyst',
        ],
        [
            'fullname' => 'Громов Александр Иванович',
            'job' => 'fullstack-developer',
        ],
        [
            'fullname' => 'Славин Семён Сергеевич',
            'job' => 'analyst',
        ],
        [
            'fullname' => 'Цой Владимир Антонович',
            'job' => 'frontend-developer',
        ],
        [
            'fullname' => 'Быстрая Юлия Сергеевна',
            'job' => 'PR-manager',
        ],
        [
            'fullname' => 'Шматко Антонина Сергеевна',
            'job' => 'HR-manager',
        ],
        [
            'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
            'job' => 'analyst',
        ],
        [
            'fullname' => 'Бардо Жаклин Фёдоровна',
            'job' => 'android-developer',
        ],
        [
            'fullname' => 'Шварцнегер Арнольд Густавович',
            'job' => 'babysitter',
        ],
    ];

    function getPartsFromFullName($Name) {
        return explode(' ', $Name);
    }

    function getGenderFromName($fullName) {
        $c = mb_substr($fullName[0], -1, 1, 'UTF-8');
        $d = 0;
        if ($c == 'в') $d++;
        $c = mb_substr($fullName[0], -2, 2, 'UTF-8');
        if ($c == 'ва') $d--;
        $c = mb_substr($fullName[1], -1, 1, 'UTF-8');
        if ($c == 'й' || $c == 'н') $d++;
        if ($c == 'а') $d--;
        $c = mb_substr($fullName[2], -2, 2, 'UTF-8');
        if ($c == 'ич') $d++;
        $c = mb_substr($fullName[2], -3, 3, 'UTF-8');
        if ($c == 'вна') $d--;
        return $d;
    }

    if ($function == 'getFullNameFromParts' || $function == 'getPerfectPartner') {
        $lname = $_POST['lname'];
        $fname = $_POST['fname'];
        $patronomyc = $_POST['patronomyc'];
        if ($function == 'getFullNameFromParts') $a = "$answer $lname $fname $patronomyc";
        else {
            $fullName = getPartsFromFullName("$lname $fname $patronomyc");
            $w = getGenderFromName($fullName);
            while (true) {
                $d = array_rand($example_persons_array, 1);
                $d = getPartsFromFullName($example_persons_array[$d]['fullname']);
                $m = getGenderFromName($d);
                if ($w >= 0 && $m < 0 || $w < 0 && $m >= 0) {
                    $rand = mt_rand(50, 99) + mt_rand(0, 10) / 10 + mt_rand(0, 10) / 100;
                    $a = $answer.$fname.' '.mb_substr($lname, 0, 1, 'UTF-8').'. + '.$d[1].' '.mb_substr($d[0], 0, 1, 'UTF-8').'. = Идеально на '.$rand.'%.';
                    break;
                }
            }
        }
    }
    else {
        $fullName = (empty($_POST['fullName'])) ? '' : $_POST['fullName'];
        
        if ($fullName != '') $fullName = getPartsFromFullName($_POST['fullName']);
        
        if ($function == 'getPartsFromFullName') {
            $keys = ['surname', 'name', 'patronomyc'];
            $b = $answer.json_encode(array_combine($keys, $fullName), JSON_UNESCAPED_UNICODE);
        }
        elseif ($function == 'getShortName') $b = $answer.$fullName[1].' '.mb_substr($fullName[0], 0, 1, 'UTF-8').'.';
        elseif ($function == 'getGenderFromName') {
            $d = getGenderFromName($fullName);
            if ($d == 0) $b = "$answer пол не определён.";
            else {
                
                if ($d > 0) $b = "$answer мужской.";
                else $b = "$answer женский.";
            }
        }
        else {
            $m = 0;
            $w = 0;
            $total = 0;
            foreach ($example_persons_array as $n) {
                $d = getGenderFromName(getPartsFromFullName($n['fullname']));
                if ($d > 0) $m++;
                if ($d < 0) $w++;
                $total++;
            }
            $m *= 100 / $total;
            $w *= 100 / $total;
            $total = 100 - $m - $w;
            $e = "Мужчины: $m%.<br>Женщины: $w%.<br>Не удалось определить: $total%.";
        }
    }
}

echo
"<!DOCTYPE html>
<html>
    <head>
        <meta charset='utf-8'>
        <title>$function</title>
    </head>
    <body>
        <form action='index.php' method='post'>
            <label for='lname'>Surname:</label><br>
            <input type='text' id='lname' name='lname' required><br>
            <label for='fname'>First name:</label><br>
            <input type='text' id='fname' name='fname' required><br>
            <label for='patronomyc'>Patronomyc:</label><br>
            <input type='text' id='patronomyc' name='patronomyc' required><br><br>
            <input type='radio' id='getFullNameFromParts' name='function' value='getFullNameFromParts' checked>
            <label for='getFullNameFromParts'>getFullNameFromParts</label><br>
            <input type='radio' id='getPerfectPartner' name='function' value='getPerfectPartner'>
            <label for='getPerfectPartner'>getPerfectPartner</label><br><br>
            <input type='submit' value='Submit'>
        </form>
        <hr>
        <p id='a'>$a</p>
        <hr>
        <form action='index.php' method='post'>
            <label for='fullName'>Full name:</label><br>
            <input type='text' id='fullName' name='fullName' required><br><br>
            <input type='radio' id='getPartsFromFullName' name='function' value='getPartsFromFullName' checked>
            <label for='getPartsFromFullName'>getPartsFromFullName</label><br>
            <input type='radio' id='getShortName' name='function' value='getShortName'>
            <label for='getShortName'>getShortName</label><br>
            <input type='radio' id='getGenderFromName' name='function' value='getGenderFromName'>
            <label for='getGenderFromName'>getGenderFromName</label><br><br>
            <input type='submit' value='Submit'>
        </form>
        <hr>
        <p id='b'>$b</p>
        <hr>
        <form action='index.php' method='post'>
            <input type='radio' id='getGenderDescription' name='function' value='getGenderDescription' checked>
            <label for='getGenderDescription'>getGenderDescription</label><br><br>
            <input type='submit' value='Submit'>
        </form>
        <hr>
        <p id='b'>$e</p>
        <hr>
    </body>
</html>";

?>
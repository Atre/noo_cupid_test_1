<html>
<head></head>
<body>
<form>
    <label>
        Number:
        <input name="number" type="text" />
    </label>
    <input type="submit" />
</form>
<hr>
</body>
</html>

<?php
// Form handler
if(!empty($_GET['number'])) {
    $get = filter_input(INPUT_GET, 'number');
    echo convert_number($get);
}

/**
 * Numbers to text translation
 * @param $number int
 * @return string
 */
function convert_number($number)
{
    // Check for input errors
    if (!is_numeric($number) || abs($number) > PHP_INT_MAX) {
        exit('Number must be numeric and lesser than ' . PHP_INT_MAX);
    }

    $number = abs($number);

    $dash = '-';
    $and = ' and ';
    $comma = ', ';
    $wordList = array(
        0 => 'zero',
        1 => 'one',
        2 => 'two',
        3 => 'three',
        4 => 'four',
        5 => 'five',
        6 => 'six',
        7 => 'seven',
        8 => 'eight',
        9 => 'nine',
        10 => 'ten',
        11 => 'eleven',
        12 => 'twelve',
        13 => 'thirteen',
        14 => 'fourteen',
        15 => 'fifteen',
        16 => 'sixteen',
        17 => 'seventeen',
        18 => 'eighteen',
        19 => 'nineteen',
        20 => 'twenty',
        30 => 'thirty',
        40 => 'forty',
        50 => 'fifty',
        60 => 'sixty',
        70 => 'seventy',
        80 => 'eighty',
        90 => 'ninety',
        100 => 'hundred',
        1000 => 'thousand',
        1000000 => 'million',
        1000000000 => 'billion',
    );

    switch (true) {
        // We have this in dictionary
        case $number < 21:
            $string = $wordList[$number];
            break;
        // Calculating remainder, delimiter and make recursion stuff
        case $number < 100:
            $tens = ((int)($number / 10)) * 10;
            $units = $number % 10;
            $string = $wordList[$tens];
            if ($units) {
                $string .= $dash . $wordList[$units];
            }
            break;
        case $number < 1000:
            $hundreds = $number / 100;
            $remainder = $number % 100;
            $string = $wordList[(int)$hundreds] . ' ' . $wordList[100];
            if ($remainder) {
                $string .= $and . convert_number($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int)($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number($numBaseUnits) . ' ' . $wordList[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $and : $comma;
                $string .= convert_number($remainder);
            }
            break;
    }

    return ucfirst($string);
}




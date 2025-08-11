<?php
// $text = "the quick brown Cat jumps over the lazy dog, and cutted the carpet";
// $patten = "/c.t/i";




// if (preg_match($patten, $text, $matches) === 1) {
//     echo "the word which start with 'c' and ends with 't' was found in the text";
//     echo "<pre>";
//     print_r($matches);
//     echo "</pre>";
// } else {
//     echo "the word for the match 'c.t' wasn't found in the text";
// }
// $search_count = preg_match_all($patten, $text, $matches);
// if ($search_count >= 1) {
//     echo "the number of words which start with 'c' and ends with 't' is " . $search_count;
//     echo "<pre>";
//     print_r($matches);
//     echo "</pre>";
// } else {
//     echo "the word for the match 'c.t' wasn't found in the text";
// }


$greeting_sentence = "welcome is everyone ready to go? i see 10000000 persons that are not ready yet, hello by the way' said Thomas";
$greeting_sentence_v2 = "hi there, is everyone ready to go? said Thomas";
$greeting_sentence_v3 = "hihi  said Thomas";
$patten = "/^(hello|(hi){1,4}|welcome) (.+) (.+\?)(\d*) (.+)? said (\w+)$/i";


if (preg_match($patten, $greeting_sentence, $matches) === 1) {
    echo "the sentence started with a greeting";
    echo "<pre>";
    print_r($matches);
    echo "</pre>";
} else {
    echo "no greeting was found at the begining";
}

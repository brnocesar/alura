<pre>
<?php

$vetor = [
    4156, 
    3876, 
    5873, 
    4091, 
    5897, 
    9678, 
    7897, 
    5438, 
    2274, 
    6320
];

foreach ( $vetor as $elemento ) {
    echo "$elemento\n";
}
echo "\n";

sort($vetor);
var_dump($vetor);
echo "\n";

rsort($vetor); //krsort(), arsort()
var_dump($vetor);
echo "\n";

shuffle($vetor);
var_dump($vetor);
echo "\n";

// 

$nomes = "Bruno, Mayra, Benicio, Maria"; 
echo "\nstring: $nomes\n";
$nomes = str_replace(' ', '', $nomes);
echo "string: $nomes\n";
$vetor = explode(',', $nomes);
var_dump($vetor);
$vetor = implode(', ', $vetor);
echo "string: $vetor\n";

?>
</pre>
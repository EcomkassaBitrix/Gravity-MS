<?php
// Сравните на разных системах:
echo "PHP Version: " . PHP_VERSION . "<br/>";
echo "Architecture: " . (PHP_INT_SIZE * 8) . "-bit<br/>";
echo "Float precision: " . PHP_FLOAT_DIG . "<br/>";
echo "Value: " . 51933.20 . "<br/>";
echo "JSON: " . json_encode(['sum' => round(51933.20, 2)]) . "<br/>";
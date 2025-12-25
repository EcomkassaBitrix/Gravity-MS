<?php
// Сравните на разных системах:
echo "PHP Version: " . PHP_VERSION . "<br/>\n";
echo "Architecture: " . (PHP_INT_SIZE * 8) . "-bit<br/>\n";
echo "Float precision: " . PHP_FLOAT_DIG . "<br/>\n";
echo "Value: " . 51933.20 . "<br/>\n";
echo "JSON (PHP_ROUND_HALF_UP): " . json_encode(['sum' => round(51933.20, 2, PHP_ROUND_HALF_UP)]) . "<br/>\n";
echo "JSON (PHP_ROUND_HALF_DOWN): " . json_encode(['sum' => round(51933.20, 2, PHP_ROUND_HALF_DOWN)]) . "<br/>\n";
echo "JSON (PHP_ROUND_HALF_ODD): " . json_encode(['sum' => round(51933.20, 2, PHP_ROUND_HALF_ODD)]) . "<br/>\n";

phpinfo();
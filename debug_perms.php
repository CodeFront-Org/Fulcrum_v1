<?php
$path = 'C:\Users\Eliud\Desktop\Work Space\Fulcrum_v1\public\uploads\supporting_docs';
echo "Path: " . $path . "\n";
echo "Exists: " . (file_exists($path) ? 'Yes' : 'No') . "\n";
echo "Is Dir: " . (is_dir($path) ? 'Yes' : 'No') . "\n";
echo "Is Writable: " . (is_writable($path) ? 'Yes' : 'No') . "\n";

$testFile = $path . DIRECTORY_SEPARATOR . 'test_php_write.txt';
echo "Trying to write to: " . $testFile . "\n";

try {
    $result = file_put_contents($testFile, 'Testing write access');
    echo "Write result: " . ($result !== false ? 'Success' : 'Fail') . "\n";
    if ($result !== false) {
        unlink($testFile);
        echo "Cleaned up test file.\n";
    }
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}

// Check parent
$parent = dirname($path);
echo "Parent ($parent) Writable: " . (is_writable($parent) ? 'Yes' : 'No') . "\n";

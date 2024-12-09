<?php
echo "Users";

echo "<pre>";
if (count($data) > 0) {
    print_r($data);
} else {
    echo "No data";
}
echo "</pre>";

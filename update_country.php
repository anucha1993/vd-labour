<?php
// Update country_id for existing demands
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$updated = DB::table('demands')->whereNull('country_id')->update(['country_id' => 1]);
echo "Updated $updated records with country_id = 1\n";

// Show sample data
$samples = DB::table('demands')
    ->join('country', 'demands.country_id', '=', 'country.country_id')
    ->select('demands.dm_id', 'demands.dm_com_name', 'country.country_name_th')
    ->take(3)
    ->get();

echo "Sample data:\n";
foreach($samples as $sample) {
    echo "ID: {$sample->dm_id}, Company: {$sample->dm_com_name}, Country: {$sample->country_name_th}\n";
}
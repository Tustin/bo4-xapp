<?php
require_once('vendor/autoload.php');

use \DiscordWebhooks\Client;

$savedXappFileName = 'latest.json';
$xappUrl = 'https://www.callofduty.com/content/atvi/callofduty/mycod/web/en/data/json/iq-content-xapp.js';

$savedXappFileContent = [];
$currentXappContent = [];

$currentXappContent = json_decode(file_get_contents($xappUrl), true);

if (file_exists($savedXappFileName))
{
    $savedXappFileContent = json_decode(file_get_contents($savedXappFileName), true);
}
else
{
    file_put_contents($savedXappFileName, json_encode($currentXappContent));
    exit(0);
}

$newValues = array_diff_key($currentXappContent, $savedXappFileContent);

if (empty($newValues))
{
    exit(0);
}

file_put_contents($savedXappFileName, json_encode($currentXappContent));

$changes = '';

foreach ($newValues as $key => $value)
{
    $changes .= $key . ' - ' . $value . "\n";
}

$changes .= "\n" . $xappUrl;

$webhook = new Client(getenv('XAPP_DISCORD_WEBHOOK'));

$webhook->username('leaky pipes')->message($changes)->send();
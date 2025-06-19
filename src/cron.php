<?php
require_once 'functions.php';
require_once 'mailer.php';
require_once 'github_api.php';

$username = "torvalds"; // Replace with your target GitHub username
$events = fetchGitHubTimeline($username);

if (!$events || !is_array($events)) {
    exit("❌ Failed to fetch GitHub events.\n");
}

// Prepare message (latest 3 events)
$latestEvents = array_slice($events, 0, 3);
$message = "🔔 Latest GitHub Activity from @$username:\n\n";

foreach ($latestEvents as $event) {
    $type = $event['type'];
    $repo = $event['repo']['name'];
    $time = date("d M Y H:i", strtotime($event['created_at']));
    $message .= "- $type on $repo at $time\n";
}

sendGitHubUpdatesToSubscribers();

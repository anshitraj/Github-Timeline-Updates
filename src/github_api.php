<?php

function fetchGitHubTimeline($username = 'torvalds') {
    $url = "https://api.github.com/users/$username/events/public";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // GitHub API requires a user-agent header
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'User-Agent: GH-Timeline-App'
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

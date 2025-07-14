<?php

$ch = curl_init();
$token = "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";
$user = "fadhlanarrizal";

curl_setopt_array($ch, [
    CURLOPT_URL => "https://api.github.com/users/$user/events",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $token",
        "User-Agent: $user"
    ],
]);

$output = curl_exec($ch);

if (curl_errno($ch)) {
    echo "cURL Error: " . curl_error($ch);
    exit;
}

$data = json_decode($output, true);

if ($data === null) {
    echo "Failed to decode JSON.";
    exit;
}

// Proses dan tampilkan aktivitas
foreach ($data as $event) {
    $type = $event['type'];
    $repo = $event['repo']['name'];

    switch ($type) {
        case 'PushEvent':
            $count = count($event['payload']['commits']);
            echo "- Pushed $count commit" . ($count > 1 ? "s" : "") . " to $repo\n";
            break;

        case 'IssuesEvent':
            $action = $event['payload']['action'];
            echo "- $action a new issue in $repo\n";
            break;

        case 'IssueCommentEvent':
            echo "- Commented on an issue in $repo\n";
            break;

        case 'WatchEvent':
            echo "- Starred $repo\n";
            break;

        case 'ForkEvent':
            echo "- Forked $repo\n";
            break;

        case 'CreateEvent':
            $refType = $event['payload']['ref_type'];
            echo "- Created a new $refType in $repo\n";
            break;

        case 'PullRequestEvent':
            $action = $event['payload']['action'];
            echo "- $action a pull request in $repo\n";
            break;

        default:
            echo "- Performed $type in $repo\n";
            break;
    }
}

curl_close($ch);

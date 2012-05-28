<?php
// This code is public domain.



/// Call myna_problem() to get an error description if another call returns FALSE
function myna_problem($private=NULL) {
    if ($private !== NULL) {
        $GLOBALS['myna_problem'] = $private;
        return FALSE;
    }
    else {
        return $GLOBALS['myna_problem'];
    }
}

/// The suggest action gets an experiment to suggest a variant to display to a user.
/// For a given Experiment UUID, myna_suggest() returns an object with two keys: "token" and "choice".
///   "token" is a unique value that must be given to myna_reward (or equivalent) if a suggestion succeeds.
///   "choice" is the name of the variant that should be used.
/// Returns FALSE if unsuccessful.
function myna_suggest($experiment_id) {
    
    $url = 'http://api.mynaweb.com/v1/experiment/' . urlencode($experiment_id) . '/suggest';
    $contents = file_get_contents($url);
    if (!$contents)
        return FALSE or myna_problem('The server did not respond.');
    
    $response = json_decode($contents, TRUE);
    if (!is_array($response))
        return FALSE or myna_problem('The server returned invalid JSON: "' . $contents . '"');
    
    if ($response['typename'] !== 'suggestion')
        return FALSE or myna_problem('There was a problem: "' . $contents . '"');
    
    unset($response['typename']);
    return $response;
}

/// The reward action notifies an experiment of the success or failure of a particular suggestion.
/// Returns TRUE or FALSE.
function myna_reward($experiment_id, $token, $amount=1) {
    
    $url = 'http://api.mynaweb.com/v1/experiment/' . urlencode($experiment_id) . '/reward'
         . '?token=' . urlencode($token)
         . '&amount=' . urlencode((string)$amount);
    $contents = file_get_contents($url);
    if (!$contents)
        return FALSE or myna_problem('The server did not respond.');
    
    $response = json_decode($contents, TRUE);
    if (!is_array($response))
        return FALSE or myna_problem('The server returned invalid JSON: "' . $contents . '"');
    
    return $response['typename'] === 'ok' or myna_problem('There was a problem: "' . $contents . '"');
}

?>
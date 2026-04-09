<?php
Session_start();
//db connection
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD','admin@123');
define('DB_DATABASE', 'blog_system');

define('SITE_URL', 'http://127.0.0.1/Blog/');

// LLM settings (set API key in server environment for production)
define('LLM_API_KEY', getenv('LLM_API_KEY') ?: '');
define('LLM_ENDPOINT', getenv('LLM_ENDPOINT') ?: 'https://api.openai.com/v1/chat/completions');
define('LLM_MODEL', getenv('LLM_MODEL') ?: 'gpt-4o-mini');
define('LLM_TIMEOUT_SECONDS', 20);


include_once('DBConnection.php');
$db = new DbConnection;

//baseurl function
function baseurl($value) 
{
    echo SITE_URL.$value;
}
//redirect function
function redirect($message,$page,$type = 'success')
{

    $redirectTo = SITE_URL.$page;
    $_SESSION['message'] ="$message";
    $_SESSION['message_type'] = $type;
    header("Location: $redirectTo");
    exit(0);
}
//validateInput function
function validateInput($dbcon,$value)
{
    return mysqli_real_escape_string($dbcon, $value);
}

?>
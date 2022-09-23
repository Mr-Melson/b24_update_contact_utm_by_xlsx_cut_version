<?
require_once (__DIR__ . '/db_settings.php');

global $servername;
global $username;
global $password;
global $dbname;
global $contacts_utm_cut_version;

$contacts_utm_cut_version = new mysqli($servername, $username, $password, $dbname);

if ($contacts_utm_cut_version->connect_error) {
    die('Connection failed: ' . $contacts_utm_cut_version->connect_error);
}

$contacts_utm_cut_version->query(
'CREATE TABLE contacts_utm_cut_version (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    contact_id VARCHAR(255),
    utm_source VARCHAR(255),
    utm_medium VARCHAR(255)
)');

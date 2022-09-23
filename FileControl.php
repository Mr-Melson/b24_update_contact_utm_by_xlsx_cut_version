<?
require_once (__DIR__ . '/DataBase.php');
require_once (__DIR__ . '/SimpleXLSX.php');

use Shuchkin\SimpleXLSX;

global $contacts_utm_cut_version;

$file_name = $_POST['file_name'];

if ($xlsx = SimpleXLSX::parse('xlsx/' . $file_name . '.xlsx')) {

    $rows = $xlsx->rows();

    for ($row_order = 1; $row_order < count($rows); $row_order++) {

        if (
            $rows[$row_order][0] !== '' ||
            $rows[$row_order][1] !== '' ||
            $rows[$row_order][2] !== ''
        ) {

            $contact_id = $contacts_utm_cut_version->real_escape_string( $rows[$row_order][0] );
            $utm_source = $contacts_utm_cut_version->real_escape_string( $rows[$row_order][1] );
            $utm_medium = $contacts_utm_cut_version->real_escape_string( $rows[$row_order][2] );

            $contacts_utm_cut_version->query(
                "INSERT INTO `contacts_utm_cut_version` (
                    contact_id,
                    utm_source,
                    utm_medium
                )
                VALUES (
                    '$contact_id',
                    '$utm_source',
                    '$utm_medium'
                )"
            );
        }
    }

} else {
    echo SimpleXLSX::parseError();
}
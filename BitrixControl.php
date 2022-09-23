<?
ini_set('max_execution_time', 2000);

require_once(__DIR__ . '/vendor/autoload.php');
require_once (__DIR__ . '/DataBase.php');

use App\Bitrix24\Bitrix24API;
use App\Bitrix24\Bitrix24APIException;

global $contacts_utm_cut_version;

if (isset($_POST['b24_source'])) {

    try {
        $webhookURL = $_POST['b24_source'];
        $bx24 = new Bitrix24API($webhookURL);

        if (isset($_POST['page'])) {
            $offset = (int)$_POST['page'] * 50;
        } else {
            $offset = 0;
        }

        $end_table = false;
        $updated_contacts = [];
        $contact_results = [];

        $user_ids = $contacts_utm_cut_version->query(
            'SELECT * FROM `contacts_utm_cut_version` LIMIT ' . $offset . ', 50'
        );

        if ($user_ids->num_rows > 0) {            
        
            while ($row = $user_ids->fetch_assoc()) {
        
                $updated_contacts[] = $row['contact_id'];

                $contact_data = [
                    'UTM_SOURCE' => $row['utm_source'],
                    'UTM_MEDIUM' => $row['utm_medium']
                ];
                $contact_results[] = $bx24->updateContact($row['contact_id'], $contact_data);
            }
        } else {
            $end_table = true;
        }
        
        if (!empty($updated_contacts)) {

            echo json_encode([
                'result' => $contact_results,
                'updated_contacts' => $updated_contacts,
                'end_table' => $end_table
            ]);
        } else {
            echo json_encode([
                'result' => 'Not found',
                'updated_contacts' => $updated_contacts,
                'end_table' => $end_table
            ]);
        }

    } catch (Bitrix24APIException $e) {
        printf(sprintf('Ошибка (%%d): %%s%s', PHP_EOL), $e->getCode(), $e->getMessage());
    } catch (Exception $e) {
        printf(sprintf('Ошибка (%%d): %%s%s', PHP_EOL), $e->getCode(), $e->getMessage());
    }
}


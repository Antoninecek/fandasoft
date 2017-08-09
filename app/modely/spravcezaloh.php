<?php
/**
 * Created by PhpStorm.
 * User: František
 * Date: 05.08.2017
 * Time: 10:48
 */

namespace app\modely;


use libs\Spravce;

class Spravcezaloh extends Spravce {

    public function zalohujTabulku($tabulka) {
        return $this->db->executeCommand('mysqldump -u ' . DB_USER . ' ' . DB_NAME . ' ' . $tabulka . ' > ' . $_SERVER['DOCUMENT_ROOT'] . "/" . ROOT_DIR . 'backups/' . $tabulka . '.sql');
    }

    public function zalohujDb($tables = '*') {

        $return = '';

        //get all of the tables
        if ($tables == '*') {
            $tables = array();
            $result = $this->db->dotazVsechny('SHOW TABLES');
            foreach ($result as $row) {
                $tables[] = $row[0];
            }
        } else {
            $tables = is_array($tables) ? $tables : explode(',', $tables);
        }

        //cycle through
        foreach ($tables as $table) {
            $result = $this->db->dotazVsechny('SELECT * FROM ' . $table);


//            $return .= 'DROP TABLE ' . $table . ';';
//            $row2 = $this->db->dotazVsechny('SHOW CREATE TABLE ' . $table));
//            $return .= "\n\n" . $row2[1] . ";\n\n";

            $return .= 'INSERT INTO ' . $table . '(ID,ean,imei,imei1,kusy,jmeno,text,datum) VALUES';
            for ($i = 0; $i < $num_fields; $i++) {
                while ($row = mysql_fetch_row($result)) {
                    $return .= '(';
                    for ($j = 0; $j < $num_fields; $j++) {
                        $row[$j] = addslashes($row[$j]);
                        $row[$j] = ereg_replace("\n", "\\n", $row[$j]);
                        if (isset($row[$j])) {
                            $return .= '"' . $row[$j] . '"';
                        } else {
                            $return .= '""';
                        }
                        if ($j < ($num_fields - 1)) {
                            $return .= ',';
                        }
                    }
                    $return .= "),\n";
                }
            }
            $return .= "\n\n\n";
        }

        //save file
        $file = 'backup/db-backup-' . date("d-m-Y") . '-' . date("H-i-s") . '-' . (md5(implode(',', $tables))) . '.sql';
        $handle = fopen($file, 'w+');
        fwrite($handle, $return);
        fclose($handle);
    }


}

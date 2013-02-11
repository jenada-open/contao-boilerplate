<?php

/**
 * SyncCtoDomainPatch
 * 
 * Patch für die Erweiterung SyncCto von menatwork
 * https://github.com/menatwork/syncCto
 * 
 * Dieser Patch verhindert das Synchronisieren des Domainnamen
 * 
 * abgeleitet / erstellt aus der Dokumentation 
 * https://github.com/menatwork/syncCto/issues/117#issuecomment-8417142
 * 
 * erfolgreich getestet mit:
 * Contao 2.11.7
 * SyncCto 2.3.0 rc2
 * SyncCto 2.3.0 rc3
 * 
 */
class SyncCtoDomainPatch extends Backend {

    /**
     * @param int $intClientID Id of current client
     * @param array $arrSyncTables List with all tables
     * @param array $arrSQL List with all querys
     * @return array
     */
    public function syncDBUpdateBeforeDrop($intClientID, $arrSyncTables, $arrSQL) {
        $arrSQL = (array) $arrSQL;

        if (!in_array('tl_page', $arrSyncTables)) {
            return $arrSQL;
        }

        $arrSQL[]['query'] = '
            UPDATE synccto_temp_tl_page AS t
            SET t.dns = (SELECT p1.dns FROM tl_page AS p1 WHERE p1.id = t.id)
            WHERE t.id IN (SELECT p2.id FROM tl_page AS p2 WHERE p2.type = \'root\')
        ';

        return $arrSQL;
    }

}

?>
<?php

//require_once 'D:\Apache Software Foundation\xampp\htdocs\AlyssumPortalCake\app\Lib\dBug.php';
App::uses('AppController', 'Controller');

class MaterialsController extends AppController {

    public $uses = array('Material');

    /**
     * recieves [{name: 'string', chromosomes: [{id: '', chrom: ''}, {id: '', chrom: ''}, {...}, ...]}, {...}, ...]
     */
    public function coordinates() {
        $this->autoRender = false;
        //$this->request->onlyAllow('ajax');
        $coords = array();
        if ($this->request->is('ajax')) {
            $mapJson = $this->request->data;
            foreach ($mapJson as $group) {  //grouped by name
                $chromJson = $group['chromosomes'];
                $coordsName = array();
                foreach ($chromJson as $value) { //each name has chromosome records
                    $material = $this->Material->find('first', array(
                        'conditions' => array('Cdata.id' => $value['id'])
                    ));
                    $latr = '';
                    $lonr = '';
                    if ($material['Material']['coordinates_e'] && $material['Material']['coordinates_n'] && $material['Material']['coordinates_e'] != 'null' && $material['Material']['coordinates_n'] != 'null') {
                        $latr = $material['Material']['coordinates_n'];
                        $lonr = $material['Material']['coordinates_e'];
                    } else if ($material['Material']['coordinates_georef_lat'] && $material['Material']['coordinates_georef_lon'] && $material['Material']['coordinates_georef_lat'] != 'null' && $material['Material']['coordinates_georef_lon'] != 'null') {
                        $latr = $material['Material']['coordinates_georef_lat'];
                        $lonr = $material['Material']['coordinates_georef_lon'];
                    }
                    $lat = str_replace("?", "", $latr);
                    $lon = str_replace("?", "", $lonr);
                    $chrom = trim($value['chrom']);
                    if (array_key_exists($chrom, $coordsName)) {
                        array_push($coordsName[$chrom], array('id' => $value['id'], 'lat' => $lat, 'lon' => $lon, 'idr' => $material['Material']['id_reference']));
                    } else {
                        $coordsName[$chrom] = array(array('id' => $value['id'], 'lat' => $lat, 'lon' => $lon, 'idr' => $material['Material']['id_reference']));
                    }
                }
                $coords[trim($group['name'])] = $coordsName;
            }
        }
        //$this->sendJson($mapJson);
        echo json_encode($coords);
    }

}

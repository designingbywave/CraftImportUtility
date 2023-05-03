<?php

namespace wavedesign\crafthrcommencementimportutility\services;

use wavedesign\crafthrcommencementimportutility\RegistrarImporterCraft;

use Craft;
use craft\base\Component;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


/**
 * Registrar Importer Craft Service service
 */
class RegistrarImporterCraftService extends Component
{
    public function excelToArray ($filepath) {
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($filepath);
        $reader->setReadDataOnly(true);
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filepath);

        $worksheet = $spreadsheet->getSheet($spreadsheet->getFirstSheetIndex());
        $sheetData = $worksheet->toArray();


        $highestRow = $worksheet->getHighestRow(); // e.g. 10
        $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

        $data = array();

        for ($row = 1; $row <= $highestRow; $row++) {
            $riga = array();
            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                $riga[] = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
            }
            if (1 === $row) {
                // Header row. Save it in "$keys".
                $keys = $riga;
                continue;
            }
            // This is not the first row; so it is a data row.
            // Transform $riga into a dictionary and add it to $data.
            $data[] = array_combine($keys, $riga);
        }
        

        return json_encode($data);
    }

    public function sortHonorRoll ($data, $title, $press_release) {
        $dataobj = json_decode($data, true);

        $instate = array();
        $outofstate = array();
        $international = array();

        foreach ($dataobj as $key => $value) {
            // $arr[3] will be updated with each value from $arr...
            //$tempobj = new HonorRollEntry($value[3], $value[1], $value[4], $value[5], $value[4], $value[6], $value[7], $value[9], $value[10], $value[11]);
            //$tempobj->combined_personal_info = $this->constructInfo($tempobj->diploma_name, $tempobj->degree_type, $tempobj->major_one, $tempobj->major_two);
            if ($value["State"] == null or $value["State"] == "") {
                array_push($international, $value);
            } else if ($value["State"] == "TX") {
                array_push($instate, $value);
            } else  {
                array_push($outofstate, $value);
            } 
            
            usort($instate, fn($a, $b) => $a["City"] <=> $b["City"]
                ?: $b["State"] <=> $a["State"]
                    ?: $a["Last Name"] <=> $b["Last Name"]
                        ?: $a["First Name"] <=> $b["First Name"]);

            usort($outofstate, fn($a, $b) => $a["State"] <=> $b["State"]
                ?: $a["City"] <=> $b["City"]
                    ?: $a["Last Name"] <=> $b["Last Name"]
                        ?: $a["First Name"] <=> $b["First Name"]);
            
            usort($international, fn($a, $b) => $a["City"] <=> $b["City"]
                ?: $a["Last Name"] <=> $b["Last Name"]
                    ?: $a["First Name"] <=> $b["First Name"]);
        }
        if ($press_release) {

            
            $tempcity = "";
            $pressoutput = "<div text-container container sub-nav >
            <p><strong>".$title." Degrees</strong></p><div>";
    
            foreach ($instate as $key => $value) {
    
                if ($value["City"] != $tempcity) {
                    $pressoutput = $pressoutput . "\n</div><br><div><strong>" .$value["City"]. "</strong></div><div>\n";
                    $tempcity = $value["City"];
                } else {
                    $pressoutput = $pressoutput.";";
                }
                $pressoutput = $pressoutput. "\n\t" .$value["First Name"]." ".$value["Last Name"];
            }
    
            foreach ($outofstate as $key => $value) {
    
                if ($value["City"] != $tempcity) {
                    $pressoutput = $pressoutput . "\n</div><br><div><strong>" .$value["City"].", ".$value["State"]. "</strong></div><div>\n";
                    $tempcity = $value["City"];
                } else {
                    $pressoutput = $pressoutput.";";
                }
                $pressoutput = $pressoutput. "\n\t" .$value["First Name"]." ".$value["Last Name"];
            }

    
            return $pressoutput;



        } else {
        $tempcity = "";
        $htmloutput = "<div text-container container sub-nav >
            <p><strong>".$title."</strong></p>
                <table >
                <tbody>
                    <tr>
                        <th></th>
                        <th></th>
                    </tr>
                    <tr>
                    <td>";

        foreach ($instate as $key => $value) {

            if (strtolower($value["City"]) != $tempcity) {
                $htmloutput = $htmloutput . "\n</td>\n</tr>\n<tr>\n\t<td><strong>" .$value["City"]. "</strong>\n</td>\n<td>";
                $tempcity = strtolower($value["City"]);
            } else {
                $htmloutput = $htmloutput.",";
            }
            $htmloutput = $htmloutput . "\n\t" . $value["First Name"]." ".$value["Last Name"];
        }

        foreach ($outofstate as $key => $value) {

            if (strtolower($value["City"]) != $tempcity) {
                $htmloutput = $htmloutput . "\n</td>\n</tr>\n<tr>\n\t<td><strong>" .$value["City"].", ".$value["State"]. "</strong>\n</td>\n<td>";
                $tempcity = strtolower($value["City"]);
            } else {
                $htmloutput = $htmloutput.",";
            }
            $htmloutput = $htmloutput . "\n\t" . $value["First Name"]." ".$value["Last Name"];
        }

        foreach ($international as $key => $value) {

            if (strtolower($value["City"]) != $tempcity) {
                $htmloutput = $htmloutput . "\n</td>\n</tr>\n<tr>\n\t<td><strong>" .$value["City"]. "</strong>\n</td>\n<td>";
                $tempcity = strtolower($value["City"]);
            } else {
                $htmloutput = $htmloutput.",";
            }
            $htmloutput = $htmloutput . "\n\t" . $value["First Name"]." ".$value["Last Name"];
        }

        $htmloutput = $htmloutput . "\n</tr>\n</tbody>\n</table>\n</div>";
        return $htmloutput;
    }
    }

    
    //Commissions requires sorting by degree type

    public function sortByDegree($data,$press) {
        $undergrad = array();
        $grad = array();
        $doc = array();

        $dataobj = json_decode($data, true);

        foreach ($dataobj as $key => $value) {
            if ($value["Division"] == "U" or $value["Division"] == "O") {
                array_push($undergrad, $value);
            } else if ($value["Division"] == "G" or $value["Division"] == "G2") {
                array_push($grad, $value);
            } else  {
                array_push($doc, $value);
            } 
        }




        $undergradoutput = $this->sortComm($undergrad,"Undergraduates",$press)."\n";
        $gradoutput = $this->sortComm($grad,"Master's Degree",$press)."\n";
        $docoutput =  $this->sortComm($doc,"Doctoral",$press);

        $htmloutput = $undergradoutput."<br>".$gradoutput."<br>".$docoutput;
        return $htmloutput;
    }

    public function sortComm ($dataobj, $title, $press_release) {

        $instate = array();
        $outofstate = array();
        $international = array();

        foreach ($dataobj as $key => $value) {
            $output = [];
            $match = preg_match('/\((.*?)\)/', $value["Major 1"], $output);
            $value["Major 1"] = trim(preg_replace('/\((.*?)\)/', '', $value["Major 1"]));
            $value["Major 2"] = trim(preg_replace('/\((.*?)\)/', '', $value["Major 2"]));
            //print_r($output);
           
            if ($match == 1) {
                $code = str_replace('.', '', $output[1]);
                $value["Diploma"]= $value["Diploma Name"]." - ".$code." - ".$value["Major 1"];
            } else {
                $value["Diploma"]= $value["Diploma Name"]." - ".$value["Major 1"];
            }

            if ($value["Major 2"] != "") {
                $value["Diploma"] = $value["Diploma"]." - ".$value["Major 2"];
                //print_r($value["Diploma"]);
            }

           


            

            // $arr[3] will be updated with each value from $arr...
            //$tempobj = new HonorRollEntry($value[3], $value[1], $value[4], $value[5], $value[4], $value[6], $value[7], $value[9], $value[10], $value[11]);
            //$tempobj->combined_personal_info = $this->constructInfo($tempobj->diploma_name, $tempobj->degree_type, $tempobj->major_one, $tempobj->major_two);
            if ($value["State"] == null or $value["State"] == "") {
                $international[] = $value;
            } elseif ($value["State"] == "TX") {
                $instate[] = $value;
            } else {
                $outofstate[] = $value;
            }
            

        }
        //print_r($instate);
        usort($instate, fn($a, $b) => $a["City"] <=> $b["City"]
        ?: $b["State"] <=> $a["State"]
            ?: $a["Last Name"] <=> $b["Last Name"]
                ?: $a["Diploma"] <=> $b["Diploma"]);


        usort($outofstate, fn($a, $b) => $a["State"] <=> $b["State"]
            ?: $a["City"] <=> $b["City"]
                ?: $a["Last Name"] <=> $b["Last Name"]
                    ?: $a["Diploma"] <=> $b["Diploma"]);
    
        usort($international, fn($a, $b) => $a["City"] <=> $b["City"]
            ?: $a["Last Name"] <=> $b["Last Name"]
                ?: $a["Diploma"] <=> $b["Diploma"]);
        
        if ($press_release) {
            $tempcity = "";
            $htmlsoutput = "<div text-container container sub-nav >
            <p><strong>".$title." Degrees</strong></p><div>";
            if (!empty($instate)) {
            $htmloutput = "<div text-container container sub-nav >
                <p><strong>".$title." Degrees</strong></p><div>";
    
            foreach ($instate as $key => $value) {
    
                if (strtolower($value["City"]) != $tempcity) {
                    $htmloutput = $htmloutput . "\n</div><br><div><strong>" .$value["City"]. "</strong></div><div>\n";
                    $tempcity = strtolower($value["City"]);
                } else {
                    $htmloutput = $htmloutput.";";
                }
                $htmloutput = $htmloutput . "\n\t" . $value["Diploma"];
            }
        }
    
            if (!empty($outofstate)) {
    
            foreach ($outofstate as $key => $value) {
    
                if (strtolower($value["City"]) != $tempcity) {
                    $htmloutput = $htmloutput . "</div><br><div><strong>" .$value["City"].", ".$value["State"]. "</strong></div><div>";
                    $tempcity = strtolower($value["City"]);
                } else {
                    $htmloutput = $htmloutput.",";
                }
                $htmloutput = $htmloutput . "\n\t" . $value["Diploma"];
            }
        }
    
            if (!empty($international)) {
            
            foreach ($international as $key => $value) {
    
                if (strtolower($value["Citizen of"]) != $tempcity) {
                    $htmloutput = $htmloutput . "\n</div><br><div><strong>" .$value["Citizen of"]. "</strong></div><div>";
                    $tempcity = strtolower($value["Citizen of"]);
                } else {
                    $htmloutput = $htmloutput.",";
                }
                $htmloutput = $htmloutput . "\n\t" . $value["Diploma"]." ".$value["Diploma"];
            }
        }
    

    
            return $htmloutput;

        } else {
        $tempcity = "";
        if (!empty($instate)) {
        $htmloutput = "<div class=\"text-container container sub-nav\" >
            
                <table class=\"content-table\">
                <tr>
                <h2>Texas ".$title." by City</h2>
                <br>
                </tr>
                <tbody>
                    <tr>
                        <th></th>
                        <th></th>
                    </tr>
                    <tr>
                    <td>";

        foreach ($instate as $key => $value) {

            if (strtolower($value["City"]) != $tempcity) {
                $htmloutput = $htmloutput . "\n</td>\n</tr>\n<tr>\n\t<td><strong>" .$value["City"]. "</strong>\n</td>\n<td>";
                $tempcity = strtolower($value["City"]);
            } else {
                $htmloutput = $htmloutput.";";
            }
            $htmloutput = $htmloutput . "\n\t" . $value["Diploma"];
        }
    }

        if (!empty($outofstate)) {

        $htmloutput = $htmloutput . "\n</tr>\n</tbody>\n</table>\n</div>\n
        <div class=\"text-container container sub-nav\" >
            
                <table class=\"content-table\" >
                <tr>
                <h2>Out-of-State ".$title."</h2>
                <br>
                </tr>
                <tbody>
                    <tr>
                        <th></th>
                        <th></th>
                    </tr>
                    <tr>
                    <td>";

        foreach ($outofstate as $key => $value) {

            if (strtolower($value["City"]) != $tempcity) {
                $htmloutput = $htmloutput . "\n</td>\n</tr>\n<tr>\n\t<td><strong>" .$value["City"].", ".$value["State"]. "</strong>\n</td>\n<td>";
                $tempcity = strtolower($value["City"]);
            } else {
                $htmloutput = $htmloutput.",";
            }
            $htmloutput = $htmloutput . "\n\t" . $value["Diploma"];
        }
    }

        if (!empty($international)) {

        $htmloutput = $htmloutput . "\n</tr>\n</tbody>\n</table>\n</div>\n
        <div class=\"text-container container sub-nav\" >
                <table class=\"content-table\" >
                <tr>
                <h2>International ".$title."</h2>
                <br>
                </tr>
                <tbody>
                    <tr>
                        <th></th>
                        <th></th>
                    </tr>
                    <tr>
                    <td>";
        
        foreach ($international as $key => $value) {

            if (strtolower($value["Citizen of"]) != $tempcity) {
                $htmloutput = $htmloutput . "\n</td>\n</tr>\n<tr>\n\t<td><strong>" .$value["Citizen of"]. "</strong>\n</td>\n<td>";
                $tempcity = strtolower($value["Citizen of"]);
            } else {
                $htmloutput = $htmloutput.",";
            }
            $htmloutput = $htmloutput . "\n\t" . $value["Diploma"]." ".$value["Diploma"];
        }
    }

        $htmloutput = $htmloutput . "\n</tr>\n</tbody>\n</table>\n</div>";

        return $htmloutput;
    }
    }
}

<?php
include_once 'Criteria.php';

/**
 * Description of CriteriaGenerate : <br>
 * Esta clase es la encargada de generar los archivos de las entidades de la base de datos
 * @author edgar
 */
class CriteriaGenerate extends Criteria {

    function __construct($db=null) {
        parent::__construct($db);
    }

    /**
     * Función que genera los archivos de las entidades, dentro de la carpeta generation
    */
    public function generateEntity() {	
        $tables = $this->getDatabaseTables();
        //dpr($this->)
        $route = CRITERIA_PATH_XML_CLASS_GENERATED;
        foreach ($tables as $key => $table) {
            $writeclass = "<?php\n";
            $writeclass .="/* Class autogenerated whith PHPCriteria v1.1 */\n\n";
            $tableName = $table['Name'];
            $writeclass .="/**\n * @Entity(Table=\"$tableName\")\n*/\n";
            $entityName = "Entity" . ucwords($tableName);
            $writeclass .= "class " . $entityName . " {\n\n";
            $descTable[$tableName] = $this->getDescTable($tableName);
            foreach ($this->getDescTable($tableName) as $key_t => $column) {
                $writeclass .= "\t/**\n";
                if($column['Key'] == "PRI")
                    $writeclass .= "\t * @Id\n";
                $writeclass .= "\t * @Column(Field=\"" . $column['Field'] . "\",Type=\"" . $column['Type'] . "\",Key=\"" . $column['Key'] . "\",Null=\"" . $column['Null'] . "\",Default=\"" . $column['Default'] . "\",Extra=\"" . $column['Extra'] . "\")\n";
                if($column['Key'] == "MUL"){
                    $fk = self::findFKGenerateEntity($tableName, $column['Field']);
                    if(count($fk)>0)
                        $writeclass .= "\t * @JoinColumn(Table=\"" . $fk['REFERENCED_TABLE_NAME'] . "\",Column=\"" . $fk['REFERENCED_COLUMN_NAME'] . "\")\n";
                }
                $writeclass .= "\t*/\n";
                $writeclass .= "\tpublic $" . $column['Field'] . ";\n\n";
            }
            $writeclass .= "\tfunction __construct() {}\n";
            $writeclass .= "}\n";
            $writeclass .= "?>";
            $this->escribirArchivo($route . $entityName . ".php", $writeclass, true);
        }
    }

    /**
     * Función que indica si la columna es una llave foránea
     * @param <type> $tableName
     * @param <type> $columnName
     * @return <type> array
     */
    public function findFKGenerateEntity($tableName, $columnName) {
        $schemaArray = parent::getDatabaseSchema($tableName);
        foreach ($schemaArray as $key => $schema) 
            if($schema['COLUMN_NAME'] == $columnName){
                return array("REFERENCED_TABLE_NAME" => $schema['REFERENCED_TABLE_NAME']
                            ,"REFERENCED_COLUMN_NAME" => $schema['REFERENCED_COLUMN_NAME']);
            }
        return null;
    }

    /**
     * Función que escribe los archivos que genera la base de datos (escribe los archivos de la entidad)
     * @param <type> $file
     * @param <type> $writestring
     * @param <type> $create
     */
    private function escribirArchivo($file, $writestring, $create = false) {
        if (file_exists($file) || $create) {
            $handle = fopen($file, "w+");
            //dpr("<BR>".$file);
            if (fwrite($handle, $writestring) === false) {
                throw new Exception("No se puede escribir en el archivo de la ruta " . $file);
            }
            fclose($handle);
        } else {
            throw new Exception("El archivo no exíste en la ruta " . $file);
        }
    }

}

?>

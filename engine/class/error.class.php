<?php

class Error{
	
	function __construct()
        {

	}
        
        /**
        * Trata o erro, escrevendo em logs
        *
        * @param Exception $err Contains an error 
        */
        public function exceptionHandling($err)
        {
            $this->writeFile($err);
            
            if(DEBUG === 'ON')
            {
                $this->trataErro($err);
                exit(0);
            }
            else 
            {
                $query = $err->getMessage()."\n".strval($err->getCode())."\n".$err->getFile()."\n".strval($err->getLine())."\n".$err->getTraceAsString()."\n\n";
                go404("myError.class.php","exceptionHandling",$query,"An error has occurred.");
            }
        }
        
        /**
        * Mostra a informacao do erro
        *
        * @param Exception $err Contains an error 
        */
        private function trataErro($err)
        {
            $trace = '<table border="0">';
            foreach ($err->getTrace() as $a => $b) 
            {
                foreach ($b as $c => $d) 
                {
                    if ($c == 'args') 
                    {
                        foreach ($d as $e => $f) 
                        {
                            $trace .= '<tr><td><b>'.strval($a).'#</b></td><td align="right"><u>args:</u></td> <td><u>'.$e.'</u>:</td><td><i>'.$f.'</i></td></tr>';
                        }
                    } 
                    else 
                    {
                        $trace .= '<tr><td><b>'.strval($a).'#</b></td><td align="right"><u>'.$c.'</u>:</td><td></td><td><i>'.$d.'</i></td>';
                    }
                }
            }
            $trace .= '</table>';
            
            echo '<br /><br /><br />
                <font face="Verdana">
                <center>
                <fieldset style="width: 66%; border: 4px solid white; background: green;"><b>[</b>PHP PDO Error '.strval($err->getCode()).'<b>]</b>
                    <table border="0">
                        <tr>
                            <td align="right"><b><u>PHP Version:</u></b></td>
                            <td style="color:blue;"><i>'.PHP_VERSION.'</i></td>
                        </tr>
                        <tr>
                            <td align="right"><b><u>Message:</u></b></td>
                            <td style="color:blue;"><i>'.$err->getMessage().'</i></td>
                        </tr>
                        <tr>
                            <td align="right"><b><u>Code:</u></b></td>
                            <td><i>'.strval($err->getCode()).'</i></td>
                        </tr>
                        <tr>
                            <td align="right"><b><u>File:</u></b></td>
                            <td style="color:red;"><i>'.$err->getFile().'</i></td>
                        </tr>
                        <tr>
                            <td align="right"><b><u>Line:</u></b></td>
                            <td style="color:red;"><i>'.strval($err->getLine()).'</i></td>
                        </tr>
                        <tr>
                            <td align="right"><b><u>Trace:</u></b></td>
                            <td><br /><br />'.$trace.'</td>
                        </tr>
                    </table>
                </fieldset>
                </center>
                </font>';
        }
        
        /**
        * Escreve a informacao do erro em logs
        *
        * @param Exception $err Contains an error 
        */
        private function writeFile($err)
        {
            if(LOGS === "ON")
            {
                $filename = ENGINE_PATH."logs/log.txt";
                $today = date("F j, Y, l, H:i:s");  
                $fh = fopen($filename, 'a+') or die("Can't open file");
                fwrite($fh, "Date: " .$today ."\n");
                fwrite($fh, "Php Version: " .PHP_VERSION ."\n");
                fwrite($fh, "Mensage: " .$err->getMessage() ."\n");
                fwrite($fh, "Code: " .strval($err->getCode()) ."\n");
                fwrite($fh, "File: " .$err->getFile() ."\n");
                fwrite($fh, "Line: " .strval($err->getLine()) ."\n");
                fwrite($fh, "Trace: \n" .$err->getTraceAsString() ."\n\n");
                fwrite($fh, "------------------------------------------------------------------------\n\n");
                fclose($fh);

                clearstatcache(); 
            }
        }
        
        /**
        * Escreve a informacao do erro em logs
        *
        * @param String $nameFile (optional) Retrieve the name file
        * @param String $function Retrieve the name function
        * @param String $query Retrieve the sql query
        * @param String $message Retrieve the user message
        */
        public function writeFileGo404($nameFile="",$function="",$query="",$message="")
        {
            if(LOGS === "ON")
            {
                $filename = ENGINE_PATH."logs/log_go404.txt";
                $today = date("F j, Y, l, H:i:s");  
                $fh = fopen($filename, 'a+') or die("Can't open file");
                fwrite($fh, "Date: " .$today ."\n");
                fwrite($fh, "Php Version: " .PHP_VERSION ."\n");
                fwrite($fh, "Name File: " .$nameFile ."\n");
                fwrite($fh, "Function: " .$function ."\n");
                fwrite($fh, "Query: " . print_r($query,true) ."\n");
                fwrite($fh, "Message: " .$message ."\n");
                fwrite($fh, "------------------------------------------------------------------------\n\n");
                fclose($fh);

                clearstatcache(); 
            }
        }
}
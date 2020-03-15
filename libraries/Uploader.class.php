<?php

defined('_EXEC') or die;

/**
* @package valkyrie.libraries
*
* @summary Stock de funciones para manejar archivos en el servidor.
*
* @author Gersón Aarón Gómez Macías <ggomez@codemonkey.com.mx>
* <@create> 01 de enero, 2019.
*
* @version 1.0.0.
*
* @copyright Code Monkey <contacto@codemonkey.com.mx>
*/

class Uploader
{
    /**
    * @summary: Sube archivos al servidor.
    *
    * @param file $file: Archivo a subir.
    * @param string $upload_directory: Directorio donde se va subir $file.
    * @param array $valid_extensions: Extensiones válidas para subir $file.
    * @param string $maximum_file_size: Tamaño máximo permitido para subir $file.
    * @param boolean $multiple: Identificador para subir multiples archivos.
    *
    * @return string
    */
    public static function up($file, $upload_directory = PATH_UPLOADS, $valid_extensions = ['png','jpg','jpeg'], $maximum_file_size = 'unlimited', $multiple = false)
	{
        if (!empty($file))
        {
            $components = new Components;

            $components->load_component('uploader');

            $uploader = new Uploader;

            if ($multiple == true)
            {
                foreach ($file as $key => $value)
                {
                    $uploader->set_file_name($value['name']);
                    $uploader->set_file_temporal_name($value['tmp_name']);
                    $uploader->set_file_type($value['type']);
                    $uploader->set_file_size($value['size']);
                    $uploader->set_upload_directory($upload_directory);
                    $uploader->set_valid_extensions($valid_extensions);
                    $uploader->set_maximum_file_size($maximum_file_size);

                    $value = $uploader->upload_file();

                    if ($value['status'] == 'success')
                        $file[$key] = $value['file'];
                    else
                        unset($file[$key]);
                }

                $file = array_merge($file);
            }
            else if ($multiple == false)
            {
                $uploader->set_file_name($file['name']);
                $uploader->set_file_temporal_name($file['tmp_name']);
                $uploader->set_file_type($file['type']);
                $uploader->set_file_size($file['size']);
                $uploader->set_upload_directory($upload_directory);
                $uploader->set_valid_extensions($valid_extensions);
                $uploader->set_maximum_file_size($maximum_file_size);

                $file = $uploader->upload_file();

                if ($file['status'] == 'success')
                    $file = $file['file'];
                else
                    $file = null;
            }

            return $file;
        }
        else
            return null;
	}

    /**
    * @summary: Elimina archivos del servidor.
    *
    * @param string $file: Archivo a eliminar.
    * @param string $upload_directory: Directorio de donde se va eliminar $file.
    */
    public static function down($file, $upload_directory = PATH_UPLOADS)
    {
        if (!empty($file))
        {
            if (is_array($file))
            {
                foreach ($file as $value)
                    unlink($upload_directory . $value);
            }
            else
                unlink($upload_directory . $file);
        }
    }
}

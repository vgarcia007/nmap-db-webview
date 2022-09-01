<?php

require_once('classes/db.php');
require_once('classes/class.upload.php');

class ICONS extends DB
{

    public function list_all_icons(){
        $files = glob('/var/www/html/img/logos/*.png');
        $data = array();
        foreach ($files as $key => $file) {
            array_push($data, str_replace('/var/www/html/img/logos/', '', $file));

        }
        return $data;
    }

    public function list_all_assignments(){
        $data = array();
        
        $query = $this->pdo->prepare("SELECT * FROM icons ORDER BY match_in");
        $result = $query->execute(array());
        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            array_push($data, $row);
        }
    
        return $data;
    }
    public function assign_icon($form_data){
        $this->insert('icons', $form_data);
    }
    
    public function upload_icon_picture($new_filename, $file){
        
        $handle = new \Verot\Upload\Upload($file);

        $handle->image_resize          = true;
        $handle->image_ratio_fill      = true;
     
        $handle->image_y              = 64;
        $handle->image_x              = 64;
        $handle->file_overwrite = false;
        $handle->file_force_extension = true;
     
        $handle->forbidden = array('application/*');
        $handle->image_convert = 'png';
        $handle->file_new_name_body = substr($new_filename, 0, strrpos($new_filename, "."));

        if ($handle->uploaded) {

            $handle->Process('/var/www/html/img/logos/');
        
            $file_dst_pathname = $handle->file_dst_pathname;
        
            if ($handle->processed) {
        
                $data['picture'] = $new_filename;
                $response['file_dst_pathname'] = $file_dst_pathname;

            }else{
                $response['state'] = 'error';
                $response['message'] = 'error - processing file';
                return $response;
            
            }
        }else{
            $response['state'] = 'error';
            $response['message'] = 'error - can not upload to dir';
            return $response;
           
        }
        $response['state'] = 'success';

        return $response;
    }


}

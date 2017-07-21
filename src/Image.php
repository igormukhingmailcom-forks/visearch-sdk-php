<?php

namespace ViSearch;

class Image
{
    /// Construct an object describing an image.

    /// Arguments:
    /// `file`, the path to the image, if it is to be taken from a file. or a upload file object
    function __construct($file = '', $resizeSettings = NULL)
    {
        if(!empty($file)) {
            $this->resizeSettings = $resizeSettings;
            $this->local_filepath = $file;
            if( ! $this->is_http_image($file)){
                if(is_string($file) && !$this->__startsWith($file,'/') ){
                    // a relative file
                    $this->local_filepath = realpath(dirname($_SERVER["SCRIPT_FILENAME"])).'/'.$file;
                }else if(is_array($file) && array_key_exists('tmp_name', $file) ){
                    $this->local_filepath = $file['tmp_name'];
                }
            }
        }
    }

    /// Return a human-readable description of the object.
    function __toString()
    {
        return "Image(local_filepath=$this->local_filepath)";
    }

    function __startsWith($haystack, $needle)
    {
        return $needle === "" || strpos($haystack, $needle) === 0;
    }

    function set_file_path($file) {
        $this->local_filepath = $file;
        if( ! $this->is_http_image($file)){
            if(is_string($file) && !$this->__startsWith($file,'/') ){
                // a relative file
                $this->local_filepath = realpath(dirname($_SERVER["SCRIPT_FILENAME"])).'/'.$file;
            }else if(is_array($file) && array_key_exists('tmp_name', $file) ){
                $this->local_filepath = $file['tmp_name'];
            }
        }
    }

    function set_resize_setting($resize_setting) {
        $this->resizeSettings = $resizeSettings;
    }

    function get_path()
    {
        return $this->local_filepath;
    }
    function set_box($box){
        $this->box = $box;
    }
    function get_box()
    {
        if(isset($this->box)){
            return $this->box;
        }else {
            return NULL;
        }
    }
    function get_box_parse()
    {
        $box = $this->box;
        return $box->x1.",".$box->y1.",".$box->x2.",".$box->y2;
    }
    function is_http_image() {
        if(is_string($this->local_filepath))
            return $this->__startsWith($this->local_filepath,"http://") || $this->__startsWith($this->local_filepath,"https://");
        else
            return false;
    }

    function is_valid_image() {
        if(!isset($this->im_id) && !isset($this->local_filepath))
            return false;
        else
            return true;
    }

    function get_im_id() {
        if(isset($this->im_id)){
            return $this->im_id;
        }else {
            return NULL;
        }
    }

    function set_im_id($im_id) {
        if(empty($im_id) && !is_null($im_id))
            throw new ViSearchException('Empty im_id');
        $this->im_id = $im_id;
    }
}

<?php
use Restserver\Libraries\REST_Controller;




require(APPPATH.'/libraries/REST_Controller.php');
require_once(APPPATH.'/libraries/Format.php');

class Api extends REST_Controller{
    
    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
$method = $_SERVER['REQUEST_METHOD'];
if($method == "OPTIONS") {
die();
}


        parent::__construct();
        

        $this->load->model('book_model');
        $this->load->helper('text');
        $this->load->helper(array('form', 'url')); 

    }

    //API - client sends isbn and on valid isbn book information is sent back
    function bookByIsbn_get(){

        $isbn  = $this->get('isbn');
        
        if(!$isbn){

            $this->response("No ISBN specified", 400);

            exit;
        }

        $result = $this->book_model->getbookbyisbn( $isbn );

        if($result){

            $this->response($result, 200); 

            exit;
        } 
        else{

             $this->response("Invalid ISBN", 404);

            exit;
        }
    } 

    //API -  Fetch All books
    function posts_get(){

        $result = $this->book_model->getallposts();
        
        if($result){
            $this->response($result, 200); 
        } 
        else{
            $this->response([], 200);
        }
    }
     
    //API - create a new book item in database.
    function addNewPost_post(){
     
         $post_type     = $this->post('post_type');
         $article_title    = $this->post('article_title');
         $article_content  = $this->post('article_content');  
         $uploadData =       $this->post('uploadData');  
         if( !$article_title || !$article_content ){
                $this->response("Enter complete Post information  to save", 400);
         }else{
            $result = $this->book_model->add(array(  "article_title"=>$article_title, "article_content"=>$article_content,"uploadData"=>$uploadData ));
            if($result === 0){
                $this->response("Post information Could not be saved. Try again.", 404);
            }else{
                $post_data = array('item_type_id' => '200');
                $this->response( $post_data, 200);  
            }
        }
    }

    //API - update a book 
    function updatePosts_put(){
   
        $id = $this->put('post_ID');
        $article_title    = $this->put('article_title');
        $article_content  = $this->put('article_content'); 
        $uploadData =       $this->put('uploadData');        
        if( !$article_title || !$article_content || !$uploadData ){
                $this->response("Enter complete Post information to save", 400);
         }else{
            $result = $this->book_model->update($id, array(  "article_title"=>$article_title, "article_content"=>$article_content , "uploadData"=>$uploadData));
            if($result === 0){
                $this->response("Book information could not be saved. Try again.", 404);
            }else{
                $this->response("success", 200);  
            }
        }
    }

    //API - delete a book 
    function deletePosts_delete()
    {
        $id = $this->uri->segment(3);
        if(!$id){ $this->response("Parameter missing", 400);
        }
        if($this->book_model->delete($id))
        {
            $this->response("Success", 200);
        } 
        else
        {
            $this->response("Failed", 400);
        }
    }
    public function uploadImg_post()
    {
        $this->load->library('upload') ; 
        if($_FILES['myFile']['size'] > 0 ) {
            $config['upload_path'] = './uploads';
            $config['allowed_types'] = "*";
           // $config['max_size'] = '10000';
         
            $config['max_filename'] = 25 ; 
            $config['overwrite'] = TRUE ; 
            $this->load->library('upload', $config);
            $this->upload->initialize($config);    

            if(!$this->upload->do_upload('myFile')) {
                $error = array('error' => $this->upload->display_errors());
                $this->response($error , 500) ;
            } else {
                $data = array('upload_data' => $this->upload->data());
                $this->response($data , 200) ;
                //$this->load->view('upload_success', $data);

 
            }
           // $this->response( $_FILES['myFile'] , 200) ;

        }


    //     print_r($this->input->post());die;
    //   $imageFile =  $_POST['img'] ; 
    //   if(!$imageFile ){
    //     $this->response("Image not found. Try again.", 404);
    //   }
    //   if(move_uploaded_file($_FILES['image']['tmp_name'], )
// $image = base64_decode($imageFile);
// $image_name = md5(uniqid(rand(), true));

// $filename = $image_name . '.' . 'jpg';
// //rename file name with random number
// $path = "blogimages/".$filename;
// file_put_contents($path . $filename, $image);
// $data_insert = array('name'=>$filename);

// $success = $this->book_model->insert_img($data_insert);
// echo 'Success'.$success ; 
// if($success){
//     $b = "User Registered Successfully..";
// }
// else
// {
//     $b = "Some Error Occurred. Please Try Again..";
// }
// echo json_encode($b);
}

}
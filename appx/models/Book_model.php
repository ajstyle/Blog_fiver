<?php
  class Book_model extends CI_Model {
      public function __construct(){
        $this->load->database();
      }
      
      //API call - get a book record by isbn
      public function getbookbyisbn($isbn){  
           $this->db->select('id, name, price, author, category, language, ISBN, publish_date');
           $this->db->from('tbl_books');
           $this->db->where('isbn',$isbn);
           $query = $this->db->get();
           if($query->num_rows() == 1)
           {
               return $query->result_array();
           }
           else
           {
             return 0;
          }
      }

    //API call - get all books record
    public function getallposts(){   
        $this->db->select('post_ID,	post_date	,post_status,	post_type	,post_has_article,	uploadData , article_title	,article_content');
        $this->db->from('posts');
        $this->db->order_by("post_ID", "desc"); 
        $query = $this->db->get();
        if($query->num_rows() > 0){
          return $query->result_array();
        }else{
          return 0;
        }
    }
   
   //API call - delete a book record
    public function delete($id){
       $this->db->where('post_ID', $id);
       if($this->db->delete('posts')){
          return true;
        }else{
          return false;
        }
   }
   
   //API call - add new book record
    public function add($data){
        if($this->db->insert('posts', $data)){
           return true;
        }else{
           return false;
        }
    }

    
    //API call - update a book record
    public function update($id, $data){
     
       $this->db->where('post_ID', $id);
       if($this->db->update('posts', $data)){
          return true;
        }else{
          return false;
        }
    }

    public function insert_img($data_insert){
      echo $data_insert ; 
      $this->db->insert('tbl_books',$data_insert);
      }
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Login_controller extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('item');
	}
	
	public function index(){
		if($this->session->userdata('id')){
			$this->load->view('admin/admin_panel/index');
		}
		else{
			$this->load->view('user/signin');
		}
		
	}
	public function signup(){
		$this->form_validation->set_rules('name','Name','required|trim|min_length[4]|max_length[12]');
		$this->form_validation->set_rules('email','Email','required|trim|valid_email');
		$this->form_validation->set_rules('mobile','Mobile','required|trim|min_length[10]|max_length[10]');
		$this->form_validation->set_rules('password','Password','required|trim|min_length[6]|max_length[10]');
		$this->form_validation->set_rules('cpassword','Confirm Password','required|trim|min_length[6]|max_length[10]');
		if($this->form_validation->run() == FALSE){
			$this->session->set_flashdata('error_message','Yor are not signup ! Pleses try again..');
			$this->load->view('user/signup');
		}
		else{
			$name = $this->input->post('name');
			$email = $this->input->post('email');
			$mobile = $this->input->post('mobile');
			$password =  $this->input->post('password');
			$cpassword =  $this->input->post('cpassword');
			$data = array('name'=>$name,'email'=>$email,'mobile'=>$mobile,'password'=>$password);
			//print_r($data);
			if($password == $cpassword){
				$result=$this->item->signup($data);
				if($result){
					$this->load->view('user/signin');
				}
				else{
					$this->load->view('user/signup');
				}
			}
			else{
				$this->load->library('session');
				$this->session->set_flashdata('error_message','password not match Try again..');
				$this->load->view('user/signup');
			}
		}
	}
	public function login_pro(){
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[6]|max_length[10]');
		if($this->form_validation->run() == FALSE){
			$this->session->set_flashdata('error_message','Plese fill all record..');
			$this->load->view('user/signin');
		}
		else{
			$email = $this->input->post('email');
			$pass =  $this->input->post('password');
			$this->load->model('Login_model');
			$data=$this->Login_model->login($email, $pass); 
			if($data){
				$id=$data->id;
				$this->session->set_userdata('id',$id);
				$this->load->view('admin/admin_panel/index');
			}
			else{
				$this->load->library('session');
				$this->session->set_flashdata('error_message','Invalid Email or password Try again..');
				$this->load->view('user/signin');
			}
		}
	}
	public function logout(){
		$this->session->unset_userdata('id');
		return redirect('login_controller');
	}
	public function dashboard(){
		$this->load->view('admin/admin_panel/index');
	}
	/*--Brand--*/
	public function add_new(){
		$this->load->view('admin/admin_panel/add_brand_form');
	}
	public function insert_brand(){
		if ($this->form_validation->run('brand') == FALSE){
		$this->load->view('admin/admin_panel/add_brand_form');
		}
		else
				{
				$brand=$this->input->post('brand_name');
				$date=date('d:m:y h:m:s');
				$data=array(
					'brand_name'=>$brand,
					'created_date'=>$date
				);
				$res=$this->item->insert_brand($data);
				if($res){
					$this->session->set_flashdata('brand_res', 'One Brand added successfully !');
					return redirect('login_controller/insert_brand');
				}
				else{
					$this->session->set_flashdata('brand_res', 'Brand is already Exist !');
					return redirect('login_controller/insert_brand');
				}
		}
	}
	public function list_brand(){
		$result=$this->item->list_brand();
		if($result){
//echo "<pre>"; print_r($result);
	$this->load->view('admin/admin_panel/list_brand',['data'=>$result]);
	}
	else{
	$this->load->view('admin/admin_panel/list_brand',['data'=>$result]);
	}
	}
	public function edit_brand($id){
	$data=$this->item->edit_brand($id);
	$this->load->view('admin/admin_panel/edit_brand',['result'=>$data]);
	}
	public function update_brand($id){
	$this->form_validation->set_rules('brand_name', 'Brandname', 'required');
	if($this->form_validation->run() == FALSE){
	$data=$this->item->edit_brand($id);
	$this->load->view('admin/admin_panel/edit_brand',['result'=>$data]);
	}
	else{
	$brand = $this->input->post('brand_name');
	$data=array(
	'brand_name'=>$brand,
	'created_date'=>date('d:m:y h:m:s')
	);
	$res=$this->item->update_brand($id,$data);
	if($res){
	$this->session->set_flashdata('brands', 'One Brand update successfully !');
	return redirect('login_controller/list_brand');
	}
	else{
	echo "Not Update";
	}
	}
	}
	public function delete_brand($id)
	{
	$res=$this->item->delete_brand($id);
	if($res){
	$this->session->set_flashdata('brands', 'One Brand delete successfully !');
	return redirect('login_controller/list_brand');
	}
	else{
	echo "Record Not Delete";
	}
	}

	/*--Category--*/
	public function add_new_category(){
		$this->load->view('admin/admin_panel/add_category');
	}
	public function insert_category(){
		if ($this->form_validation->run('category') == FALSE){
		$this->load->view('admin/admin_panel/add_category');
		}
		else
				{
				$category=$this->input->post('category_name');
				$date=date('d:m:y h:m:s');
				$data=array(
					'ct_name'=>$category,
					'created_date'=>$date
				);
				$res=$this->item->insert_category($data);
				if($res){
					$this->session->set_flashdata('category_res', 'One Category added successfully !');
					return redirect('login_controller/insert_category');
				}
				else{
					$this->session->set_flashdata('category_res', 'Category Already Exist !');
					return redirect('login_controller/insert_category');
				}
		}
	}
	public function list_category(){
		$result=$this->item->list_category();
		if($result){
	$this->load->view('admin/admin_panel/list_category',['data'=>$result]);
	}
	else{
	$this->load->view('admin/admin_panel/list_category',['data'=>$result]);
	}
	}
	public function edit_category($id){
	$data=$this->item->edit_category($id);
	$this->load->view('admin/admin_panel/edit_category',['result'=>$data]);
	}
	public function update_category($id){
	$this->form_validation->set_rules('category_name', 'Category', 'required');
	if($this->form_validation->run() == FALSE){
	$data=$this->item->edit_category($id);
	$this->load->view('admin/admin_panel/edit_category',['result'=>$data]);
	}
	else{
	$category = $this->input->post('category_name');
	$data=array(
	'ct_name'=>$category,
	'created_date'=>date('d:m:y h:m:s')
	);
	$res=$this->item->update_category($id,$data);
	if($res){
		$this->session->set_flashdata('categorys', 'One Category Updated successfully !');
	return redirect('login_controller/list_category');
	}
	else{
	echo "Not Update";
	}
	}
	}
	public function delete_category($id)
	{
	$res=$this->item->delete_category($id);
	if($res){
		$this->session->set_flashdata('categorys', 'One Category delete successfully !');
	return redirect('login_controller/list_category');
	}
	else{
	echo "Record Not Delete";
	}
	}

	/*--Items--*/
	public function add_item(){
		$result['cat']=$this->item->list_category();
		$result['brand']=$this->item->list_brand();
		$this->load->view('admin/admin_panel/add_item',['result'=>$result]);
	}
	public function insert_item(){
		if($this->form_validation->run('item') == FALSE){
		$this->form_validation->set_error_delimiters('<div class="error" style="color:red;font-size:12px">','</div>');
		return $this->add_item();
		}
		else if(empty($_FILES['upload_file']['name'])){
			$this->form_validation->set_rules('upload_file','file','required');
			return $this->add_item();
		}
		else{
			 $config['upload_path']          = './assets/img/';
                $config['allowed_types']        = 'jpg|png';
                $config['max_size']             = 3000;

                $this->load->library('upload', $config);
                //print_r($config);die;
                if ( ! $this->upload->do_upload('upload_file'))
                {
                        $error = array('error' => $this->upload->display_errors());

                        print_r($error);die;
                }
                else
                {
                	$file_name = $this->upload->data();
                	$data['img'] = $file_name['file_name'];
                	$data['item_name'] = $this->input->post('item_name'); 
                	$data['price'] = $this->input->post('item_price'); 
                	$data['brand_id'] = $this->input->post('brand_id'); 
                	$data['cat_id'] = $this->input->post('cat_id');
                	$data['des'] = $this->input->post('des'); 
                	$data['created_date'] = date('d:m:y h:m:s');
                	//print_r($data);

                	$result = $this->item->add_items($data);
                	if($result){
                		$this->session->set_flashdata('record', 'One Record added successfully !');
                		return $this->add_item();
                	}
                	else{
                		echo "Querry Error";
                	}
                }
		}
	}
	public function list_item(){
		$result=$this->item->list_item();
		if($result){
			//echo "<pre>";print_r($result);die;
			$this->load->view('admin/admin_panel/list_item',['data'=>$result]);
		}
		else{
			$this->load->view('admin/admin_panel/list_item',['data'=>$result]);
		}
	}
	public function edit_items($id){
		$data=$this->item->edit_item($id);
		$this->load->view('admin/admin_panel/edit_item',['result'=>$data]);

	}
	public function update_item($id){
		if($_FILES['upload_file']['name']){
			$config['upload_path']          = './assets/img/';
            $config['allowed_types']        = 'jpg|png';
            $config['max_size']             = 3000;

            $this->load->library('upload', $config);
            if ( ! $this->upload->do_upload('upload_file'))
                {
                        $error = array('error' => $this->upload->display_errors());

                        print_r($error);die;
                }
                else
                {
                	$file_name = $this->upload->data();
                	$data['img'] = $file_name['file_name'];
                	if($this->form_validation->run('item') == FALSE){
						$this->form_validation->set_error_delimiters('<div class="error" style="color:red;font-size:12px">','</div>');
						return $this->edit_items($id);
					}
					else{
						$data['item_name'] = $this->input->post('item_name'); 
				    	$data['price'] = $this->input->post('item_price'); 
				    	$data['brand_id'] = $this->input->post('brand_id'); 
				    	$data['cat_id'] = $this->input->post('cat_id');
				    	$data['des'] = $this->input->post('des'); 
				    	$data['created_date'] = date('d:m:y h:m:s');
				    	$item_id = $id;

						$result = $this->item->update_items($data,$item_id);
				                	if($result){
				                		$this->session->set_flashdata('record', 'One Record updated successfully !');
				                		return $this->edit_items($id);
				                	}
				                	else{
				                		echo "Querry Error";
				                	}
					}
                }

		}
		if($this->form_validation->run('item') == FALSE){
			$this->form_validation->set_error_delimiters('<div class="error" style="color:red;font-size:12px">','</div>');
			return $this->edit_items($id);
		}
		else{
			$data['item_name'] = $this->input->post('item_name'); 
	    	$data['price'] = $this->input->post('item_price'); 
	    	$data['brand_id'] = $this->input->post('brand_id'); 
	    	$data['cat_id'] = $this->input->post('cat_id');
	    	$data['des'] = $this->input->post('des'); 
	    	$data['created_date'] = date('d:m:y h:m:s');
	    	$item_id = $id;
	    	$res=$this->item->edit_item($item_id);
	    	foreach($res as $rem){
	    		$images=$rem->image;
	    	}
	    	$data['img'] = $images;

			$result = $this->item->update_items($data,$item_id);
	                	if($result){
	                		$this->session->set_flashdata('record', 'One Record updated successfully !');
	                		return $this->edit_items($id);
	                	}
	                	else{
	                		echo "Querry Error";
	                	}
		}
		
	}
	public function delete_items($id){
		$res=$this->item->delete_item($id);
		if($res){
			$this->session->set_flashdata('item_res', 'One item delete successfully !');
		return redirect('login_controller/list_item');
		}
		else{
		$this->session->set_flashdata('item_res', 'Item Not Delete Try again !');
		return redirect('login_controller/list_item');		}
	}
	public function contact_us(){
		$result=$this->item->contact_us();
		$this->load->view('admin/admin_panel/contact',['data'=>$result]);
	}
	public function show_order(){
		$result=$this->item->show_order();
		$this->load->view('admin/admin_panel/order',['data'=>$result]);
	}
	public function delete_order($id){
		$res=$this->item->delete_order($id);
		if($res){
			$this->session->set_flashdata('order', 'One item delete successfully !');
		return redirect('login_controller/show_order');
		}
		else{
		$this->session->set_flashdata('order', 'Item Not Delete Try again !');
		return redirect('login_controller/show_order');		}
	}

}

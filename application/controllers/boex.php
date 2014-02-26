<?php
class BoEx extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('boex_model');
	}

	public function index() {
		$data['loggedin'] = $this->session->userdata('loggedin');
		$data['username'] = $this->session->userdata('username');
		$data['title'] = 'WhBoEx';

		$data['activeoffers'] = $this->boex_model->getActiveSales(FALSE, 5);
		$data['legendtitle'] = 'Most Recent Offers:';

		$this->load->view('templates/header', $data);
		$this->load->view('mainpage', $data);
		$this->load->view('buying', $data);
		$this->load->view('templates/footer', $data);
	}

	public function buying() {
		$data['loggedin'] = $this->session->userdata('loggedin');
		$data['username'] = $this->session->userdata('username');
		$data['title'] = 'Buying';

		$data['activeoffers'] = $this->boex_model->getActiveSales();
		$data['legendtitle'] = 'All offers:';

		$this->load->view('templates/header', $data);
		$this->load->view('buying', $data);
		$this->load->view('templates/footer', $data);
	}

	public function courseformat($str) {
		$courseformatpattern = '([A-Z]{2}[A-Z]?-[0-9]{2})';
		$test1 = preg_match($courseformatpattern, $str);

		if ($test1) {
			return TRUE;
		}
		else {
			$this->form_validation->set_message('courseformat', 'Invalid %s - It has to be formatted like "MATH-101" for readability.');
			return FALSE;
		}
	}

	public function selling() {
		$data['loggedin'] = $this->session->userdata('loggedin');
		$data['username'] = $this->session->userdata('username');
		$data['title'] = 'Selling';

		if ($this->session->userdata('loggedin')) {
			$data['activeoffers'] = $this->boex_model->getActiveSales($this->session->userdata('username'));
		}
		else {
			$data['activeoffers'] = array();
		}

		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('title', 'Title', 'required');
		$this->form_validation->set_rules('price', 'Price', 'required');
		$this->form_validation->set_rules('course', 'Course Code', 'trim|callback_courseformat');
		$this->form_validation->set_rules('isbn', 'ISBN', '');
		$this->form_validation->set_rules('description', 'Description', '');

		if ($this->form_validation->run() == FALSE) {
			$this->load->view('templates/header', $data);
			$this->load->view('selling', $data);
			$this->load->view('account', $data);
			$this->load->view('templates/footer', $data);
		}
		else {
			$this->boex_model->createOffer();

			$this->load->helper('url');
			redirect('selling');
		}
	}

	public function search() {
		$data['loggedin'] = $this->session->userdata('loggedin');
		$data['username'] = $this->session->userdata('username');
		$data['title'] = 'Searching';

		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('title', 'Title', '');
		$this->form_validation->set_rules('author', '', '');
		$this->form_validation->set_rules('isbn', '', '');
		$this->form_validation->set_rules('wildcard', '', '');
		$this->form_validation->run();


		if ($this->input->post('isbn') != '') {
			$searchquerystring = 'isbn:' . $this->input->post('isbn');
		}
		elseif ($this->input->post('title') != '' and $this->input->post('author') != '') {
			$searchquerystring = 'intitle:' . $this->input->post('title') . '+' . 'inauthor:' . $this->input->post('author');
		}
		elseif ($this->input->post('title') != '') {
			$searchquerystring = 'intitle:' . $this->input->post('title');
		}
		elseif ($this->input->post('author') != '') {
			$searchquerystring = 'inauthor:' . $this->input->post('author');
		}
		elseif ($this->input->post('wildcard') != '') {
			$searchquerystring = $this->input->post('wildcard');
		}

		$maxNumBooks = 40;
		if (isset($searchquerystring)) {
			$matchingbooks = $this->boex_model->searchGoogle($searchquerystring, $maxNumBooks);
		}

		if(isset($matchingbooks)) {
			$data['searchresults'] = generateSearchResults($matchingbooks);
		}

		$data['numresults'] = $maxNumBooks;

		$this->load->view('templates/header', $data);
		$this->load->view('search', $data);
		$this->load->view('templates/footer', $data);
	}

	public function viewbook($googleid) {
		$data['loggedin'] = $this->session->userdata('loggedin');
		$data['username'] = $this->session->userdata('username');
		$data['title'] = 'Book';

		$this->load->helper('form');
		$this->load->library('form_validation');

		$data['googleid'] = $googleid;

		$i = 0;
		while (!isset($string_contents) and $i < 5) {
			$string_contents = @file_get_contents('https://www.googleapis.com/books/v1/volumes/' . $googleid);
			$i++;
		}
		
		$bookinfo = json_decode($string_contents);

		$data['title'] = isset($bookinfo->volumeInfo->title) ? $bookinfo->volumeInfo->title : '';
		$data['subtitle'] = isset($bookinfo->volumeInfo->subtitle) ? $bookinfo->volumeInfo->subtitle : '';
		$data['authors'] = isset($bookinfo->volumeInfo->authors) ? $bookinfo->volumeInfo->authors : array();
		$data['publisher'] = isset($bookinfo->volumeInfo->publisher) ? $bookinfo->volumeInfo->publisher : '';
		$data['publishdate'] = isset($bookinfo->volumeInfo->publishedDate) ? $bookinfo->volumeInfo->publishedDate : '';
		$data['description'] = isset($bookinfo->volumeInfo->description) ? $bookinfo->volumeInfo->description : '';
		$data['infolink'] = isset($bookinfo->volumeInfo->infoLink) ? $bookinfo->volumeInfo->infoLink : '';
		$data['listprice'] = isset($bookinfo->saleInfo->listPrice->amount) ? $bookinfo->saleInfo->listPrice->amount : '';
		$data['listpricetype'] = isset($bookinfo->saleInfo->listPrice->currencyCode) ? $bookinfo->saleInfo->listPrice->currencyCode : '';
		$data['retailprice'] = isset($bookinfo->saleInfo->retailPrice->amount) ? $bookinfo->saleInfo->retailPrice->amount : '';
		$data['retailpricetype'] = isset($bookinfo->saleInfo->retailPrice->currencyCode) ? $bookinfo->saleInfo->retailPrice->currencyCode : '';

		// Truncating length of book title if excessive.
		$MAX_LENGTH_OF_TITLE = 75;
		$data['title'] = strlen($data['title']) > $MAX_LENGTH_OF_TITLE ? substr($data['title'], 0, $MAX_LENGTH_OF_TITLE) . '&#8230;' : $data['title'];
		$data['subtitle'] = strlen($data['subtitle']) > $MAX_LENGTH_OF_TITLE ? substr($data['subtitle'], 0, $MAX_LENGTH_OF_TITLE) . '&#8230;' : $data['subtitle'];
		
		$data['imagelink'] = getLargestImage($bookinfo);

		// Getting ISBN numbers
		if(isset($bookinfo->volumeInfo->industryIdentifiers)) {
			foreach($bookinfo->volumeInfo->industryIdentifiers as $identifier) {
				$data[$identifier->type] = $identifier->identifier;
			}			
		}
		$data['ISBN_10'] = isset($data['ISBN_10']) ? $data['ISBN_10'] : '';
		$data['ISBN_13'] = isset($data['ISBN_13']) ? $data['ISBN_13'] : '';


		$this->load->view('templates/header', $data);
		$this->load->view('viewbook', $data);
		$this->load->view('templates/footer', $data);
	}

	public function offerbook($googleid) {
		$this->load->helper('form');
		$this->load->library('form_validation');

		// $this->form_validation->set_rules('username', 'Username', 'callback_usernameexists');
		$this->form_validation->set_rules('title', 'Title', 'required|max_length[75]');
		$this->form_validation->set_rules('authors', 'Authors', 'required|max_length[75]');
		$this->form_validation->set_rules('price', 'Price', 'required|integer|less_than[500]');
		$this->form_validation->set_rules('isbn', 'ISBN-10', 'required');
		$this->form_validation->set_rules('isbnv2', 'ISBN-13', 'required');


		if ($this->form_validation->run() == FALSE) {
			$data['loggedin'] = $this->session->userdata('loggedin');
			$data['username'] = $this->session->userdata('username');
			$data['title'] = 'Book Offer';

			$data['title'] = $this->input->post('title');
			$data['authors'] = $this->input->post('authors');
			$data['ISBN_10'] = str_replace('-', '', $this->input->post('isbn'));
			$data['ISBN_13'] = str_replace('-', '', $this->input->post('isbnv2'));

			$data['googleid'] = $googleid;
			
			$this->load->view('templates/header', $data);
			$this->load->view('offerbook', $data);
			$this->load->view('templates/footer', $data);
		}
		else {
			$this->boex_model->createOffer();

			$this->load->helper('url');
			redirect('/account');
		}	
	}

	public function account() {
		$data['loggedin'] = $this->session->userdata('loggedin');
		$data['username'] = $this->session->userdata('username');
		$data['title'] = 'Account';

		if ($this->session->userdata('username') != FALSE) {
			$data['activeoffers'] = $this->boex_model->getActiveSales($this->session->userdata('username'));
		}
		else {
			$this->load->helper('url');
			redirect('login');
		}

		$this->load->view('templates/header', $data);
		$this->load->view('account', $data);
		$this->load->view('templates/footer', $data);
	}

	public function deleteoffer($id) {
		$this->boex_model->deleteOffer($id);

		$this->load->helper('url');
		redirect('account');
	}

	public function editoffer($id) {
		$data['loggedin'] = $this->session->userdata('loggedin');
		$data['username'] = $this->session->userdata('username');
		$data['title'] = 'Edit Offer';

		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('title', 'Title', 'required');
		$this->form_validation->set_rules('price', 'Price', 'required');
		$this->form_validation->set_rules('course', 'Course Code', 'trim|callback_courseformat');
		$this->form_validation->set_rules('isbn', 'ISBN', '');
		$this->form_validation->set_rules('description', 'Description', '');

		if ($this->form_validation->run() == FALSE) {
			$this->load->view('templates/header', $data);
			$this->load->view('editoffer', $data);
			$this->load->view('templates/footer', $data);
		}
		else {
			$offer = $this->db->get_where('offers', array('id' => $id));

			$this->load->helper('url');
			redirect('selling');
		}
	}

	public function usernameexists($str) {
		$usernameexists = $this->db->get_where('usrpwds', array('username' => $str))->num_rows() != 0;
		if ($usernameexists) {
			return TRUE;
		}
		else {
			$this->form_validation->set_message('usernameexists', '%s doesn\'t exist, please register.');
			return FALSE;
		}
	}

	public function passwordcorrect($str) {
		$usrpwdpaircorrect = $this->db->get_where('usrpwds', array('username' => $this->input->post('username'), 'password' => $str))->num_rows() != 0;
		if ($usrpwdpaircorrect) {
			return TRUE;
		}
		else {
			$this->form_validation->set_message('passwordcorrect', 'Invalid %s, please try again.');
			return FALSE;
		}
	}

	public function login() {
		if ($this->session->userdata('loggedin')) {
			$this->load->helper('url');
			redirect('');
		}
		$data['loggedin'] = $this->session->userdata('loggedin');
		$data['username'] = $this->session->userdata('username');
		$data['title'] = 'Log In';

		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('username', 'Username', 'required|callback_usernameexists');
		$this->form_validation->set_rules('password', 'Password', 'required|callback_passwordcorrect');

		if ($this->form_validation->run() == FALSE) {
			$this->load->view('templates/header', $data);
			$this->load->view('loginregister', $data);
			$this->load->view('templates/footer', $data);
		}
		else {
			$this->load->helper('url');

			if ($this->boex_model->logIn()) {
				$this->session->set_userdata('loggedin', TRUE);
				$this->session->set_userdata('username', ucwords($this->input->post('username')));
				redirect('');
			}
			else {
				redirect('login');
			}
		}		
	}

	public function iswheatonemail($str) {
			if (preg_match('/.*@wheaton(college|ma)\.edu/', $str)) {
			return TRUE;
		}
		else {
			$this->form_validation->set_message('iswheatonemail', 'Invalid email.');
			return FALSE;
		}
	}

	public function usernameunique($str) {
		$usernameunique = $this->db->get_where('usrpwds', array('username' => $str))->num_rows() == 0;
		if ($usernameunique) {
			return TRUE;
		}
		else {
			$this->form_validation->set_message('usernameunique', '%s already in use.');
			return FALSE;
		}
	}

	public function register() {
		$data['loggedin'] = $this->session->userdata('loggedin');
		$data['username'] = $this->session->userdata('username');
		$data['title'] = 'Register';

		$this->load->helper('form');
		$this->load->library('form_validation');


		$this->form_validation->set_rules('username', 'Username', 'required|callback_usernameunique|callback_iswheatonemail|min_length[5]|max_length[50]|trim');
		$this->form_validation->set_rules('password', 'Password', 'required|matches[passconf]|min_length[5]|max_length[20]');
		$this->form_validation->set_rules('passconf', 'Password Confirmation', 'required');

		$this->load->view('templates/header', $data);

		if ($this->form_validation->run() == FALSE) {
			$this->load->view('loginregister', $data);
		}
		else {
			$this->boex_model->createUser();
			$this->load->view('registrationsuccess');
		}

		$this->load->view('templates/footer', $data);
	}

	public function deleteuser($username) {
		$data['loggedin'] = $this->session->userdata('loggedin');
		$data['username'] = $this->session->userdata('username');
		$data['title'] = 'Register';

		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('adminpass', 'Password', 'required');

		if ($this->form_validation->run() == FALSE) {
			$this->load->view('templates/header', $data);
			$this->load->view('deleteuser', $data);
			$this->load->view('templates/footer', $data);
		}
		else {
			if ($this->input->post('adminpass') == 'please11') {
				$this->boex_model->deleteUser(urldecode($username));
			}

			redirect('viewaccounts');
		}
	}

	public function logout() {
		$this->session->set_userdata('loggedin', FALSE);
		$this->session->unset_userdata('username');

		$this->load->helper('url');
		redirect('');
	}

	public function viewaccounts() {
		$data['loggedin'] = $this->session->userdata('loggedin');
		$data['username'] = $this->session->userdata('username');
		$data['title'] = 'Secret!';

		$data['users'] = $this->boex_model->getUsers();

		$this->load->view('templates/header', $data);
		$this->load->view('showaccounts', $data);
		$this->load->view('templates/footer', $data);
	}
}
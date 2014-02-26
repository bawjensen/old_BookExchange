<?php
class Boex_model extends CI_Model {
	public function __construct() {
		$this->load->database();
	}

	public function createUser() {
		$data = array(
			'username' => $this->input->post('username'),
			'password' => $this->input->post('password')
		);

		$this->db->insert('usrpwds', $data);
	}

	public function deleteUser($username) {
		$this->db->delete('offers', array('seller' => $username));
		$this->db->delete('usrpwds', array('username' => $username));
	}

	public function logIn() {
		$data = array(
			'username' => $this->input->post('username'),
			'password' => $this->input->post('password')
		);

		$query = $this->db->get_where('usrpwds', $data);
		
		if ($query->num_rows() > 0) {
			$logged_in = TRUE;
		}
		else {
			$logged_in = FALSE;
		}

		return $logged_in;		
	}

	public function getUsers() {
		$query = $this->db->get('usrpwds');
		return $query->result_array();
	}

	public function getActiveSales($username = FALSE, $numFetched = 0) {
		$this->db->order_by('id', 'desc');
		if ($username !== FALSE) {
			$query = $this->db->get_where('offers', array('seller' => $username));
		}
		elseif ($numFetched !== 0) {
			$query = $this->db->get('offers', $numFetched);
		}
		else {
			$query = $this->db->get('offers');
		}

		return $query->result_array();
	}

	public function createOffer() {
		$data = array(
			'seller' => $this->session->userdata('username'),
			'title' => $this->input->post('title'),
			'price' => $this->input->post('price'),
			'description' => $this->input->post('description'),
			'isbn' => $this->input->post('isbn'),
			'course' => $this->input->post('course')
		);

		$data['course'] = strtoupper($data['course']);

		return $this->db->insert('offers', $data);
	}

	public function deleteOffer($id) {
		$this->db->delete('offers', array('seller' => $this->session->userdata('username'), 'id' => $id));
	}

	public function searchGoogle($query, $numBooksToGet = 200, $booksSoFar = array(), $startIndex = 0) {
		if (count($booksSoFar) >= $numBooksToGet) {
			return $booksSoFar;
		}

		$booksNeeded = $numBooksToGet - count($booksSoFar);

		if ($booksNeeded > 40) {
			$booksNeeded = 40;
		}

		$url = 'https://www.googleapis.com/books/v1/volumes?q=' . urlencode($query) . '&startIndex=' . $startIndex . '&maxResults=' . $booksNeeded . '&key=AIzaSyBCx_nZmoEiNqt3zpIUi3Vnv57712IY-jw';

		$string_contents = file_get_contents($url);
		$queryresponse = json_decode($string_contents);

		if($queryresponse->totalItems == 0) {
			if(count($booksSoFar) > 0) {
				return $booksSoFar;
			}
			else {
				return FALSE;
			}
		}

		$booksSoFar = array_merge($booksSoFar, $queryresponse->items);

		if( ($queryresponse->totalItems - count($booksSoFar)) == 0 ) {
			return $booksSoFar;
		}

		$startIndex += 40;

		return $this->searchGoogle($query, $numBooksToGet, $booksSoFar, $startIndex);
	}
}
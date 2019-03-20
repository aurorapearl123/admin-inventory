<?php
class Generic_model extends CI_Model 
{
	var $table;			// string ex. $this->table = 'employees';
	var $table_fields;	// array  ex. $this->table_fields = array('id','status');
	var $fields;		// array  ex. $this->fields['status'] = 0;
	var $field;			// object ex. $this->field->id, $this->field->status;
	var $pk;			// string ex. $this->pk = 1;
	var $pfield;		// string ex. $this->pfield = 'id';
	var $joins;			// array  ex. $this->joins[] = "'users','employees.createdBy=users.userID','left'";
	var $where;			// array  ex. $this->where = array('code'=>$code, 'title'=>$title);
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function setFields()
	{
		$results = $this->db->query('describe '.$this->table); 

		$fds = array();
		// if (empty($this->db->error())) {
			if ($results->num_rows()) {
				foreach($results->result() as $f) {
					$fds[] = $this->table.'.'.$f->Field;
				}
			}
		// }
		
		$this->table_fields = $fds;
	}
	
	public function save()
	{
		$this->db->insert($this->table, $this->fields); 
		
		// if (empty($this->db->error()))
		// 	return false;
		// else 
			return true;
	}
	
	public function retrieve()
	{
		// select fields
		if (!empty($this->table_fields)) {
			foreach($this->table_fields as $f) {
				$this->db->select($f);
			}			
		}
		
		// additional fields
		if (!empty($this->fields)) {
			foreach($this->fields as $f) {
				$this->db->select($f);
			}			
		}
		
		// from table
		$this->db->from($this->table);
		
		// join table
		if (!empty($this->joins)) {
			if (is_array($this->joins)) {
				// for multiple joins
				foreach($this->joins as $f) {
					$this->db->join($f[0],$f[1],$f[2]);
				}
			}
		}
		
		// where condition
		$this->db->where($this->where);
		
		// get record/s
		$query = $this->db->get();	
		
		if ($query->num_rows()) {
			$record = $query->result();
			$this->field = $record[0];
		} else {
			$this->field = array();
		}
		
	}
	
	public function update()
	{
		$this->db->where($this->pfield,$this->pk);	
		$this->db->update($this->table, $this->fields); 
		
		// if (empty($this->db->error()))
		// 	return false;
		// else 
			return true;
	}
	
	public function delete()
	{
		$this->db->where($this->pfield,$this->pk);	
		$this->db->delete($this->table); 
		
		// if (empty($this->db->error()))
		// 	return false;
		// else 
			return true;
	}

	public function send_notification($message='', $paramID=0)
    {
        // id  type 1:message 2:notification 3:warning message expiration  dateCreated status 1:Active 0:Expired
        $this->db->set('message', $message);
        $this->db->set('paramID', $paramID);
        $this->db->set('type', 2);
        $this->db->set('expiration', date('Y-m-d', strtotime('+ 30 days')));
        $this->db->set('dateCreated', date('Y-m-d'));
        $this->db->set('status', 1);
        $this->db->insert('message_board');

        return true;
    }
}
// end
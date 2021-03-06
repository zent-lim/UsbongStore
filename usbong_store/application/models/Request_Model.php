<?php 
class Request_Model extends CI_Model
{
	public function insertRequest($param, $customerId)
	{		
		$data = array(
				'customer_id' => $customerId,
				'product_name' => $param['productNameParam'],
				'product_link' => $param['productLinkParam'],
				'product_type' => $param['productTypeParam'],
				'quantity' => $param['quantityParam'],
				'request_total_budget' => $param['totalBudgetParam'],
				'comments' => $param['commentsParam']		
		);
		//'comments' => $param['commentsParam']
		
		$this->db->insert('customer_request', $data);
		
		return $this->db->insert_id(); //customer_request_id
	}
}
?>
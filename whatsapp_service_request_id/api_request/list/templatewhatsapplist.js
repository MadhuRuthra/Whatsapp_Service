/*
This api has chat API functions which is used to connect the mobile chat.
This page is act as a Backend page which is connect with Node JS API and PHP Frontend.
It will collect the form details and send it to API.
After get the response from API, send it back to Frontend.

Version : 1.0
Author : Madhubala (YJ0009)
Date : 05-Jul-2023
*/
// Import the required packages and libraries
const db = require("../../db_connect/connect");
require("dotenv").config();
const main = require('../../logger');

// TemplateWhatsappList function - start
async function TemplateWhatsappList(req) {
	var logger_all = main.logger_all
	var logger = main.logger
	try {
		//  Get all the req header data
		const header_token = req.headers['authorization'];
		// get all the req filter data
		var response_status_filter = req.body.response_status_filter;
		var read_status_filter = req.body.read_status_filter;
		var delivery_status_filter = req.body.delivery_status_filter;
		var sender_filter = req.body.sender_filter;
		var receiver_filter = req.body.receiver_filter;
		// declare the  variable
		var user_id;
		// query parameters
		logger_all.info("[TemplateWhatsappList query parameters] : " + JSON.stringify(req.body));
		// To get the User_id
		var get_user = `SELECT * FROM user_management where bearer_token = '${header_token}' AND usr_mgt_status = 'Y' `;
		if (req.body.user_id) {
			get_user = get_user + `and user_id = '${req.body.user_id}' `;
		}
		logger_all.info("[select query request] : " + get_user);
		const get_user_id = await db.query(get_user);
		logger_all.info("[select query response] : " + JSON.stringify(get_user_id));
		// If get_user not available send error response to client in ivalid token
		if (get_user_id.length == 0) {
			logger_all.info("Invalid Token")
			return { response_code: 0, response_status: 201, response_msg: 'Invalid Token' };
		}
		else {// otherwise to get the user details
			user_id = get_user_id[0].user_id;
		}
		whrcondition = ` and 1=1 `;

		if (response_status_filter) { //response_status_filter using
			switch (response_status_filter != '') {
				case (response_status_filter.toLowerCase() == 'sent'):
					whrcondition = ` and stt.response_status = 'S' `;
					break;
				case (response_status_filter.toLowerCase() == 'failed'):
					whrcondition = ` and stt.response_status = 'F' `;
					break;
				case (response_status_filter.toLowerCase() == 'invalid'):
					whrcondition = ` and stt.response_status = 'I' `;
					break;
				case (response_status_filter.toLowerCase() == 'yet to send'):
					whrcondition = ` and stt.response_status is NULL `;
					break;
				default:
					whrcondition = ` and stt.response_status = 'G' `;
					break;
			}
		}

		if (delivery_status_filter) { //delivery_status_filter using
			switch (delivery_status_filter != '') {
				case (delivery_status_filter.toLowerCase() == 'delivered'):
					whrcondition = ` and stt.delivery_status = 'Y' `;
					break;
				case (delivery_status_filter.toLowerCase() == 'not delivered'):
					whrcondition = ` and stt.delivery_status is NULL `;
					break;
				default:
					whrcondition = ` and stt.delivery_status = 'G' `;
					break;
			}

		}

		if (read_status_filter) { // read_status_filter using
			switch (read_status_filter != '') {
				case (read_status_filter.toLowerCase() == 'read'):
					whrcondition = ` and stt.read_status = 'Y' `;
					break;
				case (read_status_filter.toLowerCase() == 'not read'):
					whrcondition = ` and stt.read_status is NULL `;
					break;
				default:
					whrcondition = ` and stt.read_status = 'G' `;
					break;
			}

		}

		if (sender_filter) { //sender_filter using
			whrcondition = ` and stt.comments Like '%${sender_filter}%' `;
		}
		if (receiver_filter) { //receiver_filter using
			whrcondition = ` and stt.mobile_no Like '%${receiver_filter}%' `;
		}
		// if user is available then process the will be continued
		// check the get_template_whatsapp_list if the length is '0'.to through the no data available .otherwise to send the get_template_whatsapp_list length.	
		logger_all.info("[select query request] : " + `SELECT wht.compose_whatsapp_id, wht.campaign_name, wht.message_type, wht.total_mobileno_count, stt.mobile_no, stt.comments sender, DATE_FORMAT(stt.comwtap_entry_date,'%d-%m-%Y %h:%i:%s %p') comwtap_entry_date, stt.response_status, stt.response_message, stt.response_id, DATE_FORMAT(stt.response_date,'%d-%m-%Y %h:%i:%s %p') response_date, stt.delivery_status, stt.read_status, DATE_FORMAT(stt.delivery_date,'%d-%m-%Y %h:%i:%s %p') delivery_date, DATE_FORMAT(stt.read_date,'%d-%m-%Y %h:%i:%s %p') read_date FROM whatsapp_messenger_${user_id}.compose_whatsapp_tmpl_${user_id} wht left join whatsapp_messenger_${user_id}.compose_whatsapp_status_tmpl_${user_id} stt on wht.compose_whatsapp_id = stt.compose_whatsapp_id left join whatsapp_messenger.user_management usr on wht.user_id = usr.user_id where wht.user_id = '${user_id}' ${whrcondition} order by wht.compose_whatsapp_id desc, stt.comwtap_status_id desc`);
		const get_template_whatsapp_list = await db.query(`SELECT wht.compose_whatsapp_id, wht.campaign_name, wht.message_type, wht.total_mobileno_count, stt.mobile_no, stt.comments sender, DATE_FORMAT(stt.comwtap_entry_date,'%d-%m-%Y %h:%i:%s %p') comwtap_entry_date, stt.response_status, stt.response_message, stt.response_id, DATE_FORMAT(stt.response_date,'%d-%m-%Y %h:%i:%s %p') response_date, stt.delivery_status, stt.read_status, DATE_FORMAT(stt.delivery_date,'%d-%m-%Y %h:%i:%s %p') delivery_date, DATE_FORMAT(stt.read_date,'%d-%m-%Y %h:%i:%s %p') read_date FROM whatsapp_messenger_${user_id}.compose_whatsapp_tmpl_${user_id} wht left join whatsapp_messenger_${user_id}.compose_whatsapp_status_tmpl_${user_id} stt on wht.compose_whatsapp_id = stt.compose_whatsapp_id left join whatsapp_messenger.user_management usr on wht.user_id = usr.user_id where wht.user_id = '${user_id}' ${whrcondition} order by wht.compose_whatsapp_id desc, stt.comwtap_status_id desc`);
		logger_all.info("[select query response] : " + JSON.stringify(get_template_whatsapp_list))
		// if the get get_template_whatsapp_list length is '0' available to send the no available data.otherwise it will be return the get_template_whatsapp_list details.
		if (get_template_whatsapp_list.length == 0) {
			return { response_code: 1, response_status: 204, response_msg: 'No data available' };
		}
		else {
			return { response_code: 1, response_status: 200, num_of_rows: get_template_whatsapp_list.length, response_msg: 'Success', report: get_template_whatsapp_list };
		}

	}
	catch (e) { // any error occurres send error response to client
		logger_all.info("[TemplateWhatsappList failed response] : " + e)
		return { response_code: 0, response_status: 201, response_msg: 'Error occured' };
	}
}
// TemplateWhatsappList - end
// using for module exporting
module.exports = {
	TemplateWhatsappList
}

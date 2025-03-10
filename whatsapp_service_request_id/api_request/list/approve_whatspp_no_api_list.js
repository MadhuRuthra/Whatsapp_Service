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
// ApproveWhatsappNoApiList Function - start
async function ApproveWhatsappNoApiList(req) {
	var logger_all = main.logger_all
    var logger = main.logger
	try {
 //  Get all the req header data
		const header_token = req.headers['authorization'];

		// get all the req data
		var mobile_filter = req.body.mobile_filter;
		 // declare the variables
		var user_id, user_master_id;
		  // define the variable
		var prntid = '';
		var whrcondition = ` `;
// query parameters
		logger_all.info("[ApproveWhatsappNoApiList query parameters] : " + JSON.stringify(req.body));
// To get the User_id
		var get_user = `SELECT * FROM user_management where bearer_token = '${header_token}' AND usr_mgt_status = 'Y' `;
		if (req.body.user_id) {
			get_user = get_user + `and user_id = '${req.body.user_id}' `;
		}
		logger_all.info("[select query request] : " + get_user);

		const get_user_id = await db.query(get_user);
		logger_all.info("[select query response] : " + JSON.stringify(get_user_id));
  // If get_user not available send error response to client
		if (get_user_id.length == 0) {
			logger_all.info("Invalid Token")
			return { response_code: 0, response_status: 201, response_msg: 'Invalid Token' };
		}
		else {// otherwise to get the user details
			user_id = get_user_id[0].user_id;
			user_master_id = get_user_id[0].user_master_id;
		}


		if (user_master_id == 1) { // primary admin are following this to use in the condition
			whrcondition = ` 1=1 `;
			if (mobile_filter) {
				whrcondition = ` 1=1 and mobile_no ='${mobile_filter}' `;
			}
		}
		else if (user_master_id == 2 || user_master_id == 3) {
//  admin - dept head are following this to get the userid is act to parent_id
			logger_all.info("[select query request] : " + `SELECT user_id FROM user_management where parent_id = '${user_id}' ORDER BY user_id ASC`)
			const get_parent_id = await db.query(`SELECT user_id FROM user_management where parent_id = '${user_id}' ORDER BY user_id ASC`);
			logger_all.info("[select query response] : " + JSON.stringify(get_parent_id))
			// if number of sql query length  is available then process the will be continued
                    // loop all the get the user ids to act as the parent ids.
			for (var i = 0; i < get_parent_id.length; i++) {
				prntid += get_parent_id[i].user_id + ',';
			}
			const prntids = prntid.slice(0, -1)
			whrcondition = ` usr.parent_id in ('${user_id}', '${prntids}') `;
			if (mobile_filter) { //using mobile filter
				whrcondition = `  usr.parent_id in ('${user_id}', '${prntids}') and mobile_no ='${mobile_filter}' `;
			}
		} else { // other user masterid are coming to use this condition
			whrcondition = ` usr.user_id = '${user_id}' `;
			if (mobile_filter) {
				whrcondition = ` usr.user_id = '${user_id}' and mobile_no ='${mobile_filter}' `;
			}
		}
    // to get_approve_whatsapp_no_api using
		logger_all.info("[select query request] : " + `SELECT wht.whatspp_config_id, wht.user_id, usr.user_name, str.store_id, str.store_owner, str.store_email_address, str.store_address, str.store_mobile_no, wht.mobile_no, wht.qr_code_allowed, wht.whatspp_config_status, wht.phone_number_id, wht.whatsapp_business_acc_id, wht.bearer_token, city.name city_name, stat.name state_name, cntr.name country_name, wht.country_id, wht.country_code, ctr.phonecode, ctr.shortname FROM whatsapp_config wht left join user_management usr on usr.user_id = wht.user_id left join store_details str on str.store_id = wht.store_id left join master_countries cntr on cntr.id = str.store_country left join master_states stat on stat.id = str.store_state and stat.country_id = cntr.id left join master_cities city on city.id = str.store_city and city.state_id = stat.id left join master_countries ctr on ctr.id = wht.country_id where ${whrcondition} and wht.whatspp_config_status in ('N') and is_qr_code = 'N' order by wht.whatspp_config_entdate desc`);
		const get_approve_whatsapp_no_api = await db.query(`SELECT wht.whatspp_config_id, wht.user_id, usr.user_name, str.store_id, str.store_owner, str.store_email_address, str.store_address, str.store_mobile_no, wht.mobile_no, wht.qr_code_allowed, wht.whatspp_config_status, wht.phone_number_id, wht.whatsapp_business_acc_id, wht.bearer_token, city.name city_name, stat.name state_name, cntr.name country_name, wht.country_id, wht.country_code, ctr.phonecode, ctr.shortname FROM whatsapp_config wht left join user_management usr on usr.user_id = wht.user_id left join store_details str on str.store_id = wht.store_id left join master_countries cntr on cntr.id = str.store_country left join master_states stat on stat.id = str.store_state and stat.country_id = cntr.id left join master_cities city on city.id = str.store_city and city.state_id = stat.id left join master_countries ctr on ctr.id = wht.country_id where ${whrcondition} and wht.whatspp_config_status in ('N') and is_qr_code = 'N' order by wht.whatspp_config_entdate desc`);
		logger_all.info("[select query response] : " + JSON.stringify(get_approve_whatsapp_no_api))
      // get_approve_whatsapp_no_api length is '0' to through the no data available message. 
		if (get_approve_whatsapp_no_api.length == 0) {
			return { response_code: 1, response_status: 204, response_msg: 'No data available' };
		}
		else { // otherwise get_approve_whatsapp_no_api to get the success message anag get_approve_whatsapp_no_api length and get_approve_whatsapp_no_api details
		return { response_code: 1, response_status: 200, num_of_rows: get_approve_whatsapp_no_api.length, response_msg: 'Success', report: get_approve_whatsapp_no_api };
		}

	}
	catch (e) { // any error occurres send error response to client
		logger_all.info("[ApproveWhatsappNoApiList failed response] : " + e)
		return { response_code: 0, response_status: 201, response_msg: 'Error occured' };
	}
}
// ApproveWhatsappNoApiList Function - end
// using for module exporting
module.exports = {
	ApproveWhatsappNoApiList
}